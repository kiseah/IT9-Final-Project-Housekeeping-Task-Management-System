<?php

// namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
// use Illuminate\Database\Seeder;

// class UserSeeder extends Seeder
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
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Room;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // ✅ Admin
        User::create([
            'first_name'  => 'Admin',
            'middle_name' => null,
            'last_name'   => 'Hotel',
            'email'       => 'admin@hotel.com',
            'password'    => Hash::make('admin1234'),
            'role'        => 'admin',
            'room_id'     => null,
        ]);

        // ✅ Housekeepers (no room assigned)
        $housekeepers = [
            ['first_name' => 'Maria',   'middle_name' => 'Santos', 'last_name' => 'Cruz'],
            ['first_name' => 'Jose',    'middle_name' => 'Reyes',  'last_name' => 'Garcia'],
            ['first_name' => 'Ana',     'middle_name' => null,     'last_name' => 'Flores'],
            ['first_name' => 'Roberto', 'middle_name' => 'Dela',   'last_name' => 'Torre'],
            ['first_name' => 'Liza',    'middle_name' => 'Mae',    'last_name' => 'Bautista'],
        ];

        foreach ($housekeepers as $index => $hk) {
            User::create([
                'first_name'  => $hk['first_name'],
                'middle_name' => $hk['middle_name'],
                'last_name'   => $hk['last_name'],
                'email'       => 'housekeeper' . ($index + 1) . '@hotel.com',
                'password'    => Hash::make('housekeeper1234'),
                'role'        => 'housekeeper',
                'room_id'     => null,
            ]);
        }

        // ✅ Guests — look up rooms by room_number dynamically
        $guests = [
            ['first_name' => 'Juan',    'middle_name' => null,     'last_name' => 'Dela Cruz', 'room' => '101'],
            ['first_name' => 'Sarah',   'middle_name' => 'Anne',   'last_name' => 'Johnson',   'room' => '103'],
            ['first_name' => 'Michael', 'middle_name' => null,     'last_name' => 'Smith',     'room' => '104'],
            ['first_name' => 'Yuki',    'middle_name' => null,     'last_name' => 'Tanaka',    'room' => '201'],
            ['first_name' => 'Emily',   'middle_name' => 'Rose',   'last_name' => 'Davis',     'room' => '202'],
            ['first_name' => 'Carlos',  'middle_name' => 'Miguel', 'last_name' => 'Reyes',     'room' => '204'],
            ['first_name' => 'Fatima',  'middle_name' => null,     'last_name' => 'Ali',       'room' => '301'],
            ['first_name' => 'James',   'middle_name' => 'Edward', 'last_name' => 'Wilson',    'room' => '303'],
        ];

        foreach ($guests as $index => $g) {
            // ✅ Find the room by room_number
            $room = Room::where('room_number', $g['room'])->first();

            User::create([
                'first_name'  => $g['first_name'],
                'middle_name' => $g['middle_name'],
                'last_name'   => $g['last_name'],
                'email'       => 'guest' . ($index + 1) . '@hotel.com',
                'password'    => Hash::make('guest1234'),
                'role'        => 'guest',
                'room_id'     => $room?->id,
            ]);
        }
    }
}