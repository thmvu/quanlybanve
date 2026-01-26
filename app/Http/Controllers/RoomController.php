<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\Cinema;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    public function index(Request $request)
    {
        $query = Room::with('cinema');
        if($request->has('cinema_id')) {
            $query->where('cinema_id', $request->cinema_id);
        }
        $rooms = $query->latest()->get();
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
        ]);

        Room::create($request->all());

        return redirect()->route('admin.rooms.index')->with('success', 'Room created successfully.');
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
