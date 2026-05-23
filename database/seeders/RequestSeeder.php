<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Request as HousekeepingRequest;
use App\Models\RequestService;
use App\Models\User;
use App\Models\Room;

class RequestSeeder extends Seeder
{
    public function run(): void
    {
        $guests = User::where('role', 'guest')->get();
        $rooms  = Room::where('status', 'occupied')->get();

        $requestData = [
            [
                'guest_index' => 0,
                'room_index'  => 0,
                'services'    => ['Room Cleaning', 'Change Linen'],
                'description' => 'Please clean thoroughly and change all bed sheets.',
                'status'      => 'completed',
            ],
            [
                'guest_index' => 1,
                'room_index'  => 1,
                'services'    => ['Extra Towels', 'Toiletries Refill'],
                'description' => 'Need 3 extra bath towels and shampoo refill.',
                'status'      => 'in_progress',
            ],
            [
                'guest_index' => 2,
                'room_index'  => 2,
                'services'    => ['Extra Pillows'],
                'description' => 'Two extra pillows needed.',
                'status'      => 'reviewed',
            ],
            [
                'guest_index' => 3,
                'room_index'  => 3,
                'services'    => ['Toiletries Refill', 'Trash Removal'],
                'description' => null,
                'status'      => 'pending',
            ],
            [
                'guest_index' => 4,
                'room_index'  => 4,
                'services'    => ['Room Cleaning', 'Extra Towels', 'Extra Blankets'],
                'description' => 'Quick tidy up needed before 2pm.',
                'status'      => 'pending',
            ],
            [
                'guest_index' => 5,
                'room_index'  => 5,
                'services'    => ['Laundry Service'],
                'description' => 'Laundry bag left at the door.',
                'status'      => 'completed',
            ],
            [
                'guest_index' => 6,
                'room_index'  => 6,
                'services'    => ['Maintenance Issue'],
                'description' => 'TV remote not working.',
                'status'      => 'in_progress',
            ],
            [
                'guest_index' => 7,
                'room_index'  => 7,
                'services'    => ['Extra Blankets', 'Extra Pillows'],
                'description' => 'Room is cold.',
                'status'      => 'pending',
            ],
            [
                'guest_index' => 0,
                'room_index'  => 0,
                'services'    => ['Trash Removal', 'Room Cleaning'],
                'description' => null,
                'status'      => 'completed',
            ],
            [
                'guest_index' => 1,
                'room_index'  => 1,
                'services'    => ['Room Cleaning'],
                'description' => 'Deep clean please, been 3 days.',
                'status'      => 'pending',
            ],
        ];

        foreach ($requestData as $data) {
            $guest = $guests[$data['guest_index']] ?? $guests->first();
            $room  = $rooms[$data['room_index']]  ?? $rooms->first();

            // ✅ Create ONE request record
            $req = HousekeepingRequest::create([
                'guest_id'    => $guest->id,
                'room_id'     => $room->id,
                'description' => $data['description'],
                'status'      => $data['status'],
            ]);

            // ✅ Create MANY service records under it
            foreach ($data['services'] as $service) {
                RequestService::create([
                    'request_id'   => $req->id,
                    'service_type' => $service,
                ]);
            }
        }
    }
}