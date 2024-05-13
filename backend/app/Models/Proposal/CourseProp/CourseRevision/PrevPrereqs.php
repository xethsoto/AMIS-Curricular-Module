<?php

namespace App\Models\Proposal\CourseProp\CourseRevision;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PrevPrereqs extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $table = 'course_rev_prev_prereqs';

    protected $fillable = [
        'rev_id',
        'prereq_code',
    ];
}
