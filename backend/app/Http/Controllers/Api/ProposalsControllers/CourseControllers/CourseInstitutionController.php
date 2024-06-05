<?php

namespace App\Http\Controllers\Api\ProposalsControllers\CourseControllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Course\Course;
use App\Models\Course\CoursePrereqs;
use App\Models\Course\CourseSemOffered;
use App\Models\Proposal\CourseProp\CourseInstitution;
use App\Models\Proposal\CourseProp\CoursePropPrereqs;
use App\Models\Proposal\CourseProp\CoursePropSemOffered;
use Exception;

class CourseInstitutionController extends Controller
{
    public function save($proposal, $content, $date)
    {
        try{
            // Course
            $newCourse = Course::create([
                'code' => $content['num'],
                'title' => $content['title'],
                'desc' => $content['desc'],
                'credit' => $content['credit'],
                'num_of_hours' => $content['numOfHours'],
                'goal' => $content['goal'],
                'outline' => $content['outline'],
                'status' => 'Active',
                'created_at' => $date,
                'updated_at' => $date,
            ]);

            // Course Proposal
            $courseInstitution = CourseInstitution::create([
                'course_id' => $newCourse['id'],
                'code' => $content['num'],
                'title' => $content['title'],
                'desc' => $content['desc'],
                'credit' => $content['credit'],
                'num_of_hours' => $content['numOfHours'],
                'goal' => $content['goal'],
                'outline' => $content['outline'],
                'prop_id' => $proposal['id']
            ]);
            
            // adding the prerequisites of the course proposal
            // and the course itself
            foreach ($content['prereqs'] as $courseCode) {
                //Course Proposal
                $coursePropPrereq = CoursePropPrereqs::create([
                    'new_course_id' => $courseInstitution->id,
                    'prereq_code' => $courseCode
                ]);
                
                //Course
                $coursePrereq = CoursePrereqs::create([
                    'course_id' => $newCourse['id'],
                    'prereq_code' => $courseCode
                ]);
            }
            
            //adding the sem offerings of the course
            foreach ($content['sem_offered'] as $semOffered) {
                //Course Proposal
                $coursePropSemOffered = CoursePropSemOffered::create([
                    'new_course_id' => $courseInstitution->id,
                    'sem_offered' => $semOffered
                ]);
    
                //Course
                $courseSemOffered = CourseSemOffered::create([
                    'course_id' => $newCourse['id'],
                    'sem_offered' => $semOffered
                ]);
            }
        } catch (\Exception $e) {
            error_log($e->getMessage());
            throw new Exception($e->getMessage());
        }

    }
}
