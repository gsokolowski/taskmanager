<?php

namespace App\Models;

use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Auth;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'title'
    ];

    // project has many tasks
    public function tasks(): HasMany {
        return $this->hasMany(Task::class);
    }

    // project belongs to a user
    public function creator(): BelongsTo {
        return $this->belongsTo(User::class, 'creator_id');
    }

    // project belongs to many users throught the pivot table member
    // we can add many projoects to the members table
    public function members(): BelongsToMany {
        return $this->belongsToMany(User::class, Member::class);
    }

    // scope to filter projects by creator
    // we can use this scope in the controller to filter the projects by the creator
    // checks is sign in user is the creator of the project
    // if not, it will not return any project
    // this is a global scope
    // we can use this scope in the controller to filter the projects by the creator

    // protected static function booted(): void
    // {
    //     static::addGlobalScope('creator', function (Builder $builder) {
    //         $builder->where('creator_id', Auth::id());
    //     });
    // }

    protected static function booted(): void
    {
        // if user is a member of the project, then return true
        static::addGlobalScope('member', function (Builder $builder) {
            $builder->whereRelation('members', 'user_id', Auth::id());
        });
    }
}
