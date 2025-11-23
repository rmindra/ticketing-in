@extends('layouts.app')
@section('page_title','Roles')

@section('main')
<div class="d-flex justify-content-between mb-2">
    <h5>Roles</h5>
    <a href="{{ route('admin.roles.create') }}" class="btn btn-primary btn-sm">Create</a>
</div>

<table id="roles-table" class="table table-striped">
    <thead><tr><th>ID</th><th>Role</th><th>Description</th><th>Action</th></tr></thead>
    <tbody>
    @foreach($roles as $r)
        <tr>
            <td>{{ $r->id }}</td>
            <td>{{ $r->role }}</td>
            <td>{{ $r->description }}</td>
            <td>
                <a class="btn btn-sm btn-warning" href="{{ route('admin.roles.edit',$r) }}">Edit</a>
                <form action="{{ route('admin.roles.destroy',$r) }}" method="post" style="display:inline">@csrf @method('DELETE')<button class="btn btn-sm btn-danger">Del</button></form>
            </td>
        </tr>
    @endforeach
    </tbody>
</table>

{{ $roles->links() }}

@push('scripts')
<script>$(function(){ $('#roles-table').DataTable({ "paging": false }); });</script>
@endpush
@endsection
