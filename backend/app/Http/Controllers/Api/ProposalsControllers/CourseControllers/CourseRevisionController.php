<?php

namespace App\Http\Controllers\Api\ProposalsControllers\CourseControllers;

use Exception;
use Illuminate\Http\Request;
use App\Models\Course\Course;
use App\Http\Controllers\Controller;
use App\Models\Course\CoursePrereqs;
use App\Models\Course\CourseSemOffered;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Api\CourseController;
use Illuminate\Validation\ValidationException;
use App\Models\Proposal\CourseProp\CourseRevision\Desc;
use App\Models\Proposal\CourseProp\CourseRevision\Content;
use App\Models\Proposal\CourseProp\CourseRevision\Prereqs;
use App\Models\Proposal\CourseProp\CourseRevision\TitleNum;
use App\Models\Proposal\CourseProp\CourseRevision\NumOfHours;
use App\Models\Proposal\CourseProp\CourseRevision\SemOffered;
use App\Models\Proposal\CourseProp\CourseRevision\PrevPrereqs;
use App\Models\Proposal\CourseProp\CourseRevision\CourseRevision;
use App\Models\Proposal\CourseProp\CourseRevision\PrevSemOffered;

class CourseRevisionController extends Controller
{    
    //Change in course number and/or course title
    public function changeCodeTitle($proposal, $content)
    {
        try{
            // Check if there is a selected course
            $validator = Validator::make($content, [
                'selectedCourse' => 'required',
            ]);
            if ($validator->fails()) {
                throw new ValidationException($validator);
            }

            // Check if course exists in the database
            $course = Course::where('code', $content['selectedCourse'])
                ->first();
            if (!$course){
                throw new Exception("Changing Code and Title Failed: ".$content['selectedCourse']." is not found.");
            }

            // Check if appropriate fields are filled
            if($content['formType'] == 'Code only'){
                $validator = Validator::make($content, [
                    'newCourseCode' => 'required'
                ]);

            } else if ($content['formType'] == 'Title only'){
                $validator = Validator::make($content, [
                    'newCourseTitle' => 'required'
                ]);

            } else {    // Both code and title
                $validator = Validator::make($content, [
                    'newCourseCode' => 'required',
                    'newCourseTitle' => 'required'
                ]);
            }

            if ($validator->fails()){
                throw new ValidationException($validator);
            }

            /*
            * Creating Course Revision proposal
            * Creating Course Revision TitleNum entry 
            * and updating the course entry
            */

            // Course Revision Proposal Creation
            $courseRevision = CourseRevision::create([
                'course_id' => $course['id'],
                'type' => 'TitleNum', // 'TitleNum' is the type for 'Change in Course Code and Title'
                'prop_id' => $proposal['id']
            ]);

            // Course Revision TitleNum Creation
            $create = array_filter([
                'rev_id' => $courseRevision['id'],
                'prev_code' => $course['code'],
                'new_code' => $content['newCourseCode'],
                'prev_title' => $course['title'],
                'new_title' => $content['newCourseTitle']
            ], function($value){
                return !is_null($value);
            });
            TitleNum::create($create);
            
            // Updating the actual course
            $updates = array_filter([
                'code' => $content['newCourseCode'],
                'title' => $content['newCourseTitle']
            ], function ($value) {
                return !is_null($value);
            });
            $course->update($updates);

            return $course['code'];  // we return the course code to edit the selected course of other subproposals

        } catch (\Exception $e) {
            error_log($e->getMessage());
            if($e instanceof ValidationException){
                throw $e;
            }
            throw new Exception($e->getMessage());
        }
    }

    //Change in course description
    public function changeDesc($proposal, $content)
    {
        try{
            // Check if there is a selected course
            $validator = Validator::make($content, [
                'selectedCourse' => 'required',
                'newDescription' => 'required'
            ]);
            if ($validator->fails()) {
                throw new ValidationException($validator);
            }

            error_log("Course in description = ".$content['selectedCourse']);
            // Check if course exists in the database
            $course = Course::where('code', $content['selectedCourse'])
                ->first();
            if (!$course){
                throw new Exception("Changing description failed: ".$content['selectedCourse']." is not found.");
            }

            /*
            * Creating Course Revision proposal 
            * and updating the course entry
            */

            // Course Revision Proposal Creation
            $courseRevision = CourseRevision::create([
                'course_id' => $course['id'],
                'type' => 'Desc', // 'Desc' is the type for 'Change in Course Description'
                'prop_id' => $proposal['id']
            ]);

            // Course Revision Desc Creation
            Desc::create([
                'rev_id' => $courseRevision['id'],
                'prev_desc' => $course['desc'],
                'new_desc' => $content['newDescription']
            ]);

            // Updating the actual course
            $course->update([
                'desc' => $content['newDescription'],
            ]);
            
        } catch (\Exception $e) {
            error_log($e->getMessage());
            if($e instanceof ValidationException){
                throw $e;
            }
            throw new Exception($e->getMessage());
        }
    }

    //Chnage in course prerequisites
    public function changePrereqs($proposal, $content)
    {
        try{
            // Check if there is a selected course
            $validator = Validator::make($content, [
                'selectedCourse' => 'required',
            ]);
            if ($validator->fails()) {
                throw new ValidationException($validator);
            }

            // Check if course exists in the database
            $course = Course::where('code', $content['selectedCourse'])
                ->first();
            if (!$course){
                throw new Exception("Changing prerequisites failed: ".$content['selectedCourse']." is not found.");
            }

            /*
            * Creating new course revision proposal
            * and Course Revision Prereqs
            */
            $courseRevision = CourseRevision::create([
                'course_id' => $course['id'],
                'type' => 'Prereqs', // 'Prereqs' is the type for 'Change in Course Prerequisites'
                'prop_id' => $proposal['id']
            ]);

            // Getting the current prerequisites and the new prerequisites
            $currPrereqs = CoursePrereqs::where('course_id', $course->id)->pluck('prereq_code')->toArray();
            $newPrereqs = $content['newPrereqs'];

            // Creating new prereqs entry
            foreach($newPrereqs as $prereq){
                Prereqs::create([
                    'rev_id' => $courseRevision['id'],
                    'prereq_code' => $prereq
                ]);
            }

            // Creating previous prereqs entry
            foreach($currPrereqs as $prereq){
                PrevPrereqs::create([
                    'rev_id' => $courseRevision['id'],
                    'prereq_code' => $prereq
                ]);
            }

            /*
            * Updating actual course entry
            */
            $prereqsToAdd = array_diff($newPrereqs, $currPrereqs);
            $prereqsToDelete = array_diff($currPrereqs, $newPrereqs);

            // Adding new prerequisites
            foreach ($prereqsToAdd as $prereq){
                CoursePrereqs::create([
                    'course_id' => $course->id,
                    'prereq_code' => $prereq
                ]);
            }

            // Deleting removed prerequisites
            foreach ($prereqsToDelete as $prereq){
                CoursePrereqs::where('course_id', $course->id)
                    ->where('prereq_code', $prereq)
                    ->delete();
            }

        } catch (\Exception $e) {
            error_log($e->getMessage());
            if($e instanceof ValidationException){
                throw $e;
            }
            throw new Exception($e->getMessage());
        }
    }

    //Change in semester offering
    public function changeSemOffered($proposal, $content)
    {
        try{
            // Check if there is a selected course
            $validator = Validator::make($content, [
                'selectedCourse' => 'required',
            ]);
            if ($validator->fails()) {
                throw new ValidationException($validator);
            }

            // Check if course exists in the database
            $course = Course::where('code', $content['selectedCourse'])
                ->first();
            if (!$course){
                throw new Exception($content['selectedCourse']." is not found.");
            }

            /*
            * Creating new course revision proposal 
            * and Course Revision sem offered
            */
            $courseRevision = CourseRevision::create([
                'course_id' => $course['id'],
                'type' => 'SemOffered', // 'SemOffered' is the type for 'Change in Semester Offered
                'prop_id' => $proposal['id']
            ]);
        
            // Getting the previous sem offered and the new sem offered
            $currSemOffered = CourseSemOffered::where('course_id', $course->id)->pluck('sem_offered')->toArray();
            $newSemOffered = $content['newSemOffering'];

            // Adding 
            foreach($currSemOffered as $sem_offered){
                PrevSemOffered::create([
                    'rev_id' => $courseRevision['id'],
                    'sem_offered' => $sem_offered
                ]);
            }

            // Adding the new sem offered for the specific course
            foreach($newSemOffered as $sem_offered){
                SemOffered::create([
                    'rev_id' => $courseRevision['id'],
                    'sem_offered' => $sem_offered
                ]);
            }

            /*
            * Updating actual course entry
            */
            $semOfferedToAdd = array_diff($newSemOffered, $currSemOffered);
            $semOfferedToDel = array_diff($currSemOffered, $newSemOffered);

            // Adding new prerequisites
            foreach ($semOfferedToAdd as $semOffered){
                CourseSemOffered::create([
                    'course_id' => $course->id,
                    'sem_offered' => $semOffered
                ]);
            }

            // Deleting removed prerequisites
            foreach ($semOfferedToDel as $semOffered){
                CourseSemOffered::where('course_id', $course->id)
                    ->where('sem_offered', $semOffered)
                    ->delete();
            }

        } catch (\Exception $e) {
            error_log($e->getMessage());
            if($e instanceof ValidationException){
                throw $e;
            }
            throw new Exception($e->getMessage());
        }
    }

    // Change in number of hours
    public function changeHours($proposal, $content)
    {
        try{
            // Check if there is a selected course
            $validator = Validator::make($content, [
                'selectedCourse' => 'required',
                'newNumOfHours' => 'required'
            ]);
            if ($validator->fails()) {
                throw new ValidationException($validator);
            }

            // Check if course exists in the database
            $course = Course::where('code', $content['selectedCourse'])
                ->first();
            if (!$course){
                throw new Exception($content['selectedCourse']." is not found.");
            }
                
            /*
            * Creating new course revision proposal 
            * and updating the actual course entry
            */

            // Creating the course revision proposal
            $courseRevision = CourseRevision::create([
                'course_id' => $course['id'],
                'type' => 'NumOfHours', // 'NumOfHours' is the type for 'Change in Number of Hours'
                'prop_id' => $proposal['id']
            ]);

            // Creating the new number of hours entry
            NumOfHours::create([
                'rev_id' => $courseRevision['id'],
                'prev_num_of_hours' => $course['num_of_hours'],
                'new_num_of_hours' => $content['newNumOfHours']
            ]);

            // Updating the actual course
            $course->update([
                'num_of_hours' => $content['newNumOfHours']
            ]);

        } catch (\Exception $e) {
            error_log($e->getMessage());
            if($e instanceof ValidationException){
                throw $e;
            }
            throw new Exception($e->getMessage());
        }
    }

    // Change in course content
    public function changeContent($proposal, $content)
    {
        try{
            // Check if there is a selected course
            $validator = Validator::make($content, [
                'selectedCourse' => 'required',
                'newGoal' => 'required',
                'newOutline' => 'required'
            ]);
            if ($validator->fails()) {
                throw new ValidationException($validator);
            }

            // Check if course exists in the database
            $course = Course::where('code', $content['selectedCourse'])
                ->first();
            if (!$course){
                throw new Exception($content['selectedCourse']." is not found.");
            }

            /*
            * Creating new course revision proposal 
            * and updating the actual course entry
            */

            // Creating the course revision proposal
            $courseRevision = CourseRevision::create([
                'course_id' => $course['id'],
                'type' => 'Content', // 'Content' is the type for 'Change in Course Content'
                'prop_id' => $proposal['id']
            ]);

            Content::create([
                'rev_id' => $courseRevision['id'],
                'prev_goal' => $course['goal'],
                'new_goal' => $content['newGoal'],
                'prev_outline' => $course['outline'],
                'new_outline' => $content['newOutline'] 
            ]);

            $course->update([
                'goal' => $content['newGoal'],
                'outline' => $content['newOutline']
            ]);

        } catch (\Exception $e) {
            error_log($e->getMessage());
            if($e instanceof ValidationException){
                throw $e;
            }
            throw new Exception($e->getMessage());
        }
    }
}
