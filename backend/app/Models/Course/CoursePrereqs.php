<?php

namespace App\Models\Course;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CoursePrereqs extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $table = 'course_prereqs';

    protected $fillable = [
        'course_id',
        'prereq_code',
    ];
}
