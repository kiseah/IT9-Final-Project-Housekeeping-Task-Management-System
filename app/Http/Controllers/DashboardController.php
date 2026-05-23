<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use App\Models\User;
use App\Models\Room;
use App\Models\Request as HousekeepingRequest;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // ✅ ADMIN Dashboard Data
        if ($user->isAdmin()) {
            $data = [
                'totalUsers'         => User::count(),
                'totalRooms'         => Room::count(),
                'pendingRequests'    => HousekeepingRequest::where('status', 'pending')->count(),
                'activeTasks'        => Task::where('status', 'in_progress')->count(),
                'completedTasks'     => Task::where('status', 'completed')->count(),
                'pendingTasks'       => Task::where('status', 'pending')->count(),
                // 'recentRequests'     => HousekeepingRequest::with(['guest', 'room'])
                //                             ->latest()->take(5)->get(),
                'recentRequests' => HousekeepingRequest::with([
                    'guest' => fn($q) => $q->withTrashed(), // ✅ preserve archived guest names
                    'room',
                    'services'
                ])->latest()->take(5)->get(),
                'recentTasks'        => Task::with(['housekeeper', 'room'])
                                            ->latest()->take(5)->get(),
            ];
            return view('dashboard.admin', $data);
        }

        // ✅ HOUSEKEEPER Dashboard Data
        if ($user->isHousekeeper()) {
            $data = [
                'pendingTasks'    => Task::where('housekeeper_id', $user->id)
                                        ->where('status', 'pending')->count(),
                'activeTasks'     => Task::where('housekeeper_id', $user->id)
                                        ->where('status', 'in_progress')->count(),
                'completedTasks'  => Task::where('housekeeper_id', $user->id)
                                        ->where('status', 'completed')->count(),
                'myTasks'         => Task::with(['room', 'request'])
                                        ->where('housekeeper_id', $user->id)
                                        ->whereIn('status', ['pending', 'in_progress'])
                                        ->latest()->take(5)->get(),
            ];
            return view('dashboard.housekeeper', $data);
        }

        // ✅ GUEST Dashboard Data
        if ($user->isGuest()) {
            $data = [
                'totalRequests'     => HousekeepingRequest::where('guest_id', $user->id)->count(),
                'pendingRequests'   => HousekeepingRequest::where('guest_id', $user->id)
                                            ->where('status', 'pending')->count(),
                'completedRequests' => HousekeepingRequest::where('guest_id', $user->id)
                                            ->where('status', 'completed')->count(),
                'myRequests'        => HousekeepingRequest::with('room')
                                            ->where('guest_id', $user->id)
                                            ->latest()->take(5)->get(),
            ];
            return view('dashboard.guest', $data);
        }
    }
}