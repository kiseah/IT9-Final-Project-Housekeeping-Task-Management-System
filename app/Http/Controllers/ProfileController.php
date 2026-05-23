<?php

// namespace App\Http\Controllers;

// use App\Http\Requests\ProfileUpdateRequest;
// use Illuminate\Http\RedirectResponse;
// use Illuminate\Http\Request;
// use Illuminate\Support\Facades\Auth;
// use Illuminate\Support\Facades\Redirect;
// use Illuminate\View\View;

// class ProfileController extends Controller
// {
//     /**
//      * Display the user's profile form.
//      */
//     public function edit(Request $request): View
//     {
//         return view('profile.edit', [
//             'user' => $request->user(),
//         ]);
//     }

//     /**
//      * Update the user's profile information.
//      */
//     public function update(ProfileUpdateRequest $request): RedirectResponse
//     {
//         $request->user()->fill($request->validated());

//         if ($request->user()->isDirty('email')) {
//             $request->user()->email_verified_at = null;
//         }

//         $request->user()->save();

//         return Redirect::route('profile.edit')->with('status', 'profile-updated');
//     }

//     /**
//      * Delete the user's account.
//      */
//     public function destroy(Request $request): RedirectResponse
//     {
//         $request->validateWithBag('userDeletion', [
//             'password' => ['required', 'current_password'],
//         ]);

//         $user = $request->user();

//         Auth::logout();

//         $user->delete();

//         $request->session()->invalidate();
//         $request->session()->regenerateToken();

//         return Redirect::to('/');
//     }
// }

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    // ✅ Show profile page
    public function edit()
    {
        $user = auth()->user();
        return view('profile.edit', compact('user'));
    }

    // ✅ Update profile info
    public function update(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'first_name'  => 'required|string|max:50',
            'middle_name' => 'nullable|string|max:50',
            'last_name'   => 'required|string|max:50',
            'email'       => 'required|email|unique:users,email,' . $user->id,
        ]);

        $user->update([
            'first_name'  => $request->first_name,
            'middle_name' => $request->middle_name,
            'last_name'   => $request->last_name,
            'email'       => $request->email,
        ]);

        return redirect()->route('profile.edit')
            ->with('success', 'Profile updated successfully.');
    }

    // ✅ Update password
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password'         => ['required', 'confirmed', Password::min(8)],
        ]);

        $user = auth()->user();

        // Check current password
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors([
                'current_password' => 'The current password is incorrect.'
            ]);
        }

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('profile.edit')
            ->with('success', 'Password changed successfully.');
    }
}