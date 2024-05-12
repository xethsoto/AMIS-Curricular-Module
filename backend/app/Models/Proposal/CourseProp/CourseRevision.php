<?php

namespace App\Models\Proposal\CourseProp;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseRevision extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $table = 'course_revisions';

    protected $fillable = [
        'course_id',
        'code',
        'title',
        'desc',
        'credit',
        'num_of_hours',
        'goal',
        'outline',
        'prop_id'
    ];

    public function prereqs()
    {
        return $this->hasMany(CourseRevPrereqs::class);
    }

    public function semOffered()
    {
        return $this->hasMany(CourseRevSemOffered::class);
    }
}
