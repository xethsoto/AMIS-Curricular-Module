<?php

namespace App\Models\Proposal\CourseProp\CourseRevision;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TitleNum extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $table = 'course_rev_title_num';

    protected $fillable = [
        'rev_id',
        'prev_code',
        'new_code',
        'prev_title',
        'new_title'
    ];
}
