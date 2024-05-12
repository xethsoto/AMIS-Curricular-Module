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
use App\Models\Proposal\CourseProp\CourseRevision;
use App\Models\Proposal\CourseProp\CourseRevPrereqs;
use App\Models\Proposal\CourseProp\CourseRevSemOffered;

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
            * and updating the course entry
            */
            $create = array_filter([
                'course_id' => $course['id'],
                'code' => $content['newCourseCode'],
                'title' => $content['newCourseTitle'],
                'prop_id' => $proposal['id']
            ], function ($value) {
                return !is_null($value);
            });

            $updates = array_filter([
                'code' => $content['newCourseCode'],
                'title' => $content['newCourseTitle']
            ], function ($value) {
                return !is_null($value);
            });

            $courseRevision = CourseRevision::create($create);
            $course->update($updates);

            return $course;

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
            $courseRevision = CourseRevision::create([
                'course_id' => $course['id'],
                'desc' => $content['newDescription'],
                'prop_id' => $proposal['id']
            ]);

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
                'prop_id' => $proposal['id']
            ]);

            foreach($content['newPrereqs'] as $prereq){
                $courseRevPrereqs = CourseRevPrereqs::create([
                    'course_rev_id' => $courseRevision['id'],
                    'prereq_code' => $prereq
                ]);
            }

            /*
            * Updating actual course entry
            */
            $currPrereqs = CoursePrereqs::where('course_id', $course->id)->pluck('prereq_code')->toArray();
            $newPrereqs = $content['newPrereqs'];

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
                'prop_id' => $proposal['id']
            ]);

            foreach($content['newSemOffering'] as $sem_offered){
                $courseRevSemOffered = CourseRevSemOffered::create([
                    'course_rev_id' => $courseRevision['id'],
                    'new_sem_offered' => $sem_offered
                ]);
            }

            /*
            * Updating actual course entry
            */
            $currSemOffered = CourseSemOffered::where('course_id', $course->id)->pluck('sem_offered')->toArray();
            $newSemOffered = $content['newSemOffering'];

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
            $courseRevision = CourseRevision::create([
                'course_id' => $course['id'],
                'num_of_hours' => $content['newNumOfHours'],
                'prop_id' => $proposal['id']
            ]);

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
            $courseRevision = CourseRevision::create([
                'course_id' => $course['id'],
                'goal' => $content['newGoal'],
                'outline' => $content['newOutline'],
                'prop_id' => $proposal['id']
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
