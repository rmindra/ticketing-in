@extends('layouts.app')
@section('page_title','Create User')

@section('main')
<form action="{{ route('admin.users.store') }}" method="post">
    @csrf
    <div class="mb-3"><label>Name</label><input name="name" class="form-control" value="{{ old('name') }}"></div>
    <div class="mb-3"><label>Email</label><input name="email" class="form-control" value="{{ old('email') }}"></div>
    <div class="mb-3"><label>Password</label><input type="password" name="password" class="form-control"></div>
    <div class="mb-3">
        <label>Role</label>
        <select name="role_id" class="form-control">
            @foreach($roles as $r)<option value="{{ $r->id }}">{{ $r->role }}</option>@endforeach
        </select>
    </div>
    <div class="mb-3">
        <label>Department</label>
        <select name="department_id" class="form-control">
            <option value="">-- none --</option>
            @foreach($departments as $d)<option value="{{ $d->id }}">{{ $d->name }}</option>@endforeach
        </select>
    </div>
    <button class="btn btn-success">Create</button>
</form>
@endsection
