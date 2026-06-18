<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Scholarship;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AdminScholarshipController extends Controller
{
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

        return view('admin.scholarships.index', compact(
            'scholarships',
            'academicYears',
            'totalScholarships',
            'openScholarships',
            'draftScholarships',
            'closedScholarships'
        ));
    }

    /**
     * Show the form for creating a new scholarship.
     */
    public function create()
    {
        return view('admin.scholarships.create');
    }

    /**
     * Store a newly created scholarship in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'eligibility_criteria' => 'required|string',
            'deadline' => 'required|date|after:today',
            'academic_year' => 'required|string|max:20',
            'status' => 'required',
        ]);



        try {
            $scholarship = Scholarship::create([
                'created_by' => Auth::id(),
                'title' => $request->title,
                'description' => $request->description,
                'eligibility_criteria' => $request->eligibility_criteria,
                'deadline' => $request->deadline,
                'academic_year' => $request->academic_year,
                'status' => $request->status,
            ]);

            return redirect()->route('admin.scholarships.index')
                ->with('success', 'Scholarship created successfully!');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to create scholarship: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified scholarship.
     */
    public function show($id)
    {
        $scholarship = Scholarship::with(['creator', 'applications.user'])
            ->findOrFail($id);

        return view('admin.scholarships.show', compact('scholarship'));
    }

    /**
     * Show the form for editing the specified scholarship.
     */
    public function edit($id)
    {
        $scholarship = Scholarship::findOrFail($id);
        return view('admin.scholarships.edit', compact('scholarship'));
    }

    /**
     * Update the specified scholarship in storage.
     */
    public function update(Request $request, $id)
    {
        $scholarship = Scholarship::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'eligibility_criteria' => 'required|string',
            'deadline' => 'required|date|after:today',
            'academic_year' => 'required|string|max:20',
            'status' => 'required|in:draft,open,closed',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $scholarship->update([
                'title' => $request->title,
                'description' => $request->description,
                'eligibility_criteria' => $request->eligibility_criteria,
                'deadline' => $request->deadline,
                'academic_year' => $request->academic_year,
                'status' => $request->status,
            ]);

            return redirect()->route('admin.scholarships.index')
                ->with('success', 'Scholarship updated successfully!');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to update scholarship: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified scholarship from storage.
     */
    public function destroy($id)
    {
        try {
            $scholarship = Scholarship::findOrFail($id);
            $scholarship->delete();

            return redirect()->route('admin.scholarships.index')
                ->with('success', 'Scholarship deleted successfully!');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to delete scholarship: ' . $e->getMessage());
        }
    }

    /**
     * Toggle scholarship status (draft <-> open <-> closed)
     */
    public function toggleStatus($id)
    {
        try {
            $scholarship = Scholarship::findOrFail($id);

            // Toggle status logic
            if ($scholarship->status == 'draft') {
                $scholarship->status = 'open';
                $message = 'Scholarship opened successfully!';
            } elseif ($scholarship->status == 'open') {
                $scholarship->status = 'closed';
                $message = 'Scholarship closed successfully!';
            } else {
                // If closed, can only be re-opened manually via edit
                return redirect()->back()
                    ->with('error', 'Closed scholarships can only be re-opened by editing.');
            }

            $scholarship->save();

            return redirect()->route('admin.scholarships.index')
                ->with('success', $message);

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to update scholarship status: ' . $e->getMessage());
        }
    }

    /**
     * Bulk action for scholarships.
     */
    public function bulkAction(Request $request)
    {
        $request->validate([
            'action' => 'required|in:open,close,delete',
            'scholarship_ids' => 'required|array',
            'scholarship_ids.*' => 'exists:scholarships,id',
        ]);

        try {
            $scholarshipIds = $request->scholarship_ids;

            if (empty($scholarshipIds)) {
                return redirect()->back()
                    ->with('error', 'No scholarships selected.');
            }

            switch ($request->action) {
                case 'open':
                    Scholarship::whereIn('id', $scholarshipIds)
                        ->where('status', '!=', 'closed')
                        ->update(['status' => 'open']);
                    $message = 'Scholarships opened successfully!';
                    break;
                case 'close':
                    Scholarship::whereIn('id', $scholarshipIds)
                        ->where('status', 'open')
                        ->update(['status' => 'closed']);
                    $message = 'Scholarships closed successfully!';
                    break;
                case 'delete':
                    Scholarship::whereIn('id', $scholarshipIds)->delete();
                    $message = 'Scholarships deleted successfully!';
                    break;
                default:
                    return redirect()->back()->with('error', 'Invalid action.');
            }

            return redirect()->route('admin.scholarships.index')
                ->with('success', $message);

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to perform bulk action: ' . $e->getMessage());
        }
    }

    /**
     * Export scholarships to CSV.
     */
    public function export(Request $request)
    {
        $scholarships = Scholarship::with(['creator'])->get();

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="scholarships_' . date('Y-m-d') . '.csv"',
        ];

        $callback = function() use ($scholarships) {
            $file = fopen('php://output', 'w');

            // Add headers
            fputcsv($file, [
                'ID', 'Title', 'Description', 'Eligibility Criteria',
                'Academic Year', 'Deadline', 'Status', 'Created By', 'Created At'
            ]);

            // Add data
            foreach ($scholarships as $scholarship) {
                fputcsv($file, [
                    $scholarship->id,
                    $scholarship->title,
                    strip_tags($scholarship->description),
                    strip_tags($scholarship->eligibility_criteria),
                    $scholarship->academic_year,
                    $scholarship->deadline->format('Y-m-d'),
                    $scholarship->status,
                    $scholarship->creator->name ?? '',
                    $scholarship->created_at->format('Y-m-d H:i:s'),
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
