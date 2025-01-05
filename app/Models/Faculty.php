<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Faculty extends Model
{
    protected $fillable = ['name']; // Add this line
    
    
    public function user(): HasMany
    {
        return $this->hasMany(User::class, 'faculty_id');
    }
    
    public function study_program(): HasMany
    {
        return $this->hasMany(Study_program::class, 'faculty_id');
    }
    
    public function RequestLog(): HasMany
    {
        return $this->hasMany(ActivityLog::class, 'faculty_id');
    }
    
    public function historyLog(): HasMany
    {
        return $this->hasMany(HistoryLog::class, 'faculty_id');
    }
}
