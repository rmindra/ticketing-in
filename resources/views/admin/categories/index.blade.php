@extends('layouts.app')
@section('page_title','Categories')
@section('main')
<div class="d-flex justify-content-between mb-2">
    <h5>Categories</h5>
    <a href="{{ route('admin.categories.create') }}" class="btn btn-primary btn-sm">Create</a>
</div>
<table class="table table-striped">
    <thead><tr><th>ID</th><th>Name</th><th>Description</th><th>Action</th></tr></thead>
    <tbody>
    @foreach($categories as $c)
        <tr>
            <td>{{ $c->id }}</td>
            <td>{{ $c->name }}</td>
            <td>{{ $c->description }}</td>
            <td>
                <a class="btn btn-sm btn-warning" href="{{ route('admin.categories.edit',$c) }}">Edit</a>
                <form action="{{ route('admin.categories.destroy',$c) }}" method="post" style="display:inline">@csrf @method('DELETE')<button class="btn btn-sm btn-danger">Del</button></form>
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
{{ $categories->links() }}
@endsection
