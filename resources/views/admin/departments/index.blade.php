@extends('layouts.app')
@section('page_title','Manage Departments')

@section('main')
<div class="d-flex justify-content-between mb-2">
    <h5>Departments</h5>
    <a href="{{ route('admin.departments.create') }}" class="btn btn-primary btn-sm">Create Department</a>
</div>

<table id="departments-table" class="table table-striped table-bordered">
    <thead><tr><th>ID</th><th>Name</th><th>Description</th><th>Action</th></tr></thead>
    <tbody>
    @foreach($departments as $d)
        <tr>
            <td>{{ $d->id }}</td>
            <td>{{ $d->name }}</td>
            <td>{{ $d->description }}</td>
            <td>
                <a href="{{ route('admin.departments.edit',$d) }}" class="btn btn-sm btn-warning">Edit</a>
                <form action="{{ route('admin.departments.destroy',$d) }}" method="post" style="display:inline" onsubmit="return confirm('Delete department?')">
                    @csrf @method('DELETE')
                    <button class="btn btn-sm btn-danger">Delete</button>
                </form>
            </td>
        </tr>
    @endforeach
    </tbody>
</table>

{{ $departments->links() }}

@push('scripts')
<script>
$(document).ready(function(){ $('#departments-table').DataTable({ "paging": false }); });
</script>
@endpush
@endsection