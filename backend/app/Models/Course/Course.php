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
        'status',
        'univ_origin',
        'created_at',
        'updated_at'
    ];

    public function usesTimestamps(): bool
    {
        return false;
    }

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
        $pluckedSemOffered = $semOffered->pluck('sem_offered')->toArray();

        // sort sem offered numbers first then letters
        usort($pluckedSemOffered, function ($a, $b) {
            if (is_numeric($a) && is_numeric($b)) {
                return $a - $b; // sort numbers in ascending order
            } elseif (is_numeric($a)) {
                return -1; // put numbers before letters
            } elseif (is_numeric($b)) {
                return 1; // put numbers before letters
            } else {
                return strcmp($a, $b); // sort letters in lexicographical order
            }
        });

        return $pluckedSemOffered;
    }
}
