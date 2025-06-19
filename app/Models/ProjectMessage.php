<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProjectMessage extends Model
{
    protected $fillable = [
        'project_id',
        'user_id',
        'content',
        'type',
        'metadata',
        'is_edited',
        'edited_at',
        'filename',
        'original_name',
        'file_path',
        'file_size',
        'file_type',
        'reply_to_message_id',
        'reply_to_user',
        'reply_to_content'
    ];

    protected $casts = [
        'metadata' => 'array',
        'is_edited' => 'boolean',
        'edited_at' => 'datetime',
    ];

    protected $appends = [
        'formatted_time',
        'is_own_message',
        'has_file',
        'file_url',
        'formatted_file_size'
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

    public function replyTo(): BelongsTo
    {
        return $this->belongsTo(ProjectMessage::class, 'reply_to_message_id');
    }

    public function replies(): HasMany
    {
        return $this->hasMany(ProjectMessage::class, 'reply_to_message_id');
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

    public function getHasFileAttribute(): bool
    {
        return !empty($this->filename) && !empty($this->file_path);
    }

    public function getFileUrlAttribute(): ?string
    {
        if (!$this->has_file) {
            return null;
        }
        
        return Storage::url($this->file_path);
    }

    public function getFormattedFileSizeAttribute(): ?string
    {
        if (!$this->file_size) {
            return null;
        }

        $bytes = $this->file_size;
        $units = ['B', 'KB', 'MB', 'GB'];
        
        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, 2) . ' ' . $units[$i];
    }

    public function isImage(): bool
    {
        return in_array($this->file_type, ['image/jpeg', 'image/jpg', 'image/png', 'image/gif']);
    }

    public function isPdf(): bool
    {
        return $this->file_type === 'application/pdf';
    }

    public function deleteFile(): bool
    {
        if ($this->has_file && Storage::exists($this->file_path)) {
            return Storage::delete($this->file_path);
        }
        return false;
    }
} 