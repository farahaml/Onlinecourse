<?php

namespace App\Http\Controllers;

//review model
use App\Review;
//course model 
use App\Course;
use Illuminate\Http\Request;
//validator
use Illuminate\Support\Facades\Validator;

class ReviewController extends Controller
{
    //create review
    public function create (Request $request) {
        $rules = [
            'user_id' => 'required|integer',
            'course_id' => 'required|integer',
            'rating' => 'required|integer|min:1|max:5',
            'note' => 'string'
        ];

        $data = $request->all();

        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            //error response
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()
            ], 400);
        }

        //find course id
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

        //find user id
        $userId = $request->input('user_id');
        $user = getUser($userId);

        //if user id not found
        if ($user ['status'] === 'error') {
            return response()->json([
                'status' => $user['status'],
                'message' => $user['message']
            ], $user['http_code']);
        }

        $isExistReview = Review::where('course_id', '=', $courseId)
                        ->where('user_id', '=', $userId)
                        ->exists();

        if ($isExistReview) {
            //error response
            return response()->json([
            'status' => 'error',
            'response' => 'review already exist'
            ], 409);
        }

        //creating review
        $review = Review::create($data);

        //success response
        return response()->json([
            'status' => 'success',
            'data' => $review
        ]);
    }

     //update review by id
     public function update (Request $request, $id) {
        $rules = [
            'rating' => 'integer|min:1|max:5',
            'note' => 'string'
        ];

        $data = $request->except('user_id', 'course_id');

        //validating
        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()
            ], 400);
        }

        //find review id
        $review = Review::find($id);

        //if review id not found
        if (!$review) {
            return response()->json([
                'status' => 'error',
                'message' => 'review not found'
            ], 404);
        }

        //updating data review
        $review->fill($data);
        $review->save();

        //success response
        return response()->json([
            'status' => 'success',
            'data' => $review
        ]);
     }

     //delete review by id
     public function destroy ($id) {
        //find review id
        $review = Review::find($id);

        //if review id not found
        if (!$review) {
            return response()->json([
                'status' => 'error',
                'message' => 'review not found'
            ], 404);
        }

        //deleting review
        $review->delete();

        //success response
        return response()->json([
            'status' => 'success',
            'message' => 'review deleted'
        ]);
     }
}
