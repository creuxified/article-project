<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class StudyProgram extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'faculty'
    ];

    public function faculty(): BelongsTo
    {
        return $this->belongsTo(Faculty::class);
    }

    public function user(): HasMany
    {
        return $this->hasMany(User::class, 'program_id');
    }

    public function RequestLog(): HasMany
    {
        return $this->hasMany(ActivityLog::class, 'program_id');
    }
}
