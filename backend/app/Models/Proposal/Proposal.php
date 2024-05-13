<?php

namespace App\Models\Proposal;

use Illuminate\Database\Eloquent\Model;
use App\Models\Proposal\CourseProp\CourseInstitution;
use App\Models\Proposal\CourseProp\CourseRevision\CourseRevision;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Proposal extends Model
{
    use HasFactory;

    protected $table = 'proposals';

    protected $fillable = [
        'name'
    ];

    /* Proposal has many proposal classification
    *  Target = Course, Degree Program
    *  Type = Institution, Revision, Abolition
    *  Sub Type (for Revision Type)
    */
    public function proposalClassification()
    {
        return $this->hasMany(ProposalClassification::class, 'prop_id');
    }

    public function getSubproposals()
    {
        $courseInstitutions = CourseInstitution::where('prop_id', $this->id)
            ->get()
            ->each(function ($courseInstitution) {
                $courseInstitution->prereqs = $courseInstitution->getPrereqs();
                $courseInstitution->sem_offered = $courseInstitution->getSemOffered();
            })->toArray();
        
        $courseRevisions = CourseRevision::where('prop_id', $this->id)
            ->get()->toArray();

        $result = [$courseInstitutions, $courseRevisions];
    
        return array_reduce($result, 'array_merge', []);
    }

}
