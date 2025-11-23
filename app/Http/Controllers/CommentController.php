<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\CommentRequest;
use App\Models\Comment;
use App\Models\Ticket;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function store(CommentRequest $request, Ticket $ticket)
    {
        $ticket->comments()->create([
            'message' => $request->message,
            'user_id' => Auth::id()
        ]);

        return back()->with('success', 'Comment added');
    }
}
