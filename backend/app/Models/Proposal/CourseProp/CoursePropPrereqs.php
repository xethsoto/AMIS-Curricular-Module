<?php

namespace App\Models\Proposal\CourseProp;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CoursePropPrereqs extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $table = 'course_prop_prereqs';

    protected $fillable = [
        'new_course_id',
        'prereq_code',
    ];
}
