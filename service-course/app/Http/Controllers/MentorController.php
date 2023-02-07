<?php

namespace App\Http\Controllers;

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
        $mentor = Mentor::find($id);
        //jika mentor tidak ditemukan
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

    //create mentors
    public function create(Request $request)
    {
        //skema validasi untuk setiap data yang masuk
        $rules = [
            'name' => 'required|string',
            'profile' => 'required|url',
            'profession' => 'required|string',
            'email' => 'required|email'
        ];
        
        $data = $request->all();

        $validator = Validator::make($data, $rules);

        //memeriksa erros pada proses validasi
        if ($validator->fails()) {
            return response()->json([
                'status' => false, 
                'message' => $validator->errors()
                ], 400);
        }

        //jika tidak ada error maka data mentor akan dibuat di database
        $mentor = Mentor::create($data);

        //respon sukses
        return response()->json([
            'status' => 'success',
            'data' => $mentor
        ]);
    } 

    //end point guna mengupdate data mentor
    public function update(Request $request, $id)
    {
        //validasi
        $rules = [
            'name' => 'string',
            'profile' => 'url',
            'profession' => 'string',
            'email' => 'email'
        ];
        
        $data = $request->all();

        $validator = Validator::make($data, $rules);

        //memeriksa error pada proses validasi
        if ($validator->fails()) {
            return response()->json([
                'status' => false, 
                'message' => $validator->errors()
                ], 400);
        }

        //memeriksa apakah id mentor ada di database
        $mentor = Mentor::find($id);

        //respon jika id mentor tidak ada di database
        if (!$mentor) {
            return response()->json([
                'status' => 'error',
                'message' => 'mentor not found'
            ], 404);
        }

        //data akan diupdate apabilla id ada di database
        $mentor->fill($data);
        $mentor->save();

        //respon update sukses
        return response()->json([
            'status' => 'success',
            'data' => $mentor
        ]);

    }
}
