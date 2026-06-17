<?php

namespace App\Http\Controllers;
use App\Models\PersonalInfo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

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

            // Check if data was saved successfully
            if ($personalInfo) {

            } else {
                return redirect()->back()
                    ->with('error', 'Failed to save personal information.')
                    ->withInput();
            }

        } catch (\Exception $e) {
            // Handle any exceptions
            return redirect()->back()
                ->with('error', 'An error occurred: ' . $e->getMessage())
                ->withInput();
        }

}

}
