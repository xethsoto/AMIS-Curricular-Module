<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProposalTypes extends Model
{
    use HasFactory;

    protected $table = 'proposal_types';

    protected $fillable = [
        'target',
        'main_type',
        'sub_type'
    ];
}
