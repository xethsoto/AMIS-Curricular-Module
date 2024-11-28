<?php

namespace App\Models\Proposal\CourseProp;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseAbolishment extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $table = 'course_abolishments';

    protected $fillable = [
        'prop_id',
        'course_id',
    ];
}
