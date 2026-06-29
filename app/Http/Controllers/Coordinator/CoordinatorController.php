<?php

namespace App\Http\Controllers\Coordinator;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\Scholarship;
use Illuminate\Http\Request;

class CoordinatorController extends Controller
{
    //

    /**
     * Display a listing of scholarships with search, filter, and pagination.
     */
    public function scholarships(Request $request)
    {
        $query = Scholarship::with(['creator', 'applications']);

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('eligibility_criteria', 'like', "%{$search}%")
                  ->orWhere('academic_year', 'like', "%{$search}%");
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by academic year
        if ($request->filled('academic_year')) {
            $query->where('academic_year', $request->academic_year);
        }

        // Sort functionality
        $sort = $request->get('sort', 'created_at');
        $direction = $request->get('direction', 'desc');

        $allowedSorts = ['id', 'title', 'deadline', 'status', 'academic_year', 'created_at'];
        if (in_array($sort, $allowedSorts)) {
            $query->orderBy($sort, $direction);
        } else {
            $query->orderBy('created_at', 'desc');
        }

        // Pagination
        $perPage = $request->get('per_page', 10);
        $scholarships = $query->paginate($perPage);

        // Get all academic years for filter
        $academicYears = Scholarship::select('academic_year')
            ->distinct()
            ->orderBy('academic_year', 'desc')
            ->pluck('academic_year');

        // Statistics
        $totalScholarships = Scholarship::count();
        $openScholarships = Scholarship::where('status', 'open')->count();
        $draftScholarships = Scholarship::where('status', 'draft')->count();
        $closedScholarships = Scholarship::where('status', 'closed')->count();

        return view('coordinator.scholarships.index', compact(
            'scholarships',
            'academicYears',
            'totalScholarships',
            'openScholarships',
            'draftScholarships',
            'closedScholarships'
        ));
    }


        public function showScholarship($id)
    {
        $scholarship = Scholarship::with(['creator', 'applications.user'])
            ->findOrFail($id);

        return view('coordinator.scholarships.show', compact('scholarship'));
    }


     /**
     * Display a listing of applications with search, filter, and pagination.
     */
    public function applications(Request $request)
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
                ->orWhereHas('user.oLevelEducation', function($eduQuery) use ($search) {
                    $eduQuery->where('form_four_index_number', 'like', "%{$search}%");
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

        return view('coordinator.applications.index', compact(
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
    public function showApplication($id)
    {
        $application = Application::with([
            'user',
            'user.personalInfo',
            'user.oLevelEducation',
            'user.aLevelEducation',
            'user.motivation',
            'scholarship'
        ])->findOrFail($id);

        return view('coordinator.applications.show', compact('application'));


    }

}
