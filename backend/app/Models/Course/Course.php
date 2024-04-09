<?php

namespace App\Models\Course;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

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

    public function getPrereqCode()
    {  
        $prereqs = $this->prereqs; 
        return $prereqs->pluck('prereq_code');
    }

    public function getSemOffered()
    {
        $semOffered = $this->semOffered;
        return $semOffered->pluck('sem_offered');
    }
}
