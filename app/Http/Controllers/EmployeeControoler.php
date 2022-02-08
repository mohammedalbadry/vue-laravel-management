<?php

namespace App\Http\Controllers;

use Image;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class EmployeeControoler extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $data = User::when($request->search, function($query) use ($request){
            return $query->where('name', 'like', '%' . $request->search . '%');
        })->orderBy('id', 'DESC')->paginate(5);

        return response()->json([
            'status' => 'success',
            'data' => $data,
        ]);
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|min:2',
            'email' => ['required', 'email', 'unique:users'],
            'password' => 'required|confirmed|min:6',
            'password_confirmation' =>'required',
            'img' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'job_title' => 'required',
        ]);

        if($validator->fails()){
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ]);
        }
        $hashPassword = bcrypt(request('password'));
        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'password' => $hashPassword,
            'job_title' => $request->job_title,
        ];
        if ($files = $request->file('img')) {        
            $ImageUpload = Image::make($files);
            $image_name = time().$files->getClientOriginalName();
            $data['img'] = $image_name;
            $ImageUpload->save(public_path() . "/uploads/employees//" . $image_name);
        }
        User::create($data);
        return response()->json([
            'status' => 'success',
            'message' => "added successfuly"
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users,email,'. $id,
            'password' => 'nullable|confirmed|min:6',
            'img' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'job_title' => 'required',
        ]);
        

        if($validator->fails()){
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ]);
        }

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'job_title' => $request->job_title,
        ];
        if ($request->has('password') && $request->password != null){
            $data['password'] = bcrypt(request('password'));    
        }
        if ($files = $request->file('img')) {   
            if(User::find($id)->image != "default.png") {      
                Storage::disk('public_uploads')->delete("/uploads/employees//" . User::find($id)->img);
            }     
            $ImageUpload = Image::make($files);
            $image_name = time().$files->getClientOriginalName();
            $data['img'] = $image_name;
            $ImageUpload->save(public_path() . "/uploads/employees//" . $image_name);
        }
        User::find($id)->update($data);
        return response()->json([
            'status' => 'success',
            'message' => "updated successfuly"
        ]);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        User::find($id)->delete();

        return response()->json([
            'status' => 'success',
            'message' => "deleted successfuly"
        ]);
    }
}
