@extends('layouts.app')
@section('page_title','Manage Categories')

@section('main')
<div class="d-flex justify-content-between mb-2">
    <h5>Categories</h5>
    <a href="{{ route('admin.categories.create') }}" class="btn btn-primary btn-sm">Create Category</a>
</div>

<table id="categories-table" class="table table-striped table-bordered">
    <thead><tr><th>ID</th><th>Name</th><th>Description</th><th>Action</th></tr></thead>
    <tbody>
    @foreach($categories as $c)
        <tr>
            <td>{{ $c->id }}</td>
            <td>{{ $c->name }}</td>
            <td>{{ $c->description }}</td>
            <td>
                <a href="{{ route('admin.categories.edit',$c) }}" class="btn btn-sm btn-warning">Edit</a>
                <form action="{{ route('admin.categories.destroy',$c) }}" method="post" style="display:inline" onsubmit="return confirm('Delete category?')">
                    @csrf @method('DELETE')
                    <button class="btn btn-sm btn-danger">Del</button>
                </form>
            </td>
        </tr>
    @endforeach
    </tbody>
</table>

{{ $categories->links() }}

@push('scripts')
<script>
$(document).ready(function(){ $('#categories-table').DataTable({ "paging": false }); });
</script>
@endpush
@endsection