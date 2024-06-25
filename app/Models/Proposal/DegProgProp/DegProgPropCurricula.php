<?php

namespace App\Models\Proposal\DegProgProp;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DegProgPropCurricula extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $table = 'deg_prog_prop_curriculum';

    protected $fillable = [
        'name',
        'new_deg_prog_major_id',
        'new_deg_prog_id'
    ];
}
