<?php

namespace App\Http\Controllers\Beneficiary;

use App\Http\Controllers\Controller;
use App\Models\SupportTicket;
use App\Models\SupportReply;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
class BeneficiaryController extends Controller
{
    //


     /**
     * Display the support page.
     */
    public function index()
    {
        $user = Auth::user();

        // Get user's tickets
        $tickets = SupportTicket::with(['replies'])
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('beneficiary.support', compact('tickets'));
    }

    /**
     * Store a new support ticket.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'subject' => 'required|string|max:255',
            'message' => 'required|string|min:10',
            'category' => 'required|in:scholarship,payment,installment,account,technical,other',
            'priority' => 'required|in:low,medium,high,urgent',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $ticket = SupportTicket::create([
                'user_id' => Auth::id(),
                'subject' => $request->subject,
                'message' => $request->message,
                'category' => $request->category,
                'priority' => $request->priority,
                'status' => 'open',
            ]);

            return redirect()->route('beneficiary.support')
                ->with('success', 'Support ticket created successfully! We will get back to you soon.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to create support ticket: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Show a specific ticket.
     */
    public function show($id)
    {
        $user = Auth::user();

        $ticket = SupportTicket::with(['replies.user', 'assignedTo'])
            ->where('user_id', $user->id)
            ->findOrFail($id);

        return view('beneficiary.support-show', compact('ticket'));
    }

    /**
     * Reply to a ticket.
     */
    public function reply(Request $request, $id)
    {
        $user = Auth::user();

        $ticket = SupportTicket::where('user_id', $user->id)
            ->whereIn('status', ['open', 'in_progress'])
            ->findOrFail($id);

        $validator = Validator::make($request->all(), [
            'message' => 'required|string|min:5',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            SupportReply::create([
                'ticket_id' => $ticket->id,
                'user_id' => $user->id,
                'message' => $request->message,
                'is_admin' => false,
            ]);

            // If ticket was closed, reopen it
            if ($ticket->status === 'closed') {
                $ticket->status = 'open';
                $ticket->save();
            }

            return redirect()->route('.support.show', $ticket->id)
                ->with('success', 'Reply sent successfully!');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to send reply: ' . $e->getMessage());
        }
    }

    /**
     * Close a ticket.
     */
    public function close($id)
    {
        $user = Auth::user();

        $ticket = SupportTicket::where('user_id', $user->id)
            ->whereIn('status', ['open', 'in_progress', 'resolved'])
            ->findOrFail($id);

        try {
            $ticket->status = 'closed';
            $ticket->closed_at = now();
            $ticket->save();

            return redirect()->route('beneficiary.support')
                ->with('success', 'Ticket closed successfully.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to close ticket: ' . $e->getMessage());
        }
    }
}
