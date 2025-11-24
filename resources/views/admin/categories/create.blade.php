@extends('layouts.app')
@section('page_title','Create Category')
@section('main')
<form action="{{ route('admin.categories.store') }}" method="post">
    @csrf
    <div class="mb-3"><label>Name</label><input name="name" class="form-control" value="{{ old('name') }}"></div>
    <div class="mb-3"><label>Description</label><textarea name="description" class="form-control">{{ old('description') }}</textarea></div>
    <button class="btn btn-primary">Create</button>
</form>
@endsection
