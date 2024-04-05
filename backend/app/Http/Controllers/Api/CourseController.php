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
        return response()->json($courses);
    }
}
