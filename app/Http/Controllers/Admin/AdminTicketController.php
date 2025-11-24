<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Ticket;
use App\Models\User;
use App\Models\Category;

class AdminTicketController extends Controller
{
    public function index()
    {
        $tickets = Ticket::with('user', 'assignedTo', 'category')->latest()->paginate(15);
        return view('admin.tickets.index', compact('tickets'));
    }

    public function edit(Ticket $ticket)
    {
        return view('admin.tickets.edit', [
            'ticket' => $ticket,
            'users' => User::all(),
            'categories' => Category::all(),
        ]);
    }

    public function update(Request $request, Ticket $ticket)
    {
        $request->validate([
            'status' => 'required|in:Open,In Progress,Closed',
            'assigned_to' => 'nullable|exists:users,id',
            'category_id' => 'required|exists:categories,id',
            'priority' => 'required|in:Low,Medium,High'
        ]);

        $ticket->update($request->only('status', 'assigned_to', 'category_id', 'priority'));

        return redirect()->route('admin.tickets.index')->with('success', 'Ticket updated');
    }

    public function destroy(Ticket $ticket)
    {
        $ticket->delete();
        return back()->with('success', 'Ticket deleted');
    }
}
