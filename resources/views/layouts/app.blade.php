@extends('adminlte::page')

@section('title', config('app.name','Ticketing App'))

@section('adminlte_css')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <!-- Load Custom CSS -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/admin.css') }}" rel="stylesheet">
    <link href="{{ asset('css/components.css') }}" rel="stylesheet">
@stop

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            @include('partials.back-button')
            @include('partials.notifications')
            <div class="card">
                <div class="card-body p-4">
                    @yield('main')
                </div>
            </div>
        </div>
    </div>
</div>
@stop

@section('adminlte_js')
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    @stack('scripts')
@stop
