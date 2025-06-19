<?php

require_once 'vendor/autoload.php';

use App\Models\Project;
use App\Models\ProjectMessage;
use Illuminate\Support\Facades\Storage;

// Simuler l'environnement Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== TEST UPLOAD SIMPLE ===\n";

// Récupérer le premier projet
$project = Project::first();
if (!$project) {
    echo "Aucun projet trouvé !\n";
    exit;
}

echo "Projet testé : " . $project->name . "\n";

// Créer un message simple sans fichier
$message = $project->messages()->create([
    'user_id' => 1,
    'content' => 'Test message simple - ' . now()->format('H:i:s'),
]);

echo "Message créé avec ID : " . $message->id . "\n";

// Vérifier que le message a été créé
$messageCount = $project->messages()->count();
echo "Nombre total de messages : " . $messageCount . "\n";

echo "\n=== TEST TERMINÉ ===\n"; 