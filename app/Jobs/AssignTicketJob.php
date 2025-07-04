<?php

namespace App\Jobs;

use App\Models\User;
use App\Models\Ticket;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class AssignTicketJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $ticket;

    public function __construct(Ticket $ticket)
    {
        $this->ticket = $ticket;
        Log::info('AssignTicketJob: Created for ticket #' . $ticket->id);
    }

    public function handle(): void
    {
        $ticket = Ticket::find($this->ticket->id);

        if (!$ticket) {
            Log::error('AssignTicketJob: Ticket not found. ID: ' . $this->ticket->id);
            return;
        }

        Log::info('AssignTicketJob: Processing assignment for ticket #' . $ticket->id);

        $leastBusyAgent = User::query()
            ->supportAgents()
            ->withCount(['assignedTickets' => function ($query) {
                $query->whereIn('status', ['open', 'assigned']);
            }])
            ->orderBy('assigned_tickets_count', 'asc')
            ->first();

        if ($leastBusyAgent) {
            $ticket->update([
                'assigned_to' => $leastBusyAgent->id,
                'status' => 'assigned'
            ]);

            Log::info("AssignTicketJob: Ticket #{$ticket->id} assigned to {$leastBusyAgent->name} (ID: {$leastBusyAgent->id})");
        } else {
            Log::warning('AssignTicketJob: No support agents available for assignment');
        }
    }
}
