<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Application;
use App\Models\AcademicResult;
use App\Models\StudentPayment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class StudentController extends Controller
{
    /**
     * Display a listing of students (beneficiaries).
     */
    public function index(Request $request)
    {
        // Get beneficiaries with approved applications and approved acknowledgement
        $query = User::where('role', 'beneficiary')
            ->whereHas('applications', function($q) {
                $q->whereIn('status', ['approved_full', 'approved_partial'])
                  ->where('acknowledgement_status', 'approved');
            });

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone_number', 'like', "%{$search}%");
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by academic year
        if ($request->filled('academic_year')) {
            $query->whereHas('academicResults', function($q) use ($request) {
                $q->where('academic_year', $request->academic_year);
            });
        }

        // Filter by suspension status
        if ($request->filled('suspension')) {
            if ($request->suspension == 'suspended') {
                $query->where('is_academic_suspended', true);
            } else {
                $query->where('is_academic_suspended', false);
            }
        }

        // Sort functionality
        $sort = $request->get('sort', 'created_at');
        $direction = $request->get('direction', 'desc');
        $allowedSorts = ['id', 'name', 'email', 'created_at', 'status', 'is_academic_suspended'];
        if (in_array($sort, $allowedSorts)) {
            $query->orderBy($sort, $direction);
        } else {
            $query->orderBy('created_at', 'desc');
        }

        // Pagination
        $perPage = $request->get('per_page', 20);
        $students = $query->with(['applications', 'latestAcademicResult'])
            ->paginate($perPage);

        // Statistics
        $stats = [
            'total' => User::where('role', 'beneficiary')
                ->whereHas('applications', function($q) {
                    $q->whereIn('status', ['approved_full', 'approved_partial'])
                      ->where('acknowledgement_status', 'approved');
                })->count(),
            'active' => User::where('role', 'beneficiary')
                ->where('status', 'active')
                ->whereHas('applications', function($q) {
                    $q->whereIn('status', ['approved_full', 'approved_partial'])
                      ->where('acknowledgement_status', 'approved');
                })->count(),
            'suspended' => User::where('role', 'beneficiary')
                ->where('is_academic_suspended', true)
                ->whereHas('applications', function($q) {
                    $q->whereIn('status', ['approved_full', 'approved_partial'])
                      ->where('acknowledgement_status', 'approved');
                })->count(),
            'inactive' => User::where('role', 'beneficiary')
                ->where('status', 'inactive')
                ->whereHas('applications', function($q) {
                    $q->whereIn('status', ['approved_full', 'approved_partial'])
                      ->where('acknowledgement_status', 'approved');
                })->count(),
        ];

        // Get academic years for filter
        $academicYears = AcademicResult::distinct()->pluck('academic_year');

        return view('admin.students.index', compact('students', 'stats', 'academicYears'));
    }

    /**
     * Display the specified student.
     */
    public function show($id)
    {
        $student = User::with([
            'personalInfo',
            'oLevelEducation',
            'aLevelEducation',
            'motivation',
            'applications',
            'applications.scholarship',
            'academicResults',
            'studentPayments',
            'studentPayments.installment'
        ])->where('role', 'beneficiary')
          ->findOrFail($id);

        // Get the approved application
        $application = $student->applications;

        // Get all academic results
        $academicResults = $student->academicResults()
            ->orderBy('created_at', 'desc')
            ->get();

        // Get payment history
        $payments = $student->studentPayments()
            ->with(['installment'])
            ->orderBy('created_at', 'desc')
            ->get();

        // Payment statistics
        $paymentStats = [
            'total' => $payments->count(),
            'approved' => $payments->where('status', 'approved')->count(),
            'pending' => $payments->where('status', 'pending')->count(),
            'total_amount' => $payments->sum('amount'),
            'approved_amount' => $payments->where('status', 'approved')->sum('amount'),
            'pending_amount' => $payments->where('status', 'pending')->sum('amount'),
        ];

        return view('admin.students.show', compact(
            'student',
            'application',
            'academicResults',
            'payments',
            'paymentStats'
        ));
    }

    /**
     * Update student status.
     */
    public function updateStatus(Request $request, $id)
    {
        $student = User::where('role', 'beneficiary')->findOrFail($id);

        $validator = Validator::make($request->all(), [
            'status' => 'required|in:active,inactive,suspended',
            'is_academic_suspended' => 'nullable|boolean',
            'suspension_reason' => 'nullable|string|required_if:is_academic_suspended,1',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            // Update account status
            $student->status = $request->status;

            // Update academic suspension
            if ($request->has('is_academic_suspended') && $request->is_academic_suspended) {
                $student->is_academic_suspended = true;
                $student->academic_suspended_at = now();
                $student->academic_suspension_reason = $request->suspension_reason;
            } else {
                $student->is_academic_suspended = false;
                $student->academic_suspended_at = null;
                $student->academic_suspension_reason = null;
            }

            $student->save();

            // Update related academic results if suspended
            if ($request->has('is_academic_suspended') && $request->is_academic_suspended) {
                AcademicResult::where('student_id', $student->id)
                    ->where('status', 'approved')
                    ->update([
                        'is_suspended' => true,
                        'suspension_reason' => $request->suspension_reason,
                        'suspended_at' => now()
                    ]);
            } else {
                AcademicResult::where('student_id', $student->id)
                    ->update([
                        'is_suspended' => false,
                        'suspension_reason' => null,
                        'suspended_at' => null,
                        'suspension_lifted_at' => now()
                    ]);
            }

            return redirect()->route('admin.students.show', $student->id)
                ->with('success', 'Student status updated successfully!');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to update student status: ' . $e->getMessage());
        }
    }

    /**
     * Update student information.
     */
    public function update(Request $request, $id)
    {
        $student = User::where('role', 'beneficiary')->findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'phone_number' => 'nullable|string|max:20',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $student->name = $request->name;
            $student->email = $request->email;
            $student->phone_number = $request->phone_number;
            $student->save();

            return redirect()->route('admin.students.show', $student->id)
                ->with('success', 'Student information updated successfully!');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to update student: ' . $e->getMessage());
        }
    }

    /**
     * Export students to CSV.
     */
    public function export(Request $request)
    {
        $students = User::where('role', 'beneficiary')
            ->whereHas('applications', function($q) {
                $q->whereIn('status', ['approved_full', 'approved_partial'])
                  ->where('acknowledgement_status', 'approved');
            })
            ->with(['applications', 'latestAcademicResult'])
            ->get();

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="students_' . date('Y-m-d') . '.csv"',
        ];

        $callback = function() use ($students) {
            $file = fopen('php://output', 'w');

            fputcsv($file, [
                'ID', 'Name', 'Email', 'Phone', 'Status', 'Academic Status',
                'Scholarship', 'Latest GPA', 'Total Payments', 'Total Received'
            ]);

            foreach ($students as $student) {
                $application = $student->applications;
                $payments = $student->studentPayments ?? collect();

                fputcsv($file, [
                    $student->id,
                    $student->name,
                    $student->email,
                    $student->phone_number ?? 'N/A',
                    $student->status,
                    $student->is_academic_suspended ? 'Suspended' : 'Active',
                    $application->scholarship->title ?? 'N/A',
                    $student->latestAcademicResult ? $student->latestAcademicResult->gpa : 'N/A',
                    $payments->count(),
                    $payments->where('status', 'approved')->sum('amount'),
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
