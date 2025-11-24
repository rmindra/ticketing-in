@extends('layouts.app')
@section('page_title','Departments')
@section('main')
<div class="d-flex justify-content-between mb-2">
    <h5>Departments</h5>
    <a href="{{ route('admin.departments.create') }}" class="btn btn-primary btn-sm">Create</a>
 </div>
<table class="table table-striped">
    <thead><tr><th>ID</th><th>Name</th><th>Description</th><th>Action</th></tr></thead>
    <tbody>
    @foreach($departments as $d)
        <tr>
            <td>{{ $d->id }}</td>
            <td>{{ $d->name }}</td>
            <td>{{ $d->description }}</td>
            <td>
                <a class="btn btn-sm btn-warning" href="{{ route('admin.departments.edit',$d) }}">Edit</a>
                <form action="{{ route('admin.departments.destroy',$d) }}" method="post" style="display:inline">@csrf @method('DELETE')<button class="btn btn-sm btn-danger">Del</button></form>
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
{{ $departments->links() }}
@endsection
