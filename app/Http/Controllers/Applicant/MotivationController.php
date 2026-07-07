<?php

namespace App\Http\Controllers\Applicant;
use App\Http\Controllers\Controller;
use App\Models\Motivation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MotivationController extends Controller
{
    //

    /**
     * Display the motivation form.
     */
    public function index()
    {
        $motivation = Motivation::where('user_id', Auth::id())->first();
        return view('applicant.motivation_letter', compact('motivation'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'motivation_letter' => 'required|string',
            'academic_goals' => 'nullable|string',
            'community_contribution' => 'nullable|string',
            'additional_information' => 'nullable|string',
        ]);

        $motivation = Motivation::where('user_id', Auth::id())->first();

        if($motivation) {
            $motivation->update([
                'motivation_letter' => $request->motivation_letter,
                'academic_goals' => $request->academic_goals,
                'community_contribution' => $request->community_contribution,
                'additional_information' => $request->additional_information,
            ]);

            return redirect()->route('applicant.motivations.index')
                ->with('success', 'Motivation updated successfully!');
        }else {
            try {
                Motivation::create([
                    'user_id' => Auth::id(),
                    'motivation_letter' => $request->motivation_letter,
                    'academic_goals' => $request->academic_goals,
                    'community_contribution' => $request->community_contribution,
                    'additional_information' => $request->additional_information,
                ]);

                return redirect()->route('applicant.motivations.index')
                    ->with('success', 'Motivation submitted successfully!');
            } catch (\Exception $e) {
                return redirect()->back()
                    ->with('error', 'Failed to submit motivation: ' . $e->getMessage())
                    ->withInput();
            }
        }

        try {
            Motivation::create([
                'user_id' => Auth::id(),
                'motivation_letter' => $request->motivation_letter,
                'academic_goals' => $request->academic_goals,
                'community_contribution' => $request->community_contribution,
                'additional_information' => $request->additional_information,
            ]);

            return redirect()->route('applicant.motivations.index')
                ->with('success', 'Motivation submitted successfully!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to submit motivation: ' . $e->getMessage())
                ->withInput();
        }
    }





}
