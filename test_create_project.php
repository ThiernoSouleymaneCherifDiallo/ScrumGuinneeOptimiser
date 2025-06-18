<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Project;
use App\Models\User;

echo "=== TEST CRÉATION PROJET ===\n";

try {
    // Simuler un utilisateur connecté (ID 1)
    auth()->loginUsingId(1);
    
    echo "Utilisateur connecté: " . auth()->id() . "\n";
    
    // Données du projet
    $projectData = [
        'name' => 'Projet Test 2',
        'description' => 'Description du projet test 2',
        'key' => 'TEST2',
        'owner_id' => 1,
        'user_id' => 1,
        'status' => 'active'
    ];
    
    echo "Données du projet: " . json_encode($projectData) . "\n";
    
    // Vérifier si la clé existe déjà
    $existingProject = Project::where('key', 'TEST')->first();
    if ($existingProject) {
        echo "ERREUR: Un projet avec la clé TEST existe déjà (ID: {$existingProject->id})\n";
        exit;
    }
    
    // Créer le projet
    echo "Création du projet...\n";
    $project = Project::create($projectData);
    
    echo "Projet créé avec succès! ID: {$project->id}\n";
    
    // Ajouter l'utilisateur comme membre
    echo "Ajout de l'utilisateur comme membre...\n";
    $project->members()->attach(1, ['role' => 'admin']);
    
    echo "Membre ajouté avec succès!\n";
    
    // Vérifier que le projet existe maintenant
    $createdProject = Project::find($project->id);
    if ($createdProject) {
        echo "Projet trouvé en base: {$createdProject->name}\n";
    } else {
        echo "ERREUR: Le projet n'a pas été trouvé en base après création\n";
    }
    
} catch (Exception $e) {
    echo "ERREUR: " . $e->getMessage() . "\n";
    echo "Fichier: " . $e->getFile() . "\n";
    echo "Ligne: " . $e->getLine() . "\n";
} 