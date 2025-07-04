<?php

namespace App\Listeners;

use App\Events\TicketCreated;
use App\Jobs\AssignTicketJob;
use Illuminate\Support\Facades\Log;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class AssignTicketListener
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(TicketCreated $event): void
    {
        Log::info('AssignTicketListener: Handling TicketCreated event for ticket #' . $event->ticket->id);
        AssignTicketJob::dispatch($event->ticket);

        Log::info('AssignTicketListener: AssignTicketJob dispatched to queue');
    }
}
