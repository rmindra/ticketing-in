<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Admin\AdminTicketController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\HomeController;

/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
*/

Auth::routes();

Route::get('/home', [HomeController::class, 'index'])
    ->name('home')
    ->middleware('auth');

Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route('home');
    }
    return view('welcome');
});

/*
|--------------------------------------------------------------------------
| User Routes (Authenticated Only)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->group(function () {
    Route::resource('tickets', TicketController::class)
        ->only(['index', 'create', 'store', 'show']);

    Route::post('tickets/{ticket}/comments', [CommentController::class, 'store'])
        ->name('tickets.comments.store');

    Route::get('profile', [UserController::class, 'showProfile'])->name('profile.show');
    Route::get('profile/edit', [UserController::class, 'editProfile'])->name('profile.edit');
    Route::put('profile', [UserController::class, 'updateProfile'])->name('profile.update');
});

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/

Route::prefix('admin')
    ->name('admin.')
    ->middleware(['auth', 'role:admin'])
    ->group(function () {
        // Admin Dashboard
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

        // ✅ Route untuk claim ticket - PASTIKAN ADA
        Route::post('tickets/{ticket}/claim', [AdminTicketController::class, 'claim'])
            ->name('tickets.claim');

        Route::post('tickets/{ticket}/resolve', [DashboardController::class, 'resolveTicket'])
            ->name('dashboard.resolve');

        // Admin Ticket Management
        Route::resource('tickets', AdminTicketController::class)->except(['show', 'create', 'store']);

        // Route assign (jika masih butuh)
        Route::post('tickets/{ticket}/assign', [AdminTicketController::class, 'assign'])
            ->name('tickets.assign');

        // Admin Manage Resources
        Route::resource('users', AdminUserController::class);
        Route::resource('roles', RoleController::class);
        Route::resource('categories', CategoryController::class);
        Route::resource('departments', DepartmentController::class);
    });

// Additional ticket routes
Route::post('/tickets/{ticket}/comments', [CommentController::class, 'store'])->name('tickets.comments.store');
Route::post('/tickets/{ticket}/confirm-resolution', [CommentController::class, 'confirmResolution'])->name('tickets.confirm-resolution');
Route::post('/tickets/{ticket}/confirm-resolution-admin', [CommentController::class, 'confirmResolutionAdmin'])->name('tickets.confirm-resolution-admin');
Route::post('/tickets/{ticket}/reopen', [CommentController::class, 'reopen'])->name('tickets.reopen');
