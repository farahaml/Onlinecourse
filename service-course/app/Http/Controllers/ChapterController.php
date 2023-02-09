<?php

namespace App\Http\Controllers;

//chapter model
use App\Chapter;
//course model
use App\Course;
use Illuminate\Http\Request;
//validator
use Illuminate\Support\Facades\Validator;

class ChapterController extends Controller
{
    //create chapter
    public function create (Request $request) {
        //schema for input data chapter
        $rules = [
            'name' => 'required|string',
            'course_id' => 'required|integer'
        ];

        //request all data from body
        $data = $request->all();

        //validating data
        $validator = Validator::make($data, $rules);

        //if data invalid 
        if ($validator->fails()) {
            //error response
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()
            ], 400);
        }

        //find course id in database
        $courseId = $request->input('course_id');
        $course = Course::find($courseId);

        //if course id not found
        if (!$course) {
            //error response
            return response()->json([
                'status' => 'error',
                'message' => 'course not found'
            ], 404);
        }

        //creating chapter data in database
        $chapter = Chapter::create($data);

        //success response
        return response()->json([
            'status' => 'success',
            'data' => $chapter
        ]);
    }
}
