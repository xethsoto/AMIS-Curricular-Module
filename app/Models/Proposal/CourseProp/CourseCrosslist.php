<?php

namespace App\Models\Proposal\CourseProp;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseCrosslist extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $table = 'course_crosslists';

    protected $fillable = [
        'course_id',
        'crosslist_id',
        'prop_id',
    ];
}
