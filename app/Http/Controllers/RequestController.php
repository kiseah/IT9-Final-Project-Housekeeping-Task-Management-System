<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\User;
use App\Models\Task;
use App\Models\RequestService;
use App\Models\Request as HousekeepingRequest;
use Illuminate\Http\Request;

class RequestController extends Controller
{
    // ✅ List requests
    public function index(Request $request)
    {
        $user = auth()->user();

        if ($user->isGuest()) {
            if ($user->trashed()) {
                return redirect()->route('login');
            }

            $query = HousekeepingRequest::with(['room', 'services'])
                        ->where('guest_id', $user->id);

            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }

            $requests = $query->orderBy('created_at', 'desc')
                              ->paginate(10)
                              ->withQueryString();

            return view('requests.index', compact('requests'));
        }

        // Admin sees all
        $query = HousekeepingRequest::with([
            'guest' => fn($q) => $q->withTrashed(),
            'room',
            'services'
        ]);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $requests = $query->orderBy('created_at', 'desc')
                          ->paginate(10)
                          ->withQueryString();

        return view('requests.index', compact('requests'));
    }

    // ✅ Show create form (Guest only)
    public function create()
    {
        $room = auth()->user()->room;

        if (!$room) {
            return redirect()->route('requests.index')
                ->with('error', 'You have no room assigned. Please contact the front desk.');
        }

        return view('requests.create', compact('room'));
    }

    // ✅ Store new grouped request (Guest only)
    public function store(Request $request)
    {
        $user = auth()->user();

        if (!$user->room_id) {
            return redirect()->route('requests.index')
                ->with('error', 'You have no room assigned. Please contact the front desk.');
        }

        $request->validate([
            'services'    => 'required|array|min:1',
            'services.*'  => 'required|string|max:100',
            'description' => 'nullable|string|max:500',
        ]);

        // ✅ Create ONE request record
        $req = HousekeepingRequest::create([
            'guest_id'    => $user->id,
            'room_id'     => $user->room_id,
            'description' => $request->description,
            'status'      => 'pending',
        ]);

        // ✅ Create MANY service records under it
        foreach ($request->services as $service) {
            RequestService::create([
                'request_id'   => $req->id,
                'service_type' => $service,
            ]);
        }

        return redirect()->route('requests.index')
            ->with('success', 'Your request has been submitted successfully.');
    }

    // ✅ Show single request
    public function show(HousekeepingRequest $request)
    {
        $request->load([
            'guest',
            'room',
            'services',
            'task.housekeeper'
        ]);
        return view('requests.show', compact('request'));
    }

    // ✅ Show edit form (Admin only)
    public function edit(HousekeepingRequest $request)
    {
        $request->load(['guest', 'room', 'services']);
        return view('requests.edit', compact('request'));
    }

    // ✅ Update request status (Admin only)
    public function update(Request $httpRequest, HousekeepingRequest $request)
    {
        $httpRequest->validate([
            'status' => 'required|in:pending,reviewed,in_progress,completed,cancelled',
        ]);

        $request->update(['status' => $httpRequest->status]);

        return redirect()->route('requests.index')
            ->with('success', 'Request status updated successfully.');
    }

    // ✅ Delete request (Admin only)
    public function destroy(HousekeepingRequest $request)
    {
        $request->services()->delete();
        $request->delete();

        return redirect()->route('requests.index')
            ->with('success', 'Request deleted successfully.');
    }

    // ✅ Convert request to task (Admin only)
    public function convertToTask(HousekeepingRequest $request)
    {
        $request->load(['guest', 'room', 'services']);
        $housekeepers = User::where('role', 'housekeeper')
                            ->orderBy('last_name')
                            ->get();

        return view('requests.convert', compact('request', 'housekeepers'));
    }
}