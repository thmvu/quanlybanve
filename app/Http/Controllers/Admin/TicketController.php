<?php

namespace App\Http\Controllers\Admin;

use App\Models\Ticket;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TicketController extends Controller
{
    public function index(Request $request)
    {
        $query = Ticket::with(['order.user', 'showtime.movie', 'showtime.room.cinema', 'seat']);

        // Search by ticket code
        if ($request->filled('search')) {
            $query->where('code', 'like', '%' . $request->search . '%');
        }

        // Filter by check-in status
        if ($request->filled('checked_in')) {
            $query->where('is_checked_in', $request->checked_in === 'yes');
        }

        $tickets = $query->orderBy('created_at', 'desc')->paginate(20);
        
        return view('admin.tickets.index', compact('tickets'));
    }

    public function checkIn(Ticket $ticket)
    {
        if ($ticket->is_checked_in) {
            return redirect()->back()->with('error', 'Ticket already checked in');
        }

        $ticket->update([
            'is_checked_in' => true
        ]);

        return redirect()->back()->with('success', 'Ticket checked in successfully. Code: ' . $ticket->code);
    }

    public function show(Ticket $ticket)
    {
        $ticket->load(['order.user', 'showtime.movie', 'showtime.room.cinema', 'seat']);
        
        return view('admin.tickets.show', compact('ticket'));
    }
}
