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
            // Only admin users can be assigned to tickets
            'users' => User::whereHas('role', function ($q) { $q->where('role', 'admin'); })->get(),
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

        // If an assignee is provided, ensure the target user has admin role
        $assignee = $request->input('assigned_to');
        if ($assignee) {
            $u = User::find($assignee);
            if (!$u || !$u->role || $u->role->role !== 'admin') {
                return back()->withErrors(['assigned_to' => 'Assignee must be an admin user.'])->withInput();
            }
        }

        $ticket->update($request->only('status', 'assigned_to', 'category_id', 'priority'));

        return redirect()->route('admin.tickets.index')->with('success', 'Ticket updated');
    }

    // Allow an admin to claim a ticket and set themselves as the assignee
    public function claim(Ticket $ticket)
    {
        $user = auth()->user();
        if (!$user->role || $user->role->role !== 'admin') {
            abort(403);
        }

        $ticket->assigned_to = $user->id;
        $ticket->status = 'In Progress';
        $ticket->save();

        return back()->with('success', 'Ticket claimed.');
    }

    public function destroy(Ticket $ticket)
    {
        $ticket->delete();
        return back()->with('success', 'Ticket deleted');
    }
}
