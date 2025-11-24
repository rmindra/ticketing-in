@extends('layouts.app')

@section('page_title', 'Admin Dashboard')

@section('main')
<div class="container">
    <h1 class="mb-4">Admin Dashboard</h1>

    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5>Total Users</h5>
                    <p class="display-4">{{ $totalUsers }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <h5>Tickets by status</h5>
                    <div class="row">
                        @php
                            // If there are no statuses yet, show common defaults
                            $statusKeys = count($ticketsByStatus) ? array_keys($ticketsByStatus) : ['Open','In Progress','Closed'];
                        @endphp
                        @foreach($statusKeys as $s)
                            <div class="col-md-4 text-center">
                                <h6 class="text-capitalize">{{ str_replace('_', ' ', $s) }}</h6>
                                <p class="h2">{{ $ticketsByStatus[$s] ?? 0 }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="card mb-3">
                <div class="card-header">Recent Users</div>
                <div class="card-body">
                    <ul class="list-unstyled mb-0">
                        @foreach($recentUsers as $u)
                            <li>{{ $u->name }} <small class="text-muted">({{ $u->email }})</small></li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card mb-3">
                <div class="card-header">Recent Tickets</div>
                <div class="card-body">
                    <ul class="list-unstyled mb-0">
                        @foreach($recentTickets as $t)
                            <li>
                                <strong>{{ $t->title }}</strong>
                                <div><small class="text-muted">by {{ $t->user?->name ?? '—' }} — {{ $t->status }}</small></div>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
