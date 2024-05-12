<?php

namespace App\Models\Proposal\CourseProp;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseRevSemOffered extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $table = 'course_revision_sem_offered';

    protected $fillable = [
        'course_rev_id',
        'new_sem_offered',
    ];
}
