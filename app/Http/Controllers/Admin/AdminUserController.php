<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AdminUserController extends Controller
{
    /**
     * Display a listing of users with search, filter, and pagination.
     */
    public function index(Request $request)
    {
        $query = User::query();

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone_number', 'like', "%{$search}%")
                  ->orWhere('id', 'like', "%{$search}%");
            });
        }

        // Filter by role
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by application status
        if ($request->filled('application_status')) {
            $query->where('application_status', $request->application_status);
        }

        // Sort functionality
        $sort = $request->get('sort', 'created_at');
        $direction = $request->get('direction', 'desc');

        // Validate sort columns to prevent SQL injection
        $allowedSorts = ['id', 'name', 'email', 'created_at', 'role', 'status'];
        if (in_array($sort, $allowedSorts)) {
            $query->orderBy($sort, $direction);
        } else {
            $query->orderBy('created_at', 'desc');
        }

        // Pagination
        $perPage = $request->get('per_page', 10);
        $users = $query->paginate($perPage);

        // Statistics
        $totalUsers = User::count();
        $activeUsers = User::where('status', 'active')->count();
        $inactiveUsers = User::where('status', 'inactive')->count();
        $pendingApplications = Application::where('status', 'pending')->count();

        return view('admin.users.index', compact(
            'users',
            'totalUsers',
            'activeUsers',
            'inactiveUsers',
            'pendingApplications'
        ));
    }

    /**
     * Show the form for creating a new user.
     */
    public function create()
    {
        return view('admin.users.create');
    }

    /**
     * Store a newly created user in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone_number' => 'nullable|string|max:20',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:admin,applicant,reviewer',
            'status' => 'required|in:active,inactive,suspended',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'phone_number' => $request->phone_number,
                'password' => Hash::make($request->password),
                'role' => $request->role,
                'status' => $request->status,
            ]);

            return redirect()->route('admin.users.index')
                ->with('success', 'User created successfully!');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to create user: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified user.
     */
    public function show($id)
    {
        $user = User::with(['personalInfo', 'oLevelEducation', 'aLevelEducation', 'motivation', 'applications'])
            ->findOrFail($id);

        return view('admin.users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified user.
     */
    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('admin.users.edit', compact('user'));
    }

    /**
     * Update the specified user in storage.
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $id,
            'phone_number' => 'nullable|string|max:20',
            'password' => 'nullable|string|min:8|confirmed',
            'role' => 'required|in:admin,applicant,reviewer',
            'status' => 'required|in:active,inactive,suspended',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $user->name = $request->name;
            $user->email = $request->email;
            $user->phone_number = $request->phone_number;
            $user->role = $request->role;
            $user->status = $request->status;

            if ($request->filled('password')) {
                $user->password = Hash::make($request->password);
            }

            $user->save();

            return redirect()->route('admin.users.index')
                ->with('success', 'User updated successfully!');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to update user: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified user from storage.
     */
    public function destroy($id)
    {
        try {
            $user = User::findOrFail($id);

            // Prevent deleting yourself
            if ($user->id == auth()->id()) {
                return redirect()->back()
                    ->with('error', 'You cannot delete your own account.');
            }

            $user->delete();

            return redirect()->route('admin.users.index')
                ->with('success', 'User deleted successfully!');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to delete user: ' . $e->getMessage());
        }
    }

    /**
     * Bulk action for users.
     */
    public function bulkAction(Request $request)
    {
        $request->validate([
            'action' => 'required|in:activate,deactivate,suspend,delete',
            'user_ids' => 'required|array',
            'user_ids.*' => 'exists:users,id',
        ]);

        try {
            $userIds = $request->user_ids;

            // Remove current user from list if action is delete
            if ($request->action == 'delete') {
                $userIds = array_filter($userIds, function($id) {
                    return $id != auth()->id();
                });
            }

            if (empty($userIds)) {
                return redirect()->back()
                    ->with('error', 'No valid users selected for this action.');
            }

            switch ($request->action) {
                case 'activate':
                    User::whereIn('id', $userIds)->update(['status' => 'active']);
                    $message = 'Users activated successfully!';
                    break;
                case 'deactivate':
                    User::whereIn('id', $userIds)->update(['status' => 'inactive']);
                    $message = 'Users deactivated successfully!';
                    break;
                case 'suspend':
                    User::whereIn('id', $userIds)->update(['status' => 'suspended']);
                    $message = 'Users suspended successfully!';
                    break;
                case 'delete':
                    User::whereIn('id', $userIds)->delete();
                    $message = 'Users deleted successfully!';
                    break;
                default:
                    return redirect()->back()->with('error', 'Invalid action.');
            }

            return redirect()->route('admin.users.index')
                ->with('success', $message);

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to perform bulk action: ' . $e->getMessage());
        }
    }
}
