<?php

namespace App\Http\Controllers;

use App\Models\OLevelEducation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class OLevelEducationController extends Controller
{
    /**
     * Display the O-Level education form (Create or Edit)
     */
    public function index()
    {
        // Check if user already has O-Level education record
        $oLevelEducation = OLevelEducation::where('applicant_id', Auth::id())->first();
        
        // If record exists, pass it to the view for editing
        return view('applicant.o_level_education', compact('oLevelEducation'));
    }

    /**
     * Store or Update O-Level education record
     */
    public function store(Request $request)
    {
        // Check if user already has a record
        $oLevelEducation = OLevelEducation::where('applicant_id', Auth::id())->first();

        // Validation rules
        $rules = [
            'school_name' => 'required|string|max:255',
            'form_four_index_number' => 'required|string|max:100',
            'division' => 'required|in:I,II,III,IV,0',
            'points' => 'nullable|integer|min:7|max:33',
            'end_of_study_year' => 'required|integer|min:2000|max:' . date('Y'),
            'form_four_certificate_path' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ];

        // Add unique validation rule only for new records
        if (!$oLevelEducation) {
            $rules['form_four_index_number'] .= '|unique:o_level_education,form_four_index_number';
        } else {
            $rules['form_four_index_number'] .= '|unique:o_level_education,form_four_index_number,' . $oLevelEducation->id;
        }

        $request->validate($rules);

        $data = $request->all();
        $data['applicant_id'] = Auth::id();

        // Handle file upload
        if ($request->hasFile('form_four_certificate_path')) {
            // Delete old file if exists (for update)
            if ($oLevelEducation && $oLevelEducation->form_four_certificate_path) {
                Storage::disk('public')->delete($oLevelEducation->form_four_certificate_path);
            }
            
            $path = $request->file('form_four_certificate_path')->store('o_level_certificates', 'public');
            $data['form_four_certificate_path'] = $path;
        }

        // Create or Update
        if ($oLevelEducation) {
            $oLevelEducation->update($data);
            $message = 'O-Level education information updated successfully!';
        } else {
            OLevelEducation::create($data);
            $message = 'O-Level education information saved successfully!';
        }

        return redirect()->route('applicant.o-level-education')
            ->with('success', $message);
    }
}