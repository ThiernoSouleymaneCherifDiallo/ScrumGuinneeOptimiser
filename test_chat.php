<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Project;
use App\Models\ProjectMessage;

echo "=== TEST DU CHAT ===\n";

try {
    // Simuler un utilisateur connecté (ID 1)
    auth()->loginUsingId(1);
    
    echo "Utilisateur connecté: " . auth()->id() . "\n";
    
    // Récupérer le premier projet
    $project = Project::first();
    if (!$project) {
        echo "ERREUR: Aucun projet trouvé\n";
        exit;
    }
    
    echo "Projet: {$project->name} (ID: {$project->id})\n";
    
    // Créer un message de test
    $message = $project->messages()->create([
        'user_id' => 1,
        'content' => 'Salut tout le monde ! 👋 Comment ça va ?',
        'type' => 'text'
    ]);
    
    echo "Message créé avec succès! ID: {$message->id}\n";
    
    // Vérifier les messages du projet
    $messages = $project->messages()->with('user')->get();
    echo "\nMessages dans le projet:\n";
    foreach($messages as $msg) {
        echo "- {$msg->user->name}: {$msg->content}\n";
    }
    
} catch (Exception $e) {
    echo "ERREUR: " . $e->getMessage() . "\n";
    echo "Fichier: " . $e->getFile() . "\n";
    echo "Ligne: " . $e->getLine() . "\n";
} 