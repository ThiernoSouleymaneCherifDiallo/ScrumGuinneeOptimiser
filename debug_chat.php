<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Project;
use App\Models\ProjectMessage;
use Illuminate\Support\Facades\Auth;

echo "=== DIAGNOSTIC DU CHAT ===\n";

try {
    // Simuler un utilisateur connecté (ID 1)
    Auth::loginUsingId(1);
    
    echo "Utilisateur connecté: " . Auth::id() . "\n";
    
    // Récupérer le premier projet
    $project = Project::first();
    if (!$project) {
        echo "ERREUR: Aucun projet trouvé\n";
        exit;
    }
    
    echo "Projet: {$project->name} (ID: {$project->id})\n";
    
    // Vérifier les permissions
    echo "Permissions:\n";
    echo "- Peut voir le projet: " . (Gate::allows('view', $project) ? 'OUI' : 'NON') . "\n";
    echo "- Peut créer des messages: " . (Gate::allows('view', $project) ? 'OUI' : 'NON') . "\n";
    
    // Test de création directe
    echo "\nTest de création directe:\n";
    $message = new ProjectMessage();
    $message->project_id = $project->id;
    $message->user_id = Auth::id();
    $message->content = 'Test de diagnostic - ' . now();
    $message->type = 'text';
    
    if ($message->save()) {
        echo "✅ Message créé avec succès! ID: {$message->id}\n";
    } else {
        echo "❌ Erreur lors de la création du message\n";
        print_r($message->getErrors());
    }
    
    // Vérifier les messages existants
    $messages = $project->messages()->with('user')->get();
    echo "\nMessages dans le projet ({$messages->count()}):\n";
    foreach($messages as $msg) {
        echo "- ID: {$msg->id}, User: {$msg->user->name}, Content: {$msg->content}\n";
    }
    
    // Test de validation
    echo "\nTest de validation:\n";
    $validator = Validator::make([
        'content' => 'Test de validation',
        'type' => 'text'
    ], [
        'content' => 'required|string|max:2000',
        'type' => 'nullable|string|in:text,file,system'
    ]);
    
    if ($validator->passes()) {
        echo "✅ Validation OK\n";
    } else {
        echo "❌ Erreurs de validation:\n";
        print_r($validator->errors()->toArray());
    }
    
} catch (Exception $e) {
    echo "ERREUR: " . $e->getMessage() . "\n";
    echo "Fichier: " . $e->getFile() . "\n";
    echo "Ligne: " . $e->getLine() . "\n";
    echo "Trace:\n" . $e->getTraceAsString() . "\n";
} 