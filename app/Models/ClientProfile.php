<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ClientProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'age',
        'height',
        'weight',
        'fitness_goal',
        'start_date',
        'coach_id',
    ];

    protected $casts = [
        'start_date' => 'date',
    ];

    // Inverse relationship to User
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Coach relationship
    public function coach(): BelongsTo
    {
        return $this->belongsTo(User::class, 'coach_id');
    }

    // Progress photos
    public function progressPhotos(): HasMany
    {
        return $this->hasMany(ProgressPhoto::class, 'client_id', 'user_id');
    }

    // Progress tracking records
    public function progressTracking(): HasMany
    {
        return $this->hasMany(ProgressTracking::class, 'client_id', 'user_id');
    }

    // Calculate BMI
    public function calculateBMI()
    {
        if ($this->height && $this->weight) {
            $heightInMeters = $this->height / 100;
            return round($this->weight / ($heightInMeters ** 2), 2);
        }
        return null;
    }

    // Get latest weight
    public function getLatestWeight()
    {
        return $this->progressTracking()
            ->latest('log_date')
            ->value('weight') ?? $this->weight;
    }
}
