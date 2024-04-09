<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\Course\Course;
use App\Http\Controllers\Controller;
use App\Models\Course\CoursePrereqs;
use App\Models\Course\CourseSemOffered;

class CourseController extends Controller
{

    //used to extract prereq_code and sem_offering
    //from separate table
    private function extractInfo($course)
    {
        $courseArray = $course->toArray();
        $courseArray['prereqs'] = $course->getPrereqCode();
        $courseArray['sem_offered'] = $course->getSemOffered();

        return $courseArray;
    }

    public function getCourses()
    {
        $courses = Course::with('prereqs', 'semOffered')->get();

        //only returns prereq_code and sem_offered in prereqs and sem_offered
        return response()->json($courses->map(function ($course) {
            return $this->extractInfo($course);
        }));
    }

    public function getRequisites(Request $request)
    {
        // 
    }

    public function getCourse($id)
    {
        $course = Course::find($id);

        if ($course) {
            return response()->json($course);
        } else {
            return response()->json([
                'success' => false,
                'status' => 404,
                'message' => 'Course not found' 
            ], 404);
        }
    }
}
