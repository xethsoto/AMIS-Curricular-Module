<?php

namespace App\Http\Controllers\Api\ProposalsControllers\CourseControllers;

use Exception;
use Illuminate\Http\Request;
use App\Models\Course\Course;
use App\Http\Controllers\Controller;
use App\Models\Course\CoursePrereqs;
use App\Models\Course\CourseSemOffered;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use App\Models\Proposal\CourseProp\CourseInstitution;
use App\Models\Proposal\CourseProp\CoursePropPrereqs;
use App\Models\Proposal\CourseProp\CoursePropSemOffered;

class CourseInstitutionController extends Controller
{
    public function saveInstitution($proposal, $content, $date)
    {
        try{
            $messages = [
                'num.required' => "The 'Course Code' field is required",
                'num.string' => "The 'Course Code' field must be a string",
                'title' => "The 'Course Title' field is required",
                'title' => "The 'Course Title' field must be a string",
                'description' => "The 'Course Description' field is required",
                'description' => "The 'Course Description' field must be a string",
                'credit' => "The 'Course Credit' field is required",
                'credit' => "The 'Course Credit' field must be an integer",
                'numOfHours' => "The 'Number of Hours' field is required",
                'numOfHours' => "The 'Number of Hours' field must be a string",
                'goal' => "The 'Course Goal' field is required",
                'goal' => "The 'Course Goal' field must be a string",
                'outline' => "The 'Course Outline' field is required",
                'outline' => "The 'Course Outline' field must be a string",
                'sem_offered' => "The 'Semesters Offered' field is required",
            ];

            $validator = Validator::make($content, [
                'num' => 'required|string',
                'title' => 'required|string',
                'desc' => 'required|string',
                'credit' => 'required|integer',
                'numOfHours' => 'required|string',
                'goal' => 'required|string',
                'outline' => 'required|string',
                'sem_offered' => 'required|array',
            ], $messages);

            if ($validator->fails()){
                throw new ValidationException($validator);
            }

            /*
            * Create a new course and course proposal
            */
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

            $this->addSemPrereqs($courseInstitution, $newCourse, $content);

        } catch (\Exception $e) {
            error_log($e->getMessage());
            if($e instanceof ValidationException){
                throw $e;
            }
            throw new Exception($e->getMessage());
        }
    }

    public function saveAdoption($proposal, $content, $date)
    {
        try{
            $messages = [
                'num.required' => "The 'Course Code' field is required",
                'num.string' => "The 'Course Code' field must be a string",
                'title' => "The 'Course Title' field is required",
                'title' => "The 'Course Title' field must be a string",
                'desc' => "The 'Course Description' field is required",
                'desc' => "The 'Course Description' field must be a string",
                'credit' => "The 'Course Credit' field is required",
                'credit' => "The 'Course Credit' field must be an integer",
                'numOfHours' => "The 'Number of Hours' field is required",
                'numOfHours' => "The 'Number of Hours' field must be a string",
                'goal' => "The 'Course Goal' field is required",
                'goal' => "The 'Course Goal' field must be a string",
                'outline' => "The 'Course Outline' field is required",
                'outline' => "The 'Course Outline' field must be a string",
                'univ_origin' => "The 'University Origin' field is required",
                'univ_origin' => "The 'University Origin' field must be a string",
            ];

            $validator = Validator::make($content, [
                'num' => 'required|string',
                'title' => 'required|string',
                'desc' => 'required|string',
                'credit' => 'required|integer',
                'numOfHours' => 'required|string',
                'goal' => 'required|string',
                'outline' => 'required|string',
                'univ_origin' => 'required|string',
            ], $messages);

            if ($validator->fails()){
                throw new ValidationException($validator);
            }

            /*
            * Create a new course and course proposal
            */
            $newCourse = Course::create([
                'code' => $content['num'],
                'title' => $content['title'],
                'desc' => $content['desc'],
                'credit' => $content['credit'],
                'num_of_hours' => $content['numOfHours'],
                'goal' => $content['goal'],
                'outline' => $content['outline'],
                'status' => 'Active',
                'univ_origin' => $content['univ_origin'],
                'created_at' => $date,
                'updated_at' => $date,
            ]);
                                
            $courseInstitution = CourseInstitution::create([
                'course_id' => $newCourse['id'],
                'code' => $content['num'],
                'title' => $content['title'],
                'desc' => $content['desc'],
                'credit' => $content['credit'],
                'num_of_hours' => $content['numOfHours'],
                'goal' => $content['goal'],
                'outline' => $content['outline'],
                'univ_origin' => $content['univ_origin'],
                'prop_id' => $proposal['id']
            ]);

            $this->addSemPrereqs($courseInstitution, $newCourse, $content);

        } catch (\Exception $e) {
            error_log($e->getMessage());
            if($e instanceof ValidationException){
                throw $e;
            }
            throw new Exception($e->getMessage());
        }
    }

    public function addSemPrereqs($courseInstitution, $newCourse, $content)
    {
        try{
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
