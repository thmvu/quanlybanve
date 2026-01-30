<?php

namespace App\Console\Commands;

use App\Models\Room;
use App\Models\Seat;
use Illuminate\Console\Command;

class GenerateSeats extends Command
{
    protected $signature = 'seats:generate {--room= : Specific room ID}';
    protected $description = 'Generate seats for cinema rooms';

    public function handle()
    {
        $roomId = $this->option('room');
        
        if ($roomId) {
            $rooms = Room::where('id', $roomId)->get();
        } else {
            $rooms = Room::all();
        }

        if ($rooms->isEmpty()) {
            $this->error('No rooms found!');
            return 1;
        }

        foreach ($rooms as $room) {
            // Check if room already has seats
            if ($room->seats()->count() > 0) {
                $this->info("Room {$room->name} already has seats. Skipping...");
                continue;
            }

            $this->info("Generating seats for room: {$room->name}");
            
            // Generate seats: 8 rows (A-H), 10 seats per row
            $rows = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H'];
            $seatsPerRow = 10;

            foreach ($rows as $row) {
                for ($number = 1; $number <= $seatsPerRow; $number++) {
                    Seat::create([
                        'room_id' => $room->id,
                        'row' => $row,
                        'number' => $number,
                    ]);
                }
            }

            $this->info("✓ Created " . (count($rows) * $seatsPerRow) . " seats for {$room->name}");
        }

        $this->info("\n✅ Seats generation completed!");
        return 0;
    }
}
