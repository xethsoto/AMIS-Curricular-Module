<?php

namespace App\Models\Proposal\CourseProp\CourseRevision;

use App\Models\Course\Course;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PrevPrereqs extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $table = 'course_rev_prev_prereqs';

    protected $fillable = [
        'rev_id',
        'prereq_code',
    ];

    public function getCourse(){
        return Course::select('id', 'code', 'title')
        ->where('code', $this->prereq_code)
        ->first();
    }
}
