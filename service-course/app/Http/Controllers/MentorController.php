<?php

namespace App\Http\Controllers;

//mentor model
use App\Mentor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MentorController extends Controller
{

    //get list mentors
    public function index ()
    {
        $mentors = Mentor::all();
        return response()->json([
            'status' => 'success',
            'data' => $mentors
        ]);
    }

    //get mentor's data
    public function show ($id)
    {
        //find mentor id in database 
        $mentor = Mentor::find($id);
        //if mentor id invalid
        if (!$mentor) {
            //error response
            return response()->json([
                'status' => 'error',
                'message' => 'mentor not found'
            ], 404);
        }

        //success response
        return response()->json([
            'status' => 'succes',
            'data' => $mentor
        ]);
    }

    //create mentor
    public function create(Request $request)
    {
        //validate schema for input data
        $rules = [
            'name' => 'required|string',
            'profile' => 'required|url',
            'profession' => 'required|string',
            'email' => 'required|email'
        ];
        
        //request all data from body
        $data = $request->all();

        //validating mentor data
        $validator = Validator::make($data, $rules);

        //if mentor data invalid
        if ($validator->fails()) {
            //error response
            return response()->json([
                'status' => false, 
                'message' => $validator->errors()
                ], 400);
        }

        //creating mentor data
        $mentor = Mentor::create($data);

        //success response
        return response()->json([
            'status' => 'success',
            'data' => $mentor
        ]);
    } 

    //update mentor data
    public function update(Request $request, $id)
    {
        //validate schema for input data
        $rules = [
            'name' => 'string',
            'profile' => 'url',
            'profession' => 'string',
            'email' => 'email'
        ];
        
        //request all data from body
        $data = $request->all();

        //validating updated mentor data
        $validator = Validator::make($data, $rules);

        //if updated mentor data invalid
        if ($validator->fails()) {
            //error response
            return response()->json([
                'status' => false, 
                'message' => $validator->errors()
                ], 400);
        }

        //find mentor id in database
        $mentor = Mentor::find($id);

        //if there's no mentor is
        if (!$mentor) {
            return response()->json([
                'status' => 'error',
                'message' => 'mentor not found'
            ], 404);
        }

        //updating data
        $mentor->fill($data);
        $mentor->save();

        //successupdated mentor data response
        return response()->json([
            'status' => 'success',
            'data' => $mentor
        ]);

    }

    //delete mentor data by id
    public function destroy($id) {
        //fin mentor id
        $mentor = Mentor::find($id);

        //jika data mentor tidak ada di database
        if (!$mentor) {
            //respon error
            return response()->json([
                'status' => 'error',
                'message' => 'mentor not found'
            ], 404);
        }

        //jika data mentor ada di database
        $mentor->delete();

        //respon sukses
        return response()->json([
            'status' => 'success',
            'message' => 'mentor deleted'
        ]);
    }
}
