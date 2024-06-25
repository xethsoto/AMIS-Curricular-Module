<?php

namespace App\Models\Proposal\DegProgProp;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DegProgInstitution extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $table = 'deg_prog_institutions';

    protected $fillable = [
        'name',
        'career',
        'college',
        'num_of_units',
        'desc',
        'prop_id'
    ];
}
