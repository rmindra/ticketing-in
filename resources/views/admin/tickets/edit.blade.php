@extends('layouts.app')
@section('page_title','Edit Ticket')

@section('main')
<form action="{{ route('admin.tickets.update',$ticket) }}" method="post">
    @csrf @method('PUT')
    <div class="mb-3"><label>Title</label><input name="title" class="form-control" value="{{ old('title',$ticket->title) }}"></div>
    <div class="mb-3"><label>Category</label>
        <select name="category_id" class="form-control">
            @foreach($categories as $c)
                <option value="{{ $c->id }}" {{ $ticket->category_id==$c->id?'selected':'' }}>{{ $c->name }}</option>
            @endforeach
        </select>
    </div>

    <div class="mb-3"><label>Assignee</label>
        <select name="assigned_to" class="form-control">
            <option value="">-- none --</option>
            @foreach($users as $u)
                <option value="{{ $u->id }}" {{ $ticket->assigned_to==$u->id?'selected':'' }}>{{ $u->name }}</option>
            @endforeach
        </select>
    </div>

    <div class="mb-3"><label>Status</label>
        <select name="status" class="form-control">
            <option {{ $ticket->status=='Open'?'selected':'' }}>Open</option>
            <option {{ $ticket->status=='In Progress'?'selected':'' }}>In Progress</option>
            <option {{ $ticket->status=='Closed'?'selected':'' }}>Closed</option>
        </select>
    </div>

    <div class="mb-3"><label>Priority</label>
        <select name="priority" class="form-control">
            <option {{ $ticket->priority=='Low'?'selected':'' }}>Low</option>
            <option {{ $ticket->priority=='Medium'?'selected':'' }}>Medium</option>
            <option {{ $ticket->priority=='High'?'selected':'' }}>High</option>
        </select>
    </div>

    <button class="btn btn-primary">Save</button>
</form>
@endsection
