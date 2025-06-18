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
            $builder
                // creator is authorised user id
                ->where('creator_id', Auth::id())
                //or wheather project_id is part of th memberships of the user
                ->orWhereIn('project_id', Auth::user()->memberships->pluck('id'));
        });



        // Sql
        // SELECT * FROM tasks
        // WHERE creator_id = 2
        // OR project_id IN (SELECT project_id FROM member WHERE user_id = 2);

        // or for better performance use join

        // SELECT t.*
        // FROM tasks t
        // LEFT JOIN member m ON t.project_id = m.project_id AND m.user_id = 123
        // WHERE t.creator_id = 123 OR m.user_id IS NOT NULL;

    }

}
