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
        'prop_id'
    ];
}
