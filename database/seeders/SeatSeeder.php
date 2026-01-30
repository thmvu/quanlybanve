<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Room;
use App\Models\Seat;

class SeatSeeder extends Seeder
{
    public function run()
    {
        // Get all rooms that don't have seats
        $rooms = Room::doesntHave('seats')->get();

        foreach ($rooms as $room) {
            $this->command->info("Generating seats for Room: {$room->name}");
            
            // Standard layout: 10 rows (A-J), 10 seats per row
            $rows = range('A', 'J'); 
            foreach ($rows as $row) {
                for ($i = 1; $i <= 10; $i++) {
                    Seat::create([
                        'room_id' => $room->id,
                        'row' => $row,
                        'number' => $i,
                        'type' => 'normal'
                    ]);
                }
            }
        }
        
        $this->command->info('Seat generation completed!');
    }
}
