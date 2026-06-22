<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class AdminAcknowledgementController extends Controller
{
    /**
     * Display the acknowledgement management page.
     */
    public function index()
    {
        // Get all applications with submitted acknowledgement letters
        $applications = Application::with(['user', 'scholarship'])
            ->whereNotNull('acknowledgement_letter_path')
            ->where('acknowledgement_status', '!=', 'rejected')
            ->orderBy('acknowledgement_letter_submitted_at', 'desc')
            ->paginate(20);

        // Get statistics
        $totalSubmitted = Application::whereNotNull('acknowledgement_letter_path')->count();
        $pendingReview = Application::where('acknowledgement_status', 'submitted')->count();
        $approved = Application::where('acknowledgement_status', 'approved')->count();
        $rejected = Application::where('acknowledgement_status', 'rejected')->count();

        return view('admin.acknowledgement.index', compact(
            'applications',
            'totalSubmitted',
            'pendingReview',
            'approved',
            'rejected'
        ));
    }

    /**
     * Show the template upload form.
     */
    public function template()
    {
        // Check if template exists
        $templateExists = Storage::disk('public')->exists('documents/acknowledgements/acknowledgement.pdf');

        return view('admin.acknowledgement.template', compact('templateExists'));
    }

    /**
     * Upload the acknowledgement letter template.
     */
    public function uploadTemplate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'template' => 'required|file|mimes:pdf|max:10240', // Max 10MB
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            // Delete existing template if it exists
            if (Storage::disk('public')->exists('documents/acknowledgements/acknowledgement.pdf')) {
                Storage::disk('public')->delete('documents/acknowledgements/acknowledgement.pdf');
            }

            // Store the new template
            $file = $request->file('template');
            $path = $file->storeAs('documents/acknowledgements', 'acknowledgement.pdf', 'public');

            return redirect()->route('admin.acknowledgement.template')
                ->with('success', 'Acknowledgement letter template uploaded successfully!');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to upload template: ' . $e->getMessage());
        }
    }

    /**
     * Update user type to beneficiary (individual).
     */
    public function updateUserType(Request $request, $id)
    {
        $application = Application::with('user')->findOrFail($id);

        if (!$application->user) {
            return redirect()->back()
                ->with('error', 'User not found for this application.');
        }

        $validator = Validator::make($request->all(), [
            'user_type' => 'required|in:beneficiary,applicant,admin,coordinator',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $user = $application->user;
            $user->role = $request->user_type;
            $user->save();

            return redirect()->route('admin.acknowledgement.index')
                ->with('success', 'User "' . $user->name . '" has been updated to ' . ucfirst($request->user_type) . ' successfully!');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to update user type: ' . $e->getMessage());
        }
    }

    /**
     * Bulk update user types to beneficiary.
     */
    public function bulkUpdateUserType(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'application_ids' => 'required|array',
            'application_ids.*' => 'exists:applications,id',
            'user_type' => 'required|in:beneficiary,applicant,admin,coordinator',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $applicationIds = $request->application_ids;
            $userType = $request->user_type;

            // Get all users from the selected applications
            $applications = Application::with('user')
                ->whereIn('id', $applicationIds)
                ->get();

            $updatedCount = 0;
            foreach ($applications as $application) {
                if ($application->user) {
                    $application->user->role = $userType;
                    $application->user->save();
                    $updatedCount++;
                }
            }

            return redirect()->route('admin.acknowledgement.index')
                ->with('success', $updatedCount . ' users have been updated to ' . ucfirst($userType) . ' successfully!');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to update user types: ' . $e->getMessage());
        }
    }

    /**
     * Bulk approve acknowledgement letters.
     */
    public function bulkApprove(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'application_ids' => 'required|array',
            'application_ids.*' => 'exists:applications,id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            Application::whereIn('id', $request->application_ids)
                ->update([
                    'acknowledgement_status' => 'approved',
                    'acknowledgement_admin_notes' => 'Approved by admin.'
                ]);

            return redirect()->route('admin.acknowledgement.index')
                ->with('success', 'Acknowledgement letters approved successfully!');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to approve letters: ' . $e->getMessage());
        }
    }

    /**
     * Bulk reject acknowledgement letters.
     */
    public function bulkReject(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'application_ids' => 'required|array',
            'application_ids.*' => 'exists:applications,id',
            'rejection_reason' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $reason = $request->rejection_reason ?? 'Please review and resubmit your acknowledgement letter.';

            Application::whereIn('id', $request->application_ids)
                ->update([
                    'acknowledgement_status' => 'rejected',
                    'acknowledgement_admin_notes' => $reason
                ]);

            return redirect()->route('admin.acknowledgement.index')
                ->with('success', 'Acknowledgement letters rejected successfully!');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to reject letters: ' . $e->getMessage());
        }
    }

    /**
     * View uploaded acknowledgement letter.
     */
    public function viewLetter($id)
    {
        $application = Application::findOrFail($id);

        if (!$application->acknowledgement_letter_path) {
            return redirect()->back()
                ->with('error', 'No acknowledgement letter found for this application.');
        }

        // Redirect to the file
        return redirect(asset('storage/' . $application->acknowledgement_letter_path));
    }

    /**
     * Download the acknowledgement letter template.
     */
    public function downloadTemplate()
    {
        $templatePath = 'documents/acknowledgements/acknowledgement.pdf';

        if (!Storage::disk('public')->exists($templatePath)) {
            return redirect()->back()
                ->with('error', 'Acknowledgement letter template not found. Please upload one first.');
        }

        return Storage::disk('public')->download($templatePath, 'acknowledgement_template.pdf');
    }

    /**
     * Delete the acknowledgement letter template.
     */
    public function deleteTemplate()
    {
        $templatePath = 'documents/acknowledgements/acknowledgement.pdf';

        if (!Storage::disk('public')->exists($templatePath)) {
            return redirect()->back()
                ->with('error', 'Acknowledgement letter template not found.');
        }

        try {
            Storage::disk('public')->delete($templatePath);

            return redirect()->route('admin.acknowledgement.template')
                ->with('success', 'Acknowledgement letter template deleted successfully!');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to delete template: ' . $e->getMessage());
        }
    }
}
