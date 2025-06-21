<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\Task;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Route de test pour le chat sans CSRF
Route::post('/test-chat', function(Request $request) {
    return response()->json([
        'success' => true,
        'message' => 'Test réussi via API',
        'data' => $request->all()
    ]);
});

// API pour déplacer les tâches entre les sprints
Route::post('/tasks/{task}/move', function(Request $request, Task $task) {
    try {
        $sprintId = $request->input('sprint_id');
        
        // Si sprint_id est null, on retire la tâche du sprint (backlog)
        if ($sprintId === null) {
            $task->update(['sprint_id' => null]);
        } else {
            // Vérifier que le sprint existe
            $sprint = \App\Models\Sprint::find($sprintId);
            if (!$sprint) {
                return response()->json([
                    'success' => false,
                    'message' => 'Sprint non trouvé'
                ], 404);
            }
            
            // Vérifier que le sprint appartient au même projet que la tâche
            if ($sprint->project_id !== $task->project_id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Le sprint n\'appartient pas au même projet'
                ], 400);
            }
            
            $task->update(['sprint_id' => $sprintId]);
        }
        
        return response()->json([
            'success' => true,
            'message' => 'Tâche déplacée avec succès',
            'task' => $task->fresh()
        ]);
        
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Erreur lors du déplacement de la tâche: ' . $e->getMessage()
        ], 500);
    }
})->middleware('web'); 