<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Project;
use App\Models\User;

echo "=== TEST DES PROJETS ===\n";

// Vérifier les utilisateurs
echo "Utilisateurs:\n";
User::all()->each(function($user) {
    echo "- ID: {$user->id}, Nom: {$user->name}, Email: {$user->email}\n";
});

echo "\nProjets:\n";
Project::all()->each(function($project) {
    echo "- ID: {$project->id}, Nom: {$project->name}, Owner: {$project->owner_id}, Key: {$project->key}\n";
});

echo "\nProjets pour l'utilisateur 1:\n";
$projects = Project::where('owner_id', 1)->get();
foreach($projects as $project) {
    echo "- ID: {$project->id}, Nom: {$project->name}, Owner: {$project->owner_id}\n";
} 