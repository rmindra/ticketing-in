@extends('layouts.app')
@section('page_title','Edit User')

@section('main')
<form action="{{ route('admin.users.update',$user) }}" method="post">
    @csrf @method('PUT')
    <div class="mb-3"><label>Name</label><input name="name" class="form-control" value="{{ old('name',$user->name) }}"></div>
    <div class="mb-3"><label>Email</label><input name="email" class="form-control" value="{{ old('email',$user->email) }}"></div>
    <div class="mb-3"><label>Password (leave empty to keep)</label><input type="password" name="password" class="form-control"></div>
    <div class="mb-3">
        <label>Role</label>
        <select name="role_id" class="form-control">
            @foreach($roles as $r)
                <option value="{{ $r->id }}" {{ $user->role_id == $r->id ? 'selected' : ''}}>{{ $r->role }}</option>
            @endforeach
        </select>
    </div>
    <div class="mb-3">
        <label>Department</label>
        <select name="department_id" class="form-control">
            <option value="">-- none --</option>
            @foreach($departments as $d)
                <option value="{{ $d->id }}" {{ $user->department_id == $d->id ? 'selected' : ''}}>{{ $d->name }}</option>
            @endforeach
        </select>
    </div>
    <button class="btn btn-primary">Save</button>
</form>
@endsection
