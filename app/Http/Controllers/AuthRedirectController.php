<?php

namespace App\Http\Controllers;

use App\Models\ALevelEducation;
use App\Models\Application;
use App\Models\Motivation;
use App\Models\OLevelEducation;
use App\Models\PersonalInfo;
use App\Models\Scholarship;
use App\Models\User;
use Illuminate\Container\Attributes\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AuthRedirectController extends Controller
{
    //

    public function redirect(Request $request){
        return redirect()->route("login");
    }


     /**
     * Display the admin dashboard.
     */
    public function index()
    {
        // Get database driver
        $driver = DB::connection()->getDriverName();

        // Total counts
        $totalUsers = User::count();
        $totalApplications = Application::count();
        $totalScholarships = Scholarship::count();
        $totalPersonalInfo = PersonalInfo::count();
        $totalOLevel = OLevelEducation::count();
        $totalALevel = ALevelEducation::count();
        $totalMotivations = Motivation::count();

        // Application status counts
        $pendingApplications = Application::where('status', 'pending')->count();
        $submittedApplications = Application::where('status', 'submitted')->count();
        $underReviewApplications = Application::where('status', 'under_review')->count();
        $approvedFullApplications = Application::where('status', 'approved_full')->count();
        $approvedPartialApplications = Application::where('status', 'approved_partial')->count();
        $rejectedApplications = Application::where('status', 'rejected')->count();

        // Scholarship status counts
        $openScholarships = Scholarship::where('status', 'open')->count();
        $draftScholarships = Scholarship::where('status', 'draft')->count();
        $closedScholarships = Scholarship::where('status', 'closed')->count();

        // User role counts
        $adminUsers = User::where('role', 'admin')->count();
        $applicantUsers = User::where('role', 'applicant')->count();
        $coordinatorUsers = User::where('role', 'coordinator')->count();
        $beneficiaryUsers = User::where('role', 'beneficiary')->count();

        // User status counts
        $activeUsers = User::where('status', 'active')->count();
        $inactiveUsers = User::where('status', 'inactive')->count();
        $suspendedUsers = User::where('status', 'suspended')->count();

        // Recent applications (last 10)
        $recentApplications = Application::with(['user', 'scholarship'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // Recent users (last 10)
        $recentUsers = User::orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // Recent scholarships (last 10)
        $recentScholarships = Scholarship::with(['creator'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // Monthly application statistics (last 12 months) - SQLite compatible
        $monthlyStats = $this->getMonthlyStats($driver);

        // Monthly applications by status - SQLite compatible
        $monthlyStatusStats = $this->getMonthlyStatusStats($driver);

        // Top performing scholarships (most applications)
        $topScholarships = Scholarship::withCount('applications')
            ->orderBy('applications_count', 'desc')
            ->limit(5)
            ->get();

        // Completion rates
        $completionStats = [
            'personal_info' => [
                'completed' => PersonalInfo::count(),
                'total' => User::count(),
                'percentage' => User::count() > 0 ? round((PersonalInfo::count() / User::count()) * 100) : 0
            ],
            'o_level' => [
                'completed' => OLevelEducation::count(),
                'total' => User::count(),
                'percentage' => User::count() > 0 ? round((OLevelEducation::count() / User::count()) * 100) : 0
            ],
            'a_level' => [
                'completed' => ALevelEducation::count(),
                'total' => User::count(),
                'percentage' => User::count() > 0 ? round((ALevelEducation::count() / User::count()) * 100) : 0
            ],
            'motivation' => [
                'completed' => Motivation::count(),
                'total' => User::count(),
                'percentage' => User::count() > 0 ? round((Motivation::count() / User::count()) * 100) : 0
            ]
        ];

        // Application trend (last 7 days) - SQLite compatible
        $dailyStats = $this->getDailyStats($driver);

        // Get current date and time
        $currentTime = now();

        // Calculate completion rate
        $totalRequired = 4;
        $completedUsers = 0;
        $users = User::all();
        foreach($users as $user) {
            $completed = 0;
            if($user->personalInfo) $completed++;
            if($user->oLevelEducation) $completed++;
            if($user->aLevelEducation) $completed++;
            if($user->motivation) $completed++;
            if($completed == $totalRequired) $completedUsers++;
        }
        $completionRate = $totalUsers > 0 ? round(($completedUsers / $totalUsers) * 100) : 0;

        return view('admin.index', compact(
            'totalUsers',
            'totalApplications',
            'totalScholarships',
            'totalPersonalInfo',
            'totalOLevel',
            'totalALevel',
            'totalMotivations',
            'pendingApplications',
            'submittedApplications',
            'underReviewApplications',
            'approvedFullApplications',
            'approvedPartialApplications',
            'rejectedApplications',
            'openScholarships',
            'draftScholarships',
            'closedScholarships',
            'adminUsers',
            'applicantUsers',
            'coordinatorUsers',
            'beneficiaryUsers',
            'activeUsers',
            'inactiveUsers',
            'suspendedUsers',
            'recentApplications',
            'recentUsers',
            'recentScholarships',
            'monthlyStats',
            'monthlyStatusStats',
            'topScholarships',
            'completionStats',
            'dailyStats',
            'currentTime',
            'completionRate',
            'completedUsers'
        ));
    }

    /**
     * Get monthly statistics compatible with different database drivers.
     */
    private function getMonthlyStats($driver)
    {
        if ($driver === 'sqlite') {
            return Application::select(
                DB::raw("strftime('%Y', created_at) as year"),
                DB::raw("strftime('%m', created_at) as month"),
                DB::raw("COUNT(*) as total")
            )
            ->where('created_at', '>=', now()->subMonths(12))
            ->groupBy('year', 'month')
            ->orderBy('year', 'asc')
            ->orderBy('month', 'asc')
            ->get()
            ->map(function($item) {
                $item->year = (int)$item->year;
                $item->month = (int)$item->month;
                return $item;
            });
        } else {
            return Application::select(
                DB::raw('YEAR(created_at) as year'),
                DB::raw('MONTH(created_at) as month'),
                DB::raw('COUNT(*) as total')
            )
            ->where('created_at', '>=', now()->subMonths(12))
            ->groupBy('year', 'month')
            ->orderBy('year', 'asc')
            ->orderBy('month', 'asc')
            ->get();
        }
    }

    /**
     * Get monthly status statistics compatible with different database drivers.
     */
    private function getMonthlyStatusStats($driver)
    {
        if ($driver === 'sqlite') {
            return Application::select(
                DB::raw("strftime('%Y', created_at) as year"),
                DB::raw("strftime('%m', created_at) as month"),
                'status',
                DB::raw("COUNT(*) as total")
            )
            ->where('created_at', '>=', now()->subMonths(6))
            ->groupBy('year', 'month', 'status')
            ->orderBy('year', 'asc')
            ->orderBy('month', 'asc')
            ->get()
            ->map(function($item) {
                $item->year = (int)$item->year;
                $item->month = (int)$item->month;
                return $item;
            });
        } else {
            return Application::select(
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
        }
    }

    /**
     * Get daily statistics compatible with different database drivers.
     */
    private function getDailyStats($driver)
    {
        if ($driver === 'sqlite') {
            return Application::select(
                DB::raw("DATE(created_at) as date"),
                DB::raw("COUNT(*) as total")
            )
            ->where('created_at', '>=', now()->subDays(7))
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get();
        } else {
            return Application::select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('COUNT(*) as total')
            )
            ->where('created_at', '>=', now()->subDays(7))
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get();
        }
    }

    /**
     * Get dashboard statistics via AJAX.
     */
    public function statistics(Request $request)
    {
        $stats = [
            'total_users' => User::count(),
            'total_applications' => Application::count(),
            'total_scholarships' => Scholarship::count(),
            'pending_applications' => Application::where('status', 'pending')->count(),
            'under_review_applications' => Application::where('status', 'under_review')->count(),
            'approved_applications' => Application::whereIn('status', ['approved_full', 'approved_partial'])->count(),
            'rejected_applications' => Application::where('status', 'rejected')->count(),
            'open_scholarships' => Scholarship::where('status', 'open')->count(),
        ];

        return response()->json($stats);
    }

    /**
     * Get chart data for applications.
     */
    public function chartData(Request $request)
    {
        $period = $request->get('period', 'monthly');
        $driver = DB::connection()->getDriverName();

        if ($period == 'weekly') {
            if ($driver === 'sqlite') {
                $data = Application::select(
                    DB::raw("DATE(created_at) as date"),
                    DB::raw("COUNT(*) as total")
                )
                ->where('created_at', '>=', now()->subDays(7))
                ->groupBy('date')
                ->orderBy('date', 'asc')
                ->get();
            } else {
                $data = Application::select(
                    DB::raw('DATE(created_at) as date'),
                    DB::raw('COUNT(*) as total')
                )
                ->where('created_at', '>=', now()->subDays(7))
                ->groupBy('date')
                ->orderBy('date', 'asc')
                ->get();
            }
        } elseif ($period == 'monthly') {
            if ($driver === 'sqlite') {
                $data = Application::select(
                    DB::raw("strftime('%Y', created_at) as year"),
                    DB::raw("strftime('%m', created_at) as month"),
                    DB::raw("COUNT(*) as total")
                )
                ->where('created_at', '>=', now()->subMonths(12))
                ->groupBy('year', 'month')
                ->orderBy('year', 'asc')
                ->orderBy('month', 'asc')
                ->get()
                ->map(function($item) {
                    return (object) [
                        'year' => (int)$item->year,
                        'month' => (int)$item->month,
                        'total' => $item->total
                    ];
                });
            } else {
                $data = Application::select(
                    DB::raw('YEAR(created_at) as year'),
                    DB::raw('MONTH(created_at) as month'),
                    DB::raw('COUNT(*) as total')
                )
                ->where('created_at', '>=', now()->subMonths(12))
                ->groupBy('year', 'month')
                ->orderBy('year', 'asc')
                ->orderBy('month', 'asc')
                ->get();
            }
        } else {
            // Yearly
            if ($driver === 'sqlite') {
                $data = Application::select(
                    DB::raw("strftime('%Y', created_at) as year"),
                    DB::raw("COUNT(*) as total")
                )
                ->where('created_at', '>=', now()->subYears(5))
                ->groupBy('year')
                ->orderBy('year', 'asc')
                ->get()
                ->map(function($item) {
                    return (object) [
                        'year' => (int)$item->year,
                        'total' => $item->total
                    ];
                });
            } else {
                $data = Application::select(
                    DB::raw('YEAR(created_at) as year'),
                    DB::raw('COUNT(*) as total')
                )
                ->where('created_at', '>=', now()->subYears(5))
                ->groupBy('year')
                ->orderBy('year', 'asc')
                ->get();
            }
        }

        return response()->json($data);
    }







    public function dashboardRedirect(Request $request)
    {
        $user = $request->user();










        if ($user->role === 'admin') {



            return $this->index();
        } elseif ($user->role === 'beneficiary') {
            return view('beneficiary.index');
        } elseif ($user->role === 'coordinator') {
            return view('coordinator.index');
        } else {
            return view('applicant.index');
        }
    }

    public function logout(Request $request){
        auth()->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');

    }
}
