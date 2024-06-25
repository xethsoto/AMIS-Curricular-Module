<?php

namespace App\Models\Proposal\CourseProp;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CoursePropSemOffered extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $table = 'course_prop_sem_offered';

    protected $fillable = [
        'new_course_id',
        'sem_offered',
    ];
}
