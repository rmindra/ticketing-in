<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{
    public function index()
    {
        // Statistik tiket
        $totalTickets = Ticket::count();
        $openTickets = Ticket::where('status', 'Open')->count();

        // Tiket yang belum diterima (Open dan belum diassign)
        $unassignedTickets = Ticket::where('status', 'Open')
            ->whereNull('assigned_to')
            ->count();

        // Tiket yang sudah diterima (In Progress)
        $assignedTickets = Ticket::where('status', 'In Progress')->count();

        // Tiket yang selesai (Resolved + Closed)
        $resolvedTickets = Ticket::whereIn('status', ['Resolved', 'Closed'])->count();

        // Data untuk tiket yang bisa di-claim
        $ticketsToClaim = Ticket::with('user', 'category')
            ->where('status', 'Open')
            ->whereNull('assigned_to')
            ->latest()
            ->get();

        // Data untuk tiket yang sedang dikerjakan oleh admin ini
        $myTickets = Ticket::with('user', 'category')
            ->where('status', 'In Progress')
            ->where('assigned_to', Auth::id())
            ->latest()
            ->get();

        return view('admin.dashboard', compact(
            'totalTickets',
            'openTickets',
            'unassignedTickets',
            'assignedTickets',
            'resolvedTickets',
            'ticketsToClaim',
            'myTickets'
        ));
    }

    // Method untuk menerima tiket
    public function claimTicket(Ticket $ticket)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        Log::info('Claim ticket called from Dashboard', [
            'ticket_id' => $ticket->id,
            'user_id' => $user->id
        ]);

        // Validasi: hanya admin yang bisa claim
        //if (!$user->isAdmin()) {
        //    return redirect()->back()->with('error', 'Unauthorized action.');
        //}

        // Validasi: ticket tidak sudah diassign ke orang lain
        if ($ticket->assigned_to && $ticket->assigned_to !== $user->id) {
            return redirect()->back()->with('error', 'Ticket sudah diassign ke admin lain.');
        }

        // Update ticket
        $ticket->update([
            'assigned_to' => $user->id,
            'status' => 'In Progress'
        ]);

        Log::info('Ticket successfully claimed from Dashboard', [
            'ticket_id' => $ticket->id,
            'assigned_to' => $user->id
        ]);

        return redirect()->back()->with('success', 'Ticket berhasil di-claim.');
    }

    public function resolveTicket(Ticket $ticket)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // Robust admin check: allow based on isAdmin(), role_id fallback (1),
        // or inspecting role->role / role->name to avoid false negatives.
        $isAdmin = false;
        try {
            $isAdmin = $user->isAdmin() || $user->role_id === 1 || (($user->role->role ?? null) === 'admin') || (($user->role->name ?? null) === 'admin');
        } catch (\Throwable $e) {
            Log::warning('Error while checking admin role in resolveTicket', ['user_id' => $user->id ?? null, 'error' => $e->getMessage()]);
        }

        if (! $isAdmin) {
            Log::warning('Unauthorized resolveTicket attempt', ['user_id' => $user->id ?? null, 'ticket_id' => $ticket->id]);
            abort(403);
        }

        if ($ticket->assigned_to === $user->id && $ticket->status === 'In Progress') {
            $ticket->update([
                'status' => 'Resolved'
            ]);

            return redirect()->route('admin.dashboard')
                ->with('success', 'Ticket berhasil diselesaikan!');
        }

        return redirect()->route('admin.dashboard')
            ->with('error', 'Tidak bisa menyelesaikan tiket.');
    }
}
