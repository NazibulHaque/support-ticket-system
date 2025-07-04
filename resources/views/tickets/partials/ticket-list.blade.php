@if($tickets->count() > 0)
    @foreach($tickets as $ticket)
        <div class="ticket-item priority-{{ strtolower($ticket->priority) }} status-{{ $ticket->status }} p-3 mb-3 rounded">
            <div class="d-flex justify-content-between align-items-start mb-2">
                <h6 class="mb-1">#{{ $ticket->id }} - {{ $ticket->subject }}</h6>
                <div>
                    @if($ticket->priority === 'High')
                        <span class="badge bg-danger">High</span>
                    @elseif($ticket->priority === 'Medium')
                        <span class="badge bg-warning">Medium</span>
                    @else
                        <span class="badge bg-success">Low</span>
                    @endif

                    @if($ticket->status === 'open')
                        <span class="badge bg-primary">Open</span>
                    @elseif($ticket->status === 'assigned')
                        <span class="badge bg-warning">Assigned</span>
                    @else
                        <span class="badge bg-success">Closed</span>
                    @endif
                </div>
            </div>
            <p class="mb-2 text-muted">{{ $ticket->description }}</p>
            <div class="d-flex justify-content-between align-items-center">
                @if($ticket->assignedAgent)
                    <small class="text-muted">
                        <i class="fas fa-user me-1"></i>
                        Assigned to: {{ $ticket->assignedAgent->name }}
                    </small>
                @else
                    <small class="text-muted">
                        <i class="fas fa-clock me-1"></i>
                        Awaiting assignment
                    </small>
                @endif
                <small class="text-muted">
                    <i class="fas fa-calendar me-1"></i>
                    {{ $ticket->created_at->format('M d, Y H:i') }}
                </small>
            </div>
        </div>
    @endforeach
@else
    <div class="text-center py-5 text-muted">
        <i class="fas fa-inbox fa-3x mb-3"></i>
        <p>No tickets found</p>
    </div>
@endif
