<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'is_done',
        'project_id',
    ];

    protected $casts = [
        'is_done' => 'boolean',
    ];

    protected $hidden = [
        'updated_at'
    ];


    // Task belongs to a user
    public function creator():BelongsTo {
        return $this->belongsTo(User::class, 'creator_id');
    }

    // Task belongs to a project
    public function project():BelongsTo {
        return $this->belongsTo(Project::class);
    }

    // scope to filter tasks by creator
    protected static function booted(): void
    {
        static::addGlobalScope('creator', function (Builder $builder) {
            $builder->where('creator_id', Auth::id());
        });
    }

}
