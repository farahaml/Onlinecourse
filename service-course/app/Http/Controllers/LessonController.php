<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
//model lesson
use App\Lesson;
//model chapter
use App\Chapter;
//validator 
use Illuminate\Support\Facades\Validator;

class LessonController extends Controller
{
    //create lesson
    public function create (Request $request) {
        //input schema
        $rules = [
            'name' => 'required|string',
            'video' => 'required|string',
            'chapter_id' => 'required|integer'
        ];

        $data = $request->all();

        //validating data
        $validator = Validator::make ($data, $rules);

        //if data invalid
        if ($validator->fails()) {
            //error response
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()
            ], 400);
        }

        //find chapter id
        $chapterId = $request->input('chapter_id');
        $chapter = Chapter::find($chapterId);

        //if chapter id not found
        if (!$chapter) {
            //error response
            return response()->json([
                'status' => 'error',
                'message' => 'chapter not found'
            ], 404);
        }

        //creating lesson data in database
        $lesson = Lesson::create($data);

        //success response
        return response()->json([
            'status' => 'success',
            'data' => $lesson
        ]);
    }
}
