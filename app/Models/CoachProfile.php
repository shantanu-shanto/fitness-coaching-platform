<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CoachProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'specialization',
        'certifications',
        'experience_years',
        'hourly_rate',
    ];

    // Inverse relationship to User
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Clients assigned to this coach
    public function clients(): HasMany
    {
        return $this->hasMany(ClientProfile::class, 'coach_id', 'user_id');
    }

    // Count total clients
    public function getTotalClientsCount()
    {
        return $this->clients()->count();
    }

    // Count total meal plans
    public function getTotalMealPlansCount()
    {
        return $this->user->mealPlans()->count();
    }

    // Count total workout plans
    public function getTotalWorkoutPlansCount()
    {
        return $this->user->workoutPlans()->count();
    }
}
