<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Auth;

class ProjectMessage extends Model
{
    protected $fillable = [
        'project_id',
        'user_id',
        'content',
        'type',
        'metadata',
        'is_edited',
        'edited_at'
    ];

    protected $casts = [
        'metadata' => 'array',
        'is_edited' => 'boolean',
        'edited_at' => 'datetime',
    ];

    protected $appends = [
        'formatted_time',
        'is_own_message'
    ];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function reads(): HasMany
    {
        return $this->hasMany(ProjectMessageRead::class, 'message_id');
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

    public function getIsOwnMessageAttribute(): bool
    {
        return $this->user_id === Auth::id();
    }

    public function markAsRead(): void
    {
        $userId = Auth::id();
        if ($userId) {
            $this->reads()->updateOrCreate(
                ['user_id' => $userId],
                ['read_at' => now()]
            );
        }
    }

    public function isReadBy(int $userId): bool
    {
        return $this->reads()->where('user_id', $userId)->exists();
    }
} 