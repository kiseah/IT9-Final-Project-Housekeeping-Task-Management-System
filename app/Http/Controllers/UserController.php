<?php

// namespace App\Http\Controllers;

// use App\Models\User;
// use App\Models\Room;
// use Illuminate\Http\Request;
// use Illuminate\Support\Facades\Hash;

// class UserController extends Controller
// {
//     // ✅ List all users with role filter
//     public function index(Request $request)
//     {
//         $query = User::orderBy('role')->orderBy('last_name');

//         if ($request->filled('role')) {
//             $query->where('role', $request->role);
//         }

//         $users = $query->paginate(10)->withQueryString();

//         return view('users.index', compact('users'));
//     }

//     // ✅ Show create form
//     public function create()
//     {
//         $rooms = Room::orderBy('room_number')->get();
//         return view('users.create', compact('rooms'));
//     }

//     // ✅ Store new user
//     public function store(Request $request)
//     {
//         $request->validate([
//             'first_name'  => 'required|string|max:50',
//             'middle_name' => 'nullable|string|max:50',
//             'last_name'   => 'required|string|max:50',
//             'email'       => 'required|email|unique:users,email',
//             'password'    => 'required|string|min:8|confirmed',
//             'role'        => 'required|in:admin,housekeeper,guest',
//             'room_id'     => 'nullable|exists:rooms,id|required_if:role,guest',
//         ]);

//         User::create([
//             'first_name'  => $request->first_name,
//             'middle_name' => $request->middle_name,
//             'last_name'   => $request->last_name,
//             'email'       => $request->email,
//             'password'    => Hash::make($request->password),
//             'role'        => $request->role,
//             'room_id'     => $request->role === 'guest' ? $request->room_id : null,
//         ]);

//         return redirect()->route('users.index')
//             ->with('success', 'User account created successfully.');
//     }

//     // ✅ Show single user
//     public function show(User $user)
//     {
//         $user->load(['tasks', 'requests', 'room']);
//         return view('users.show', compact('user'));
//     }

//     // ✅ Show edit form
//     public function edit(User $user)
//     {
//         $rooms = Room::orderBy('room_number')->get();
//         return view('users.edit', compact('user', 'rooms'));
//     }

//     // ✅ Update user
//     public function update(Request $request, User $user)
//     {
//         $request->validate([
//             'first_name'  => 'required|string|max:50',
//             'middle_name' => 'nullable|string|max:50',
//             'last_name'   => 'required|string|max:50',
//             'email'       => 'required|email|unique:users,email,' . $user->id,
//             'password'    => 'nullable|string|min:8|confirmed',
//             'role'        => 'required|in:admin,housekeeper,guest',
//             'room_id'     => 'nullable|exists:rooms,id|required_if:role,guest',
//         ]);

//         $data = [
//             'first_name'  => $request->first_name,
//             'middle_name' => $request->middle_name,
//             'last_name'   => $request->last_name,
//             'email'       => $request->email,
//             'role'        => $request->role,
//             'room_id'     => $request->role === 'guest' ? $request->room_id : null,
//         ];

//         if ($request->filled('password')) {
//             $data['password'] = Hash::make($request->password);
//         }

//         $user->update($data);

//         return redirect()->route('users.index')
//             ->with('success', 'User updated successfully.');
//     }

//     // ✅ Delete user
//     public function destroy(User $user)
//     {
//         if ($user->id === auth()->id()) {
//             return redirect()->route('users.index')
//                 ->with('error', 'You cannot delete your own account.');
//         }

//         $user->delete();

//         return redirect()->route('users.index')
//             ->with('success', 'User deleted successfully.');
//     }
// }

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    // ✅ List active users with role filter
    public function index(Request $request)
    {
        $query = User::orderBy('role')->orderBy('last_name');

        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        $users = $query->paginate(10)->withQueryString();

        return view('users.index', compact('users'));
    }

    // ✅ Show archived (soft-deleted) users
    public function archived()
    {
        $archivedUsers = User::onlyTrashed()
                            ->orderBy('deleted_at', 'desc')
                            ->paginate(10);

        return view('users.archived', compact('archivedUsers'));
    }

    // ✅ View archived user's full history
    // public function archivedShow($id)
    // {
    //     $user = User::onlyTrashed()
    //                 ->with([
    //                     'room',
    //                     'requests' => function($q) {
    //                         $q->with([
    //                             'room',
    //                             'task' => function($q) {
    //                                 $q->with('housekeeper');
    //                             }
    //                         ])->orderBy('created_at', 'desc');
    //                     }
    //                 ])
    //                 ->findOrFail($id);

    //     return view('users.archived-show', compact('user'));
    // }

    // ✅ View archived user's full history (guest or housekeeper)
    public function archivedShow($id)
    {
        $user = User::onlyTrashed()
                    ->with([
                        'room',
                        // Guest: load their requests + linked tasks + housekeeper
                        'requests' => function($q) {
                            $q->with([
                                'room',
                                'task' => function($q) {
                                    $q->with('housekeeper');
                                }
                            ])->orderBy('created_at', 'desc');
                        },
                        // Housekeeper: load their assigned tasks + room + linked request
                        'tasks' => function($q) {
                            $q->with(['room', 'request.guest' => function($q) {
                                $q->withTrashed(); // include archived guests
                            }])->orderBy('created_at', 'desc');
                        },
                    ])
                    ->findOrFail($id);

        return view('users.archived-show', compact('user'));
    }

    // ✅ Restore a soft-deleted user
    public function restore($id)
    {
        $user = User::onlyTrashed()->findOrFail($id);
        $user->restore();

        return redirect()->route('users.archived')
            ->with('success', $user->full_name . ' has been restored successfully.');
    }

    // ✅ Permanently delete a user (force delete)
    public function forceDelete($id)
    {
        $user = User::onlyTrashed()->findOrFail($id);
        $user->forceDelete();

        return redirect()->route('users.archived')
            ->with('success', 'User permanently deleted.');
    }

    // ✅ Show create form
    public function create()
    {
        $rooms = Room::orderBy('room_number')->get();
        return view('users.create', compact('rooms'));
    }

    // ✅ Store new user
    public function store(Request $request)
    {
        $request->validate([
            'first_name'  => 'required|string|max:50',
            'middle_name' => 'nullable|string|max:50',
            'last_name'   => 'required|string|max:50',
            'email'       => 'required|email|unique:users,email',
            'password'    => 'required|string|min:8|confirmed',
            'role'        => 'required|in:admin,housekeeper,guest',
            'room_id'     => 'nullable|exists:rooms,id|required_if:role,guest',
        ]);

        User::create([
            'first_name'  => $request->first_name,
            'middle_name' => $request->middle_name,
            'last_name'   => $request->last_name,
            'email'       => $request->email,
            'password'    => Hash::make($request->password),
            'role'        => $request->role,
            'room_id'     => $request->role === 'guest' ? $request->room_id : null,
        ]);

        return redirect()->route('users.index')
            ->with('success', 'User account created successfully.');
    }

    // ✅ Show single user
    public function show(User $user)
    {
        $user->load(['tasks', 'requests', 'room']);
        return view('users.show', compact('user'));
    }

    // ✅ Show edit form
    public function edit(User $user)
    {
        $rooms = Room::orderBy('room_number')->get();
        return view('users.edit', compact('user', 'rooms'));
    }

    // ✅ Update user
    public function update(Request $request, User $user)
    {
        $request->validate([
            'first_name'  => 'required|string|max:50',
            'middle_name' => 'nullable|string|max:50',
            'last_name'   => 'required|string|max:50',
            'email'       => 'required|email|unique:users,email,' . $user->id,
            'password'    => 'nullable|string|min:8|confirmed',
            'role'        => 'required|in:admin,housekeeper,guest',
            'room_id'     => 'nullable|exists:rooms,id|required_if:role,guest',
        ]);

        $data = [
            'first_name'  => $request->first_name,
            'middle_name' => $request->middle_name,
            'last_name'   => $request->last_name,
            'email'       => $request->email,
            'role'        => $request->role,
            'room_id'     => $request->role === 'guest' ? $request->room_id : null,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('users.index')
            ->with('success', 'User updated successfully.');
    }

    // ✅ Soft delete (checkout guest)
    // Sets deleted_at — does NOT permanently remove
    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return redirect()->route('users.index')
                ->with('error', 'You cannot delete your own account.');
        }

        // ✅ Invalidate the guest's session so they are logged out immediately
        \Illuminate\Support\Facades\DB::table('sessions')
            ->where('user_id', $user->id)
            ->delete();

        $user->delete(); // soft delete

        $message = match($user->role) {
            'guest'       => $user->full_name . ' has been checked out and archived.',
            'housekeeper' => $user->full_name . ' has been archived. Task history is preserved.',
            default       => $user->full_name . ' has been archived.',
        };

        return redirect()->route('users.index')
            ->with('success', $message);
    }
}