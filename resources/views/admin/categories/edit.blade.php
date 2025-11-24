@extends('layouts.app')
@section('page_title','Edit Category')
@section('main')
<form action="{{ route('admin.categories.update', $category) }}" method="post">
    @csrf @method('PUT')
    <div class="mb-3"><label>Name</label><input name="name" class="form-control" value="{{ old('name', $category->name) }}"></div>
    <div class="mb-3"><label>Description</label><textarea name="description" class="form-control">{{ old('description', $category->description) }}</textarea></div>
    <button class="btn btn-primary">Save</button>
</form>
@endsection
