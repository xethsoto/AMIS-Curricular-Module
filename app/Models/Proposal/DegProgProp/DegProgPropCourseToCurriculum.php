<?php

namespace App\Models\Proposal\DegProgProp;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DegProgPropCourseToCurriculum extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $table = 'prop_courses_to_new_curriculum';

    protected $fillable = [
        'new_curriculum_id',
        'year_to_be_taken',
        'sem_to_be_taken',
        'course_type'
    ];}
