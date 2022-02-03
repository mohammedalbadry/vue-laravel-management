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
        return response()->json([
            'status' => 'success',
            'data' => Settings::first(),
        ]);
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'logo' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'icon' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'description' => 'required',
            'status' => 'required',
            'alt_text' => 'required',
        ]);

        $data = [
            'name' => $request->name,
            'description' => $request->description,
            'status' => $request->status,
            'alt_text' => $request->alt_text,
        ];

        $model = Settings::first();

        if ($files = $request->file('logo')) {  
            if($model->logo != "default.png") {      
                Storage::disk('public_uploads')->delete($this->img_path . $model->logo);
            }

            $ImageUpload = Image::make($files);
            $image_name = time().$files->getClientOriginalName();
            $ImageUpload->save(public_path() . "/uploads/settings//" . $image_name);
        
            $data['logo'] = $image_name;
        }
        if ($files = $request->file('icon')) {  
            if($model->icon != "default.png") {      
                Storage::disk('public_uploads')->delete($this->img_path . $model->icon);
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
