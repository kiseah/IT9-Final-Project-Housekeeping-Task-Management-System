<?php

// namespace App\Http\Controllers;

// use App\Models\Task;
// use App\Models\User;
// use App\Models\Room;
// use App\Models\Request as HousekeepingRequest;
// use Illuminate\Http\Request;

// class TaskController extends Controller
// {
//     // ✅ List tasks (Admin sees all, Housekeeper sees only theirs)
//     public function index(Request $request)
//     {
//         $user  = auth()->user();
//         $query = Task::with(['housekeeper', 'room', 'request']);

//         if ($user->isHousekeeper()) {
//             $query->where('housekeeper_id', $user->id);
//         }

//         // Filter by status
//         if ($request->filled('status')) {
//             $query->where('status', $request->status);
//         }

//         // Filter by priority
//         if ($request->filled('priority')) {
//             $query->where('priority', $request->priority);
//         }

//         $tasks = $query->orderBy('created_at', 'desc')->paginate(10)->withQueryString();

//         return view('tasks.index', compact('tasks'));
//     }

//     // ✅ Show create form (Admin only)
//     public function create()
//     {
//         $housekeepers = User::where('role', 'housekeeper')->orderBy('last_name')->get();
//         $rooms        = Room::orderBy('room_number')->get();
//         $requests     = HousekeepingRequest::where('status', 'pending')
//                             ->orWhere('status', 'reviewed')
//                             ->with(['guest', 'room'])
//                             ->get();

//         return view('tasks.create', compact('housekeepers', 'rooms', 'requests'));
//     }

//     // ✅ Store new task (Admin only)
//     public function store(Request $request)
//     {
//         $request->validate([
//             'housekeeper_id' => 'required|exists:users,id',
//             'room_id'        => 'required|exists:rooms,id',
//             'request_id'     => 'nullable|exists:requests,id',
//             'title'          => 'required|string|max:100',
//             'description'    => 'nullable|string|max:500',
//             'status'         => 'required|in:pending,in_progress,completed,cancelled',
//             'priority'       => 'required|in:low,normal,high,urgent',
//             'due_date'       => 'nullable|date',
//         ]);

//         Task::create($request->all());

//         // If linked to a request, update request status to reviewed
//         if ($request->filled('request_id')) {
//             HousekeepingRequest::find($request->request_id)
//                 ->update(['status' => 'reviewed']);
//         }

//         return redirect()->route('tasks.index')
//             ->with('success', 'Task created successfully.');
//     }

//     // ✅ Show single task
//     public function show(Task $task)
//     {
//         $task->load(['housekeeper', 'room', 'request.guest']);
//         return view('tasks.show', compact('task'));
//     }

//     // ✅ Show edit form (Admin only)
//     public function edit(Task $task)
//     {
//         $housekeepers = User::where('role', 'housekeeper')->orderBy('last_name')->get();
//         $rooms        = Room::orderBy('room_number')->get();
//         $requests     = HousekeepingRequest::with(['guest', 'room'])->get();

//         return view('tasks.edit', compact('task', 'housekeepers', 'rooms', 'requests'));
//     }

//     // ✅ Update task
//     // Admin can update everything, Housekeeper can only update status
//     public function update(Request $request, Task $task)
//     {
//         $user = auth()->user();

//         if ($user->isHousekeeper()) {
//             // Housekeeper: status update only
//             $request->validate([
//                 'status' => 'required|in:pending,in_progress,completed,cancelled',
//             ]);

//             $task->update(['status' => $request->status]);

//             return redirect()->route('tasks.show', $task->id)
//                 ->with('success', 'Task status updated successfully.');
//         }

//         // Admin: full update
//         $request->validate([
//             'housekeeper_id' => 'required|exists:users,id',
//             'room_id'        => 'required|exists:rooms,id',
//             'request_id'     => 'nullable|exists:requests,id',
//             'title'          => 'required|string|max:100',
//             'description'    => 'nullable|string|max:500',
//             'status'         => 'required|in:pending,in_progress,completed,cancelled',
//             'priority'       => 'required|in:low,normal,high,urgent',
//             'due_date'       => 'nullable|date',
//         ]);

//         $task->update($request->all());

//         return redirect()->route('tasks.index')
//             ->with('success', 'Task updated successfully.');
//     }

//     // ✅ Delete task (Admin only)
//     public function destroy(Task $task)
//     {
//         $task->delete();

//         return redirect()->route('tasks.index')
//             ->with('success', 'Task deleted successfully.');
//     }
// }

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\User;
use App\Models\Room;
use App\Models\Request as HousekeepingRequest;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    // ✅ List tasks — Active and History tabs
    public function index(Request $request)
    {
        $user = auth()->user();

        // Block guests
        if ($user->isGuest()) {
            abort(403, 'Access denied.');
        }

        $view = $request->get('view', 'active'); // default to active tab

        $query = Task::with(['housekeeper', 'room', 'request']);

        // Housekeeper sees only their tasks
        if ($user->isHousekeeper()) {
            $query->where('housekeeper_id', $user->id);
        }

        // ✅ Tab filter
        if ($view === 'history') {
            $query->history(); // locked tasks
        } else {
            $query->active(); // unlocked tasks
        }

        // Status filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Priority filter
        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }

        $tasks = $query->orderBy('updated_at', 'desc')
                       ->paginate(10)
                       ->withQueryString();

        // Count for tab badges
        $activeCount  = Task::active()
            ->when($user->isHousekeeper(), fn($q) => $q->where('housekeeper_id', $user->id))
            ->count();
        $historyCount = Task::history()
            ->when($user->isHousekeeper(), fn($q) => $q->where('housekeeper_id', $user->id))
            ->count();

        return view('tasks.index', compact('tasks', 'view', 'activeCount', 'historyCount'));
    }

    // ✅ Show create form (Admin only)
    public function create()
    {
        $housekeepers = User::where('role', 'housekeeper')->orderBy('last_name')->get();
        $rooms        = Room::orderBy('room_number')->get();
        $requests     = HousekeepingRequest::whereIn('status', ['pending', 'reviewed'])
                            ->with(['guest', 'room'])
                            ->get();

        return view('tasks.create', compact('housekeepers', 'rooms', 'requests'));
    }

    // ✅ Store new task (Admin only)
    public function store(Request $request)
    {
        $request->validate([
            'housekeeper_id' => 'required|exists:users,id',
            'room_id'        => 'required|exists:rooms,id',
            'request_id'     => 'nullable|exists:requests,id',
            'title'          => 'required|string|max:100',
            'description'    => 'nullable|string|max:500',
            'status'         => 'required|in:pending,in_progress,completed,cancelled',
            'priority'       => 'required|in:low,normal,high,urgent',
            'due_date'       => 'nullable|date',
        ]);

        Task::create($request->all());

        if ($request->filled('request_id')) {
            HousekeepingRequest::find($request->request_id)
                ->update(['status' => 'reviewed']);
        }

        return redirect()->route('tasks.index')
            ->with('success', 'Task created successfully.');
    }

    // ✅ Show single task
    public function show(Task $task)
    {
        $user = auth()->user();

        if ($user->isGuest()) {
            abort(403, 'Access denied.');
        }

        if ($user->isHousekeeper() && $task->housekeeper_id !== $user->id) {
            abort(403, 'This task is not assigned to you.');
        }

        $task->load(['housekeeper', 'room', 'request.guest']);
        return view('tasks.show', compact('task'));
    }

    // ✅ Show edit form (Admin only)
    public function edit(Task $task)
    {
        // ✅ Block editing locked tasks
        if ($task->is_locked) {
            return redirect()->route('tasks.show', $task->id)
                ->with('error', 'This task is locked and cannot be edited.');
        }

        $housekeepers = User::where('role', 'housekeeper')->orderBy('last_name')->get();
        $rooms        = Room::orderBy('room_number')->get();
        $requests     = HousekeepingRequest::with(['guest', 'room'])->get();

        return view('tasks.edit', compact('task', 'housekeepers', 'rooms', 'requests'));
    }

    // ✅ Update task
    public function update(Request $request, Task $task)
    {
        $user = auth()->user();

        // ✅ Block updating locked tasks
        if ($task->is_locked) {
            return redirect()->route('tasks.show', $task->id)
                ->with('error', 'This task is locked and cannot be modified.');
        }

        if ($user->isHousekeeper()) {
            // Housekeeper: status update only
            $request->validate([
                'status' => 'required|in:pending,in_progress,completed,cancelled',
            ]);

            $task->update(['status' => $request->status]);

            // ✅ If task is now locked, redirect to history
            if ($task->fresh()->is_locked) {
                return redirect()->route('tasks.index', ['view' => 'history'])
                    ->with('success', 'Task marked as ' . ucfirst($request->status) . ' and moved to history.');
            }

            return redirect()->route('tasks.show', $task->id)
                ->with('success', 'Task status updated successfully.');
        }

        // Admin: full update
        $request->validate([
            'housekeeper_id' => 'required|exists:users,id',
            'room_id'        => 'required|exists:rooms,id',
            'request_id'     => 'nullable|exists:requests,id',
            'title'          => 'required|string|max:100',
            'description'    => 'nullable|string|max:500',
            'status'         => 'required|in:pending,in_progress,completed,cancelled',
            'priority'       => 'required|in:low,normal,high,urgent',
            'due_date'       => 'nullable|date',
        ]);

        $task->update($request->all());

        // ✅ If task is now locked, redirect to history
        if ($task->fresh()->is_locked) {
            return redirect()->route('tasks.index', ['view' => 'history'])
                ->with('success', 'Task marked as ' . ucfirst($request->status) . ' and moved to history.');
        }

        return redirect()->route('tasks.index')
            ->with('success', 'Task updated successfully.');
    }

    // ✅ Delete task (Admin only — only unlocked tasks)
    public function destroy(Task $task)
    {
        if ($task->is_locked) {
            return redirect()->route('tasks.index')
                ->with('error', 'Locked tasks cannot be deleted. They are part of the history.');
        }

        $task->delete();

        return redirect()->route('tasks.index')
            ->with('success', 'Task deleted successfully.');
    }
}