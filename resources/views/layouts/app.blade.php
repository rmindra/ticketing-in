@extends('adminlte::page')

@section('title', config('app.name','Ticketing App'))

@section('adminlte_css')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
@stop

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-9">
            @include('partials.back-button')
            @include('partials.notifications')
            <div class="card">
                <div class="card-body">
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
