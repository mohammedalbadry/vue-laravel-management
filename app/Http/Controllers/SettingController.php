<?php

namespace App\Http\Controllers;

use App\Models\Settings;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class SettingController extends Controller
{

    public function index()
    {

        $data = Settings::first();
        return response()->json([
            'status' => 'success',
            'data' => $data,
        ]);
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|min:2',
            'logo' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'icon' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'description' => 'required|string|min:10',
            'status' => 'required|string|min:2',
            'alt_text' => 'required|string|min:10',
        ]);

        if($validator->fails()){
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ]);
        }

        $data = [
            'name' => $request->name,
            'description' => $request->description,
            'status' => $request->status,
            'alt_text' => $request->alt_text,
        ];

        $model = Settings::first();

        if ($files = $request->file('logo')) {  
            if($model->logo != "default.png") {      
                Storage::disk('public_uploads')->delete(public_path() . "/uploads/settings//" . $model->logo);
            }

            $ImageUpload = Image::make($files);
            $image_name = time().$files->getClientOriginalName();
            $ImageUpload->save(public_path() . "/uploads/settings//" . $image_name);
        
            $data['logo'] = $image_name;
        }
        if ($files = $request->file('icon')) {  
            if($model->icon != "default.png") {      
                Storage::disk('public_uploads')->delete(public_path() . "/uploads/settings//" . $model->icon);
            }

            $ImageUpload = Image::make($files);
            $image_name = time().$files->getClientOriginalName();
            $ImageUpload->save(public_path() . "/uploads/settings//" . $image_name);
        
            $data['icon'] = $image_name;
        }

        $model->update($data);
        return response()->json([
            'status' => 'success',
            'message' => "updated successfuly"
        ]);

    }

}
