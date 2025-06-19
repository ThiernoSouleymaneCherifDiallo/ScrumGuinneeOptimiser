<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Auth\Events\Verified;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\DashboardController;
use Illuminate\Http\Request;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\SprintController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\BacklogController;
use App\Http\Controllers\SprintSummaryController;
use App\Http\Controllers\ProjectMemberController;
use App\Http\Controllers\ProjectChatController;
use App\Http\Middleware\VerifyCsrfToken;
use App\Http\Controllers\SprintTaskController;
use App\Http\Controllers\TaskCommentController;
use App\Http\Controllers\AllTasksController;

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
Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('projects', ProjectController::class);
    
    // Project chat routes
    Route::get('/projects/{project}/chat', [ProjectChatController::class, 'index'])->name('projects.chat.index');
    Route::post('/projects/{project}/chat', [ProjectChatController::class, 'store'])->name('projects.chat.store');
    Route::put('/projects/{project}/chat/{message}', [ProjectChatController::class, 'update'])->name('projects.chat.update');
    Route::delete('/projects/{project}/chat/{message}', [ProjectChatController::class, 'destroy'])->name('projects.chat.destroy');
    Route::post('/projects/{project}/chat/mark-read', [ProjectChatController::class, 'markAsRead'])->name('projects.chat.mark-read');
    Route::get('/projects/{project}/chat/{message}/download', [ProjectChatController::class, 'downloadFile'])->name('projects.chat.download');
    
    // Route de test pour le chat
    Route::post('/test-chat', function(Request $request) {
        return response()->json([
            'success' => true,
            'message' => 'Test réussi',
            'data' => $request->all()
        ]);
    })->name('test.chat')->middleware('web');
    
    // Route de test sans CSRF
    Route::post('/test-chat-no-csrf', function(Request $request) {
        return response()->json([
            'success' => true,
            'message' => 'Test réussi sans CSRF',
            'data' => $request->all()
        ]);
    })->name('test.chat.no.csrf');
    
    // Route de test simple pour le chat
    Route::get('/projects/{project}/chat-simple', function($project) {
        $project = \App\Models\Project::findOrFail($project);
        $messages = $project->messages()->with('user')->orderBy('created_at', 'desc')->paginate(50);
        return view('projects.chat.simple', compact('project', 'messages'));
    })->name('projects.chat.simple');
    
    // Route de test ultra-simple pour le chat
    Route::get('/projects/{project}/chat-test', function($project) {
        $project = \App\Models\Project::findOrFail($project);
        $messages = $project->messages()->with('user')->orderBy('created_at', 'desc')->get();
        return view('projects.chat.test', compact('project', 'messages'));
    })->name('projects.chat.test');
    
    // Route de test web pour le chat
    Route::post('/test-chat-web', function(Request $request) {
        return response()->json([
            'success' => true,
            'message' => 'Test réussi via web',
            'data' => $request->all()
        ]);
    })->name('test.chat.web')->withoutMiddleware(['web', 'auth', 'verified']);
    
    // Project members routes
    Route::get('/projects/{project}/members/create', [ProjectMemberController::class, 'create'])->name('projects.members.create');
    Route::post('/projects/{project}/members', [ProjectMemberController::class, 'store'])->name('projects.members.store');
    Route::delete('/projects/{project}/members/{member}', [ProjectMemberController::class, 'destroy'])->name('projects.members.destroy');
    
    // Routes pour les tâches
    Route::get('/projects/{project}/tasks/create', [TaskController::class, 'create'])->name('projects.tasks.create');
    Route::post('/projects/{project}/tasks', [TaskController::class, 'store'])->name('projects.tasks.store');
    Route::get('/projects/{project}/tasks/{task}', [TaskController::class, 'show'])->name('projects.tasks.show');
    
    // Route de chat détaché (nouvel onglet)
    Route::get('/projects/{project}/chat-detached', function($project) {
        $project = \App\Models\Project::findOrFail($project);
        $messages = $project->messages()->with('user')->orderBy('created_at', 'desc')->get();
        return view('projects.chat.detached', compact('project', 'messages'));
    })->name('projects.chat.detached');
    
    // Route de test pour le chat détaché sans middlewares
    Route::get('/projects/{project}/chat-detached-test', function($project) {
        $project = \App\Models\Project::findOrFail($project);
        $messages = $project->messages()->with('user')->orderBy('created_at', 'desc')->get();
        return view('projects.chat.detached', compact('project', 'messages'));
    })->name('projects.chat.detached.test')->withoutMiddleware(['auth', 'verified']);
    
    // Route pour le backlog
    Route::get('/projects/{project}/backlog', [BacklogController::class, 'index'])->name('projects.backlog.index');
    
    // Routes pour les commentaires de tâches
    Route::get('/projects/{project}/tasks/{task}/comments', [TaskCommentController::class, 'index'])->name('tasks.comments.index');
    Route::post('/projects/{project}/tasks/{task}/comments', [TaskCommentController::class, 'store'])->name('tasks.comments.store');
    Route::put('/projects/{project}/tasks/{task}/comments/{comment}', [TaskCommentController::class, 'update'])->name('tasks.comments.update');
    Route::delete('/projects/{project}/tasks/{task}/comments/{comment}', [TaskCommentController::class, 'destroy'])->name('tasks.comments.destroy');
});

// Sprint routes
Route::middleware(['auth', 'verified'])->group(function () {
    // Routes pour les sprints
    Route::resource('projects.sprints', SprintController::class);
    
    // Routes pour les tâches des sprints
    Route::post('/projects/{project}/sprints/{sprint}/tasks', [SprintTaskController::class, 'store'])->name('projects.sprints.tasks.store');
    Route::put('/projects/{project}/sprints/{sprint}/tasks/{task}/status', [SprintTaskController::class, 'updateStatus'])->name('sprints.tasks.update-status');
    Route::put('/projects/{project}/sprints/{sprint}/tasks/{task}/assign', [SprintTaskController::class, 'assignTask'])->name('sprints.tasks.assign');
    
    // Route pour le résumé du sprint
    Route::get('/projects/{project}/sprints/{sprint}/summary', [SprintSummaryController::class, 'show'])->name('projects.sprints.summary');
    Route::get('/projects/{project}/sprints/{sprint}/summary/data', [SprintSummaryController::class, 'getData'])->name('projects.sprints.summary.data');
});

// Route de test pour vérifier l'authentification
Route::get('/test-auth', function() {
    if (auth()->check()) {
        return response()->json([
            'authenticated' => true,
            'user' => auth()->user()->name,
            'email_verified' => !is_null(auth()->user()->email_verified_at),
            'email' => auth()->user()->email
        ]);
    } else {
        return response()->json([
            'authenticated' => false,
            'message' => 'Non connecté'
        ]);
    }
})->name('test.auth');

// Route de test pour le chat sans middlewares
Route::get('/test-chat-access', function() {
    $project = \App\Models\Project::first();
    if (!$project) {
        return response()->json(['error' => 'Aucun projet trouvé']);
    }
    
    return response()->json([
        'project' => $project->name,
        'project_id' => $project->id,
        'chat_url' => route('projects.chat.detached', $project),
        'auth_check' => auth()->check(),
        'user' => auth()->user() ? auth()->user()->name : null
    ]);
})->name('test.chat.access');

// Route de test pour le chat simple
Route::get('/test-chat-whatsapp', function () {
    $project = \App\Models\Project::first();
    if (!$project) {
        return 'Aucun projet trouvé';
    }
    $messages = $project->messages()->with('user')->orderBy('created_at', 'asc')->get();
    return view('projects.chat.simple-whatsapp', compact('project', 'messages'));
})->name('test.chat.whatsapp');

// Route de test pour les commentaires de tâches
Route::get('/test-task-comments', function () {
    $project = \App\Models\Project::first();
    if (!$project) {
        return 'Aucun projet trouvé';
    }
    $task = $project->tasks()->first();
    if (!$task) {
        return 'Aucune tâche trouvée';
    }
    $comments = $task->comments()->with(['user', 'replies.user'])->get();
    return view('tasks.comments.index', compact('project', 'task', 'comments'));
})->name('test.task.comments');

// Route pour toutes les tâches d'un projet
Route::get('/projects/{project}/tasks', [AllTasksController::class, 'index'])->name('tasks.index');

require __DIR__.'/auth.php';

