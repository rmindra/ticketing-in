<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ticket;
use App\Models\Category;
use App\Http\Requests\TicketRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

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
        $data = $request->validated();
        $data['user_id'] = Auth::id();

        $ticket = Ticket::create($data);

        return redirect()->route('tickets.show', $ticket)->with('success', 'Ticket berhasil dibuat');
    }

    public function show(Ticket $ticket)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if ($ticket->user_id !== $user->id && !$user->isAdmin()) {
            abort(403);
        }

        return view('tickets.show', [
            'ticket' => $ticket->load('comments.user', 'category', 'assignedTo')
        ]);
    }

    // Tambahkan method ini di TicketController.php
    public function claimTicket(Ticket $ticket)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // Log untuk debugging
        Log::info('Claim ticket called', [
            'ticket_id' => $ticket->id,
            'user_id' => $user->id,
            'ticket_current_assigned_to' => $ticket->assigned_to
        ]);

        // Cek apakah user adalah admin
        if (!$user->isAdmin()) {
            return redirect()->back()->with('error', 'Hanya admin yang dapat claim ticket.');
        }

        // Cek apakah ticket sudah di-assign ke admin lain
        if ($ticket->assigned_to && $ticket->assigned_to !== $user->id) {
            return redirect()->back()->with('error', 'Ticket sudah diassign ke admin lain.');
        }

        // Assign ticket ke admin yang sedang login
        $ticket->update([
            'assigned_to' => $user->id,
            'status' => 'In Progress'
        ]);

        Log::info('Ticket successfully claimed', [
            'ticket_id' => $ticket->id,
            'assigned_to' => $user->id
        ]);

        return redirect()->back()->with('success', 'Ticket berhasil di-claim.');
    }
}
