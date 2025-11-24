@extends('layouts.app')
@section('page_title','Edit Role')
@section('main')
<form action="{{ route('admin.roles.update', $role) }}" method="post">
	@csrf @method('PUT')
	<div class="mb-3"><label>Role</label><input name="role" class="form-control" value="{{ old('role', $role->role) }}"></div>
	<div class="mb-3"><label>Description</label><textarea name="description" class="form-control">{{ old('description', $role->description) }}</textarea></div>
	<button class="btn btn-primary">Save</button>
</form>
@endsection

