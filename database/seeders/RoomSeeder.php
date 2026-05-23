<?php

// namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
// use Illuminate\Database\Seeder;

// class RoomSeeder extends Seeder
// {
//     /**
//      * Run the database seeds.
//      */
//     public function run(): void
//     {
//         //
//     }
// }

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Room;

class RoomSeeder extends Seeder
{
    public function run(): void
    {
        $rooms = [
            // Floor 1
            ['room_number' => '101', 'room_type' => 'Single',  'status' => 'occupied',          'notes' => null],
            ['room_number' => '102', 'room_type' => 'Single',  'status' => 'available',         'notes' => null],
            ['room_number' => '103', 'room_type' => 'Double',  'status' => 'occupied',          'notes' => null],
            ['room_number' => '104', 'room_type' => 'Double',  'status' => 'occupied',          'notes' => null],
            ['room_number' => '105', 'room_type' => 'Twin',    'status' => 'available',         'notes' => null],
            // Floor 2
            ['room_number' => '201', 'room_type' => 'Double',  'status' => 'occupied',          'notes' => null],
            ['room_number' => '202', 'room_type' => 'Suite',   'status' => 'occupied',          'notes' => 'VIP Guest'],
            ['room_number' => '203', 'room_type' => 'Single',  'status' => 'available',         'notes' => null],
            ['room_number' => '204', 'room_type' => 'Twin',    'status' => 'occupied',          'notes' => null],
            ['room_number' => '205', 'room_type' => 'Double',  'status' => 'under_maintenance', 'notes' => 'AC unit repair'],
            // Floor 3
            ['room_number' => '301', 'room_type' => 'Suite',   'status' => 'occupied',          'notes' => 'Honeymoon suite'],
            ['room_number' => '302', 'room_type' => 'Deluxe',  'status' => 'available',         'notes' => null],
            ['room_number' => '303', 'room_type' => 'Deluxe',  'status' => 'occupied',          'notes' => null],
            ['room_number' => '304', 'room_type' => 'Single',  'status' => 'available',         'notes' => null],
            ['room_number' => '305', 'room_type' => 'Double',  'status' => 'under_maintenance', 'notes' => 'Bathroom plumbing'],
        ];

        foreach ($rooms as $room) {
            Room::create($room);
        }
    }
}