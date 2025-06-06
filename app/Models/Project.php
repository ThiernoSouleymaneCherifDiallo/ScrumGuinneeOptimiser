<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Project extends Model
{
    protected $fillable = [
        'name',
        'description',
        'key',
        'status',
        'owner_id',
    ];

    public function owner(): BelongsTo
    {
        // return $this->belongsTo(User::class, 'owner_id');
        return $this->belongsTo(User::class, 'user_id');
    }

    public function members(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'project_member')
                    ->withPivot('role')
                    ->withTimestamps();
    }

}
