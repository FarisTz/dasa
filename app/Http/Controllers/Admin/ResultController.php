<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\ResultApprovedNotification;
use App\Mail\ResultRejectedNotification;
use App\Models\AcademicResult;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Log;

class ResultController extends Controller
{
    /**
     * Display all academic results.
     */
    public function index(Request $request)
    {
        $query = AcademicResult::with(['student', 'reviewer']);

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by academic year
        if ($request->filled('academic_year')) {
            $query->where('academic_year', $request->academic_year);
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('student', function($userQuery) use ($search) {
                    $userQuery->where('name', 'like', "%{$search}%")
                              ->orWhere('email', 'like', "%{$search}%");
                })
                ->orWhere('academic_year', 'like', "%{$search}%")
                ->orWhere('course_name', 'like', "%{$search}%");
            });
        }

        // Filter by suspension status
        if ($request->filled('suspension_status')) {
            if ($request->suspension_status === 'suspended') {
                $query->where('is_suspended', true);
            } elseif ($request->suspension_status === 'active') {
                $query->where('is_suspended', false);
            }
        }

        $results = $query->orderBy('created_at', 'desc')
            ->paginate(20);

        // Statistics
        $stats = [
            'total' => AcademicResult::count(),
            'pending' => AcademicResult::where('status', 'pending')->count(),
            'under_review' => AcademicResult::where('status', 'under_review')->count(),
            'approved' => AcademicResult::where('status', 'approved')->count(),
            'rejected' => AcademicResult::where('status', 'rejected')->count(),
            'suspended' => AcademicResult::where('is_suspended', true)->count(),
        ];

        // Get academic years for filter
        $academicYears = AcademicResult::distinct()->pluck('academic_year');

        return view('admin.results.index', compact('results', 'stats', 'academicYears'));
    }

    /**
     * Show a specific result.
     */
    public function show($id)
    {
        $result = AcademicResult::with(['student', 'reviewer'])
            ->findOrFail($id);

        // Get student's all results
        $studentResults = AcademicResult::where('student_id', $result->student_id)
            ->where('id', '!=', $id)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.results.show', compact('result', 'studentResults'));
    }

    /**
     * Approve a result.
     */
    public function approve(Request $request, $id)
    {
        $result = AcademicResult::with(['student'])->findOrFail($id);

        $validator = Validator::make($request->all(), [
            'admin_feedback' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $result->status = 'approved';
            $result->admin_feedback = $request->admin_feedback;
            $result->reviewed_by = Auth::id();
            $result->reviewed_at = now();

            // If student was suspended, lift suspension
            if ($result->is_suspended) {
                $result->is_suspended = false;
                $result->suspended_at = null;
                $result->suspension_lifted_at = now();
                $result->suspension_reason = null;

                // Also lift from user table
                $student = $result->student;
                $student->liftAcademicSuspension();
            }

            $result->save();

            // Send email notification
            try {
                // Mail::to($result->student->email)->send(new ResultApprovedNotification($result));
            } catch (\Exception $e) {
                \Log::error('Failed to send approval email', ['error' => $e->getMessage()]);
            }

            return redirect()->route('admin.results.index')
                ->with('success', 'Result approved successfully!');

        } catch (\Exception $e) {
            \Log::error('Failed to approve result', ['error' => $e->getMessage()]);
            return redirect()->back()
                ->with('error', 'Failed to approve result: ' . $e->getMessage());
        }
    }

    /**
     * Reject a result.
     */
    public function reject(Request $request, $id)
    {
        $result = AcademicResult::with(['student'])->findOrFail($id);

        $validator = Validator::make($request->all(), [
            'admin_feedback' => 'required|string|min:10',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $result->status = 'rejected';
            $result->admin_feedback = $request->admin_feedback;
            $result->reviewed_by = Auth::id();
            $result->reviewed_at = now();
            $result->save();

            // Send email notification
            try {
                // Mail::to($result->student->email)->send(new ResultRejectedNotification($result, $request->admin_feedback));
            } catch (\Exception $e) {
                \Log::error('Failed to send rejection email', ['error' => $e->getMessage()]);
            }

            return redirect()->route('admin.results.index')
                ->with('success', 'Result rejected successfully!');

        } catch (\Exception $e) {
            \Log::error('Failed to reject result', ['error' => $e->getMessage()]);
            return redirect()->back()
                ->with('error', 'Failed to reject result: ' . $e->getMessage());
        }
    }

    /**
     * Suspend a student from payments.
     */
    public function suspend(Request $request, $id)
    {
        $result = AcademicResult::with(['student'])->findOrFail($id);

        $validator = Validator::make($request->all(), [
            'suspension_reason' => 'required|string|min:10',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $student = $result->student;

            // Update result
            $result->is_suspended = true;
            $result->suspension_reason = $request->suspension_reason;
            $result->suspended_at = now();
            $result->save();

            // Update user
            $student->suspendAcademically($request->suspension_reason);

            // Send email notification
            try {
                // Mail::to($student->email)->send(new StudentSuspendedNotification($student, $request->suspension_reason));
            } catch (\Exception $e) {
                \Log::error('Failed to send suspension email', ['error' => $e->getMessage()]);
            }

            return redirect()->route('admin.results.index')
                ->with('success', 'Student suspended successfully!');

        } catch (\Exception $e) {
            \Log::error('Failed to suspend student', ['error' => $e->getMessage()]);
            return redirect()->back()
                ->with('error', 'Failed to suspend student: ' . $e->getMessage());
        }
    }

    /**
     * Lift suspension for a student.
     */
    public function liftSuspension($id)
    {
        $result = AcademicResult::with(['student'])->findOrFail($id);

        try {
            $student = $result->student;

            // Update result
            $result->is_suspended = false;
            $result->suspension_lifted_at = now();
            $result->save();

            // Update user
            $student->liftAcademicSuspension();

            return redirect()->route('admin.results.index')
                ->with('success', 'Suspension lifted successfully!');

        } catch (\Exception $e) {
            \Log::error('Failed to lift suspension', ['error' => $e->getMessage()]);
            return redirect()->back()
                ->with('error', 'Failed to lift suspension: ' . $e->getMessage());
        }
    }

    /**
     * Bulk action for results.
     */
    public function bulkAction(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'action' => 'required|in:approve,reject,suspend,lift_suspension,delete',
            'result_ids' => 'required|array',
            'result_ids.*' => 'exists:academic_results,id',
            'admin_feedback' => 'nullable|string',
            'suspension_reason' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $resultIds = $request->result_ids;

            switch ($request->action) {
                case 'approve':
                    AcademicResult::whereIn('id', $resultIds)
                        ->update([
                            'status' => 'approved',
                            'reviewed_by' => Auth::id(),
                            'reviewed_at' => now(),
                            'admin_feedback' => $request->admin_feedback,
                        ]);
                    $message = 'Results approved successfully!';
                    break;

                case 'reject':
                    if (empty($request->admin_feedback)) {
                        return redirect()->back()
                            ->with('error', 'Admin feedback is required for rejection.');
                    }
                    AcademicResult::whereIn('id', $resultIds)
                        ->update([
                            'status' => 'rejected',
                            'reviewed_by' => Auth::id(),
                            'reviewed_at' => now(),
                            'admin_feedback' => $request->admin_feedback,
                        ]);
                    $message = 'Results rejected successfully!';
                    break;

                case 'suspend':
                    if (empty($request->suspension_reason)) {
                        return redirect()->back()
                            ->with('error', 'Suspension reason is required.');
                    }

                    $results = AcademicResult::whereIn('id', $resultIds)->get();
                    foreach ($results as $result) {
                        $result->is_suspended = true;
                        $result->suspension_reason = $request->suspension_reason;
                        $result->suspended_at = now();
                        $result->save();

                        // Suspend user
                        $student = $result->student;
                        $student->suspendAcademically($request->suspension_reason);
                    }
                    $message = 'Students suspended successfully!';
                    break;

                case 'lift_suspension':
                    $results = AcademicResult::whereIn('id', $resultIds)->get();
                    foreach ($results as $result) {
                        $result->is_suspended = false;
                        $result->suspension_lifted_at = now();
                        $result->save();

                        // Lift suspension from user
                        $student = $result->student;
                        $student->liftAcademicSuspension();
                    }
                    $message = 'Suspension lifted successfully!';
                    break;

                case 'delete':
                    $results = AcademicResult::whereIn('id', $resultIds)->get();
                    foreach ($results as $result) {
                        // Delete file
                        if ($result->result_file_path && Storage::disk('public')->exists($result->result_file_path)) {
                            Storage::disk('public')->delete($result->result_file_path);
                        }
                        $result->delete();
                    }
                    $message = 'Results deleted successfully!';
                    break;

                default:
                    return redirect()->back()->with('error', 'Invalid action.');
            }

            return redirect()->route('admin.results.index')
                ->with('success', $message);

        } catch (\Exception $e) {
            Log::error('Failed to perform bulk action', ['error' => $e->getMessage()]);
            return redirect()->back()
                ->with('error', 'Failed to perform action: ' . $e->getMessage());
        }
    }

    /**
     * Download result file.
     */
    public function download($id)
    {
        $result = AcademicResult::findOrFail($id);

        if (!$result->result_file_path) {
            return redirect()->back()
                ->with('error', 'File not found.');
        }

        return Storage::disk('public')->download(
            $result->result_file_path
        );
    }
}
