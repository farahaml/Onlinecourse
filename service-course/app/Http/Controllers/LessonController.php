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

    //update lesson by id
    public function update (Request $request, $id) {
        $rules = [
            'name' => 'string',
            'video' => 'string',
            'chapter_id' => 'integer'
        ];

        $data = $request->all();

        //validating data
        $validator = Validator::make($data, $rules);

        //if data invalid 
        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()
            ], 400);
        }

        //find lesson_id in database
        $lesson = Lesson::find($id);

        //if lesson_id not found
        if (!$lesson) {
            //error response
            return response()->json([
                'status' => 'error',
                'message' => 'lesson not found'
            ], 404);
        }

        //if there's chapter_id update
        $chapterId = $request->input('chapter_id');
        if ($chapterId) {
            //find chapter_id
            $chapter = Mentor::find($chapterId);

            //if chapter_id not found
            if (!$chapter) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'chapter not found'
                ], 404);
            }
        }

        //updating data lesson
        $lesson->fill($data);
        $lesson->save();

        //success response
        return response()->json([
            'status' => 'success',
            'data' => $lesson
        ]);
    }

    //delete lesson by id
    public function destroy (Request $request, $id) {
        //find lesson id
        $lesson = Lesson::find($id);

        //if lesson not found
        if (!$lesson) {
            //error response
            return response()->json([
                'status' => 'error',
                'message' => 'lesson not found'
            ], 404);
        }

        //deleting lesson
        $lesson->delete();

        //success response
        return response()->json([
            'status' => 'success',
            'message' => 'lesson deleted'
        ]);
    }
}
