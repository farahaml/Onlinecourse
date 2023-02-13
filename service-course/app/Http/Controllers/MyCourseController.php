<?php

namespace App\Http\Controllers;

//My Course Model
use App\MyCourse;
//Course Model
use App\Course;
use Illuminate\Http\Request;
//validator
use Illuminate\Support\Facades\Validator;

class MyCourseController extends Controller
{
    public function create (Request $request) {
        $rules = [
            'course_id' => 'required|integer',
            'user_id' => 'required|integer'
        ];

        $data = $request->all();

        $validator = Validator::make($data, $rules);

        //if data invalid
        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'messsage' => $validator->errors()
            ], 400);
        }

        $courseId = $request->input('course_id');
        $course = Course::find($courseId);

        //if course not found
        if (!$course) {
            return response()->json([
                'status' => 'error',
                'message' => 'course not found'
            ], 404);
        }

        $userId = $request->input('user_id');
        $user = getUser($userId);

        if ($user ['status'] === 'error') {
            return response()->json([
                'status' => $user['status'],
                'message' => $user['message']
            ], $user['http_code']);
        }

        //pengecekan duplikasi data
        $isExistMyCourse = MyCourse::where('course_id', '=', $courseId)
                                    ->where('user_id', '=', $userId)
                                    ->exists();

        if ($isExistMyCourse) {
            //error response
            return response()->json([
                'status' => 'error',
                'response' => 'user already taken this course'
            ], 409);
        }
        //creating my course data in database
        $myCourse = MyCourse::create($data);

        //success response 
        return response()->json([
            'status' => 'success',
            'data' => $myCourse
        ]);
    }
}
