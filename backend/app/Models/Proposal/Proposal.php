<?php

namespace App\Models\Proposal;

use Illuminate\Database\Eloquent\Model;
use App\Models\Proposal\CourseProp\CourseCrosslist;
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
use App\Models\Proposal\CourseProp\CourseRevision\PrevSemOffered;

class Proposal extends Model
{
    use HasFactory;

    protected $table = 'proposals';

    protected $fillable = [
        'name',
        'created_at',
        'updated_at'
    ];

    public function usesTimestamps(): bool
    {
        return false;
    }

    /* Proposal has many proposal classification
    *  Target = Course, Degree Program
    *  Type = Institution, Revision, Abolition
    *  Sub Type (for Revision Type)
    */
    public function proposalClassification()
    {
        return $this->hasMany(ProposalClassification::class, 'prop_id');
    }

    public function courseInstitutions()
    {
        return $this->hasMany(CourseInstitution::class, 'prop_id');
    }

    public function courseRevisions()
    {
        return $this->hasMany(CourseRevision::class, 'prop_id');
    }

    public function courseCrosslists()
    {
        return $this->hasMany(CourseCrosslist::class, 'prop_id');
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
                    // Proposed Semester Offerings
                    $result = SemOffered::where('rev_id', $revision['id'])->get();
                    $semOfferings = $result->map(function($semOffered){
                        return $semOffered->sem_offered;
                    })->join(', ');
                    $revision['details']['curr_sem_offered'] = $semOfferings;

                    // Previous Semester Offerings
                    $result = PrevSemOffered::where('rev_id', $revision['id'])->get();
                    $semOfferings = $result->map(function($semOffered){
                        return $semOffered->sem_offered;
                    })->join(', ');
                    $revision['details']['prev_sem_offered'] = $semOfferings;
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
