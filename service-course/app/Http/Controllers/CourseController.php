<?php

namespace App\Http\Controllers;

//memanggil model course
use App\Course;
//memanggil model mentor
use App\Mentor;
use Illuminate\Http\Request;
//validator
use Illuminate\Support\Facades\Validator;

class CourseController extends Controller
{
    public function create (Request $request)
    {
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

        //meminta semua data dari body
        $data = $request->all();

        //validasi data
        $validator = Validator::make($data, $rules);

        //jika validasi error
        if ($validator->fails()) {
            //respon error
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()
            ], 400);
        }

        //memeriksa apakah id mentor terdaftar di database
        $mentorId = $request->input('mentor_id');
        $mentor = Mentor::find($mentorId);

        //jika id mentor tidak terdaftar di database
        if (!$mentor) {
            //respon error
            return response()->json([
                'status' => 'error',
                'message' => 'mentor not found'
            ], 404);
        }

        //menyimpan data course yang dibuat
        $course = Course::create($data);

        //respon sukses
        return response()->json([
            'status' => 'succes',
            'data' => $course
        ]);
    }

    public function update (Request $request, $id) {
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

        //meminta semua data dari body
        $data = $request->all();

        //validasi data
        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            //respon error
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()
            ], 400);
        }

        //mencari id course
        $course = Course::find($id);
        //jika id course tidak ada
        if (!$course) {
            //respon error
            return response()->json([
                'status' => 'error',
                'message' => 'course not found'
            ], 404);
        }

        //memeriksa id mentor ketika diupdate
        $mentorId = $request->input('mentor_id');
        if ($mentorId) {
            //mencari id mentor
            $mentor = Mentor::find($mentorId);
            if (!$mentor) {
                //respon error
                return response()->json([
                    'status' => 'error',
                    'message' => 'mentor not found'
                ], 404);
            }
        }

        //data akan diupdate
        $course->fill($data);
        $course->save();
       
        //respon update sukses
        return response()->json([
            'status' => 'success',
            'data' => $course
        ]);
    }
}
