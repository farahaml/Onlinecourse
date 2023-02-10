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
    //get list chapter
    public function index(Request $request) {
        $chapters = Chapter::query();

        //filter by course
        $courseId = $request->query('course_id');

        $chapters->when($courseId, function($query) use ($courseId) {
            return $query->where('course_id', '=', $courseId);
        });

        return response()->json([
            'status' => 'success',
            'data' => $chapters->get()
        ]);
    }

    //get detail chapter
    public function show($id) {
        $chapter = Chapter::find($id);
        if  (!$chapter) {
            //error response
            return response()->json([
                'status' => 'error',
                'message' => 'chapter not found'
            ], 404);
        }

        //success response
        return response()->json([
            'status' => 'success',
            'data' => $chapter
        ]);
    }

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

    //Update Chapter
    public function update (Request $request, $id) {
        //schema input data
        $rules = [
            'name' => 'string',
            'course_id' => 'integer'
        ];

        //request all data from body
        $data = $request->All();

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

        //searching chapter id
        $chapter = Chapter::find($id);

        //if chapter not found
        if (!$chapter) {
            return response()->json([
                'status' => 'error',
                'message'=> 'chapter not found'
            ], 404);
        }

        //searching course id
        $courseId = $request->input('course_id');
        if ($courseId) {
            //searching course id
            $course = Course::find($id);
            //if course id not found
            if (!$course) {
                //error response
                return response()->json([
                'status' => 'error',
                'message' => 'course not found'
                ], 404);
            }
        }

        //updating chapter data
        $chapter->fill($data);
        $chapter->save();

        //success response
        return response()->json([
            'status' => 'success',
            'data' => $chapter
        ]);
    }
}
