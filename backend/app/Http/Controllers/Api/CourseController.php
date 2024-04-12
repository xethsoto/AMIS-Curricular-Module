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
            // $coursePrereqs = $course->getPrereqCode();
            // return $coursePrereqs->map(function ($code){
            //     return Course::select('id', 'title', 'code')
            //         ->where('code', $code)
            //         ->get();
            // });
        }));
    }

    public function getRequisites($id)
    {
        $courseCode = Course::select('code')->where('id', $id)->first();
        $courseReqs = CoursePrereqs::where('prereq_code', $courseCode['code'])->get();
        $queryResult = Course::select('id','code','title')->whereIn('id', $courseReqs->pluck('course_id'))->get();
        return $queryResult;
        // return CoursePrereqs::where('prereq_code', '==', $courseCode)->get();
    }

    public function getCourse($id)
    {
        $course = Course::with('prereqs', 'semOffered')
            ->where('id', $id)
            ->first();
            
        if ($course) {
            // convert prereq course code to its id, title, and code
            $courseArray = $course->toArray();
            $temp = $course['prereqs']->pluck('prereq_code');
            $courseArray['prereqs'] = [];
            foreach($temp as $code){
                array_push(
                    $courseArray['prereqs'], 
                    Course::select('id', 'title', 'code')
                        ->where('code', $code)
                        ->first()
                );
            }
    
            $courseArray['sem_offered'] = $course->getSemOffered();
            
            return response()->json($courseArray);
        } else {
            return response()->json([
                'success' => false,
                'status' => 404,
                'message' => 'Course not found' 
            ], 404);
        }
    }

    public function codeToCourse($code)
    {
        return Course::where('code', $code);
    }
}
