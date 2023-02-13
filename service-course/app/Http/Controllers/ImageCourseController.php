<?php

namespace App\Http\Controllers;

//ImageCourse model
use App\ImageCourse;
//Course model
use App\Course;
use Illuminate\Http\Request;
//validator
use Illuminate\Support\Facades\Validator;

class ImageCourseController extends Controller
{
    //create Image Course
    public function create (Request $request) {
        $rules = [
            'image' => 'required|url',
            'course_id' => 'required|integer'
        ];

        $data = $request->all();

        $validator = Validator::make($data, $rules);

        //if validator fail
        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()
            ], 400);
        }

        $courseId = $request->input('course_id');
        //find course id
        $course = Course::find($courseId);
        //if course id not found
        if (!$course) {
            return response()->json([
                'status' => 'error',
                'message' => 'course not found'
            ], 404);
        }

        //creating image course data
        $imageCourse = ImageCourse::create($data);

        //success response
        return response()->json([
            'status' => 'success',
            'data' => $imageCourse
        ]);
    }

    
}
