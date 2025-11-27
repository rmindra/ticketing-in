@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
<div class="container-fluid">
    <!-- Alert Messages -->
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

    <div class="row">
        <div class="col-md-12">
            <h1 class="h3 mb-4">Admin Dashboard</h1>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row g-3 align-items-stretch">

        <!-- Total Tickets -->
        <div class="col-xl-3 col-md-6 mb-4 d-flex">
            <div class="small-box bg-primary w-100 h-100 d-flex flex-column justify-content-between">
                <div>
                    <div class="inner p-3">
                        <h4 class="text-uppercase font-weight-bold">Total Tickets</h4>
                        <p class="h5 font-weight-bold">{{ $totalTickets }}</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-ticket-alt"></i>
                    </div>
                </div>
                <a href="{{ route('admin.tickets.index') }}" class="small-box-footer mt-2">
                    More info <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>

        <!-- Unassigned Tickets -->
        <div class="col-xl-3 col-md-6 mb-4 d-flex">
            <div class="small-box bg-warning w-100 h-100 d-flex flex-column justify-content-between">
                <div>
                    <div class="inner p-3">
                        <h4 class="text-uppercase font-weight-bold">Belum diclaim</h4>
                        <p class="h5 font-weight-bold">{{ $unassignedTickets }}</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-clock"></i>
                    </div>
                </div>
                <a href="{{ route('admin.tickets.index') }}?status=Open" class="small-box-footer mt-2">
                    More info <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>

        <!-- Assigned Tickets -->
        <div class="col-xl-3 col-md-6 mb-4 d-flex">
            <div class="small-box bg-info w-100 h-100 d-flex flex-column justify-content-between">
                <div>
                    <div class="inner p-3">
                        <h4 class="text-uppercase font-weight-bold">Sedang Dikerjakan</h4>
                        <p class="h5 font-weight-bold">{{ $assignedTickets }}</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-user-cog"></i>
                    </div>
                </div>
                <a href="{{ route('admin.tickets.index') }}?status=In Progress" class="small-box-footer mt-2">
                    More info <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>

        <!-- Resolved Tickets -->
        <div class="col-xl-3 col-md-6 mb-4 d-flex">
            <div class="small-box bg-success w-100 h-100 d-flex flex-column justify-content-between">
                <div>
                    <div class="inner p-3">
                        <h4 class="text-uppercase font-weight-bold">Selesai</h4>
                        <p class="h5 font-weight-bold">{{ $resolvedTickets }}</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-check-circle"></i>
                    </div>
                </div>
                <a href="{{ route('admin.tickets.index') }}?status=Resolved" class="small-box-footer mt-2">
                    More info <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- Main Content - Tiket yang Bisa Di-claim dan Tiket Saya -->
    <div class="row">
        <!-- Tiket yang Bisa Di-claim -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-warning">
                        <i class="fas fa-hand-paper me-2"></i>Tiket yang Bisa Diterima
                    </h6>
                    <span class="badge bg-warning">{{ $ticketsToClaim->count() }}</span>
                </div>
                <div class="card-body">
                    @forelse($ticketsToClaim as $ticket)
                    <div class="ticket-item mb-3 p-3 border rounded">
                        <div class="d-flex justify-content-between align-items-start">
                            <div class="flex-grow-1">
                                <h6 class="mb-1"><a href="{{ route('tickets.show', $ticket) }}">{{ $ticket->title }}</a></h6>
                                <p class="mb-1 text-muted small">
                                    <i class="fas fa-user me-1"></i>By: {{ $ticket->user->name }}
                                </p>
                                <p class="mb-2 small">
                                    <span class="badge bg-{{ $ticket->priority === 'Urgent' ? 'danger' : ($ticket->priority === 'High' ? 'warning' : 'secondary') }}">
                                        <i class="fas fa-flag me-1"></i>{{ $ticket->priority }}
                                    </span>
                                    <span class="badge bg-light text-dark">
                                        <i class="fas fa-tag me-1"></i>{{ $ticket->category->name ?? 'N/A' }}
                                    </span>
                                </p>
                                <small class="text-muted">
                                    <i class="fas fa-clock me-1"></i>{{ $ticket->created_at->format('M d, Y H:i') }}
                                </small>
                            </div>
                            <div class="ms-3">
                                <form action="/admin/tickets/{{ $ticket->id }}/claim" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-success btn-sm">
                                        <i class="fas fa-hand-paper me-1"></i> Claim
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-4">
                        <i class="fas fa-check-circle fa-2x text-success mb-2"></i>
                        <p class="text-muted">Tidak ada tiket yang bisa diterima</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Tiket yang Sedang Dikerjakan oleh Saya -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-info">
                        <i class="fas fa-user-cog me-2"></i>Tiket yang Sedang Saya Kerjakan
                    </h6>
                    <span class="badge bg-info">{{ $myTickets->count() }}</span>
                </div>
                <div class="card-body">
                    @forelse($myTickets as $ticket)
                    <div class="ticket-item mb-3 p-3 border rounded">
                        <div class="d-flex justify-content-between align-items-start">
                            <div class="flex-grow-1">
                                <h6 class="mb-1"><a href="{{ route('tickets.show', $ticket) }}">{{ $ticket->title }}</a></h6>
                                <p class="mb-1 text-muted small">
                                    <i class="fas fa-user me-1"></i>By: {{ $ticket->user->name }}
                                </p>
                                <p class="mb-2 small">
                                    <span class="badge bg-{{ $ticket->priority === 'Urgent' ? 'danger' : ($ticket->priority === 'High' ? 'warning' : 'secondary') }}">
                                        <i class="fas fa-flag me-1"></i>{{ $ticket->priority }}
                                    </span>
                                    <span class="badge bg-light text-dark">
                                        <i class="fas fa-tag me-1"></i>{{ $ticket->category->name ?? 'N/A' }}
                                    </span>
                                </p>
                                <small class="text-muted">
                                    <i class="fas fa-calendar me-1"></i>Diterima: {{ $ticket->updated_at->format('M d, Y H:i') }}
                                </small>
                            </div>
                            <div class="ms-3">
                                <form action="/admin/tickets/{{ $ticket->id }}/resolve" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-success btn-sm">
                                        <i class="fas fa-check me-1"></i> Selesai
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-4">
                        <i class="fas fa-user-cog fa-2x text-info mb-2"></i>
                        <p class="text-muted">Tidak ada tiket yang sedang dikerjakan</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Include Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<style>
.card {
    border: none;
    border-radius: 10px;
}
.ticket-item {
    transition: all 0.3s ease;
    border: 1px solid #e3e6f0 !important;
}
.ticket-item:hover {
    background-color: #f8f9fa;
    border-color: #b7b9cc !important;
}

.btn-block {
    display: flex;
    align-items: center;
    justify-content: center;
}
</style>
@endsection
