@extends('layouts.app')
@section('main')
    <div class="jumbotron text-center">
        <h1>Welcome to {{ config('app.name', 'Ticketing App') }}</h1>
        <p>Your one-stop solution for managing support tickets efficiently.</p>
        @guest
            <a href="{{ route('login') }}" class="btn btn-primary btn-lg">Login</a>
            <a href="{{ route('register') }}" class="btn btn-secondary btn-lg">Register</a>
        @endguest
    </div>
@endsection

@section('css')
    <style>
        .jumbotron {
            background-color: #f8f9fa;
            padding: 4rem 2rem;
            border-radius: 0.5rem;
        }
    </style>
@endsection

@section('js')
    <script>
        console.log('Welcome to the Ticketing App!');
    </script>
@endsection