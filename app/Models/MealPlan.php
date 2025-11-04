<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MealPlan extends Model
{
    use HasFactory;

    protected $fillable = [
        'coach_id',
        'client_id',
        'plan_name',
        'description',
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

    // Meal items
    public function mealItems(): HasMany
    {
        return $this->hasMany(MealPlanItem::class);
    }

    // Get meals by day
    public function getMealsByDay($day)
    {
        return $this->mealItems()
            ->where('day', $day)
            ->get();
    }

    // Calculate total daily calories
    public function getTotalCaloriesByDay($day)
    {
        return $this->mealItems()
            ->where('day', $day)
            ->sum('calories');
    }
}
