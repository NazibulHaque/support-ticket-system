<?php

namespace App\Observers;

use App\Models\Ticket;
use App\Events\TicketCreated;
use Illuminate\Support\Facades\Log;

class TicketObserver
{
    public function created(Ticket $ticket): void
    {
        Log::info('TicketObserver: Ticket created event triggered for ticket #' . $ticket->id);

        event(new TicketCreated($ticket));
    }

    public function updated(Ticket $ticket): void
    {
        Log::info('TicketObserver: Ticket updated event triggered for ticket #' . $ticket->id);
    }

    public function deleted(Ticket $ticket): void
    {
        Log::info('TicketObserver: Ticket deleted event triggered for ticket #' . $ticket->id);
    }
}
