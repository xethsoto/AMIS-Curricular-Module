<?php

namespace App\Models\Proposal\CourseProp\CourseRevision;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NumOfHours extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $table = 'course_rev_num_of_hours';

    protected $fillable = [
        'rev_id',
        'prev_num_of_hours',
        'new_num_of_hours'
    ];
}
