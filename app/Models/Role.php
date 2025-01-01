<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Role extends Model
{
    /** @use HasFactory<\Database\Factories\RoleFactory> */
    use HasFactory;

    public function user(): HasMany
    {
        return $this->hasMany(User::class, 'role_id');
    }
    
    public function log(): HasMany
    {
        return $this->hasMany(ActivityLog::class, 'requestrole_id');
    }
}
