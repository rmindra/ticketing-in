@extends('layouts.app')
@section('main')
    <div class="jumbotron">
        <h1>My Profile</h1>
        <p>Name: {{ $user->name }}</p>
        <p>Email: {{ $user->email }}</p>
        <a href="{{ route('profile.edit') }}" class="btn btn-primary btn-md">Edit Profile</a>        
    </div>
@endsection
@section('css')
    <style>
        .jumbotron {
            background-color: #f8f9fa;
            padding: 2rem 1rem;
            border-radius: 0.3rem;
        }
    </style>
@endsection