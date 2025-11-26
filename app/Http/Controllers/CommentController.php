<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class CommentController extends Controller
{
    public function store(Request $request, Ticket $ticket)
    {
        $request->validate([
            'content' => 'required|string|min:3'
        ]);

        /** @var \App\Models\User $user */
        $user = Auth::user();

        // Cek apakah user bisa menambah komentar
        if (!$ticket->canBeViewedBy($user)) {
            abort(403, 'Unauthorized action.');
        }

        // Cek apakah ticket masih bisa dikomentari
        if (!$ticket->canAddComments()) {
            return redirect()->back()->with('error', 'Tidak bisa menambah komentar pada ticket yang sudah closed/resolved.');
        }

        // Buat komentar
        $comment = new Comment();
        $comment->content = $request->content;
        $comment->user_id = $user->id;
        $comment->ticket_id = $ticket->id;
        $comment->is_admin = $user->isAdmin(); // Tandai jika komentar dari admin
        $comment->save();

        // Update timestamp ticket (agar muncul di atas di list)
        $ticket->touch();

        Log::info('Comment added', [
            'ticket_id' => $ticket->id,
            'user_id' => $user->id,
            'is_admin' => $user->isAdmin()
        ]);

        return redirect()->back()->with('success', 'Komentar berhasil ditambahkan.');
    }

    /**
     * Update an existing comment.
     */
    public function update(Request $request, Ticket $ticket, Comment $comment)
    {
        $request->validate([
            'content' => 'required|string|min:1'
        ]);

        $user = Auth::user();

        // Only admin or owner of comment can update
        if (!($user->isAdmin() || $comment->user_id === $user->id)) {
            abort(403);
        }

        $comment->content = $request->input('content');
        // don't change is_admin/is_system here
        $comment->save();

        return redirect()->back()->with('success', 'Komentar diperbarui.');
    }

    /**
     * Delete a comment.
     */
    public function destroy(Ticket $ticket, Comment $comment)
    {
        $user = Auth::user();

        // Only admin or owner can delete
        if (!($user->isAdmin() || $comment->user_id === $user->id)) {
            abort(403);
        }

        $comment->delete();
        return redirect()->back()->with('success', 'Komentar dihapus.');
    }

    // Method untuk user konfirmasi resolusi
    public function confirmResolution(Ticket $ticket)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // Validasi: hanya user pemilik ticket yang bisa konfirmasi
        if (!$ticket->canBeConfirmedByUser($user->id)) {
            return redirect()->back()->with('error', 'Tidak dapat mengonfirmasi resolusi.');
        }

        $ticket->update(['user_confirmed' => true]);

        // Jika kedua belah pihak sudah konfirmasi, close ticket
        if ($ticket->isFullyConfirmed()) {
            $ticket->update(['status' => 'Closed']);
        }

        // Tambahkan komentar otomatis
        $comment = new Comment();
        $comment->content = 'User telah mengonfirmasi bahwa masalah telah terselesaikan.';
        $comment->user_id = $user->id;
        $comment->ticket_id = $ticket->id;
        $comment->is_admin = false;
        $comment->is_system = true; // Komentar sistem
        $comment->save();

        return redirect()->back()->with('success', 'Terima kasih telah mengonfirmasi resolusi.');
    }

    // Method untuk admin konfirmasi resolusi
    public function confirmResolutionAdmin(Ticket $ticket)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // Validasi: hanya admin yang bisa konfirmasi
        if (!$ticket->canBeConfirmedByAdmin($user)) {
            return redirect()->back()->with('error', 'Tidak dapat mengonfirmasi resolusi.');
        }

        $ticket->update(['admin_confirmed' => true]);

        // Jika kedua belah pihak sudah konfirmasi, close ticket
        if ($ticket->isFullyConfirmed()) {
            $ticket->update(['status' => 'Closed']);
        }

        // Tambahkan komentar otomatis
        $comment = new Comment();
        $comment->content = 'Admin telah mengonfirmasi penyelesaian ticket.';
        $comment->user_id = $user->id;
        $comment->ticket_id = $ticket->id;
        $comment->is_admin = true;
        $comment->is_system = true; // Komentar sistem
        $comment->save();

        return redirect()->back()->with('success', 'Resolusi telah dikonfirmasi.');
    }

    // Method untuk reopen ticket
    public function reopen(Ticket $ticket)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // Validasi: hanya user pemilik atau admin yang bisa reopen
        if (!$ticket->isOwnedBy($user->id) && !$user->isAdmin()) {
            return redirect()->back()->with('error', 'Unauthorized action.');
        }

        // Validasi: hanya ticket resolved/closed yang bisa di-reopen
        if (!$ticket->isResolved() && !$ticket->isClosed()) {
            return redirect()->back()->with('error', 'Hanya ticket yang sudah resolved/closed yang bisa di-reopen.');
        }

        $ticket->update([
            'status' => 'In Progress',
            'user_confirmed' => false,
            'admin_confirmed' => false
        ]);

        // Tambahkan komentar otomatis
        $comment = new Comment();
        $comment->content = $user->isAdmin()
            ? 'Admin telah membuka kembali ticket ini.'
            : 'User telah membuka kembali ticket karena masalah belum terselesaikan.';
        $comment->user_id = $user->id;
        $comment->ticket_id = $ticket->id;
        $comment->is_admin = $user->isAdmin();
        $comment->is_system = true;
        $comment->save();

        return redirect()->back()->with('success', 'Ticket berhasil dibuka kembali.');
    }
}
