@extends('layouts.app')
@section('page_title','Manage Users')

@section('main')
<div class="d-flex justify-content-between mb-2">
    <h5>Users</h5>
    <a href="{{ route('admin.users.create') }}" class="btn btn-primary btn-sm">Create User</a>
</div>

<table id="users-table" class="table table-striped table-bordered">
    <thead><tr><th>ID</th><th>Name</th><th>Email</th><th>Role</th><th>Department</th><th>Action</th></tr></thead>
    <tbody>
    @foreach($users as $u)
        <tr>
            <td>{{ $u->id }}</td>
            <td>{{ $u->name }}</td>
            <td>{{ $u->email }}</td>
            <td>{{ $u->role?->role }}</td>
            <td>{{ $u->department?->name }}</td>
            <td>
                <a href="{{ route('admin.users.edit',$u) }}" class="btn btn-sm btn-warning">Edit</a>
                <form action="{{ route('admin.users.destroy',$u) }}" method="post" style="display:inline" onsubmit="return confirm('Delete user?')">
                    @csrf @method('DELETE')
                    <button class="btn btn-sm btn-danger">Delete</button>
                </form>
            </td>
        </tr>
    @endforeach
    </tbody>
</table>

{{ $users->links() }}

@push('scripts')
<script>
$(document).ready(function(){ $('#users-table').DataTable({ "paging": false }); });
</script>
@endpush
@endsection
