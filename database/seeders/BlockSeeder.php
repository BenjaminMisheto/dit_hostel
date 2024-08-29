<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Block;
use App\Models\Floor;
use App\Models\Room;
use App\Models\Bed;

class BlockSeeder extends Seeder
{
    public function run()
    {
        // Create blocks
        for ($b = 1; $b <= 7; $b++) { // Adjust the number of blocks as needed
            $block = Block::create([
                'name' => 'Block ' . $b,
                'manager' => 'Manager ' . $b,
                'location' => 'Location ' . $b,
                'number_of_floors' => rand(7, 10), // Random number of floors
                'price' => rand(500, 1500),
            ]);

            // Create floors for each block
            for ($f = 1; $f <= $block->number_of_floors; $f++) {
                $floor = Floor::create([
                    'block_id' => $block->id,
                    'floor_number' => $f,
                    'number_of_rooms' => rand(10, 20), // Random number of rooms per floor
                    'gender' => json_encode(['male', 'female']),
                    'eligibility' => json_encode(['D1', 'D2']),
                ]);

                // Create rooms for each floor
                for ($r = 1; $r <= $floor->number_of_rooms; $r++) {
                    // Randomly assign gender to the room
                    $genderOptions = ['male', 'female'];
                    $assignedGender = $genderOptions[array_rand($genderOptions)];

                    $room = Room::create([
                        'floor_id' => $floor->id,
                        'room_number' => 'F' . $floor->floor_number . '-' . $r,
                        'gender' => $assignedGender, // Assign random gender
                    ]);

                    // Create beds for each room
                    $numberOfBeds = rand(2, 10); // Random number of beds per room
                    for ($bedNumber = 1; $bedNumber <= $numberOfBeds; $bedNumber++) {
                        Bed::create([
                            'room_id' => $room->id,
                            'bed_number' => $bedNumber,
                        ]);
                    }
                }
            }
        }
    }
}
