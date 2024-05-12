<?php

namespace App\Models\Proposal\CourseProp;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseRevPrereqs extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $table = 'course_revision_prereqs';

    protected $fillable = [
        'course_rev_id',
        'prereq_code',
    ];
}
