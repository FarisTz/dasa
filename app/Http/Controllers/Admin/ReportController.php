<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AcademicResult;
use App\Models\Application;
use App\Models\Installment;
use App\Models\Scholarship;
use App\Models\StudentPayment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ReportController extends Controller
{
    /**
     * Dashboard for all reports.
     */
    public function index()
    {
        // Summary statistics
        $stats = [
            'total_applications' => Application::count(),
            'pending_applications' => Application::where('status', 'pending')->count(),
            'approved_applications' => Application::whereIn('status', ['approved_full', 'approved_partial'])->count(),
            'rejected_applications' => Application::where('status', 'rejected')->count(),
            'total_beneficiaries' => User::where('role', 'beneficiary')->count(),
            'total_scholarships' => Scholarship::count(),
            'open_scholarships' => Scholarship::where('status', 'open')->count(),
        ];

        return view('admin.reports.index', compact('stats'));
    }

    /**
     * Application Report with Charts.
     */
    public function applicationReport(Request $request)
    {
        // Get application statistics by status
        $statusStats = [
            'pending' => Application::where('status', 'pending')->count(),
            'submitted' => Application::where('status', 'submitted')->count(),
            'under_review' => Application::where('status', 'under_review')->count(),
            'approved_full' => Application::where('status', 'approved_full')->count(),
            'approved_partial' => Application::where('status', 'approved_partial')->count(),
            'rejected' => Application::where('status', 'rejected')->count(),
        ];

        // Monthly application trends
        $monthlyData = Application::select(
            DB::raw('YEAR(created_at) as year'),
            DB::raw('MONTH(created_at) as month'),
            DB::raw('COUNT(*) as total')
        )
        ->where('created_at', '>=', now()->subMonths(12))
        ->groupBy('year', 'month')
        ->orderBy('year', 'asc')
        ->orderBy('month', 'asc')
        ->get();

        $monthlyLabels = [];
        $monthlyCounts = [];
        foreach ($monthlyData as $data) {
            $monthlyLabels[] = date('M Y', mktime(0, 0, 0, $data->month, 1, $data->year));
            $monthlyCounts[] = $data->total;
        }

        // Applications by scholarship
        $scholarshipApps = Scholarship::withCount('applications')
            ->orderBy('applications_count', 'desc')
            ->limit(10)
            ->get();

        $scholarshipLabels = [];
        $scholarshipCounts = [];
        foreach ($scholarshipApps as $scholarship) {
            $scholarshipLabels[] = Str::limit($scholarship->title, 20);
            $scholarshipCounts[] = $scholarship->applications_count;
        }

        // Application status breakdown for pie chart
        $statusLabels = ['Pending', 'Submitted', 'Under Review', 'Approved Full', 'Approved Partial', 'Rejected'];
        $statusData = array_values($statusStats);

        // Monthly application trends by status (last 6 months)
        $monthlyStatusData = Application::select(
            DB::raw('YEAR(created_at) as year'),
            DB::raw('MONTH(created_at) as month'),
            'status',
            DB::raw('COUNT(*) as total')
        )
        ->where('created_at', '>=', now()->subMonths(6))
        ->groupBy('year', 'month', 'status')
        ->orderBy('year', 'asc')
        ->orderBy('month', 'asc')
        ->get();

        // Get unique statuses and months for stacked chart
        $statuses = ['pending', 'submitted', 'under_review', 'approved_full', 'approved_partial', 'rejected'];
        $months = [];
        $statusDataByMonth = [];

        foreach ($monthlyStatusData as $data) {
            $monthKey = date('M Y', mktime(0, 0, 0, $data->month, 1, $data->year));
            if (!in_array($monthKey, $months)) {
                $months[] = $monthKey;
            }

            if (!isset($statusDataByMonth[$data->status])) {
                $statusDataByMonth[$data->status] = [];
            }
            $statusDataByMonth[$data->status][$monthKey] = $data->total;
        }

        // Fill missing months with 0
        foreach ($statuses as $status) {
            if (!isset($statusDataByMonth[$status])) {
                $statusDataByMonth[$status] = [];
            }
            foreach ($months as $month) {
                if (!isset($statusDataByMonth[$status][$month])) {
                    $statusDataByMonth[$status][$month] = 0;
                }
            }
        }

        // Get academic years for filter
        $academicYears = Application::distinct()
            ->join('scholarships', 'applications.scholarship_id', '=', 'scholarships.id')
            ->select('scholarships.academic_year')
            ->distinct()
            ->pluck('academic_year');

        $totalApplications = Application::count();
        $approvedApplications = Application::whereIn('status', ['approved_full', 'approved_partial'])->count();
        $rejectionRate = $totalApplications > 0 ? round(($statusStats['rejected'] / $totalApplications) * 100, 2) : 0;
        $approvalRate = $totalApplications > 0 ? round(($approvedApplications / $totalApplications) * 100, 2) : 0;

        return view('admin.reports.application', compact(
            'statusStats',
            'monthlyLabels',
            'monthlyCounts',
            'scholarshipLabels',
            'scholarshipCounts',
            'statusLabels',
            'statusData',
            'statuses',
            'months',
            'statusDataByMonth',
            'totalApplications',
            'approvedApplications',
            'rejectionRate',
            'approvalRate',
            'academicYears'
        ));
    }

    /**
     * Beneficiary Financial Report with Charts.
     */
    public function beneficiaryFinancialReport(Request $request)
    {
        $query = User::where('role', 'beneficiary');

        // Filters
        if ($request->filled('academic_year')) {
            $query->whereHas('academicResults', function($q) use ($request) {
                $q->where('academic_year', $request->academic_year);
            });
        }

        if ($request->filled('suspension_status')) {
            if ($request->suspension_status == 'suspended') {
                $query->where('is_academic_suspended', true);
            } else {
                $query->where('is_academic_suspended', false);
            }
        }

        $beneficiaries = $query->with(['studentPayments', 'academicResults'])->get();

        $reportData = [];
        $summary = [
            'total_beneficiaries' => $beneficiaries->count(),
            'total_allocated' => 0,
            'total_received' => 0,
            'total_pending' => 0,
            'suspended_count' => 0,
        ];

        $paymentStatusCounts = [
            'pending' => 0,
            'confirmed' => 0,
            'approved' => 0,
            'rejected' => 0,
        ];

        foreach ($beneficiaries as $beneficiary) {
            $totalAllocated = $beneficiary->studentPayments->sum('amount');
            $totalReceived = $beneficiary->studentPayments->where('status', 'approved')->sum('amount');
            $totalPending = $beneficiary->studentPayments->where('status', 'pending')->sum('amount');

            $summary['total_allocated'] += $totalAllocated;
            $summary['total_received'] += $totalReceived;
            $summary['total_pending'] += $totalPending;

            // Count payment statuses
            $paymentStatusCounts['pending'] += $beneficiary->studentPayments->where('status', 'pending')->count();
            $paymentStatusCounts['confirmed'] += $beneficiary->studentPayments->where('status', 'confirmed')->count();
            $paymentStatusCounts['approved'] += $beneficiary->studentPayments->where('status', 'approved')->count();
            $paymentStatusCounts['rejected'] += $beneficiary->studentPayments->where('status', 'rejected')->count();

            if ($beneficiary->is_academic_suspended) {
                $summary['suspended_count']++;
            }

            $reportData[] = [
                'beneficiary' => $beneficiary,
                'total_allocated' => $totalAllocated,
                'total_received' => $totalReceived,
                'total_pending' => $totalPending,
                'payment_count' => $beneficiary->studentPayments->count(),
                'approved_count' => $beneficiary->studentPayments->where('status', 'approved')->count(),
                'is_suspended' => $beneficiary->is_academic_suspended,
                'latest_result' => $beneficiary->latestAcademicResult,
            ];
        }

        // Top 10 beneficiaries by amount received
        $topBeneficiaries = collect($reportData)
            ->sortByDesc('total_received')
            ->take(10)
            ->values();

        // Payment distribution (amount ranges)
        $amountRanges = [
            '0-100,000' => 0,
            '100,001-500,000' => 0,
            '500,001-1,000,000' => 0,
            '1,000,000-5,000,000' => 0,
            '5,000,000+' => 0,
        ];

        foreach ($reportData as $data) {
            $amount = $data['total_received'];
            if ($amount <= 100000) {
                $amountRanges['0-100,000']++;
            } elseif ($amount <= 500000) {
                $amountRanges['100,001-500,000']++;
            } elseif ($amount <= 1000000) {
                $amountRanges['500,001-1,000,000']++;
            } elseif ($amount <= 5000000) {
                $amountRanges['1,000,000-5,000,000']++;
            } else {
                $amountRanges['5,000,000+']++;
            }
        }

        // Get academic years for filter
        $academicYears = AcademicResult::distinct()->pluck('academic_year');

        return view('admin.reports.beneficiary-financial', compact(
            'reportData',
            'summary',
            'paymentStatusCounts',
            'topBeneficiaries',
            'amountRanges',
            'academicYears'
        ));
    }

    /**
     * Academic Performance Report with Charts.
     */
    public function academicPerformanceReport(Request $request)
    {
        $query = AcademicResult::with(['student']);

        // Filters
        if ($request->filled('academic_year')) {
            $query->where('academic_year', $request->academic_year);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('min_gpa')) {
            $query->where('gpa', '>=', $request->min_gpa);
        }

        $results = $query->orderBy('created_at', 'desc')->get();

        // Group by student
        $studentPerformance = [];
        foreach ($results as $result) {
            $studentId = $result->student_id;
            if (!isset($studentPerformance[$studentId])) {
                $studentPerformance[$studentId] = [
                    'student' => $result->student,
                    'results' => [],
                    'average_gpa' => 0,
                    'total_results' => 0,
                    'latest_result' => null,
                ];
            }
            $studentPerformance[$studentId]['results'][] = $result;
            $studentPerformance[$studentId]['total_results']++;

            if (!$studentPerformance[$studentId]['latest_result'] ||
                $result->created_at > $studentPerformance[$studentId]['latest_result']->created_at) {
                $studentPerformance[$studentId]['latest_result'] = $result;
            }
        }

        // Calculate averages
        foreach ($studentPerformance as &$performance) {
            $gpas = array_column($performance['results'], 'gpa');
            $performance['average_gpa'] = count($gpas) > 0 ? array_sum($gpas) / count($gpas) : 0;
        }

        // Sort by average GPA (descending)
        usort($studentPerformance, function($a, $b) {
            return $b['average_gpa'] <=> $a['average_gpa'];
        });

        // GPA Distribution
        $gpaDistribution = [
            '4.0 (Excellent)' => 0,
            '3.5 - 3.99 (Very Good)' => 0,
            '3.0 - 3.49 (Good)' => 0,
            '2.5 - 2.99 (Satisfactory)' => 0,
            '2.0 - 2.49 (Poor)' => 0,
            'Below 2.0 (Very Poor)' => 0,
        ];

        foreach ($studentPerformance as $performance) {
            $gpa = $performance['average_gpa'];
            if ($gpa >= 4.0) {
                $gpaDistribution['4.0 (Excellent)']++;
            } elseif ($gpa >= 3.5) {
                $gpaDistribution['3.5 - 3.99 (Very Good)']++;
            } elseif ($gpa >= 3.0) {
                $gpaDistribution['3.0 - 3.49 (Good)']++;
            } elseif ($gpa >= 2.5) {
                $gpaDistribution['2.5 - 2.99 (Satisfactory)']++;
            } elseif ($gpa >= 2.0) {
                $gpaDistribution['2.0 - 2.49 (Poor)']++;
            } else {
                $gpaDistribution['Below 2.0 (Very Poor)']++;
            }
        }

        // Performance by academic year
        $yearlyPerformance = AcademicResult::select(
            'academic_year',
            DB::raw('AVG(gpa) as avg_gpa'),
            DB::raw('COUNT(*) as total_results')
        )
        ->groupBy('academic_year')
        ->orderBy('academic_year', 'asc')
        ->get();

        // Statistics
        $stats = [
            'total_results' => $results->count(),
            'total_students' => count($studentPerformance),
            'approved_results' => $results->where('status', 'approved')->count(),
            'rejected_results' => $results->where('status', 'rejected')->count(),
            'pending_results' => $results->where('status', 'pending')->count(),
            'under_review_results' => $results->where('status', 'under_review')->count(),
            'suspended_students' => $results->where('is_suspended', true)->count(),
            'average_gpa_overall' => $results->avg('gpa'),
            'highest_gpa' => $results->max('gpa'),
            'lowest_gpa' => $results->min('gpa'),
        ];

        // Get academic years for filter
        $academicYears = AcademicResult::distinct()->pluck('academic_year');

        return view('admin.reports.academic-performance', compact(
            'studentPerformance',
            'stats',
            'gpaDistribution',
            'yearlyPerformance',
            'academicYears'
        ));
    }

    /**
     * Scholarship Utilization Report with Charts.
     */
    public function scholarshipUtilizationReport(Request $request)
    {
        // Get all scholarships
        $scholarships = Scholarship::with(['applications', 'applications.user'])
            ->get();

        // Get all payments
        $payments = StudentPayment::with(['student', 'installment'])->get();

        // Calculate scholarship statistics
        $scholarshipStats = [];
        $totalUtilization = 0;

        foreach ($scholarships as $scholarship) {
            $applications = $scholarship->applications;
            $approvedApplications = $applications->whereIn('status', ['approved_full', 'approved_partial']);

            // Calculate total paid for this scholarship
            $totalPaid = 0;
            foreach ($applications as $app) {
                if ($app->user) {
                    $totalPaid += $app->user->studentPayments->where('status', 'approved')->sum('amount');
                }
            }

            $utilizationRate = $applications->count() > 0 ?
                round(($approvedApplications->count() / $applications->count()) * 100, 2) : 0;

            $scholarshipStats[] = [
                'scholarship' => $scholarship,
                'total_applications' => $applications->count(),
                'approved_applications' => $approvedApplications->count(),
                'beneficiaries' => $applications->where('status', 'approved_full')->count(),
                'total_paid' => $totalPaid,
                'utilization_rate' => $utilizationRate,
            ];

            $totalUtilization += $utilizationRate;
        }

        // Sort by utilization rate
        usort($scholarshipStats, function($a, $b) {
            return $b['utilization_rate'] <=> $a['utilization_rate'];
        });

        // Payment statistics
        $paymentStats = [
            'total_payments' => $payments->count(),
            'total_amount' => $payments->sum('amount'),
            'approved_amount' => $payments->where('status', 'approved')->sum('amount'),
            'pending_amount' => $payments->where('status', 'pending')->sum('amount'),
            'rejected_amount' => $payments->where('status', 'rejected')->sum('amount'),
            'confirmed_amount' => $payments->where('status', 'confirmed')->sum('amount'),
            'average_payment' => $payments->count() > 0 ? $payments->avg('amount') : 0,
        ];

        // Payment status breakdown
        $statusBreakdown = [
            'pending' => $payments->where('status', 'pending')->count(),
            'confirmed' => $payments->where('status', 'confirmed')->count(),
            'approved' => $payments->where('status', 'approved')->count(),
            'rejected' => $payments->where('status', 'rejected')->count(),
        ];

        // Monthly payment trends (last 12 months)
        $monthlyTrends = StudentPayment::select(
            DB::raw('YEAR(created_at) as year'),
            DB::raw('MONTH(created_at) as month'),
            DB::raw('COUNT(*) as total'),
            DB::raw('SUM(amount) as total_amount')
        )
        ->where('created_at', '>=', now()->subMonths(12))
        ->groupBy('year', 'month')
        ->orderBy('year', 'asc')
        ->orderBy('month', 'asc')
        ->get();

        $monthlyLabels = [];
        $monthlyPaymentCounts = [];
        $monthlyPaymentAmounts = [];

        foreach ($monthlyTrends as $data) {
            $monthlyLabels[] = date('M Y', mktime(0, 0, 0, $data->month, 1, $data->year));
            $monthlyPaymentCounts[] = $data->total;
            $monthlyPaymentAmounts[] = $data->total_amount;
        }

        // Get academic years for filter
        $academicYears = AcademicResult::distinct()->pluck('academic_year');

        return view('admin.reports.scholarship-utilization', compact(
            'scholarshipStats',
            'paymentStats',
            'statusBreakdown',
            'monthlyTrends',
            'monthlyLabels',
            'monthlyPaymentCounts',
            'monthlyPaymentAmounts',
            'academicYears'
        ));
    }

    /**
     * Export Reports as CSV.
     */
    public function exportCSV(Request $request)
    {
        $type = $request->type;

        if ($type == 'application') {
            return $this->exportApplicationCSV($request);
        } elseif ($type == 'financial') {
            return $this->exportFinancialCSV($request);
        } elseif ($type == 'academic') {
            return $this->exportAcademicCSV($request);
        } else {
            return $this->exportUtilizationCSV($request);
        }
    }

    private function exportApplicationCSV($request)
    {
        $applications = Application::with(['user', 'scholarship'])->get();

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="application_report_' . date('Y-m-d') . '.csv"',
        ];

        $callback = function() use ($applications) {
            $file = fopen('php://output', 'w');

            fputcsv($file, [
                'ID', 'Applicant Name', 'Email', 'Scholarship', 'Academic Year',
                'Status', 'Submitted Date', 'Created Date'
            ]);

            foreach ($applications as $app) {
                fputcsv($file, [
                    $app->id,
                    $app->user->name ?? 'N/A',
                    $app->user->email ?? 'N/A',
                    $app->scholarship->title ?? 'N/A',
                    $app->scholarship->academic_year ?? 'N/A',
                    $app->status,
                    $app->submitted_at ? $app->submitted_at->format('Y-m-d') : 'N/A',
                    $app->created_at->format('Y-m-d'),
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    private function exportFinancialCSV($request)
    {
        $beneficiaries = User::where('role', 'beneficiary')
            ->with(['studentPayments', 'academicResults'])
            ->get();

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="financial_report_' . date('Y-m-d') . '.csv"',
        ];

        $callback = function() use ($beneficiaries) {
            $file = fopen('php://output', 'w');

            fputcsv($file, [
                'Name', 'Email', 'Phone', 'Total Allocated', 'Total Received', 'Total Pending',
                'Payment Count', 'Approved Count', 'Status', 'Latest GPA'
            ]);

            foreach ($beneficiaries as $beneficiary) {
                fputcsv($file, [
                    $beneficiary->name,
                    $beneficiary->email,
                    $beneficiary->phone_number ?? 'N/A',
                    $beneficiary->studentPayments->sum('amount'),
                    $beneficiary->studentPayments->where('status', 'approved')->sum('amount'),
                    $beneficiary->studentPayments->where('status', 'pending')->sum('amount'),
                    $beneficiary->studentPayments->count(),
                    $beneficiary->studentPayments->where('status', 'approved')->count(),
                    $beneficiary->is_academic_suspended ? 'Suspended' : 'Active',
                    $beneficiary->latestAcademicResult ? $beneficiary->latestAcademicResult->gpa : 'N/A',
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    private function exportAcademicCSV($request)
    {
        $results = AcademicResult::with(['student'])->get();

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="academic_report_' . date('Y-m-d') . '.csv"',
        ];

        $callback = function() use ($results) {
            $file = fopen('php://output', 'w');

            fputcsv($file, [
                'Student Name', 'Email', 'Academic Year', 'Student Year',
                'GPA', 'CGPA', 'Division', 'Status', 'Is Suspended', 'Submitted Date'
            ]);

            foreach ($results as $result) {
                fputcsv($file, [
                    $result->student->name ?? 'N/A',
                    $result->student->email ?? 'N/A',
                    $result->academic_year,
                    $result->student_year,
                    $result->gpa ?? 'N/A',
                    $result->cgpa ?? 'N/A',
                    $result->division ?? 'N/A',
                    $result->status,
                    $result->is_suspended ? 'Yes' : 'No',
                    $result->created_at->format('Y-m-d'),
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    private function exportUtilizationCSV($request)
    {
        $scholarships = Scholarship::with(['applications'])->get();

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="utilization_report_' . date('Y-m-d') . '.csv"',
        ];

        $callback = function() use ($scholarships) {
            $file = fopen('php://output', 'w');

            fputcsv($file, [
                'Scholarship', 'Academic Year', 'Total Applications', 'Approved Applications',
                'Beneficiaries', 'Utilization Rate (%)'
            ]);

            foreach ($scholarships as $scholarship) {
                $applications = $scholarship->applications;
                $approvedApplications = $applications->whereIn('status', ['approved_full', 'approved_partial']);

                fputcsv($file, [
                    $scholarship->title,
                    $scholarship->academic_year,
                    $applications->count(),
                    $approvedApplications->count(),
                    $applications->where('status', 'approved_full')->count(),
                    $applications->count() > 0 ?
                        round(($approvedApplications->count() / $applications->count()) * 100, 2) : 0,
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
