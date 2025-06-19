<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Task extends Model
{
    protected $fillable = [
        'title',
        'description',
        'status',
        'priority',
        'type',
        'assignee_id',
        'reporter_id',
        'due_date',
        'project_id',
        'sprint_id',
        'story_points',
        'is_blocked'
    ];

    protected $casts = [
        'due_date' => 'date',
        'is_blocked' => 'boolean'
    ];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function sprint(): BelongsTo
    {
        return $this->belongsTo(Sprint::class);
    }

    public function assignee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assignee_id');
    }

    public function reporter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reporter_id');
    }

    public function comments(): HasMany
    {
        return $this->hasMany(TaskComment::class)->root()->withReplies()->orderBy('created_at', 'asc');
    }

    public function allComments(): HasMany
    {
        return $this->hasMany(TaskComment::class)->withReplies()->orderBy('created_at', 'asc');
    }
}
