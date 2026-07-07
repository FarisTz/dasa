<?php

namespace App\Http\Controllers\Beneficiary;

use App\Http\Controllers\Controller;
use App\Models\AcademicResult;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ResultController extends Controller
{
    /**
     * Display the result upload page.
     */
    public function index()
    {
        $student = Auth::user();

        // Get all results for the student
        $results = AcademicResult::where('student_id', $student->id)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        // Get the latest result
        $latestResult = $student->latestAcademicResult;

        // Check if student is suspended
        $isSuspended = $student->is_academic_suspended;

        return view('beneficiary.results.index', compact('results', 'latestResult', 'isSuspended'));
    }

    /**
     * Show the form for uploading a new result.
     */
    public function create()
    {
        $student = Auth::user();

        // Check if user is suspended
        if ($student->is_academic_suspended) {
            return redirect()->route('beneficiary.results.index')
                ->with('error', 'You are currently suspended from uploading results. Please contact the administrator.');
        }

        // Get existing years to prevent duplicates
        $existingYears = AcademicResult::where('student_id', $student->id)
            ->pluck('academic_year')
            ->toArray();

        return view('beneficiary.results.create', compact('existingYears'));
    }

    /**
     * Store a new academic result.
     */
    public function store(Request $request)
    {
        $student = Auth::user();

        // Check if user is suspended
        if ($student->is_academic_suspended) {
            return redirect()->back()
                ->with('error', 'You are currently suspended. Cannot upload results.');
        }

        $validator = Validator::make($request->all(), [
            'academic_year' => 'required|string|max:20',
            'student_year' => 'required|integer|min:1|max:10',
            'course_name' => 'nullable|string|max:255',
            'gpa' => 'nullable|numeric|min:0|max:4',
            'cgpa' => 'nullable|numeric|min:0|max:4',
            'division' => 'nullable|string|max:20',
            'remarks' => 'nullable|string',
            'result_file' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120', // 5MB
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Check for duplicate academic year
        $exists = AcademicResult::where('student_id', $student->id)
            ->where('academic_year', $request->academic_year)
            ->exists();

        if ($exists) {
            return redirect()->back()
                ->with('error', 'You have already uploaded a result for the academic year ' . $request->academic_year)
                ->withInput();
        }

        try {
            // Handle file upload
            $file = $request->file('result_file');
            $fileName = time() . '_' . Str::slug($student->name) . '_' . $request->academic_year . '.' . $file->getClientOriginalExtension();
            $filePath = $file->storeAs('academic_results', $fileName, 'public');

            // Create result record
            $result = AcademicResult::create([
                'student_id' => $student->id,
                'academic_year' => $request->academic_year,
                'student_year' => $request->student_year,
                'course_name' => $request->course_name,
                'gpa' => $request->gpa,
                'cgpa' => $request->cgpa,
                'division' => $request->division,
                'remarks' => $request->remarks,
                'result_file_path' => $filePath,
                'result_file_name' => $file->getClientOriginalName(),
                'status' => 'pending',
            ]);

            return redirect()->route('beneficiary.results.index')
                ->with('success', 'Result uploaded successfully! It is now pending review.');

        } catch (\Exception $e) {
            \Log::error('Failed to upload result', ['error' => $e->getMessage()]);
            return redirect()->back()
                ->with('error', 'Failed to upload result: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * View a specific result.
     */
    public function show($id)
    {
        $student = Auth::user();

        $result = AcademicResult::where('student_id', $student->id)
            ->findOrFail($id);

        return view('beneficiary.results.show', compact('result'));
    }

    /**
     * Download the result file.
     */
    public function download($id)
    {
        $student = Auth::user();

        $result = AcademicResult::where('student_id', $student->id)
            ->findOrFail($id);

        if (!$result->result_file_path) {
            return redirect()->back()
                ->with('error', 'File not found.');
        }

        return Storage::disk('public')->download($result->result_file_path, $result->result_file_name ?? 'result_file');
    }

    /**
     * Delete a result (only if pending).
     */
    public function destroy($id)
    {
        $student = Auth::user();

        $result = AcademicResult::where('student_id', $student->id)
            ->where('status', 'pending')
            ->findOrFail($id);

        try {
            // Delete file if exists
            if ($result->result_file_path && Storage::disk('public')->exists($result->result_file_path)) {
                Storage::disk('public')->delete($result->result_file_path);
            }

            $result->delete();

            return redirect()->route('beneficiary.results.index')
                ->with('success', 'Result deleted successfully.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to delete result: ' . $e->getMessage());
        }
    }
}
