<?php

namespace App\Models\Proposal;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProposalClassification extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $table = 'proposal_classification';

    protected $fillable = [
        'prop_id',
        'target',
        'type',
        'sub_type',
        'rationale'
    ];

    public function proposal(){
        return $this->belongsTo(Proposal::class, 'prop_id');
    }
}
