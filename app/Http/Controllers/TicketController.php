<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ticket;
use App\Models\Category;
use App\Http\Requests\TicketRequest;
use Illuminate\Support\Facades\Auth;

class TicketController extends Controller
{
    public function index()
    {
        $tickets = Ticket::where('user_id', Auth::id())->latest()->paginate(10);
        return view('tickets.index', compact('tickets'));
    }

    public function create()
    {
        return view('tickets.create', [
            'categories' => Category::all()
        ]);
    }

    public function store(TicketRequest $request)
    {
        $data = $request->validated(); // Validasi terjadi di App\Http\Requests\TicketRequest
        $data['user_id'] = Auth::id();

        $ticket = Ticket::create($data);

        return redirect()->route('tickets.show', $ticket)->with('success', 'Ticket berhasil dibuat');
    }

    public function show(Ticket $ticket)
    {
        if ($ticket->user_id !== Auth::id() && !Auth::user()->isAdmin())
            abort(403);

        return view('tickets.show', [
            'ticket' => $ticket->load('comments.user', 'category', 'assignedTo')
        ]);
    }
}
