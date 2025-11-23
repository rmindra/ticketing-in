@extends('layouts.app')

@section('main')
<h3>My Tickets</h3>

<a href="{{ route('tickets.create') }}" class="btn btn-primary mb-3">Create Ticket</a>

<table class="table table-bordered">
    <thead>
        <tr>
            <th>Title</th><th>Category</th><th>Status</th><th>Priority</th><th>Action</th>
        </tr>
    </thead>
    <tbody>
        @foreach($tickets as $t)
        <tr>
            <td>{{ $t->title }}</td>
            <td>{{ $t->category->name }}</td>
            <td>{{ $t->status }}</td>
            <td>{{ $t->priority }}</td>
            <td><a href="{{ route('tickets.show',$t) }}" class="btn btn-sm btn-info">View</a></td>
        </tr>
        @endforeach
    </tbody>
</table>

{{ $tickets->links() }}

@endsection
