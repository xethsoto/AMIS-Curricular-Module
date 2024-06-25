<?php

namespace App\Models\Proposal\CourseProp\CourseRevision;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SemOffered extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $table = 'course_rev_sem_offered';

    protected $fillable = [
        'rev_id',
        'sem_offered',
    ];
}
