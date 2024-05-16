<?php

namespace App\Models\Proposal;

use Illuminate\Database\Eloquent\Model;
use App\Models\Proposal\CourseProp\CourseInstitution;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Proposal\CourseProp\CourseRevision\Desc;
use App\Models\Proposal\CourseProp\CourseRevision\Content;
use App\Models\Proposal\CourseProp\CourseRevision\Prereqs;
use App\Models\Proposal\CourseProp\CourseRevision\TitleNum;
use App\Models\Proposal\CourseProp\CourseRevision\NumOfHours;
use App\Models\Proposal\CourseProp\CourseRevision\SemOffered;
use App\Models\Proposal\CourseProp\CourseRevision\PrevPrereqs;
use App\Models\Proposal\CourseProp\CourseRevision\CourseRevision;

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
        
        $courseRevResults = CourseRevision::where('prop_id', $this->id)
            ->get()->toArray();

        error_log("courseRevResults: " . print_r($courseRevResults, true) . "\n");

        // For each revision, we relate the appropriate revision data to it
        $courseRevisions = [];
        foreach($courseRevResults as $revision){
            error_log('$revision: ' . print_r($revision, true) . "\n");
            switch($revision['type']){
                case 'Change in course number and/or course title':
                    $revision['details'] = TitleNum::where('rev_id', $revision['id'])->first();
                    break;
                case 'Change in course description':
                    $revision['details'] = Desc::where('rev_id', $revision['id'])->first();
                    break;
                case 'Change in prerequisites':
                    // Acquiring and converting each prereq code to course object
                    $result = Prereqs::where('rev_id', $revision['id'])->get();
                    $courses = $result->map(function($prereq){
                        return $prereq->getCourse();
                    });
                    $revision['details']['curr_prereqs'] = $courses;

                    // Acquiring and converting each previous prereq code to course object
                    $result = PrevPrereqs::where('rev_id', $revision['id'])->get();
                    $courses = $result->map(function($prereq){
                        return $prereq->getCourse();
                    });
                    $revision['details']['prev_prereqs'] = $courses;
                    
                    break;
                case 'Change in semester offering':
                    $revision['details'] = SemOffered::where('rev_id', $revision['id'])->first();
                    break;
                case 'Change in number of hours':
                    $revision['details'] = NumOfHours::where('rev_id', $revision['id'])->first();
                    break;
                case 'Change in course content':
                    $revision['details'] = Content::where('rev_id', $revision['id'])->first();
                    break;
            }
            $courseRevisions[] = $revision;
        }

        // $courseRevisions = array_reduce($courseRevisions, 'array_merge', []);
        $result = [$courseInstitutions, $courseRevisions];
            
        //removes empty arrays
        return array_reduce($result, 'array_merge', []);
    }

}
