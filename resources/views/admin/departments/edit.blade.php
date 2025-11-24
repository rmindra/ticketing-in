@extends('layouts.app')
@section('page_title','Edit Department')
@section('main')
<form action="{{ route('admin.departments.update', $department) }}" method="post">
    @csrf @method('PUT')
    <div class="mb-3"><label>Name</label><input name="name" class="form-control" value="{{ old('name', $department->name) }}"></div>
    <div class="mb-3"><label>Description</label><textarea name="description" class="form-control">{{ old('description', $department->description) }}</textarea></div>
    <button class="btn btn-primary">Save</button>
</form>
@endsection
