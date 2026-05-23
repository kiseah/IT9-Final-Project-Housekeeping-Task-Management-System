<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Task;
use App\Models\User;
use App\Models\Room;
use App\Models\Request as HousekeepingRequest;
use Carbon\Carbon;

class TaskSeeder extends Seeder
{
    public function run(): void
    {
        $housekeepers = User::where('role', 'housekeeper')->get();
        $rooms        = Room::all();
        $requests     = HousekeepingRequest::all();

        $tasks = [
            [
                'hk_index'    => 0,
                'room_index'  => 0,
                'req_index'   => 0,   // linked to request
                'title'       => 'Clean Room 101 - Full Service',
                'description' => 'Full room cleaning, change linens and towels.',
                'status'      => 'completed',
                'is_locked' => true,
                'priority'    => 'normal',
                'due_date'    => Carbon::today()->subDays(2),
            ],
            [
                'hk_index'    => 1,
                'room_index'  => 2,
                'req_index'   => 1,
                'title'       => 'Deliver Extra Towels to Room 103',
                'description' => 'Guest requested 3 extra bath towels.',
                'status'      => 'in_progress',
                'priority'    => 'normal',
                'due_date'    => Carbon::today(),
            ],
            [
                'hk_index'    => 2,
                'room_index'  => 3,
                'req_index'   => null,
                'title'       => 'Routine Clean Room 104',
                'description' => 'Standard morning room cleaning.',
                'status'      => 'pending',
                'priority'    => 'normal',
                'due_date'    => Carbon::today(),
            ],
            [
                'hk_index'    => 0,
                'room_index'  => 5,
                'req_index'   => null,
                'title'       => 'Deep Clean Room 201',
                'description' => 'Deep cleaning before new guest check-in.',
                'status'      => 'pending',
                'priority'    => 'high',
                'due_date'    => Carbon::today(),
            ],
            [
                'hk_index'    => 3,
                'room_index'  => 6,
                'req_index'   => null,
                'title'       => 'VIP Suite Preparation - Room 202',
                'description' => 'Full premium service for VIP guest.',
                'status'      => 'in_progress',
                'priority'    => 'urgent',
                'due_date'    => Carbon::today(),
            ],
            [
                'hk_index'    => 1,
                'room_index'  => 8,
                'req_index'   => null,
                'title'       => 'Clean Room 204',
                'description' => 'Morning routine cleaning.',
                'status'      => 'completed',
                'is_locked' => true,
                'priority'    => 'normal',
                'due_date'    => Carbon::today()->subDay(),
            ],
            [
                'hk_index'    => 4,
                'room_index'  => 10,
                'req_index'   => null,
                'title'       => 'Honeymoon Suite Turndown - Room 301',
                'description' => 'Evening turndown service with rose petals.',
                'status'      => 'pending',
                'priority'    => 'urgent',
                'due_date'    => Carbon::today(),
            ],
            [
                'hk_index'    => 2,
                'room_index'  => 12,
                'req_index'   => null,
                'title'       => 'Clean Room 303',
                'description' => 'Standard cleaning and restocking.',
                'status'      => 'in_progress',
                'priority'    => 'normal',
                'due_date'    => Carbon::tomorrow(),
            ],
            [
                'hk_index'    => 3,
                'room_index'  => 2,
                'req_index'   => null,
                'title'       => 'Inspect and Restock Room 103',
                'description' => 'Check minibar, toiletries, and linen supply.',
                'status'      => 'pending',
                'priority'    => 'low',
                'due_date'    => Carbon::tomorrow(),
            ],
            [
                'hk_index'    => 4,
                'room_index'  => 7,
                'req_index'   => 6,
                'title'       => 'Fix TV Remote - Room 202',
                'description' => 'Replace batteries or swap remote for guest.',
                'status'      => 'in_progress',
                'priority'    => 'high',
                'due_date'    => Carbon::today(),
            ],
        ];

        foreach ($tasks as $data) {
            $hk   = $housekeepers[$data['hk_index']]   ?? $housekeepers->first();
            $room = $rooms[$data['room_index']]         ?? $rooms->first();
            $req  = $data['req_index'] !== null
                        ? ($requests[$data['req_index']] ?? null)
                        : null;

            Task::create([
                'housekeeper_id' => $hk->id,
                'room_id'        => $room->id,
                'request_id'     => $req?->id,
                'title'          => $data['title'],
                'description'    => $data['description'],
                'status'         => $data['status'],
                'priority'       => $data['priority'],
                'due_date'       => $data['due_date'],
            ]);
        }
    }
}