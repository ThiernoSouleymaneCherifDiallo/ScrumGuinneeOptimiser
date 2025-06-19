<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Project;
use Illuminate\Support\Facades\Auth;

echo "=== TEST ENVOI MESSAGE VIA POST ===\n";

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
    
    // Simuler une requête POST
    $request = new \Illuminate\Http\Request();
    $request->merge([
        'content' => 'Test message via POST - ' . now(),
        'type' => 'text'
    ]);
    
    echo "Contenu du message: " . $request->input('content') . "\n";
    
    // Créer le contrôleur et appeler la méthode store
    $controller = new \App\Http\Controllers\ProjectChatController();
    
    // Utiliser la réflexion pour appeler la méthode privée
    $reflection = new ReflectionClass($controller);
    $method = $reflection->getMethod('store');
    $method->setAccessible(true);
    
    $response = $method->invoke($controller, $request, $project);
    
    echo "Réponse: " . get_class($response) . "\n";
    
    if ($response instanceof \Illuminate\Http\JsonResponse) {
        $data = json_decode($response->getContent(), true);
        echo "Données JSON: " . json_encode($data, JSON_PRETTY_PRINT) . "\n";
    }
    
} catch (Exception $e) {
    echo "ERREUR: " . $e->getMessage() . "\n";
    echo "Fichier: " . $e->getFile() . "\n";
    echo "Ligne: " . $e->getLine() . "\n";
} 