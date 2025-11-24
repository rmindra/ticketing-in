@extends('layouts.app')
@section('page_title','Profile')

@section('main')
<form action="{{ route('profile.update') }}" method="post">
    @csrf @method('PUT')
    @include('partials.notifications')
    <div class="mb-3"><label>Name</label><input name="name" class="form-control" value="{{ old('name', $user->name) }}"></div>
    <div class="mb-3"><label>Current Password (required to change password)</label><input name="current_password" type="password" class="form-control"></div>
    <div class="mb-3"><label>New Password</label><input name="password" type="password" class="form-control"></div>
    <div class="mb-3"><label>Confirm New Password</label><input name="password_confirmation" type="password" class="form-control"></div>
    <div class="mb-3"><label>Email</label><input class="form-control" value="{{ $user->email }}" disabled></div>
    <button class="btn btn-success">Save</button>
</form>
@endsection
