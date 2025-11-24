@extends('layouts.app')
@section('page_title','Create Role')
@section('main')
<form action="{{ route('admin.roles.store') }}" method="post">
	@csrf
	<div class="mb-3"><label>Role</label><input name="role" class="form-control" value="{{ old('role') }}"></div>
	<div class="mb-3"><label>Description</label><textarea name="description" class="form-control">{{ old('description') }}</textarea></div>
	<button class="btn btn-primary">Create</button>
</form>
@endsection

