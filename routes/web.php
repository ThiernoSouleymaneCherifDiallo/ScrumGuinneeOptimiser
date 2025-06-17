<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Auth\Events\Verified;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\DashboardController;

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
    Route::get('/projects/{project}/tasks/index', [TaskController::class, 'index'])->name('projects.tasks.index');
});

// Sprint routes
use App\Http\Controllers\SprintController;
use App\Http\Controllers\SprintTaskController;
use App\Http\Controllers\SprintSummaryController;

Route::middleware(['auth', 'verified'])->group(function () {
    // Routes pour les sprints
    Route::resource('projects.sprints', SprintController::class);
    
    // Routes pour les tâches des sprints
    Route::post('/projects/{project}/sprints/{sprint}/tasks', [SprintTaskController::class, 'store'])->name('projects.sprints.tasks.store');
    Route::put('/projects/{project}/sprints/{sprint}/tasks/{task}/status', [SprintTaskController::class, 'updateStatus'])->name('sprints.tasks.update-status');
    Route::put('/projects/{project}/sprints/{sprint}/tasks/{task}/assign', [SprintTaskController::class, 'assignTask'])->name('sprints.tasks.assign');
    
    // Route pour le résumé du sprint
    Route::get('/projects/{project}/sprints/{sprint}/summary', [SprintSummaryController::class, 'show'])->name('projects.sprints.summary');
});

require __DIR__.'/auth.php';
