<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProposalAction extends Model
{
    use HasFactory;

    protected $table = 'proposal_has_types';

    protected $fillable = [
        'action',
        'rationale'
    ];
}
