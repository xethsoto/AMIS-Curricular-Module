<?php

namespace App\Models\Proposal\CourseProp\CourseRevision;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prereqs extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $table = 'course_rev_prereqs';

    protected $fillable = [
        'rev_id',
        'prereq_code',
    ];
}
