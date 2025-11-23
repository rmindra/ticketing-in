@extends('layouts.app')

@section('main')
<h3>Ticket Detail</h3>

<p><b>Title:</b> {{ $ticket->title }}</p>
<p><b>Description:</b> {{ $ticket->description }}</p>
<p><b>Status:</b> {{ $ticket->status }}</p>

<h4>Comments</h4>

@foreach($ticket->comments as $c)
<div class="card mb-2">
    <div class="card-body">
        <b>{{ $c->user->name }}</b><br>
        {{ $c->message }}
    </div>
</div>
@endforeach

<form method="POST" action="{{ route('tickets.comments.store', $ticket->id) }}">
    @csrf
    <textarea name="message" class="form-control" placeholder="Add comment"></textarea>
    <button class="btn btn-primary mt-2">Submit</button>
</form>

@endsection
