<?php

namespace App\Models\Course;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseSemOffered extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $table = 'course_sem_offered';

    protected $fillable = [
        'course_id',
        'sem_offered',
    ];
}
