<?php

// use App\Http\Controllers\ProfileController;
// use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('welcome');
// });

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

// Route::middleware('auth')->group(function () {
//     Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
//     Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
//     Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
// });

// require __DIR__.'/auth.php';

// =================================================================================

// use Illuminate\Support\Facades\Route;
// use App\Http\Controllers\DashboardController;

// Route::get('/', function () {
//     return redirect()->route('login');
// });

// // ✅ Protected routes (must be logged in)
// Route::middleware(['auth'])->group(function () {
//     Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
// });

// require __DIR__.'/auth.php';

// =================================================================================

// use Illuminate\Support\Facades\Route;
// use App\Http\Controllers\DashboardController;

// Route::get('/', function () {
//     return redirect()->route('login');
// });

// // ✅ Protected routes (must be logged in)
// Route::middleware(['auth'])->group(function () {

//     // Dashboard
//     Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

//     // ✅ Temporary placeholder routes (we'll replace these one by one in later steps)
//     Route::get('/tasks', function () {
//         return redirect()->route('dashboard');
//     })->name('tasks.index');

//     Route::get('/tasks/{id}', function () {
//         return redirect()->route('dashboard');
//     })->name('tasks.show');

//     Route::get('/requests', function () {
//         return redirect()->route('dashboard');
//     })->name('requests.index');

//     Route::get('/requests/create', function () {
//         return redirect()->route('dashboard');
//     })->name('requests.create');

//     Route::get('/rooms', function () {
//         return redirect()->route('dashboard');
//     })->name('rooms.index');

//     Route::get('/users', function () {
//         return redirect()->route('dashboard');
//     })->name('users.index');

// });

// require __DIR__.'/auth.php';

// =================================================================================

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\RequestController;
use App\Http\Controllers\ProfileController;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware(['auth'])->group(function () {

    // ✅ Dashboard (all roles)
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

    // ✅ Profile (all roles)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])
        ->name('profile.password');

    // ✅ ADMIN only routes
    Route::middleware(['admin'])->group(function () {

        // Rooms - full CRUD
        Route::resource('rooms', RoomController::class);

        // ✅ Soft delete routes for users
        Route::get('/users/archived', [UserController::class, 'archived'])
            ->name('users.archived');
        Route::get('/users/{id}/archived-show', [UserController::class, 'archivedShow'])
            ->name('users.archived.show');
        Route::put('/users/{id}/restore', [UserController::class, 'restore'])
            ->name('users.restore');
        Route::delete('/users/{id}/force-delete', [UserController::class, 'forceDelete'])
            ->name('users.force-delete');

        // Users - full CRUD
        Route::resource('users', UserController::class);

        // Tasks - Admin CRUD only (no index, show, update)
        Route::resource('tasks', TaskController::class)
            ->except(['index', 'show', 'update']);

        // Requests - Admin management (no index, show, create, store)
        Route::resource('requests', RequestController::class)
            ->except(['index', 'show', 'create', 'store']);

        // Convert request to task
        Route::get('/requests/{request}/convert', [RequestController::class, 'convertToTask'])
            ->name('requests.convert');

        // Admin: update task (full edit)
        Route::put('/tasks/{task}', [TaskController::class, 'update'])
            ->name('tasks.update');
    });

    // ✅ Guest only: submit requests
    // ⚠️ MUST be defined BEFORE requests resource to avoid 404
    Route::middleware(['role.guest'])->group(function () {
        Route::get('/requests/create', [RequestController::class, 'create'])
            ->name('requests.create');
        Route::post('/requests', [RequestController::class, 'store'])
            ->name('requests.store');
    });

    // ✅ Admin + Housekeeper: view tasks (handled in controller)
    Route::get('/tasks', [TaskController::class, 'index'])->name('tasks.index');
    Route::get('/tasks/{task}', [TaskController::class, 'show'])->name('tasks.show');

    // ✅ Housekeeper: update task status
    Route::put('/tasks/{task}/status', [TaskController::class, 'update'])
        ->name('tasks.update.status')
        ->middleware(['housekeeper']);

    // ✅ Admin + Guest: view requests (handled in controller)
    Route::get('/requests', [RequestController::class, 'index'])->name('requests.index');
    Route::get('/requests/{request}', [RequestController::class, 'show'])->name('requests.show');

});

require __DIR__.'/auth.php';