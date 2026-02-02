<?php

namespace App\Http\Controllers;

use App\Models\Movie;
use App\Services\MovieService;
use Illuminate\Http\Request;

class MovieController extends Controller
{
    protected $movieService;

    public function __construct(MovieService $movieService)
    {
        $this->movieService = $movieService;
    }

    public function index(Request $request)
    {
        $movies = $this->movieService->getAllMovies(null, $request->get('search'), 4);
        return view('admin.movies.index', compact('movies'));
    }

    public function create()
    {
        return view('admin.movies.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'duration' => 'required|integer|min:1',
            'release_date' => 'required|date',
            'status' => 'required|in:coming_soon,now_showing,stop_showing',
            'poster' => 'nullable|image|max:2048',
            'age_rating' => 'required|integer|min:0',
        ]);

        $this->movieService->createMovie($request->all());

        return redirect()->route('admin.movies.index')->with('success', 'Movie created successfully.');
    }

    public function edit(Movie $movie)
    {
        return view('admin.movies.edit', compact('movie'));
    }

    public function update(Request $request, Movie $movie)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'duration' => 'required|integer|min:1',
            'release_date' => 'required|date',
            'status' => 'required|in:coming_soon,now_showing,stop_showing',
            'poster' => 'nullable|image|max:2048',
            'age_rating' => 'required|integer|min:0',
        ]);

        $this->movieService->updateMovie($movie, $request->all());

        return redirect()->route('admin.movies.index')->with('success', 'Movie updated successfully.');
    }

    public function destroy(Movie $movie)
    {
        $this->movieService->deleteMovie($movie);
        return redirect()->route('admin.movies.index')->with('success', 'Movie deleted successfully.');
    }
}
