<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ALevelEducation;
use App\Models\Applicant;
use Illuminate\Support\Facades\Storage;

class ALevelEducationController extends Controller
{
    public function index()
    {
        $applicant = Applicant::where('user_id', auth()->id())->firstOrFail();
        $aLevel = ALevelEducation::where('applicant_id', $applicant->id)->first();
        
        if ($aLevel) {
            return redirect()->route('applicant.a-level.edit');
        } else {
            return redirect()->route('applicant.a-level.create');
        }
    }

    public function create()
    {
        return view('applicant.a_level_information');
    }

    public function store(Request $request)
    {
        $request->validate([
            'school_name' => 'required|string|max:255',
            'form_six_index_number' => 'required|string|unique:a_level_educations,form_six_index_number',
            'division' => 'required|string|max:10',
            'points' => 'required|integer|min:3|max:18',
            'end_of_study_year' => 'required|integer|digits:4|min:1950|max:' . date('Y'),
            'preferred_university' => 'required|string|max:255',
            'form_six_certificate' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        $applicant = Applicant::where('user_id', auth()->id())->firstOrFail();
        $data = $request->all();
        $data['applicant_id'] = $applicant->id;

        if ($request->hasFile('form_six_certificate')) {
            $path = $request->file('form_six_certificate')->store('form_six_certificates', 'public');
            $data['form_six_certificate_path'] = $path;
        }

        ALevelEducation::create($data);

        return redirect()->route('dashboard')->with('success', 'A-Level education information saved successfully!');
    }

    public function edit()
    {
        $applicant = Applicant::where('user_id', auth()->id())->firstOrFail();
        $aLevel = ALevelEducation::where('applicant_id', $applicant->id)->firstOrFail();
        
        return view('applicant.a_level_information', compact('aLevel'));
    }

    public function update(Request $request, $id)
    {
        $aLevel = ALevelEducation::findOrFail($id);
        
        // Ensure user can only update their own A-Level education
        $applicant = Applicant::where('user_id', auth()->id())->firstOrFail();
        if ($aLevel->applicant_id !== $applicant->id) {
            abort(403);
        }

        $request->validate([
            'school_name' => 'required|string|max:255',
            'form_six_index_number' => 'required|string|unique:a_level_educations,form_six_index_number,'.$aLevel->id,
            'division' => 'required|string|max:10',
            'points' => 'required|integer|min:3|max:18',
            'end_of_study_year' => 'required|integer|digits:4|min:1950|max:' . date('Y'),
            'preferred_university' => 'required|string|max:255',
            'form_six_certificate' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        $data = $request->all();

        if ($request->hasFile('form_six_certificate')) {
            // Delete old file if exists
            if ($aLevel->form_six_certificate_path) {
                Storage::disk('public')->delete($aLevel->form_six_certificate_path);
            }
            
            $path = $request->file('form_six_certificate')->store('form_six_certificates', 'public');
            $data['form_six_certificate_path'] = $path;
        }

        $aLevel->update($data);

        return redirect()->route('dashboard')->with('success', 'A-Level education information updated successfully!');
    }
}
