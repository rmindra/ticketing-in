<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Admin\AdminTicketController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
*/

Auth::routes();

use App\Http\Controllers\HomeController;

Route::get('/home', [HomeController::class, 'index'])
    ->name('home')
    ->middleware('auth');


Route::get('/', function () {
    return auth()->guest()
        ? view('welcome')
        : redirect()->route('home');
});

/*
|--------------------------------------------------------------------------
| User Routes (Authenticated Only)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->group(function () {

    // User Ticketing
    Route::resource('tickets', TicketController::class)
        ->only(['index', 'create', 'store', 'show']);

    // Comment on ticket
    Route::post(
        'tickets/{ticket}/comments',
        [CommentController::class, 'store']
    )
        ->name('tickets.comments.store');

    // User Profile (non-admin)
    Route::get('profile', [UserController::class, 'showProfile'])->name('profile.show');
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

        // Admin Ticket Management
        Route::resource('tickets', AdminTicketController::class)
            ->except(['show', 'create', 'store']);

        // Admin Manage Users
        Route::resource('users', AdminUserController::class);

        // Admin Manage Roles
        Route::resource('roles', RoleController::class);

        // Admin Manage Categories
        Route::resource('categories', CategoryController::class);

        // Admin Manage Departments
        Route::resource('departments', DepartmentController::class);
    });
