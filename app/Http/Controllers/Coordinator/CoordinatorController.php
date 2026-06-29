<?php

namespace App\Http\Controllers\Coordinator;

use App\Http\Controllers\Controller;
use App\Models\Scholarship;
use Illuminate\Http\Request;

class CoordinatorController extends Controller
{
    //

    /**
     * Display a listing of scholarships with search, filter, and pagination.
     */
    public function index(Request $request)
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
}
