<?php

require_once 'vendor/autoload.php';

use App\Models\Project;
use App\Models\Task;
use App\Models\TaskComment;
use App\Models\User;

// Initialiser Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== Test des commentaires ===\n";

// Récupérer le premier projet
$project = Project::first();
if (!$project) {
    echo "❌ Aucun projet trouvé\n";
    exit;
}
echo "✅ Projet trouvé: {$project->name} (ID: {$project->id})\n";

// Récupérer la première tâche
$task = $project->tasks()->first();
if (!$task) {
    echo "❌ Aucune tâche trouvée dans le projet\n";
    exit;
}
echo "✅ Tâche trouvée: {$task->title} (ID: {$task->id})\n";

// Récupérer le premier utilisateur
$user = User::first();
if (!$user) {
    echo "❌ Aucun utilisateur trouvé\n";
    exit;
}
echo "✅ Utilisateur trouvé: {$user->name} (ID: {$user->id})\n";

// Vérifier les permissions du projet
echo "\n=== Test des permissions ===\n";
echo "Projet owner_id: {$project->owner_id}\n";
echo "Utilisateur actuel ID: {$user->id}\n";

$isOwner = $project->owner_id === $user->id;
$isMember = $project->members()->where('user_id', $user->id)->exists();

echo "Est propriétaire: " . ($isOwner ? 'Oui' : 'Non') . "\n";
echo "Est membre: " . ($isMember ? 'Oui' : 'Non') . "\n";

if ($isOwner || $isMember) {
    echo "✅ L'utilisateur a accès au projet\n";
} else {
    echo "❌ L'utilisateur n'a pas accès au projet\n";
}

// Vérifier les commentaires existants
echo "\n=== Commentaires existants ===\n";
$comments = $task->comments()->with(['user', 'replies.user'])->get();
echo "Nombre de commentaires: {$comments->count()}\n";

foreach ($comments as $comment) {
    echo "- Commentaire {$comment->id}: {$comment->content} (par {$comment->user->name})\n";
    if ($comment->replies->count() > 0) {
        echo "  Réponses: {$comment->replies->count()}\n";
    }
}

// Test de création d'un commentaire
echo "\n=== Test de création de commentaire ===\n";
try {
    $newComment = TaskComment::create([
        'task_id' => $task->id,
        'user_id' => $user->id,
        'content' => 'Test commentaire ' . now()->format('H:i:s'),
    ]);
    echo "✅ Commentaire créé avec succès (ID: {$newComment->id})\n";
} catch (Exception $e) {
    echo "❌ Erreur lors de la création: " . $e->getMessage() . "\n";
}

echo "\n=== Test terminé ===\n"; 