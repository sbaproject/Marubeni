<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class MediaController extends Controller
{
    public function test()
    {
        return view('media.test');
    }
    public function storeTmp(Request $request)
    {
        // $path = storage_path('/uploads/tmps');

        // if (!file_exists($path)) {
        //     mkdir($path, 0777, true);
        // }

        $rules = array(
            'file' => config('const.rules.attached_file'),
        );

        $validation = Validator::make($request->all(), $rules);

        if ($validation->fails()) {
            return response()->json([
                'status'=> 400,
                'msg' => $validation->errors()->first(),
            ]);
        }

        $file = $request->file('file');

        $name = uniqid() . '_' . trim($file->getClientOriginalName());

        // $file->move($path, $name);

        $file->storeAs('uploads/tmp/', $name);

        return response()->json([
            'status'        => 200,
            'msg'           => 'success',
            'name'          => $name,
            'original_name' => $file->getClientOriginalName(),
        ]);
    }
}
