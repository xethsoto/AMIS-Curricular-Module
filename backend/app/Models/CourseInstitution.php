<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseInstitution extends Model
{
    use HasFactory;

    protected $table = 'course_institutions';

    protected $fillable = [
        'code',
        'title',
        'desc',
        'credit',
        'num_of_hours',
        'goal',
        'outline',
        'prop_id'
    ];
}
