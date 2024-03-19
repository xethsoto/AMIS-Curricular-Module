<?php

namespace App\Http\Controllers\Api;

use App\Models\Course;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CourseController extends Controller
{
    public function index()
    {
        $courses = Course::all();
        if($courses->count() > 0){
            $data = [
                'status' => 200,
                'courses' => $courses
            ];
        } else {
            $data = [
                'status' => 404,
                'message' => 'No Records Found'
            ];
        }

        return response()->json($data, $data['status']);
    } 

    // public function save(Request $request)
    // {

    // }
}
