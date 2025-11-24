@extends('layouts.app')
@section('page_title','All Tickets')

@section('main')
<h5>All Tickets</h5>
<table id="admin-tickets-table" class="table table-striped">
<thead><tr><th>ID</th><th>Title</th><th>Creator</th><th>Assignee</th><th>Category</th><th>Status</th><th>Priority</th><th>Action</th></tr></thead>
<tbody>
@foreach($tickets as $t)
<tr>
    <td>{{ $t->id }}</td>
    <td>{{ $t->title }}</td>
    <td>{{ $t->user->name }}</td>
    <td>{{ $t->assignedTo?->name }}</td>
    <td>{{ $t->category?->name }}</td>
    <td>{{ $t->status }}</td>
    <td>{{ $t->priority }}</td>
    <td>
        <a href="{{ route('admin.tickets.edit',$t) }}" class="btn btn-sm btn-warning">Edit</a>
        <form action="{{ route('admin.tickets.destroy',$t) }}" method="post" style="display:inline">@csrf @method('DELETE')<button class="btn btn-sm btn-danger">Del</button></form>

        @if(!$t->assigned_to || $t->assigned_to != auth()->id())
            <form action="{{ route('admin.tickets.claim', $t) }}" method="post" style="display:inline">
                @csrf
                <button class="btn btn-sm btn-success">Claim</button>
            </form>
        @endif
    </td>
</tr>
@endforeach
</tbody>
</table>
{{ $tickets->links() }}

@push('scripts')
<script>$(function(){ $('#admin-tickets-table').DataTable({ "paging": false }); });</script>
@endpush
@endsection
