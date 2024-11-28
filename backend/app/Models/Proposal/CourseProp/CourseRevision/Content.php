<?php

namespace App\Models\Proposal\CourseProp\CourseRevision;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Content extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $table = 'course_rev_content';

    protected $fillable = [
        'rev_id',
        'prev_goal',
        'new_goal',
        'prev_outline',
        'new_outline'
    ];

}
