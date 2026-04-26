<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\OLevelEducation;
use App\Models\Applicant;
use Illuminate\Support\Facades\Storage;

class OLevelEducationController extends Controller
{
    public function index()
    {
        $applicant = Applicant::where('user_id', auth()->id())->firstOrFail();
        $oLevel = OLevelEducation::where('applicant_id', $applicant->id)->first();
        
        if ($oLevel) {
            return redirect()->route('applicant.o-level.edit');
        } else {
            return redirect()->route('applicant.o-level.create');
        }
    }

    public function create()
    {
        return view('applicant.o_level_information');
    }

    public function store(Request $request)
    {
        $request->validate([
            'school_name' => 'required|string|max:255',
            'form_four_index_number' => 'required|string|unique:o_level_educations,form_four_index_number',
            'division' => 'required|string|max:10',
            'points' => 'required|integer|min:0|max:40',
            'end_of_study_year' => 'required|integer|digits:4|min:1950|max:' . date('Y'),
            'form_four_certificate' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        $applicant = Applicant::where('user_id', auth()->id())->firstOrFail();
        $data = $request->all();
        $data['applicant_id'] = $applicant->id;

        if ($request->hasFile('form_four_certificate')) {
            $path = $request->file('form_four_certificate')->store('form_four_certificates', 'public');
            $data['form_four_certificate_path'] = $path;
        }

        OLevelEducation::create($data);

        return redirect()->route('dashboard')->with('success', 'O-Level education information saved successfully!');
    }

    public function edit()
    {
        $applicant = Applicant::where('user_id', auth()->id())->firstOrFail();
        $oLevel = OLevelEducation::where('applicant_id', $applicant->id)->firstOrFail();
        
        return view('applicant.o_level_information', compact('oLevel'));
    }

    public function update(Request $request, $id)
    {
        $oLevel = OLevelEducation::findOrFail($id);
        
        // Ensure user can only update their own O-Level education
        $applicant = Applicant::where('user_id', auth()->id())->firstOrFail();
        if ($oLevel->applicant_id !== $applicant->id) {
            abort(403);
        }

        $request->validate([
            'school_name' => 'required|string|max:255',
            'form_four_index_number' => 'required|string|unique:o_level_educations,form_four_index_number,'.$oLevel->id,
            'division' => 'required|string|max:10',
            'points' => 'required|integer|min:0|max:40',
            'end_of_study_year' => 'required|integer|digits:4|min:1950|max:' . date('Y'),
            'form_four_certificate' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        $data = $request->all();

        if ($request->hasFile('form_four_certificate')) {
            // Delete old file if exists
            if ($oLevel->form_four_certificate_path) {
                Storage::disk('public')->delete($oLevel->form_four_certificate_path);
            }
            
            $path = $request->file('form_four_certificate')->store('form_four_certificates', 'public');
            $data['form_four_certificate_path'] = $path;
        }

        $oLevel->update($data);

        return redirect()->route('dashboard')->with('success', 'O-Level education information updated successfully!');
    }
}
