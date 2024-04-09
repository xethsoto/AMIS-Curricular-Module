<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Course\Course;
use App\Models\Course\CoursePrereqs;
use App\Models\Course\CourseSemOffered;

class CourseController extends Controller
{
    public function getCourses()
    {
        $courses = Course::with('prereqs', 'semOffered')->get();

        //only returns prereq_code and sem_offered in prereqs and sem_offered
        return response()->json($courses->map(function ($course) {
            $courseArray = $course->toArray();
            $courseArray['prereqs'] = $course->getPrereqCode();
            $courseArray['sem_offered'] = $course->getSemOffered();

            error_log('$courseArray = '.print_r($courseArray,true));
            return $courseArray;
        }));
    }
}
