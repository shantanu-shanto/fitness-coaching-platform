<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProgressTracking extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id',
        'log_date',
        'weight',
        'body_fat_percentage',
        'notes',
    ];

    protected $casts = [
        'log_date' => 'date',
    ];

    // Client relationship
    public function client(): BelongsTo
    {
        return $this->belongsTo(User::class, 'client_id');
    }

    // Calculate weight progress
    public function getWeightChange()
    {
        $previousLog = $this->client()
            ->progressTracking()
            ->where('log_date', '<', $this->log_date)
            ->latest('log_date')
            ->first();

        if ($previousLog) {
            return $this->weight - $previousLog->weight;
        }
        return null;
    }
}
