<?php

namespace App\Http\Controllers\Api\ProposalsControllers\CourseControllers;

use Exception;
use Illuminate\Http\Request;
use App\Models\Course\Course;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use App\Models\Proposal\CourseProp\CourseCrosslist;

class CourseCrosslistController extends Controller
{
    public function save($proposal, $content)
    {
        try{
            // Check if there is a selected course and crosslist course
            $validator = Validator::make($content, [
                'selectedCourse' => 'required',
                'crosslistCourse' => 'required'
            ]);
            if($validator->fails()){
                throw new ValidationException($validator);
            }

            $selectedCourseId = $content['selectedCourse']['id'];
            $crosslistCourseId = $content['crosslistCourse']['id'];

            // Check if both courses are the same
            if ($selectedCourseId == $crosslistCourseId){
                throw new Exception('The selected courses should not be the same');
            }

            // Check if selected course exists in the database
            $selectedCourse = Course::find($selectedCourseId);
            if (!$selectedCourse){
                throw new Exception($content['selectedCourse']['code']." is not found.");
            }

            // Check if crosslist course exists in the database
            $crosslistCourse = Course::find($crosslistCourseId);
            if (!$crosslistCourse){
                throw new Exception($content['crosslistCourse']['code']." is not found.");
            }

            // Creating a new course crosslist entry
            CourseCrosslist::create([
                'course_id' => $selectedCourse->id,
                'crosslist_id' => $crosslistCourse->id,
                'prop_id' => $proposal['id']
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
