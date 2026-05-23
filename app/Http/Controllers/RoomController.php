<?php

namespace App\Http\Controllers;

use App\Models\Room;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    // ✅ List all rooms
    public function index()
    {
        $rooms = Room::orderBy('room_number')->paginate(10);
        return view('rooms.index', compact('rooms'));
    }

    // ✅ Show create form
    public function create()
    {
        return view('rooms.create');
    }

    // ✅ Store new room
    public function store(Request $request)
    {
        $request->validate([
            'room_number' => 'required|string|unique:rooms,room_number|max:10',
            'room_type'   => 'required|string|max:50',
            'status'      => 'required|in:available,occupied,under_maintenance',
            'notes'       => 'nullable|string|max:500',
        ]);

        Room::create($request->all());

        return redirect()->route('rooms.index')
            ->with('success', 'Room created successfully.');
    }

    // ✅ Show single room
    public function show(Room $room)
    {
        $room->load(['tasks.housekeeper', 'requests.guest']);
        return view('rooms.show', compact('room'));
    }

    // ✅ Show edit form
    public function edit(Room $room)
    {
        return view('rooms.edit', compact('room'));
    }

    // ✅ Update room
    public function update(Request $request, Room $room)
    {
        $request->validate([
            'room_number' => 'required|string|unique:rooms,room_number,' . $room->id . '|max:10',
            'room_type'   => 'required|string|max:50',
            'status'      => 'required|in:available,occupied,under_maintenance',
            'notes'       => 'nullable|string|max:500',
        ]);

        $room->update($request->all());

        return redirect()->route('rooms.index')
            ->with('success', 'Room updated successfully.');
    }

    // ✅ Delete room
    public function destroy(Room $room)
    {
        $room->delete();

        return redirect()->route('rooms.index')
            ->with('success', 'Room deleted successfully.');
    }
}