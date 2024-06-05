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
    public function save($proposal, $content, $date, $propSubType, $courseRef)
    {
        try{
            // Check if there is a selected course
            $validator = Validator::make($content, [
                'selectedCourse' => 'required',
            ]);
            if ($validator->fails()) {
                throw new ValidationException($validator);
            }

            $id = $content['selectedCourse']['id'];
            // Check if course exists in the database
            $course = Course::find($id);
            if (!$course){
                throw new Exception($content['selectedCourse']['code']." is not found.");
            }

            // Course Revision Proposal Creation
            if($courseRef){
                // if this revision is part of a bulk course revisions and the course code was changed
                $courseRevision = CourseRevision::create([
                    'course_id' => $course['id'],
                    'type' => $propSubType,
                    'curr_course_code' => $courseRef['code'],
                    'curr_course_title' => $courseRef['title'],
                    'prop_id' => $proposal['id']
                ]);
            } else {
                $courseRevision = CourseRevision::create([
                    'course_id' => $course['id'],
                    'type' => $propSubType,
                    'curr_course_code' => $course['code'],
                    'curr_course_title' => $course['title'],
                    'prop_id' => $proposal['id']
                ]);
            }
            
            switch($propSubType){
                case 'Change in course number and/or course title':
                    return $this->changeCodeTitle($content, $course, $courseRevision['id'], $date);
                    break;
                case 'Change in course description':
                    return $this->changeDesc($content, $course, $courseRevision['id'], $date);
                    break;     
                case 'Change in prerequisites':
                    return $this->changePrereqs($content, $course, $courseRevision['id'], $date);
                    break;                                      
                case 'Change in semester offering':
                    return $this->changeSemOffered($content, $course, $courseRevision['id'], $date);
                    break;
                case 'Change in number of hours':
                    return $this->changeHours($content, $course, $courseRevision['id'], $date);
                    break;
                case 'Change in course content':
                    return $this->changeContent($content, $course, $courseRevision['id'], $date);
                    break;
            }
        } catch (\Exception $e){
            error_log($e->getMessage());
            if($e instanceof ValidationException){
                throw $e;
            }
            throw new Exception($e->getMessage());
        }

            
    }

    //Change in course number and/or course title
    private function changeCodeTitle($content, $course, $courseRevId, $date)
    {
        try{
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

            // Course Revision TitleNum Creation
            $create = array_filter([
                'rev_id' => $courseRevId,
                'prev_code' => $course['code'],
                'new_code' => $content['newCourseCode'],
                'prev_title' => $course['title'],
                'new_title' => $content['newCourseTitle']
            ], function($value){
                return !is_null($value);
            });
            TitleNum::create($create);
            
            $beforeUpdCourse = [
                'id' => $course['id'],
                'code' => $course['code'],
                'title' => $course['title']
            ];

            // Updating the actual course
            $updates = array_filter([
                'code' => $content['newCourseCode'],
                'title' => $content['newCourseTitle'],
                'updated_at' => $date,
            ], function ($value) {
                return !is_null($value);
            });
            $course->update($updates);

            if ($content['newCourseCode']){
                return $beforeUpdCourse;  // we return the course before its code and/or title were updated
            }

        } catch (\Exception $e) {
            error_log($e->getMessage());
            if($e instanceof ValidationException){
                throw $e;
            }
            throw new Exception($e->getMessage());
        }
    }

    //Change in course description
    private function changeDesc($content, $course, $courseRevId, $date)
    {
        try{
            // Check if there is a newDescription
            $validator = Validator::make($content, [
                'newDescription' => 'required'
            ]);
            if ($validator->fails()) {
                throw new ValidationException($validator);
            }

            /*
            * Creating Course Revision proposal 
            * and updating the course entry
            */

            // Course Revision Desc Creation
            Desc::create([
                'rev_id' => $courseRevId,
                'prev_desc' => $course['desc'],
                'new_desc' => $content['newDescription']
            ]);

            // Updating the actual course
            $course->update([
                'desc' => $content['newDescription'],
                'updated_at' => $date
            ]);

            return;
            
        } catch (\Exception $e) {
            error_log($e->getMessage());
            if($e instanceof ValidationException){
                throw $e;
            }
            throw new Exception($e->getMessage());
        }
    }

    //Chnage in course prerequisites
    private function changePrereqs($content, $course, $courseRevId, $date)
    {
        try{
            /*
            * Creating new course revision proposal
            * and Course Revision Prereqs
            */

            // Getting the current prerequisites and the new prerequisites
            $currPrereqs = CoursePrereqs::where('course_id', $course->id)->pluck('prereq_code')->toArray();
            $newPrereqs = $content['newPrereqs'];

            // Creating new prereqs entry
            foreach($newPrereqs as $prereq){
                Prereqs::create([
                    'rev_id' => $courseRevId,
                    'prereq_code' => $prereq
                ]);
            }

            // Creating previous prereqs entry
            foreach($currPrereqs as $prereq){
                PrevPrereqs::create([
                    'rev_id' => $courseRevId,
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

            // Updating the actual course
            $course->update([
                'updated_at' => $date
            ]);

        } catch (\Exception $e) {
            error_log($e->getMessage());
            if($e instanceof ValidationException){
                throw $e;
            }
            throw new Exception($e->getMessage());
        }
    }

    //Change in semester offering
    private function changeSemOffered($content, $course, $courseRevId, $date)
    {
        try{
            // Check if there is a selected course
            $validator = Validator::make($content, [
                'selectedCourse' => 'required',
            ]);
            if ($validator->fails()) {
                throw new ValidationException($validator);
            }

            /*
            * Creating new course revision proposal 
            * and Course Revision sem offered
            */
        
            // Getting the previous sem offered and the new sem offered
            $currSemOffered = CourseSemOffered::where('course_id', $course->id)->pluck('sem_offered')->toArray();
            $newSemOffered = $content['newSemOffering'];

            // Adding 
            foreach($currSemOffered as $sem_offered){
                PrevSemOffered::create([
                    'rev_id' => $courseRevId,
                    'sem_offered' => $sem_offered
                ]);
            }

            // Adding the new sem offered for the specific course
            foreach($newSemOffered as $sem_offered){
                SemOffered::create([
                    'rev_id' => $courseRevId,
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

            // Updating the actual course
            $course->update([
                'updated_at' => $date
            ]);

        } catch (\Exception $e) {
            error_log($e->getMessage());
            if($e instanceof ValidationException){
                throw $e;
            }
            throw new Exception($e->getMessage());
        }
    }

    // Change in number of hours
    private function changeHours($content, $course, $courseRevId, $date)
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
            
            error_log('$course = '.$course);
            error_log('$date = '.$date);
            
            /*
            * Creating new course revision proposal 
            * and updating the actual course entry
            */
            // Creating the new number of hours entry
            NumOfHours::create([
                'rev_id' => $courseRevId,
                'prev_num_of_hours' => $course['num_of_hours'],
                'new_num_of_hours' => $content['newNumOfHours']
            ]);

            // Updating the actual course
            $course->update([
                'num_of_hours' => $content['newNumOfHours'],
                'updated_at' => $date
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
    private function changeContent($content, $course, $courseRevId, $date)
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

            /*
            * Creating new course revision proposal 
            * and updating the actual course entry
            */
            Content::create([
                'rev_id' => $courseRevId,
                'prev_goal' => $course['goal'],
                'new_goal' => $content['newGoal'],
                'prev_outline' => $course['outline'],
                'new_outline' => $content['newOutline'] 
            ]);

            $course->update([
                'goal' => $content['newGoal'],
                'outline' => $content['newOutline'],
                'updated_at' => $date
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
