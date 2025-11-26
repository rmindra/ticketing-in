<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Models\Category;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AdminTicketController extends Controller
{
    public function index()
    {
        $tickets = Ticket::with('user', 'assignedTo', 'category')->latest()->paginate(15);
        return view('admin.tickets.index', compact('tickets'));
    }

    public function edit(Ticket $ticket)
    {
        $categories = Category::all();

        // PERBAIKAN: Gunakan Auth::id() untuk menghindari error Intelephense
        $users = User::with('role')
            ->whereHas('role', function ($query) {
                $query->where('name', 'admin');
            })
            ->get();

        // Jika tidak ada users, tambahkan current user sebagai option
        if ($users->isEmpty()) {
            $users = User::where('id', Auth::id())->get(); // ✅ PERBAIKAN: Auth::id()
        }

        return view('admin.tickets.edit', compact('ticket', 'categories', 'users'));
    }

    public function update(Request $request, Ticket $ticket)
    {
        // Debug: lihat data yang dikirim
        Log::info('Update request data:', $request->all());

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'status' => 'required|in:Open,In Progress,Resolved,Closed',
            'priority' => 'required|in:Low,Medium,High,Urgent',
            'category_id' => 'required|exists:categories,id',
            'assigned_to' => 'nullable|exists:users,id'
        ]);

        // Debug: lihat data yang divalidasi
        Log::info('Validated data:', $validated);

        // Jika assigned_to dikosongkan, set ke null
        if (empty($validated['assigned_to'])) {
            $validated['assigned_to'] = null;

            // Jika assigned_to dikosongkan dan status In Progress, ubah ke Open
            if ($validated['status'] === 'In Progress') {
                $validated['status'] = 'Open';
            }
        } else {
            // Jika assigned_to diisi dan status Open, ubah ke In Progress
            if ($validated['status'] === 'Open') {
                $validated['status'] = 'In Progress';
            }
        }

        // Update ticket
        $ticket->update($validated);

        // Debug: lihat data setelah update
        Log::info('Ticket after update:', $ticket->toArray());

        return redirect()->route('admin.tickets.index')
            ->with('success', 'Ticket updated successfully.');
    }

    public function destroy(Ticket $ticket)
    {
        $ticket->delete();

        return redirect()->route('admin.tickets.index')
            ->with('success', 'Ticket deleted successfully.');
    }

    public function claim(Ticket $ticket)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        Log::info('Claim ticket called from AdminTicketController', [
            'ticket_id' => $ticket->id,
            'user_id' => $user->id
        ]);

        // Validasi dan logic claim ticket
        if (!$user->isAdmin()) {
            return redirect()->back()->with('error', 'Unauthorized action.');
        }

        if ($ticket->assigned_to && $ticket->assigned_to !== $user->id) {
            return redirect()->back()->with('error', 'Ticket sudah diassign ke admin lain.');
        }

        $ticket->update([
            'assigned_to' => $user->id,
            'status' => 'In Progress'
        ]);

        return redirect()->back()->with('success', 'Ticket berhasil di-claim.');
    }
}
