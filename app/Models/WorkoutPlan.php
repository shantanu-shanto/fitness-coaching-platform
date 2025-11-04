<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class WorkoutPlan extends Model
{
    use HasFactory;

    protected $fillable = [
        'coach_id',
        'client_id',
        'plan_name',
        'description',
        'difficulty_level',
    ];

    // Coach relationship
    public function coach(): BelongsTo
    {
        return $this->belongsTo(User::class, 'coach_id');
    }

    // Client relationship
    public function client(): BelongsTo
    {
        return $this->belongsTo(User::class, 'client_id');
    }

    // Exercises
    public function exercises(): HasMany
    {
        return $this->hasMany(WorkoutExercise::class);
    }

    // Count total exercises
    public function getTotalExercisesCount()
    {
        return $this->exercises()->count();
    }

    // Calculate total workout duration
    public function getTotalDuration()
    {
        return $this->exercises()->sum('duration');
    }
}
