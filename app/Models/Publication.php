<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Publication extends Model
{
    use HasFactory;

    // Define the table if it's different from the default plural form of the model
    protected $table = 'publications';

    // Define the fillable columns
    protected $fillable = [
        'title',
        'journal_name',
        'publication_date',
        'citations',
        'doi',
        'author_name',
        'institution',
        'source',
    ];
}
