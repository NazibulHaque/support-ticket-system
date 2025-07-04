<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Ticket;
use App\Jobs\AssignTicketJob;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class TicketController extends Controller
{
    public function index()
    {

        $tickets = Ticket::with('assignedAgent')
            ->where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();

        return view('tickets.index', compact('tickets'));
    }

    public function store(Request $request)
    {
        Log::info('TicketController: Processing ticket submission');

        $validated = $request->validate([
            'subject' => 'required|string|max:255',
            'description' => 'required|string',
            'priority' => 'required|in:Low,Medium,High'
        ]);

        $ticket = Ticket::create([
            'subject' => $validated['subject'],
            'description' => $validated['description'],
            'priority' => $validated['priority'],
            'user_id' => Auth::id(),
            'status' => 'open'
        ]);

        Log::info('TicketController: Ticket #' . $ticket->id . ' created successfully');
        AssignTicketJob::dispatch($ticket);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Ticket submitted successfully!',
                'ticket' => $ticket->load('assignedAgent')
            ]);
        }

        return redirect()->route('tickets.index')
            ->with('success', 'Ticket submitted successfully!');
    }

    public function topSupportAgents()
    {
        Log::info('TicketController: Executing raw SQL query for top support agents');


        $topAgents = DB::select("
            SELECT
                u.id,
                u.name,
                u.email,
                COUNT(t.id) as tickets_handled
            FROM users u
            LEFT JOIN tickets t ON u.id = t.assigned_to
            WHERE u.role = 'support_agent'
            AND (t.created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY) OR t.created_at IS NULL)
            GROUP BY u.id, u.name, u.email
            ORDER BY tickets_handled DESC
            LIMIT 3
        ");

        return response()->json([
            'success' => true,
            'agents' => $topAgents
        ]);
    }
    public function getUserTickets()
    {
        $tickets = Ticket::with('assignedAgent')
            ->where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'tickets' => $tickets
        ]);
    }
}
