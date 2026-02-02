<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\Cinema;
use App\Models\Seat; // Imported Seat model
use Illuminate\Http\Request;

class RoomController extends Controller
{
    public function index(Request $request)
    {
        $query = Room::with('cinema');
        if($request->has('cinema_id')) {
            $query->where('cinema_id', $request->cinema_id);
        }
        $rooms = $query->latest()->paginate(10);
        return view('admin.rooms.index', compact('rooms'));
    }

    public function create()
    {
        $cinemas = Cinema::all();
        return view('admin.rooms.create', compact('cinemas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'cinema_id' => 'required|exists:cinemas,id',
            'name' => 'required|string|max:255',
            'type' => 'required|string|in:2D,3D,IMAX',
            'total_rows' => 'required|integer|min:1|max:26',
            'seats_per_row' => 'required|integer|min:1|max:50',
        ]);

        $room = Room::create([
            'cinema_id' => $request->cinema_id,
            'name' => $request->name,
            'type' => $request->type,
        ]);

        // Auto-generate seats
        $rows = range('A', 'Z');
        for ($i = 0; $i < $request->total_rows; $i++) {
            $rowLabel = $rows[$i];
            for ($j = 1; $j <= $request->seats_per_row; $j++) {
                Seat::create([
                    'room_id' => $room->id,
                    'row' => $rowLabel,
                    'number' => $j,
                    'type' => 'normal', // Default status
                ]);
            }
        }

        return redirect()->route('admin.rooms.index')->with('success', 'Room and tickets created successfully.');
    }

    public function edit(Room $room)
    {
        $cinemas = Cinema::all();
        return view('admin.rooms.edit', compact('room', 'cinemas'));
    }

    public function update(Request $request, Room $room)
    {
        $request->validate([
            'cinema_id' => 'required|exists:cinemas,id',
            'name' => 'required|string|max:255',
            'type' => 'required|string|in:2D,3D,IMAX',
        ]);

        $room->update($request->all());

        return redirect()->route('admin.rooms.index')->with('success', 'Room updated successfully.');
    }

    public function destroy(Room $room)
    {
        $room->delete();
        return redirect()->route('admin.rooms.index')->with('success', 'Room deleted successfully.');
    }
}
