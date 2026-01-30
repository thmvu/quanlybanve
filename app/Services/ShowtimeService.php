<?php

namespace App\Services;

use App\Models\Showtime;
use Illuminate\Support\Carbon;

class ShowtimeService
{
    public function checkOverlap($roomId, $startTime, $endTime, $excludeShowtimeId = null)
    {
        $query = Showtime::where('room_id', $roomId)
            ->where(function ($query) use ($startTime, $endTime) {
                $query->where(function ($q) use ($startTime, $endTime) {
                    $q->where('start_time', '<', $endTime)
                      ->where('end_time', '>', $startTime);
                });
            });

        if ($excludeShowtimeId) {
            $query->where('id', '!=', $excludeShowtimeId);
        }

        return $query->exists();
    }

    public function createShowtime(array $data)
    {
        // Validation should happen in Controller or Request, but service ensures logic
        return Showtime::create($data);
    }

    public function updateShowtime(Showtime $showtime, array $data)
    {
        $showtime->update($data);
        return $showtime;
    }

    public function deleteShowtime(Showtime $showtime)
    {
        return $showtime->delete();
    }
}
