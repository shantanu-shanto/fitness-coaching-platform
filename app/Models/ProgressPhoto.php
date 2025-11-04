<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProgressPhoto extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id',
        'photo_url',
        'upload_date',
        'weight',
        'body_notes',
    ];

    protected $casts = [
        'upload_date' => 'date',
    ];

    // Client relationship
    public function client(): BelongsTo
    {
        return $this->belongsTo(User::class, 'client_id');
    }
}
