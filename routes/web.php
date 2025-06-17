<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Auth\Events\Verified;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SummaryController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth', 'verified')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});



Route::middleware(['auth', 'role:Admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::post('/users/{user}/role', [UserController::class, 'updateRole'])->name('users.updateRole');
});



// Project routes
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ProjectMemberController;
use App\Http\Controllers\TaskController;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('projects', ProjectController::class);
    
    // Project members routes
    Route::get('/projects/{project}/members/create', [ProjectMemberController::class, 'create'])->name('projects.members.create');
    Route::post('/projects/{project}/members', [ProjectMemberController::class, 'store'])->name('projects.members.store');
    Route::delete('/projects/{project}/members/{member}', [ProjectMemberController::class, 'destroy'])->name('projects.members.destroy');
    
    // Routes pour les tâches
    Route::get('/projects/{project}/tasks/create', [TaskController::class, 'create'])->name('projects.tasks.create');
    Route::post('/projects/{project}/tasks', [TaskController::class, 'store'])->name('projects.tasks.store');

    Route::get('/projects/{project}/resume', [SummaryController::class, 'show'])->name('projects.resume');
});

require __DIR__.'/auth.php';
