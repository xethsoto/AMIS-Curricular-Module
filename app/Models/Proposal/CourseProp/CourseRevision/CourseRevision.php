<?php

namespace App\Models\Proposal\CourseProp\CourseRevision;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseRevision extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $table = 'course_revisions';

    protected $fillable = [
        'course_id',
        'type',
        'curr_course_code',
        'curr_course_title',
        'prop_id'
    ];

    public function titleNum()
    {
        return $this->hasMany(TitleNum::class, 'rev_id');
    }

    public function desc()
    {
        return $this->hasMany(Desc::class, 'rev_id');
    }

    public function prereqs()
    {
        return $this->hasMany(Prereqs::class, 'rev_id');
    }

    public function prevPrereqs()
    {
        return $this->hasMany(PrevPrereqs::class, 'rev_id');
    }

    public function numOfHours()
    {
        return $this->hasMany(NumOfHours::class, 'rev_id');
    }

    public function semOffered()
    {
        return $this->hasMany(SemOffered::class, 'rev_id');
    }

    public function prevSemOffered()
    {
        return $this->hasMany(PrevSemOffered::class, 'rev_id');
    }

    public function content()
    {
        return $this->hasMany(Content::class, 'rev_id');
    }
}
