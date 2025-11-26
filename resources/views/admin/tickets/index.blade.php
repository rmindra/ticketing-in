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
            <h6 class="m-0 font-weight-bold">All Tickets</h6>
            <div class="d-flex align-items-center">
                <div class="btn-group me-2">
                    <div class="me-2">
                        <a href="{{ route('admin.tickets.index', array_merge(request()->except('page'), ['status' => 'Open'])) }}" class="btn btn-warning btn-sm {{ ($status ?? '') === 'Open' ? 'active' : '' }}">Open</a>
                        <a href="{{ route('admin.tickets.index', array_merge(request()->except('page'), ['status' => 'In Progress'])) }}" class="btn btn-info btn-sm {{ ($status ?? '') === 'In Progress' ? 'active' : '' }}">In Progress</a>
                        <a href="{{ route('admin.tickets.index', array_merge(request()->except('page'), ['status' => 'Resolved'])) }}" class="btn btn-success btn-sm {{ ($status ?? '') === 'Resolved' ? 'active' : '' }}">Resolved</a>
                        <a href="{{ route('admin.tickets.index', request()->except(['page','status'])) }}" class="btn btn-primary btn-sm {{ empty($status) ? 'active' : '' }}">All</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table id="tickets-table" class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Title</th>
                            <th>User</th>
                            <th>Status</th>
                            <th>Priority</th>
                            <th>Category</th>
                            <th>Assigned To</th>
                            <th>Created</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($tickets as $ticket)
                        <tr>
                            <td>{{ $ticket->id }}</td>
                            <td><a href="{{ route('tickets.show', $ticket) }}">{{ $ticket->title }}</a></td>
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

            {{ $tickets->links() }}
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function(){ $('#tickets-table').DataTable({ "paging": false }); });
</script>
@endpush
