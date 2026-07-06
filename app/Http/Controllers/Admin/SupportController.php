<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SupportTicket;
use App\Models\SupportReply;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class SupportController extends Controller
{
    /**
     * Display all support tickets.
     */
    public function index(Request $request)
    {
        $query = SupportTicket::with(['user', 'assignedTo']);

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by category
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        // Filter by priority
        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('subject', 'like', "%{$search}%")
                  ->orWhere('message', 'like', "%{$search}%")
                  ->orWhereHas('user', function($userQuery) use ($search) {
                      $userQuery->where('name', 'like', "%{$search}%")
                                ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }

        $tickets = $query->orderBy('created_at', 'desc')
            ->paginate(20);

        // Statistics
        $stats = [
            'total' => SupportTicket::count(),
            'open' => SupportTicket::whereIn('status', ['open', 'in_progress'])->count(),
            'resolved' => SupportTicket::where('status', 'resolved')->count(),
            'closed' => SupportTicket::where('status', 'closed')->count(),
            'urgent' => SupportTicket::where('priority', 'urgent')->whereIn('status', ['open', 'in_progress'])->count(),
        ];

        // Get categories and priorities for filters
        $categories = SupportTicket::distinct()->pluck('category');
        $priorities = SupportTicket::distinct()->pluck('priority');
        $statuses = SupportTicket::distinct()->pluck('status');

        return view('admin.support.index', compact('tickets', 'stats', 'categories', 'priorities', 'statuses'));
    }

    /**
     * Show a specific ticket.
     */
    public function show($id)
    {
        $ticket = SupportTicket::with(['user', 'assignedTo', 'replies.user'])
            ->findOrFail($id);

        $admins = User::whereIn('role', ['admin', 'coordinator'])->get();

        return view('admin.support.show', compact('ticket', 'admins'));
    }

    /**
     * Reply to a ticket (admin).
     */
    public function reply(Request $request, $id)
    {
        $ticket = SupportTicket::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'message' => 'required|string|min:5',
            'status' => 'nullable|in:open,in_progress,resolved,closed',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            // Create reply
            SupportReply::create([
                'ticket_id' => $ticket->id,
                'user_id' => Auth::id(),
                'message' => $request->message,
                'is_admin' => true,
            ]);

            // Update ticket status if provided
            if ($request->filled('status')) {
                $ticket->status = $request->status;

                if ($request->status === 'resolved') {
                    $ticket->resolved_at = now();
                }

                $ticket->save();
            }

            return redirect()->route('admin.support.show', $ticket->id)
                ->with('success', 'Reply sent successfully!');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to send reply: ' . $e->getMessage());
        }
    }

    /**
     * Assign a ticket to an admin.
     */
    public function assign(Request $request, $id)
    {
        $ticket = SupportTicket::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'assigned_to' => 'required|exists:users,id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $ticket->assigned_to = $request->assigned_to;
            $ticket->status = 'in_progress';
            $ticket->save();

            return redirect()->route('admin.support.show', $ticket->id)
                ->with('success', 'Ticket assigned successfully!');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to assign ticket: ' . $e->getMessage());
        }
    }

    /**
     * Update ticket status.
     */
    public function status(Request $request, $id)
    {
        $ticket = SupportTicket::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'status' => 'required|in:open,in_progress,resolved,closed',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $ticket->status = $request->status;

            if ($request->status === 'resolved') {
                $ticket->resolved_at = now();
            } elseif ($request->status === 'closed') {
                $ticket->closed_at = now();
            }

            $ticket->save();

            return redirect()->route('admin.support.show', $ticket->id)
                ->with('success', 'Ticket status updated successfully!');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to update status: ' . $e->getMessage());
        }
    }

    /**
     * Bulk action for tickets.
     */
    public function bulkAction(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'action' => 'required|in:resolve,close,delete',
            'ticket_ids' => 'required|array',
            'ticket_ids.*' => 'exists:support_tickets,id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $ticketIds = $request->ticket_ids;

            switch ($request->action) {
                case 'resolve':
                    SupportTicket::whereIn('id', $ticketIds)
                        ->update([
                            'status' => 'resolved',
                            'resolved_at' => now()
                        ]);
                    $message = 'Tickets resolved successfully!';
                    break;

                case 'close':
                    SupportTicket::whereIn('id', $ticketIds)
                        ->update([
                            'status' => 'closed',
                            'closed_at' => now()
                        ]);
                    $message = 'Tickets closed successfully!';
                    break;

                case 'delete':
                    SupportTicket::whereIn('id', $ticketIds)->delete();
                    $message = 'Tickets deleted successfully!';
                    break;

                default:
                    return redirect()->back()->with('error', 'Invalid action.');
            }

            return redirect()->route('admin.support.index')
                ->with('success', $message);

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to perform action: ' . $e->getMessage());
        }
    }
}
