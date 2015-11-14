<?php namespace App\Http\Controllers;

use Input;
use Validator;
use Config;
use Response;

class PictureController extends Controller {

    public function doUploadPicture() {

        $input = Input::all();
        $rules = array(
            'file' => 'image|max:' . Config::get('rescue.max_picture_size')
        );

        $validation = Validator::make($input, $rules);

        if ($validation->fails()) {
            return Response::make($validation->errors->first(), 400);
        }

        $file = Input::file('file');

        if (Input::file('file')->isValid()) {
            $destinationPath = Config::get('rescue.upload_path');
            $filename = $file->getClientOriginalName();

            $upload_success = Input::file('file')->move($destinationPath, $filename);

            if ($upload_success) {
                $ret = array(
                    'status' => 200,
                    'data' => 'success',
                    'file_name' => $filename,
                );
                return Response::json($ret, 200);
            } else {
                return Response::json('error', 400);
            }
        } else {
            return Response::json('error', 400);
        }
    }
}