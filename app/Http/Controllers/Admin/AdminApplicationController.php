<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\Scholarship;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AdminApplicationController extends Controller
{
    /**
     * Display a listing of applications with search, filter, and pagination.
     */
    public function index(Request $request)
    {
        $query = Application::with(['user', 'scholarship']);

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('user', function($userQuery) use ($search) {
                    $userQuery->where('name', 'like', "%{$search}%")
                              ->orWhere('email', 'like', "%{$search}%");
                })
                ->orWhereHas('user.aLevelEducation', function($eduQuery) use ($search) {
                    $eduQuery->where('school_name', 'like', "%{$search}%");
                })
                ->orWhereHas('scholarship', function($scholarQuery) use ($search) {
                    $scholarQuery->where('title', 'like', "%{$search}%");
                });
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by scholarship
        if ($request->filled('scholarship')) {
            $query->where('scholarship_id', $request->scholarship);
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('submitted_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('submitted_at', '<=', $request->date_to);
        }

        // Sort functionality
        $sort = $request->get('sort', 'created_at');
        $direction = $request->get('direction', 'desc');

        $allowedSorts = ['id', 'user_id', 'status', 'submitted_at', 'created_at'];
        if (in_array($sort, $allowedSorts)) {
            $query->orderBy($sort, $direction);
        } else {
            $query->orderBy('created_at', 'desc');
        }

        // Pagination
        $perPage = $request->get('per_page', 10);
        $applications = $query->paginate($perPage);

        // Get all scholarships for filter
        $scholarships = Scholarship::all();

        // Statistics
        $totalApplications = Application::count();
        $pendingApplications = Application::where('status', 'pending')->count();
        $submittedApplications = Application::where('status', 'submitted')->count();
        $underReviewApplications = Application::where('status', 'under_review')->count();
        $approvedFullApplications = Application::where('status', 'approved_full')->count();
        $approvedPartialApplications = Application::where('status', 'approved_partial')->count();
        $rejectedApplications = Application::where('status', 'rejected')->count();

        return view('admin.applications.index', compact(
            'applications',
            'scholarships',
            'totalApplications',
            'pendingApplications',
            'submittedApplications',
            'underReviewApplications',
            'approvedFullApplications',
            'approvedPartialApplications',
            'rejectedApplications'
        ));
    }

    /**
     * Show the specified application.
     */
    public function show($id)
    {
        $application = Application::with([
            'user',
            'user.personalInfo',
            'user.oLevelEducation',
            'user.aLevelEducation',
            'user.motivation',
            'scholarship'
        ])->findOrFail($id);

        return view('admin.applications.show', compact('application'));


    }

    /**
     * Show the form for editing the specified application.
     */
    public function edit($id)
    {
        $application = Application::with(['user', 'scholarship'])->findOrFail($id);
        $scholarships = Scholarship::all();
        return view('admin.applications.edit', compact('application', 'scholarships'));
    }

    /**
     * Update the specified application in storage.
     */
    public function update(Request $request, $id)
    {
        $application = Application::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'status' => 'required|in:pending,submitted,under_review,approved_full,approved_partial,rejected',
            'admin_notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $application->status = $request->status;
            $application->admin_notes = $request->admin_notes;
            $application->save();

            return redirect()->route('admin.applications.index')
                ->with('success', 'Application updated successfully!');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to update application: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Review application (change status to under_review).
     */
    public function review($id)
    {
        $application = Application::findOrFail($id);

        try {
            $application->status = 'under_review';
            $application->save();

            return redirect()->route('admin.applications.show', $id)
                ->with('success', 'Application is now under review.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to update application status: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified application from storage.
     */
    public function destroy($id)
    {
        try {
            $application = Application::findOrFail($id);
            $application->delete();

            return redirect()->route('admin.applications.index')
                ->with('success', 'Application deleted successfully!');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to delete application: ' . $e->getMessage());
        }
    }

    /**
     * Bulk action for applications.
     */
    public function bulkAction(Request $request)
    {
        $request->validate([
            'action' => 'required|in:under_review,approved_full,approved_partial,rejected,delete',
            'application_ids' => 'required',

        ]);

        try {
            $applicationIds = $request->application_ids;
             // If it's a JSON string, decode it
        if (is_string($applicationIds)) {
            $applicationIds = json_decode($applicationIds, true);
        }

        // Ensure it's an array
        if (!is_array($applicationIds)) {
            $applicationIds = [$applicationIds];
        }

        // Filter out empty values
        $applicationIds = array_filter($applicationIds);
            if (empty($applicationIds)) {
                return redirect()->back()
                    ->with('error', 'No applications selected.');
            }

            switch ($request->action) {
                case 'under_review':
                    Application::whereIn('id', $applicationIds)->update(['status' => 'under_review']);
                    $message = 'Applications marked as under review!';
                    break;
                case 'approved_full':
                    Application::whereIn('id', $applicationIds)->update(['status' => 'approved_full']);
                    $message = 'Applications approved (full scholarship)!';
                    break;
                case 'approved_partial':
                    Application::whereIn('id', $applicationIds)->update(['status' => 'approved_partial']);
                    $message = 'Applications approved (partial scholarship)!';
                    break;
                case 'rejected':
                    Application::whereIn('id', $applicationIds)->update(['status' => 'rejected']);
                    $message = 'Applications rejected!';
                    break;
                case 'delete':
                    Application::whereIn('id', $applicationIds)->delete();
                    $message = 'Applications deleted successfully!';
                    break;
                default:
                    return redirect()->back()->with('error', 'Invalid action.');
            }

            return redirect()->route('admin.applications.index')
                ->with('success', $message);

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to perform bulk action: ' . $e->getMessage());
        }
    }

    /**
     * Update status via AJAX.
     */
    public function updateStatus(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:applications,id',
            'status' => 'required|in:pending,submitted,under_review,approved_full,approved_partial,rejected',
        ]);

        try {
            $application = Application::find($request->id);
            $application->status = $request->status;
            $application->save();

            if ($request->ajax()) {
                return response()->json(['success' => true]);
            }

            return redirect()->route('admin.applications.show', $application->id)
                ->with('success', 'Application status updated successfully!');

        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => $e->getMessage()]);
            }
            return redirect()->back()
                ->with('error', 'Failed to update status: ' . $e->getMessage());
        }
    }

    /**
     * Export applications to CSV.
     */
    public function export(Request $request)
    {
        $applications = Application::with(['user', 'scholarship'])->get();

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="applications_' . date('Y-m-d') . '.csv"',
        ];

        $callback = function() use ($applications) {
            $file = fopen('php://output', 'w');

            // Add headers
            fputcsv($file, [
                'ID', 'Applicant Name', 'Email', 'Scholarship',
                'Academic Year', 'Status', 'Submitted At', 'Admin Notes'
            ]);

            // Add data
            foreach ($applications as $application) {
                fputcsv($file, [
                    $application->id,
                    $application->user->name ?? '',
                    $application->user->email ?? '',
                    $application->scholarship->title ?? '',
                    $application->scholarship->academic_year ?? '',
                    $application->status,
                    $application->submitted_at ? $application->submitted_at->format('Y-m-d H:i:s') : '',
                    $application->admin_notes ?? '',
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }



    /**
 * Update acknowledgement letter status.
 */
public function updateAcknowledgement(Request $request, $id)
{
    $application = Application::findOrFail($id);

    $validator = Validator::make($request->all(), [
        'acknowledgement_status' => 'required|in:pending,submitted,approved,rejected',
        'acknowledgement_admin_notes' => 'nullable|string',
    ]);

    if ($validator->fails()) {
        return redirect()->back()
            ->withErrors($validator)
            ->withInput();
    }

    try {
        $application->acknowledgement_status = $request->acknowledgement_status;
        $application->acknowledgement_admin_notes = $request->acknowledgement_admin_notes;
        $application->save();

        return redirect()->route('admin.applications.show', $id)
            ->with('success', 'Acknowledgement letter status updated successfully!');

    } catch (\Exception $e) {
        return redirect()->back()
            ->with('error', 'Failed to update acknowledgement status: ' . $e->getMessage());
    }
}


}
