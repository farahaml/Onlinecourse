<?php

namespace App\Http\Controllers;

//course model
use App\Course;
//mentor model
use App\Mentor;
//review model
use App\Review;
//my course model
use App\MyCourse;
//chapter model
use App\Chapter;
use Illuminate\Http\Request;
//validator
use Illuminate\Support\Facades\Validator;

class CourseController extends Controller
{
    //get courses list
    public function index (Request $request)
    {
        $courses = Course::query();

        //filter 
        $q = $request->query('q');
        $status = $request->query('status');

        $courses->when($q, function($query) use ($q) {
            return $query->whereRaw("name LIKE '%".strtolower($q)."%'");
        });

        $courses->when($status, function($query) use ($status) {
            return $query->where('status', '=', $status);
        });

        //success response
        return response()->json([
            'status' => 'success',
            'data' => $courses->paginate(10)
        ]);
    }

    //get detail course
    public function show ($id) {
        //find course id
        $course = Course::with('mentor')->with('chapters.lessons')->with('images')->find($id);

        //if course id not found
        if (!$course) {
            return response()->json([
                'status' => 'error',
                'message' => 'course not found'
            ], 404);
        }

        //show review course
        $reviews = Review::where('course_id', '=', $id)->get()->toArray();
        //show data user with helpers
        if (count($reviews) > 0 ) {
            $userIds = array_column($reviews, 'user_id');
            $users = getUserByIds($userIds);
        if ($users['status'] === 'error') {
            $reviews = $users;
        } else {
            foreach($reviews as $key => $review) {
                $userIndex = array_search($review['user_id'], array_column($users['data'], 'id'));
                $reviews[$key]['users'] = $users['data'][$userIndex];
            }
        }
        }
        //show student
        $totalStudent = MyCourse::where('course_id', '=', $id)->count();
        //show lessons
        $totalVideos = Chapter::where('course_id', '=', $id)->withCount('lessons')->get()->toArray();
        //show final total videos
        $finalTotalVideos = array_sum(array_column($totalVideos, 'lessons_count'));

        //add student, videos, and reviews data
        $course['reviews'] = $reviews;
        $course['total_videos'] = $finalTotalVideos;
        $course['total_student'] = $totalStudent;

        //success response
        return response()->json([
            'status' => 'success',
            'data' => $course
        ]);

    }

    //create course
    public function create (Request $request)
    {
        //input schema
        $rules = [
            'name' => 'required|string',
            'certificate' => 'required|boolean',
            'thumbnail' => 'string|url',
            'type' => 'required|in:free,premium',
            'status' => 'required|in:draft,published',
            'price' => 'integer',
            'level' => 'required|in:all-level,beginner,intermediate,advanced',
            'description' => 'string',
            'mentor_id' => 'required|integer'
        ];

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

        //find mentor id from database
        $mentorId = $request->input('mentor_id');
        $mentor = Mentor::find($mentorId);

        //if mentor id invalid
        if (!$mentor) {
            //error response
            return response()->json([
                'status' => 'error',
                'message' => 'mentor not found'
            ], 404);
        }

        //creating data course in database
        $course = Course::create($data);

        //success response
        return response()->json([
            'status' => 'success',
            'data' => $course
        ]);
    }

    //update course with id
    public function update (Request $request, $id) {
        //validate schema for input data
        $rules = [
            'name' => 'string',
            'certificate' => 'boolean',
            'thumbnail' => 'string|url',
            'type' => 'in:free,premium',
            'status' => 'in:draft,published',
            'price' => 'integer',
            'level' => 'in:all-level,beginner,intermediate,advanced',
            'description' => 'string',
            'mentor_id' => 'integer'
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
        $course = Course::find($id);

        //if there's no course id
        if (!$course) {
            //error response
            return response()->json([
                'status' => 'error',
                'message' => 'course not found'
            ], 404);
        }

        //if there's mentor update
        $mentorId = $request->input('mentor_id');
        if ($mentorId) {
            //find mentor id in database
            $mentor = Mentor::find($mentorId);
            //if mentor id invalid
            if (!$mentor) {
                //error response
                return response()->json([
                    'status' => 'error',
                    'message' => 'mentor not found'
                ], 404);
            }
        }

        //updating data course
        $course->fill($data);
        $course->save();
       
        //success response
        return response()->json([
            'status' => 'success',
            'data' => $course
        ]);
    }

    //delete  courses data by id
    public function destroy ($id) {
        //find course id
        $course = Course::find($id);

        //if course id invalid
        if (!$course) {
            //error response
            return response()->json([
                'status' => 'error',
                'message' => 'course not found'
            ], 404);
        }

        //if course id valid = deleting course
        $course->delete();

        //success deleted course response
        return response()->json([
            'status' => 'success',
            'message' => 'course deleted'
        ]);
    }
}
?>
