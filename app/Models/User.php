<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'role',
        'bio',
        'avatar_url',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Coach Profile Relationship
    public function coachProfile(): HasOne
    {
        return $this->hasOne(CoachProfile::class);
    }

    // Client Profile Relationship
    public function clientProfile(): HasOne
    {
        return $this->hasOne(ClientProfile::class);
    }

    // Meals created by coach
    public function mealPlans(): HasMany
    {
        return $this->hasMany(MealPlan::class, 'coach_id');
    }

    // Workouts created by coach
    public function workoutPlans(): HasMany
    {
        return $this->hasMany(WorkoutPlan::class, 'coach_id');
    }

    // Check if user is coach
    public function isCoach(): bool
    {
        return $this->role === 'coach';
    }

    // Check if user is client
    public function isClient(): bool
    {
        return $this->role === 'client';
    }
}
