<?php

namespace App\Models\Proposal\CourseProp\CourseRevision;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Desc extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $table = 'course_rev_desc';

    protected $fillable = [
        'rev_id',
        'prev_desc',
        'new_desc',
    ];
}
