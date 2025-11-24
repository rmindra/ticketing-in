@extends('layouts.app')

@section('page_title','My Dashboard')

@section('main')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1>My Dashboard</h1>
        <div>
            <a href="{{ route('tickets.create') }}" class="btn btn-primary">Create Ticket</a>
            <a href="{{ route('profile.show') }}" class="btn btn-secondary">Profile</a>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <h5>Your Tickets by status</h5>
                    <div class="row">
                        @php
                            $statusKeys = count($ticketsByStatus) ? array_keys($ticketsByStatus) : ['Open','In Progress','Closed'];
                        @endphp
                        @foreach($statusKeys as $s)
                            <div class="col-md-3 text-center">
                                <h6 class="text-capitalize">{{ str_replace('_',' ', $s) }}</h6>
                                <p class="h3">{{ $ticketsByStatus[$s] ?? 0 }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Recent Tickets</div>
                <div class="card-body">
                    <ul class="list-unstyled mb-0">
                        @forelse($recentTickets as $t)
                            <li>
                                <a href="{{ route('tickets.show', $t) }}"><strong>{{ $t->title }}</strong></a>
                                <div><small class="text-muted">Status: {{ $t->status }} — Priority: {{ $t->priority }}</small></div>
                            </li>
                        @empty
                            <li>No recent tickets.</li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
