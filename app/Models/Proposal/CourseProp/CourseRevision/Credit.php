<?php

namespace App\Models\Proposal\CourseProp\CourseRevision;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Credit extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $table = 'course_rev_credit';

    protected $fillable = [
        'rev_id',
        'prev_credit',
        'new_credit'
    ];
}
