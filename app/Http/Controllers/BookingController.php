<?php

namespace App\Http\Controllers;

use App\Models\Movie;
use App\Models\Showtime;
use App\Models\Seat;
use App\Models\Order;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class BookingController extends Controller
{
    public function index()
    {
        // Front page - List movies with showtimes
        $movies = Movie::where('status', 'now_showing')->with(['showtimes' => function($q) {
            $q->where('start_time', '>', now())->orderBy('start_time');
        }])->get();
        return view('booking.index', compact('movies'));
    }

    public function movie(Movie $movie)
    {
        $movie->load(['showtimes.room.cinema']);
        return view('booking.movie', compact('movie'));
    }

    public function show(Showtime $showtime)
    {
        $showtime->load(['room.seats', 'movie', 'tickets']);
        
        // Get booked seats
        $bookedSeatIds = $showtime->tickets->pluck('seat_id')->toArray();
        
        // Get locked seats from Cache
        // Pattern: seat_lock_{showtime_id}_{seat_id}
        // Ideally we should have a list, but iterating all seats is easier for small rooms
        
        return view('booking.seats', compact('showtime', 'bookedSeatIds'));
    }

    public function lockSeat(Request $request, Showtime $showtime)
    {
        $request->validate([
            'seat_id' => 'required|exists:seats,id'
        ]);

        $seatId = $request->seat_id;
        $userId = auth()->id() ?? 'guest_' . session()->getId(); // Allow guests if needed, or force auth
        $lockKey = "seat_lock_{$showtime->id}_{$seatId}";

        // Check if seat is already booked
        if (Ticket::where('showtime_id', $showtime->id)->where('seat_id', $seatId)->exists()) {
            return response()->json(['status' => 'error', 'message' => 'Seat already booked'], 409);
        }

        // Check if locked
        if (Cache::has($lockKey) && Cache::get($lockKey) !== $userId) {
            return response()->json(['status' => 'error', 'message' => 'Seat is currently held by someone else'], 409);
        }

        // Lock it
        Cache::put($lockKey, $userId, now()->addMinutes(10));

        return response()->json(['status' => 'success', 'message' => 'Seat held for 10 minutes']);
    }

    public function checkout(Request $request, Showtime $showtime)
    {
        $userId = auth()->id() ?? 0; // Guest?
        
        // In real app, we get selected seats from request
        // For security, we must verify that these seats are LOCKED by THIS user
        // simplified: accepting a list of "seat_ids"
        $seatIds = explode(',', $request->seat_ids);
        
        if(empty($seatIds)) {
            return back()->with('error', 'No seats selected');
        }

        DB::beginTransaction();
        try {
            // Create Order
            // Calculate price (placeholder 100k per seat)
            $pricePerSeat = 100000;
            $total = count($seatIds) * $pricePerSeat;

            $order = Order::create([
                'user_id' => auth()->id() ?? 1, // Fallback to user 1 if guest
                'total_price' => $total,
                'payment_method' => 'cash', // or vnpay
                'status' => 'paid', // Instant success for demo
                'transaction_id' => 'TXN-' . strtoupper(uniqid()),
            ]);

            foreach ($seatIds as $sid) {
                // Verify lock
                $lockKey = "seat_lock_{$showtime->id}_{$sid}";
                // if (!Cache::has($lockKey) || Cache::get($lockKey) != $userId) { ... }

                Ticket::create([
                    'order_id' => $order->id,
                    'showtime_id' => $showtime->id,
                    'seat_id' => $sid,
                    'price' => $pricePerSeat,
                    'code' => strtoupper(uniqid('TICKET-')),
                    'is_checked_in' => false,
                ]);

                // Remove lock
                Cache::forget($lockKey);
            }
            
            DB::commit();
            return redirect()->route('booking.index')->with('success', 'Booking confirmed! Order ID: ' . $order->id);

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Booking failed: ' . $e->getMessage());
        }
    }
}
