<?php

namespace App\Models\Proposal\DegProgProp;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DegProgPropMajors extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $table = 'deg_prog_prop_majors';

    protected $fillable = [
        'new_deg_prog',
        'name',
    ];
}
