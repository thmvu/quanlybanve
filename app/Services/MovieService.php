<?php

namespace App\Services;

use App\Models\Movie;
use Illuminate\Support\Facades\Storage;

class MovieService
{
    public function getAllMovies($status = null, $search = null, $perPage = null)
    {
        $query = Movie::query();

        if ($status) {
            $query->where('status', $status);
        }

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', '%' . $search . '%')
                  ->orWhere('description', 'like', '%' . $search . '%');
            });
        }

        $query = $query->latest();

        return $perPage ? $query->paginate($perPage) : $query->get();
    }

    public function createMovie(array $data)
    {
        if (isset($data['poster']) && $data['poster'] instanceof \Illuminate\Http\UploadedFile) {
            $path = $data['poster']->store('posters', 'public');
            $data['poster'] = $path;
        }

        return Movie::create($data);
    }

    public function updateMovie(Movie $movie, array $data)
    {
        if (isset($data['poster']) && $data['poster'] instanceof \Illuminate\Http\UploadedFile) {
            // Delete old poster
            if ($movie->poster) {
                Storage::disk('public')->delete($movie->poster);
            }
            $path = $data['poster']->store('posters', 'public');
            $data['poster'] = $path;
        }

        $movie->update($data);
        return $movie;
    }

    public function deleteMovie(Movie $movie)
    {
        if ($movie->poster) {
            Storage::disk('public')->delete($movie->poster);
        }
        return $movie->delete();
    }
}
