<?php

namespace App\Models\Course;

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
        'credit',
        'num_of_hours',
        'goal',
        'outline',
        'status'
    ];

    public function prereqs()
    {
        return $this->hasMany(CoursePrereqs::class);
    }

    public function semOffered()
    {
        return $this->hasMany(CourseSemOffered::class);
    }
}
