<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Auth;

class TaskComment extends Model
{
    protected $fillable = [
        'task_id',
        'user_id',
        'content',
        'parent_id',
        'is_edited',
        'edited_at'
    ];

    protected $casts = [
        'is_edited' => 'boolean',
        'edited_at' => 'datetime',
    ];

    protected $appends = [
        'formatted_time',
        'is_own_comment',
        'replies_count',
        'level'
    ];

    public function task(): BelongsTo
    {
        return $this->belongsTo(Task::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(TaskComment::class, 'parent_id');
    }

    public function replies(): HasMany
    {
        return $this->hasMany(TaskComment::class, 'parent_id')->orderBy('created_at', 'asc');
    }

    public function allReplies(): HasMany
    {
        return $this->hasMany(TaskComment::class, 'parent_id')
                    ->with('replies')
                    ->orderBy('created_at', 'asc');
    }

    public function getFormattedTimeAttribute(): string
    {
        $now = now();
        $diff = $now->diffInMinutes($this->created_at);

        if ($diff < 1) {
            return 'À l\'instant';
        } elseif ($diff < 60) {
            return "Il y a {$diff} min";
        } elseif ($diff < 1440) {
            $hours = floor($diff / 60);
            return "Il y a {$hours}h";
        } else {
            return $this->created_at->format('d/m/Y H:i');
        }
    }

    public function getIsOwnCommentAttribute(): bool
    {
        return $this->user_id === Auth::id();
    }

    public function getRepliesCountAttribute(): int
    {
        return $this->replies()->count();
    }

    public function getLevelAttribute(): int
    {
        $level = 0;
        $parent = $this->parent;
        
        while ($parent) {
            $level++;
            $parent = $parent->parent;
        }
        
        return $level;
    }

    public function isRoot(): bool
    {
        return is_null($this->parent_id);
    }

    public function hasReplies(): bool
    {
        return $this->replies()->exists();
    }

    public function scopeRoot($query)
    {
        return $query->whereNull('parent_id');
    }

    public function scopeWithReplies($query)
    {
        return $query->with(['replies' => function ($query) {
            $query->orderBy('created_at', 'asc');
        }]);
    }
}
