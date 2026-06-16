<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Applicant;
use Illuminate\Support\Facades\Storage;

class ApplicantController extends Controller
{


    public function create()
    {
        return view('applicant.personal_information');
    }

    public function store(Request $request)
    {
        $request->validate([
            // Basic Personal Information
            
            'gender' => 'required|in:male,female,other',
            'birthdate' => 'required|date|before:today',
            'place_of_birth' => 'required|string|max:255',
            'nationality' => 'required|string|max:255',
            'marital_status' => 'required|in:single,married,divorced,widowed',
            'religion' => 'required|in:muslim,christian',

            // Contact Information
            'address' => 'required|string',
            'region' => 'required|string|max:255',
            'district' => 'required|string|max:255',
            'email' => 'required|email|unique:applicants,email',
            'phone_number' => 'required|string|max:20',

            // Identification Details
            'zanzibar_national_id' => 'nullable|string|unique:applicants,zanzibar_national_id',
            'passport_number' => 'nullable|string|unique:applicants,passport_number',

            // Additional Information & Documents
            'disability' => 'boolean',
            'birth_certificate' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',

            // Next of Kin Information
            'kin_full_name' => 'required|string|max:255',
            'kin_relationship' => 'required|in:father,mother,uncle,guardian',
            'kin_phone_number' => 'required|string|max:20',
            'kin_religion' => 'required|in:muslim,christian',
            'kin_address' => 'required|string',
            'kin_region' => 'required|string|max:255',
            'kin_district' => 'required|string|max:255',
        ]);

        $data = $request->all();
        $data['user_id'] = auth()->id();
        $data['disability'] = $request->has('disability');

        if ($request->hasFile('birth_certificate')) {
            $path = $request->file('birth_certificate')->store('birth_certificates', 'public');
            $data['birth_certificate_path'] = $path;
        }

        Applicant::create($data);

        return redirect()->route('dashboard')->with('success', 'Personal information saved successfully!');
    }

    public function edit()
    {
        $applicant = Applicant::where('user_id', auth()->id())->first();

        if (!$applicant) {
            return redirect()->route('applicant.personal-information.create');
        }

        return view('applicant.personal_information', compact('applicant'));
    }

    public function update(Request $request, $id)
    {
        $applicant = Applicant::where('user_id', auth()->id())->findOrFail($id);

        $request->validate([
            // Basic Personal Information
            'full_name' => 'required|string|max:255',
            'gender' => 'required|in:male,female,other',
            'birthdate' => 'required|date|before:today',
            'place_of_birth' => 'required|string|max:255',
            'nationality' => 'required|string|max:255',
            'marital_status' => 'required|in:single,married,divorced,widowed',
            'religion' => 'required|in:muslim,christian',

            // Contact Information
            'address' => 'required|string',
            'region' => 'required|string|max:255',
            'district' => 'required|string|max:255',
            'email' => 'required|email|unique:applicants,email,'.$applicant->id,
            'phone_number' => 'required|string|max:20',

            // Identification Details
            'zanzibar_national_id' => 'nullable|string|unique:applicants,zanzibar_national_id,'.$applicant->id,
            'passport_number' => 'nullable|string|unique:applicants,passport_number,'.$applicant->id,

            // Additional Information & Documents
            'disability' => 'boolean',
            'birth_certificate' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',

            // Next of Kin Information
            'kin_full_name' => 'required|string|max:255',
            'kin_relationship' => 'required|in:father,mother,uncle,guardian',
            'kin_phone_number' => 'required|string|max:20',
            'kin_religion' => 'required|in:muslim,christian',
            'kin_address' => 'required|string',
            'kin_region' => 'required|string|max:255',
            'kin_district' => 'required|string|max:255',
        ]);

        $data = $request->all();
        $data['disability'] = $request->has('disability');

        if ($request->hasFile('birth_certificate')) {
            // Delete old file if exists
            if ($applicant->birth_certificate_path) {
                Storage::disk('public')->delete($applicant->birth_certificate_path);
            }

            $path = $request->file('birth_certificate')->store('birth_certificates', 'public');
            $data['birth_certificate_path'] = $path;
        }

        $applicant->update($data);

        return redirect()->route('dashboard')->with('success', 'Personal information updated successfully!');
    }
}
