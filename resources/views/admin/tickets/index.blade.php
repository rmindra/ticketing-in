@extends('layouts.app')

@section('title', 'Manage Tickets')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <h1 class="h3 mb-4">Manage Tickets</h1>
        </div>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <div class="card shadow">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">All Tickets</h6>
            <div>
                <a href="{{ route('admin.tickets.index') }}?status=Open" class="btn btn-warning btn-sm">Open</a>
                <a href="{{ route('admin.tickets.index') }}?status=In Progress" class="btn btn-info btn-sm">In Progress</a>
                <a href="{{ route('admin.tickets.index') }}" class="btn btn-primary btn-sm">All</a>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>User</th>
                            <th>Status</th>
                            <th>Priority</th>
                            <th>Category</th>
                            <th>Assigned To</th>
                            <th>Created</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($tickets as $ticket)
                        <tr>
                            <td>{{ $ticket->title }}</td>
                            <td>{{ $ticket->user->name }}</td>
                            <td>
                                <span class="badge bg-{{ $ticket->status === 'Open' ? 'warning' : ($ticket->status === 'In Progress' ? 'info' : 'success') }}">
                                    {{ $ticket->status }}
                                </span>
                            </td>
                            <td>
                                <span class="badge bg-{{ $ticket->priority === 'Urgent' ? 'danger' : ($ticket->priority === 'High' ? 'warning' : 'secondary') }}">
                                    {{ $ticket->priority }}
                                </span>
                            </td>
                            <td>{{ $ticket->category->name ?? 'N/A' }}</td>
                            <td>{{ $ticket->assignedTo->name ?? 'Not Assigned' }}</td>
                            <td>{{ $ticket->created_at->format('M d, Y') }}</td>
                            <td>
                                <a href="{{ route('admin.tickets.edit', $ticket) }}" class="btn btn-sm btn-warning">Edit</a>

                                @if(!$ticket->assigned_to || $ticket->assigned_to != auth()->id())
                                <form action="{{ route('admin.tickets.claim', $ticket) }}" method="POST" style="display:inline">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-success">Claim</button>
                                </form>
                                @endif

                                <form action="{{ route('admin.tickets.destroy', $ticket) }}" method="POST" style="display:inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-center">
                {{ $tickets->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
