<?php

namespace App\Http\Controllers\Api\ProposalsControllers\CourseControllers;

use Exception;
use Illuminate\Http\Request;
use App\Models\Course\Course;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use App\Models\Proposal\CourseProp\CourseAbolishment;

class CourseAbolishmentController extends Controller
{
    public function save ($proposal, $content)
    {
        try {
            $messages = [
                'selectedCourse.required' => "Please select a course to revise",
            ];

            // Check if there is a selected course
            $validator = Validator::make($content, [
                'selectedCourse' => 'required'
            ], $messages);
            if($validator->fails()){
                throw new ValidationException($validator);
            }

            $selectedCourseCode = $content['selectedCourse'];

            // Check if selected course exists in the database
            $selectedCourse = Course::where('code', $selectedCourseCode)->first();
            if (!$selectedCourse) {
                throw new Exception($content['selectedCourse']." is not found.");
            }

            // Creating a new course abolishment entry
            CourseAbolishment::create([
                'course_id' => $selectedCourse['id'],
                'prop_id' => $proposal['id']
            ]);

            // Abolishing the course itself
            $selectedCourse->update([
                'status' => 'Abolished'
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
