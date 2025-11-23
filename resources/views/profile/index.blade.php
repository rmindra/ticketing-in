@extends('layouts.app')
@section('page_title','Profile')

@section('main')
<form action="{{ route('profile.update') }}" method="post">
    @csrf @method('PUT')
    <div class="mb-3"><label>Name</label><input name="name" class="form-control" value="{{ old('name', $user->name) }}"></div>
    <div class="mb-3"><label>Email</label><input class="form-control" value="{{ $user->email }}" disabled></div>
    <button class="btn btn-success">Save</button>
</form>
@endsection
