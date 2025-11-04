<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WorkoutExercise extends Model
{
    use HasFactory;

    protected $fillable = [
        'workout_plan_id',
        'exercise_name',
        'sets',
        'reps',
        'duration',
        'rest_period',
        'video_url',
    ];

    // Workout plan relationship
    public function workoutPlan(): BelongsTo
    {
        return $this->belongsTo(WorkoutPlan::class);
    }

    // Calculate total volume (sets × reps)
    public function getTotalVolume()
    {
        return $this->sets * $this->reps;
    }
}
