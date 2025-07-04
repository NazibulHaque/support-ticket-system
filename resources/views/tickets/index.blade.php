@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8">
                <div class="card mb-4">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0">
                            <i class="fas fa-plus-circle me-2"></i>
                            Submit New Support Ticket
                        </h4>
                    </div>
                    <div class="card-body">
                        <div id="success-alert" class="alert alert-success alert-dismissible fade" role="alert"
                            style="display: none;">
                            <i class="fas fa-check-circle me-2"></i>
                            <span id="success-message">Ticket submitted successfully!</span>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>

                        <div id="error-alert" class="alert alert-danger alert-dismissible fade" role="alert"
                            style="display: none;">
                            <i class="fas fa-exclamation-circle me-2"></i>
                            <span id="error-message">An error occurred. Please try again.</span>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>

                        <form id="ticket-form">
                            @csrf
                            <div class="mb-3">
                                <label for="subject" class="form-label fw-bold">Subject *</label>
                                <input type="text" class="form-control" id="subject" name="subject" required
                                    placeholder="Brief description of your issue">
                            </div>

                            <div class="mb-3">
                                <label for="description" class="form-label fw-bold">Description *</label>
                                <textarea class="form-control" id="description" name="description" rows="4" required
                                    placeholder="Please provide detailed information about your issue"></textarea>
                            </div>

                            <div class="mb-3">
                                <label for="priority" class="form-label fw-bold">Priority</label>
                                <select class="form-control" id="priority" name="priority">
                                    <option value="Low">Low</option>
                                    <option value="Medium" selected>Medium</option>
                                    <option value="High">High</option>
                                </select>
                            </div>

                            <button type="submit" class="btn btn-primary btn-lg w-100" id="submit-btn">
                                <span class="loading-spinner me-2"></span>
                                <i class="fas fa-paper-plane me-2"></i>
                                Submit Ticket
                            </button>
                        </form>
                    </div>
                </div>

                <!-- User's Tickets List -->
                <div class="card">
                    <div class="card-header bg-info text-white">
                        <h4 class="mb-0">
                            <i class="fas fa-list me-2"></i>
                            My Support Tickets
                        </h4>
                    </div>
                    <div class="card-body">
                        <div id="tickets-container">
                            @include('tickets.partials.ticket-list', ['tickets' => $tickets])
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card mb-4 top-agents-card">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-trophy me-2"></i>
                            Top Support Agents
                        </h5>
                    </div>
                    <div class="card-body" id="top-agents-container">
                        <div class="text-center">
                            <div class="spinner-border text-light" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            loadTopSupportAgents();

            setInterval(function() {
                loadUserTickets();
            }, 10000);

            $('#ticket-form').on('submit', function(e) {
                e.preventDefault();

                const submitBtn = $('#submit-btn');
                const spinner = submitBtn.find('.loading-spinner');

                submitBtn.prop('disabled', true);
                spinner.show();

                $('#success-alert, #error-alert').removeClass('show').hide();

                $.ajax({
                    url: '{{ route('tickets.store') }}',
                    method: 'POST',
                    data: $(this).serialize(),
                    success: function(response) {
                        if (response.success) {
                            $('#success-message').text(response.message);
                            $('#success-alert').addClass('show').show();

                            $('#ticket-form')[0].reset();
                            $('#priority').val('Medium');

                            loadUserTickets();

                            $('html, body').animate({
                                scrollTop: $('#success-alert').offset().top - 100
                            }, 500);
                        }
                    },
                    error: function(xhr) {
                        let errorMessage = 'An error occurred. Please try again.';

                        if (xhr.responseJSON && xhr.responseJSON.errors) {
                            const errors = xhr.responseJSON.errors;
                            errorMessage = Object.values(errors).flat().join('<br>');
                        }

                        $('#error-message').html(errorMessage);
                        $('#error-alert').addClass('show').show();
                    },
                    complete: function() {
                        submitBtn.prop('disabled', false);
                        spinner.hide();
                    }
                });
            });


            function loadUserTickets() {
                $.ajax({
                    url: '{{ route('tickets.user') }}',
                    method: 'GET',
                    success: function(response) {
                        if (response.success) {
                            updateTicketsList(response.tickets);
                        }
                    },
                    error: function() {
                        console.log('Error loading tickets');
                    }
                });
            }


            function updateTicketsList(tickets) {
                let html = '';

                if (tickets.length === 0) {
                    html = `
                <div class="text-center py-5 text-muted">
                    <i class="fas fa-inbox fa-3x mb-3"></i>
                    <p>No tickets found</p>
                </div>
            `;
                } else {
                    tickets.forEach(function(ticket) {
                        const priorityClass = 'priority-' + ticket.priority.toLowerCase();
                        const statusClass = 'status-' + ticket.status;
                        const statusBadge = getStatusBadge(ticket.status);
                        const priorityBadge = getPriorityBadge(ticket.priority);
                        const assignedAgent = ticket.assigned_agent ?
                            `<small class="text-muted"><i class="fas fa-user me-1"></i>Assigned to: ${ticket.assigned_agent.name}</small>` :
                            '<small class="text-muted"><i class="fas fa-clock me-1"></i>Awaiting assignment</small>';

                        html += `
                    <div class="ticket-item ${priorityClass} ${statusClass} p-3 mb-3 rounded">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <h6 class="mb-1">#${ticket.id} - ${ticket.subject}</h6>
                            <div>
                                ${priorityBadge}
                                ${statusBadge}
                            </div>
                        </div>
                        <p class="mb-2 text-muted">${ticket.description}</p>
                        <div class="d-flex justify-content-between align-items-center">
                            ${assignedAgent}
                            <small class="text-muted">
                                <i class="fas fa-calendar me-1"></i>
                                ${formatDate(ticket.created_at)}
                            </small>
                        </div>
                    </div>
                `;
                    });
                }

                $('#tickets-container').html(html);
            }

            function loadTopSupportAgents() {
                $.ajax({
                    url: '{{ route('api.top-agents') }}',
                    method: 'GET',
                    success: function(response) {
                        if (response.success) {
                            updateTopAgentsList(response.agents);
                        }
                    },
                    error: function() {
                        $('#top-agents-container').html(
                        '<p class="text-center">Error loading data</p>');
                    }
                });
            }

            function updateTopAgentsList(agents) {
                let html = '';

                if (agents.length === 0) {
                    html = '<p class="text-center">No data available</p>';
                } else {
                    agents.forEach(function(agent, index) {
                        const icon = getAgentIcon(index);
                        html += `
                    <div class="d-flex align-items-center mb-3 p-2 bg-white bg-opacity-25 rounded">
                        <div class="me-3">
                            ${icon}
                        </div>
                        <div class="flex-grow-1">
                            <h6 class="mb-0">${agent.name}</h6>
                            <small>${agent.email}</small>
                        </div>
                        <div class="text-end">
                            <h5 class="mb-0">${agent.tickets_handled}</h5>
                            <small>tickets</small>
                        </div>
                    </div>
                `;
                    });
                }

                $('#top-agents-container').html(html);
            }

            function getStatusBadge(status) {
                const badges = {
                    'open': '<span class="badge bg-primary">Open</span>',
                    'assigned': '<span class="badge bg-warning">Assigned</span>',
                    'closed': '<span class="badge bg-success">Closed</span>'
                };
                return badges[status] || '<span class="badge bg-secondary">Unknown</span>';
            }

            function getPriorityBadge(priority) {
                const badges = {
                    'High': '<span class="badge bg-danger">High</span>',
                    'Medium': '<span class="badge bg-warning">Medium</span>',
                    'Low': '<span class="badge bg-success">Low</span>'
                };
                return badges[priority] || '<span class="badge bg-secondary">Unknown</span>';
            }

            function getAgentIcon(index) {
                const icons = [
                    '<i class="fas fa-trophy text-warning fa-2x"></i>',
                    '<i class="fas fa-medal text-light fa-2x"></i>',
                    '<i class="fas fa-award text-warning fa-2x"></i>'
                ];
                return icons[index] || '<i class="fas fa-user fa-2x"></i>';
            }

            function formatDate(dateString) {
                const date = new Date(dateString);
                return date.toLocaleDateString() + ' ' + date.toLocaleTimeString();
            }
        });
    </script>
@endsection
