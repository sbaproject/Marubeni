<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class MediaController extends Controller
{
    public function test(){
        return view('media.test');
    }
    public function store(Request $request){
        // $path = storage_path('/uploads/tmps');

        // if (!file_exists($path)) {
        //     mkdir($path, 0777, true);
        // }

        $rules = array(
            'file' => 'image|max:50',
        );

        $validation = Validator::make($request->input(), $rules);

        if ($validation->fails()) {
            return Response::make($validation->errors->first(), 400);
        }

        $file = $request->file('file');

        $name = uniqid() . '_' . trim($file->getClientOriginalName());

        // $file->move($path, $name);

        $file->storeAs('uploads/tmp/', $name);

        return response()->json([
            'name'          => $name,
            'original_name' => $file->getClientOriginalName(),
        ]);
    }
}
