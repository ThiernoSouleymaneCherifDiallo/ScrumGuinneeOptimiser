<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Epic extends Model
{
    protected $fillable = [
        'name',
        'description',
        'status',
        'start_date',
        'end_date',
        'project_id',
        'created_by',
        'priority',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    /**
     * Get the project that owns the epic.
     */
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * Get the tasks associated with the epic.
     */
    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class);
    }

    /**
     * Get the user who created the epic.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Calculate the progress of the epic based on its tasks.
     */
    public function getProgressAttribute(): float
    {
        $tasks = $this->tasks;
        if ($tasks->isEmpty()) {
            return 0;
        }

        $completedTasks = $tasks->where('status', 'Terminé')->count();
        return round(($completedTasks / $tasks->count()) * 100, 1);
    }
} 