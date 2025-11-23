@extends('layouts.app')

@section('main')
<h3>Create Ticket</h3>

<form method="POST" action="{{ route('tickets.store') }}">
    @csrf

    <div class="mb-3">
        <label>Title</label>
        <input name="title" class="form-control">
    </div>

    <div class="mb-3">
        <label>Description</label>
        <textarea name="description" class="form-control"></textarea>
    </div>

    <div class="mb-3">
        <label>Category</label>
        <select name="category_id" class="form-control">
            @foreach($categories as $c)
            <option value="{{ $c->id }}">{{ $c->name }}</option>
            @endforeach
        </select>
    </div>

    <div class="mb-3">
        <label>Priority</label>
        <select name="priority" class="form-control">
            <option>Low</option>
            <option>Medium</option>
            <option>High</option>
        </select>
    </div>

    <button class="btn btn-success">Submit</button>
</form>
@endsection
