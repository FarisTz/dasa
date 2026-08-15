<?php

namespace App\Http\Controllers\Applicant;
use App\Http\Controllers\Controller;
use App\Mail\ApplicationSubmittedNotification;
use App\Models\ALevelEducation;
use App\Models\Application;
use App\Models\Motivation;
use App\Models\OLevelEducation;
use App\Models\PersonalInfo;
use App\Models\Scholarship;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ApplicantController extends Controller
{


    public function index()
    {
        // Check if the authenticated user already has personal information
        $personalInfo = PersonalInfo::where('user_id', Auth::id())->first();
        return view('applicant.personal_information', compact('personalInfo'));
    }

    public function store(Request $request)
    {

    // Validate the incoming request data
        $request->validate([
            'gender' => 'in:male,female,other',
            'birthdate' => 'date',
            'place_of_birth' => 'string|max:255',
            'nationality' => 'string|max:100',
            'marital_status' => 'string|max:50',
            'religion' => 'string|max:100',
            'address' => 'string',
            'region' => 'string|max:100',
            'district' => 'nullable|string|max:100',
            'phone_number' => 'string|max:20',
            'id_type' => 'nullable|string|max:50',
            'id_number' => 'string|max:100',
            'disability' => 'nullable|string|max:255',
            'birth_certificate_path' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'kin_full_name' => 'nullable|string|max:255',
            'kin_relationship' => 'nullable|string|max:100',
            'kin_phone_number' => 'nullable|string|max:20',
            'kin_address' => 'nullable|string',
            'kin_district' => 'nullable|string|max:100',
        ]);

        $personalInfo = PersonalInfo::where('user_id', Auth::id())->first();

        try {
            // Handle file upload for birth certificate
            $birthCertificatePath = null;
            if ($request->hasFile('birth_certificate_path')) {
                //Delete old file if exists (for update)

                if ($personalInfo && $personalInfo->birth_certificate_path) {
                    Storage::disk('public')->delete($personalInfo->birth_certificate_path);
                }

                $file = $request->file('birth_certificate_path');
                $filename = time() . '_' . $file->getClientOriginalName();
                $birthCertificatePath = $file->storeAs('birth_certificates', $filename, 'public');
            }

            // Create new PersonalInfo record
           $data = $request->all();
              $data['user_id'] = Auth::id();
            $data['birth_certificate_path'] = $birthCertificatePath;



            if (!$personalInfo) {
                $personalInfo = PersonalInfo::create($data);
                return redirect()->back()
                    ->with('success', 'Personal information saved successfully!');
                }
            else {
                $personalInfo->update($data);
                return redirect()->back()
                    ->with('success', 'Personal information updated successfully!');
            }



        } catch (\Exception $e) {
            // Handle any exceptions
            return redirect()->back()
                ->with('error', 'An error occurred: ' . $e->getMessage())
                ->withInput();
        }

}





/**
 * Display the review page with all sections.
 */
public function review()
{
    $user = Auth::user();

    // Fetch all application data
    $personalInfo = PersonalInfo::where('user_id', $user->id)->first();
    $oLevel = OLevelEducation::where('user_id', $user->id)->first();
    $aLevel = ALevelEducation::where('user_id', $user->id)->first();
    $motivation = Motivation::where('user_id', $user->id)->first();

    // Get open scholarships
    $openScholarships = Scholarship::where('status', 'open')
        ->where('deadline', '>=', now())
        ->orderBy('deadline', 'asc')
        ->get();

    // Get existing application
    $existingApplication = Application::where('user_id', $user->id)->first();

    // Get selected scholarship from session or database
    $selectedScholarship = null;

    if ($existingApplication) {
        $selectedScholarship = $existingApplication->scholarship;
    } elseif (session('selected_scholarship_id')) {
        $selectedScholarship = Scholarship::find(session('selected_scholarship_id'));
    }

    return view('applicant.review', compact(
        'personalInfo',
        'oLevel',
        'aLevel',
        'motivation',
        'openScholarships',
        'selectedScholarship',
        'existingApplication'
    ));
}
    /**
     * Submit the complete application.
     */
    // public function submit(Request $request)
    // {
    //     $user = Auth::user();

    //     // Check if all sections are completed
    //     $personalInfo = PersonalInfo::where('user_id', $user->id)->first();
    //     $oLevel = OLevelEducation::where('user_id', $user->id)->first();
    //     $aLevel = ALevelEducation::where('user_id', $user->id)->first();
    //     $motivation = Motivation::where('user_id', $user->id)->first();

    //     if (!$personalInfo || !$oLevel || !$aLevel || !$motivation) {
    //         return redirect()->back()->with('error', 'Please complete all sections before submitting your application.');
    //     }

    //     try {
    //         // Update user status to submitted
    //        $application = Application::create([
    //             'user_id' => $user->id,
    //             'application_status' => 'submitted',
    //             'submitted_at' => now()
    //         ]);

    //         // You can also store submission status in a separate table if needed
    //         // Application::create([
    //         //     'user_id' => $user->id,
    //         //     'submitted_at' => now(),
    //         //     'status' => 'pending'
    //         // ]);

    //         return redirect()->route('applicant.dashboard')
    //             ->with('success', 'Your application has been submitted successfully!');
    //     } catch (\Exception $e) {
    //         return redirect()->back()
    //             ->with('error', 'Failed to submit application: ' . $e->getMessage());
    //     }
    // }

     /**
     * Select a scholarship for application.
     */
    public function selectScholarship(Request $request)
    {
        $request->validate([
            'scholarship_id' => 'required|exists:scholarships,id'
        ]);



        $scholarship = Scholarship::find($request->scholarship_id);

        // Check if scholarship is open
        if ($scholarship->status != 'open') {
            return redirect()->back()
                ->with('error', 'This scholarship is not currently open for applications.')
                ->withInput();
        }

        // Check if deadline has passed
        if (now()->gt($scholarship->deadline)) {
            return redirect()->back()
                ->with('error', 'The application deadline for this scholarship has passed.')
                ->withInput();
        }

        $user = Auth::user();

        try {
            // Check if user already has an application
            $application = Application::where('user_id', $user->id)
                ->whereIn('status', ['pending', 'submitted', 'under_review'])
                ->first();

            if ($application) {
                // Update existing application
                $application->scholarship_id = $scholarship->id;
                $application->save();
            } else {
                // Create new application
                $application = Application::create([
                    'user_id' => $user->id,
                    'scholarship_id' => $scholarship->id,
                    'status' => 'pending',
                    'submitted_at' => null
                ]);
            }

            // Store in session
            session(['selected_scholarship_id' => $scholarship->id]);

            return redirect()->route('applicant.application.review')
                ->with('success', 'You have selected: ' . $scholarship->title)
                ->with('scholarship_selected', true);

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to select scholarship: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Submit the complete application.
     */
    /**
     * Submit the complete application.
     */
    public function submit(Request $request)
    {
        $user = Auth::user();

        // Check if all sections are completed
        $personalInfo = PersonalInfo::where('user_id', $user->id)->first();
        $oLevel = OLevelEducation::where('user_id', $user->id)->first();
        $aLevel = ALevelEducation::where('user_id', $user->id)->first();
        $motivation = Motivation::where('user_id', $user->id)->first();

        if (!$personalInfo || !$oLevel || !$aLevel || !$motivation) {
            return redirect()->back()->with('error', 'Please complete all sections before submitting your application.');
        }

        // Get application
        $application = Application::where('user_id', $user->id)
            ->where('status', 'pending')
            ->first();

        if (!$application) {
            return redirect()->back()->with('error', 'No application found. Please select a scholarship first.');
        }

        // Check if scholarship is still open
        $scholarship = $application->scholarship;
        if (!$scholarship || $scholarship->status != 'open' || now()->gt($scholarship->deadline)) {
            return redirect()->back()->with('error', 'The selected scholarship is no longer accepting applications.');
        }

        try {
            // Update application
            $application->status = 'submitted';
            $application->submitted_at = now();
            $application->save();

            // Update user status
            $user->update([
                'application_status' => 'submitted',
                'submitted_at' => now()
            ]);

            // Send email notification
            try {
                Mail::to($user->email)->send(new ApplicationSubmittedNotification($application, $user));
                \Log::info('Application submission email sent to: ' . $user->email);
            } catch (\Exception $e) {
                \Log::error('Failed to send application submission email: ' . $e->getMessage());
                // Continue even if email fails
            }

            // Clear session
            session()->forget('selected_scholarship_id');

            return redirect()->route('dashboard')
                ->with('success', 'Your application for ' . $scholarship->title . ' has been submitted successfully! A confirmation email has been sent to your inbox.');

        } catch (\Exception $e) {
            \Log::error('Application submission failed: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Failed to submit application: ' . $e->getMessage());
        }
    }

    /**
     * Display the user's application.
     */
    public function myApplication()
    {
        $user = Auth::user();

        // Get the latest application for the user
        $application = Application::with(['scholarship'])
            ->where('user_id', $user->id)
            ->latest()
            ->first();

        return view('applicant.my-application', compact('application'));
    }



    /**
     * Download acceptance letter.
     */
    public function downloadAcceptance()
    {
        $user = Auth::user();
        $application = Application::with(['scholarship', 'user'])
            ->where('user_id', $user->id)
            ->whereIn('status', ['approved_full', 'approved_partial'])
            ->first();

        if (!$application) {
            return redirect()->back()
                ->with('error', 'No acceptance letter available for your application.');
        }

        // Generate PDF acceptance letter
        $pdf = Pdf::loadView('applicant.acceptance-letter', compact('application', 'user'));

        // Download the PDF
        return $pdf->download('acceptance-letter-' . $application->id . '.pdf');
    }


    /**
     * Show the acknowledgement letter form.
     */
    public function acknowledgementShow()
    {
        $user = Auth::user();

        // Get the approved application
        $application = Application::where('user_id', $user->id)
            ->whereIn('status', ['approved_full', 'approved_partial'])
            ->first();

        if (!$application) {
            return redirect()->route('dashboard')
                ->with('error', 'You do not have an approved application.');
        }

        return view('applicant.acknowledgement.index', compact('application'));
    }



   public function downloadAcknowledgementLetter()
    {
        $user = Auth::user();

        // Get the approved application
        $application = Application::where('user_id', $user->id)
            ->whereIn('status', ['approved_full', 'approved_partial'])
            ->first();

        if (!$application) {
            return redirect()->route('dashboard')
                ->with('error', 'You do not have an approved application.');
        }

        // Check if the acknowledgement letter has been submitted
        // if (!$application->isAcknowledgementSubmitted()) {
        //     return redirect()->route('applicant.acknowledgement-letter')
        //         ->with('error', 'You have not submitted your acknowledgement letter yet.');
        // }

        // Check if the file exists
        if (!Storage::disk('public')->exists('documents/acknowledgements/acknowledgement.pdf')) {
            return redirect()->route('applicant.acknowledgement-letter')
                ->with('error', 'Acknowledgement letter file not found.');
        }

        // Download the acknowledgement letter
        return Storage::disk('public')->download('documents/acknowledgements/acknowledgement.pdf');
    }


    /**
     * Upload and submit the signed acknowledgement letter.
     */
    public function submitAcknowledgementLetter(Request $request)
    {
        $user = Auth::user();

        $application = Application::where('user_id', $user->id)
            ->whereIn('status', ['approved_full', 'approved_partial'])
            ->first();

        if (!$application) {
            return redirect()->back()
                ->with('error', 'You do not have an approved application.');
        }

        if (!$application->canSubmitAcknowledgement()) {
            return redirect()->back()
                ->with('error', 'You have already submitted an acknowledgement letter.');
        }

        $request->validate([
            'acknowledgement_letter' => 'required|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:5120',
        ]);

        try {
            // Store the uploaded file
            $file = $request->file('acknowledgement_letter');
            $filename = time() . '_' . Str::slug($user->name) . '_acknowledgement.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('documents/acknowledgements/uploads', $filename, 'public');

            // Update application
            $application->acknowledgement_letter_path = $path;
            $application->acknowledgement_letter_submitted_at = now();
            $application->acknowledgement_status = 'submitted';
            $application->save();

            return redirect()->route('applicant.acknowledgement-letter')
                ->with('success', 'Acknowledgement letter submitted successfully!');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to submit acknowledgement letter: ' . $e->getMessage());
        }
    }

}
