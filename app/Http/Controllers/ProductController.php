<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $data = Product::when($request->search, function($query) use ($request){
            return $query->where('name', 'like', '%' . $request->search . '%');
        })->with('category')->orderBy('id', 'DESC')->paginate(5);

        $categories = Category::all();

        return response()->json([
            'status' => 'success',
            'data' => $data,
            'categories' => $categories,
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
            'name' => 'required',
            'price' => 'required',
            'img' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'description' =>'required',
            'stock' => 'required',
            'category_id' => 'required',
        ]);

        if($validator->fails()){
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ]);
        }
        $data = [
            'name' => $request->name,
            'price' => $request->price,
            'description' => $request->description,
            'stock' => $request->stock,
            'category_id' => $request->category_id,
        ];
        if ($files = $request->file('img')) {        
            $ImageUpload = Image::make($files);
            $image_name = time().$files->getClientOriginalName();
            $data['img'] = $image_name;
            $ImageUpload->save(public_path() . "/uploads/products//" . $image_name);
        }
        
        Product::create($data);
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
            'price' => 'required',
            'img' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'description' =>'required',
            'stock' => 'required',
            'category_id' => 'required',
        ]);

        if($validator->fails()){
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ]);
        }
        $data = [
            'name' => $request->name,
            'price' => $request->price,
            'description' => $request->description,
            'stock' => $request->stock,
            'category_id' => $request->category_id,
        ];
        if ($files = $request->file('img')) {        
            $ImageUpload = Image::make($files);
            $image_name = time().$files->getClientOriginalName();
            $data['img'] = $image_name;
            $ImageUpload->save(public_path() . "/uploads/products//" . $image_name);
        }
        
        Product::find($id)->update($data);
        return response()->json([
            'status' => 'success',
            'message' => "added successfuly"
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
        Product::find($id)->delete();

        return response()->json([
            'status' => 'success',
            'message' => "deleted successfuly"
        ]);
    }
}
