<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Ticket;

class DashboardController extends Controller
{
    public function index()
    {
        $totalUsers = User::count();

        $ticketsByStatus = Ticket::selectRaw('status, count(*) as cnt')
            ->groupBy('status')
            ->pluck('cnt', 'status')
            ->toArray();

        $recentUsers = User::latest()->limit(5)->get();
        $recentTickets = Ticket::with('user')->latest()->limit(5)->get();

        return view('admin.index', compact('totalUsers', 'ticketsByStatus', 'recentUsers', 'recentTickets'));
    }
}
