<?php

namespace App\Http\Controllers;

use App\Models\Showtime;
use App\Models\Movie;
use App\Models\Room;
use App\Services\ShowtimeService;
use Illuminate\Http\Request;

class ShowtimeController extends Controller
{
    protected $showtimeService;

    public function __construct(ShowtimeService $showtimeService)
    {
        $this->showtimeService = $showtimeService;
    }

    public function index()
    {
        $showtimes = Showtime::with(['movie', 'room.cinema'])->latest()->paginate(10);
        return view('admin.showtimes.index', compact('showtimes'));
    }

    public function create()
    {
        $movies = Movie::where('status', 'now_showing')->orWhere('status', 'coming_soon')->get();
        $rooms = Room::with('cinema')->get(); // Better: Group by cinema in view
        return view('admin.showtimes.create', compact('movies', 'rooms'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'movie_id' => 'required|exists:movies,id',
            'room_id' => 'required|exists:rooms,id',
            'start_time' => 'required|date|after:now',
            'end_time' => 'required|date|after:start_time',
        ]);

        if ($this->showtimeService->checkOverlap($request->room_id, $request->start_time, $request->end_time)) {
            return back()->withErrors(['error' => 'Showtime overlaps with an existing schedule in this room.'])->withInput();
        }

        $this->showtimeService->createShowtime($request->all());

        return redirect()->route('admin.showtimes.index')->with('success', 'Showtime created successfully.');
    }

    public function edit(Showtime $showtime)
    {
        $movies = Movie::all(); // Allow changing to any movie
        $rooms = Room::with('cinema')->get();
        return view('admin.showtimes.edit', compact('showtime', 'movies', 'rooms'));
    }

    public function update(Request $request, Showtime $showtime)
    {
        $request->validate([
            'movie_id' => 'required|exists:movies,id',
            'room_id' => 'required|exists:rooms,id',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
        ]);

        if ($this->showtimeService->checkOverlap($request->room_id, $request->start_time, $request->end_time, $showtime->id)) {
            return back()->withErrors(['error' => 'Showtime overlaps with an existing schedule in this room.'])->withInput();
        }

        $this->showtimeService->updateShowtime($showtime, $request->all());

        return redirect()->route('admin.showtimes.index')->with('success', 'Showtime updated successfully.');
    }

    public function destroy(Showtime $showtime)
    {
        $this->showtimeService->deleteShowtime($showtime);
        return redirect()->route('admin.showtimes.index')->with('success', 'Showtime deleted successfully.');
    }
}
