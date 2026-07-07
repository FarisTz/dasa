<?php

namespace App\Http\Controllers\Applicant;
use App\Http\Controllers\Controller;
use App\Models\ALevelEducation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ALevelEducationController extends Controller
{
    /**
     * Display the A-Level education form (Create or Edit)
     */
    public function index()
    {
        // Check if user already has A-Level education record
        $aLevelEducation = ALevelEducation::where('user_id', Auth::id())->first();

        // If record exists, pass it to the view for editing
        return view('applicant.a_level_education', compact('aLevelEducation'));
    }

    /**
     * Store or Update A-Level education record
     */
    public function store(Request $request)
    {
        // Check if user already has a record
        $aLevelEducation = ALevelEducation::where('user_id', Auth::id())->first();

        // Validation rules
        $rules = [
            'school_name' => 'required|string|max:255',
            'form_six_index_number' => 'required|string|max:100',
            'division' => 'required|in:I,II,III,IV,0',
            'points' => 'nullable|integer|min:7|max:33',
            'end_of_study_year' => 'required|integer|min:2000|max:' . date('Y'),
            'preferred_university' => 'nullable|string|max:255',
            'form_six_certificate_path' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ];

        // Add unique validation rule only for new records
        if (!$aLevelEducation) {
            $rules['form_six_index_number'] .= '|unique:a_level_education,form_six_index_number';
        } else {
            $rules['form_six_index_number'] .= '|unique:a_level_education,form_six_index_number,' . $aLevelEducation->id;
        }

        $request->validate($rules);

        $data = $request->all();
        $data['user_id'] = Auth::id();

        // Handle file upload
        if ($request->hasFile('form_six_certificate_path')) {
            // Delete old file if exists (for update)
            if ($aLevelEducation && $aLevelEducation->form_six_certificate_path) {
                Storage::disk('public')->delete($aLevelEducation->form_six_certificate_path);
            }

            $path = $request->file('form_six_certificate_path')->store('a_level_certificates', 'public');
            $data['form_six_certificate_path'] = $path;
        }

        // Create or Update
        if ($aLevelEducation) {
            $aLevelEducation->update($data);
            $message = 'A-Level education information updated successfully!';
        } else {
            ALevelEducation::create($data);
            $message = 'A-Level education information saved successfully!';
        }

        return redirect()->route('applicant.a-level-education')
            ->with('success', $message);
    }

    /**
     * Delete A-Level education record
     */
    public function destroy()
    {
        $aLevelEducation = ALevelEducation::where('user_id', Auth::id())->first();

        if (!$aLevelEducation) {
            return redirect()->route('applicant.a-level-education')
                ->with('error', 'No A-Level education record found to delete.');
        }

        // Delete certificate file if exists
        if ($aLevelEducation->form_six_certificate_path) {
            Storage::disk('public')->delete($aLevelEducation->form_six_certificate_path);
        }

        $aLevelEducation->delete();

        return redirect()->route('applicant.a-level-education')
            ->with('success', 'A-Level education record deleted successfully!');
    }
}
