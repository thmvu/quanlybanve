<?php

namespace App\Http\Controllers;

use App\Models\Cinema;
use Illuminate\Http\Request;

class CinemaController extends Controller
{
    public function index()
    {
        $cinemas = Cinema::latest()->get();
        return view('admin.cinemas.index', compact('cinemas'));
    }

    public function create()
    {
        return view('admin.cinemas.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        Cinema::create($request->all());

        return redirect()->route('admin.cinemas.index')->with('success', 'Cinema created successfully.');
    }

    public function edit(Cinema $cinema)
    {
        return view('admin.cinemas.edit', compact('cinema'));
    }

    public function update(Request $request, Cinema $cinema)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $cinema->update($request->all());

        return redirect()->route('admin.cinemas.index')->with('success', 'Cinema updated successfully.');
    }

    public function destroy(Cinema $cinema)
    {
        $cinema->delete();
        return redirect()->route('admin.cinemas.index')->with('success', 'Cinema deleted successfully.');
    }
}
