<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;

    protected $table = 'courses';

    protected $fillable = [
        'code',
        'title',
        'desc',
        'prerequisites',
        'sem_offered',
        'credit',
        'num_of_hours',
        'goal',
        'outline'
    ];

    protected $casts = [
        'prerequisites' => 'array',
        'sem_offered' => 'array'
    ];
}
