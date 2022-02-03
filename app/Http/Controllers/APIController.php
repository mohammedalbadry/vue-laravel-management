<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Clint;
use Illuminate\Http\Request;

class APIController extends Controller
{
    public function category_with_products()
    {
        $data = Category::with('products')->orderBy('id', 'DESC')->get();

        return response()->json([
            'status' => 'success',
            'data' => $data,
        ]);
    }

    public function clint_search(Request $request)
    {

        $clint = $request->clint;
        
        $data = Clint::where('name', 'like', '%' . $clint . '%')
                      ->orWhere('phone', 'like', '%' . $clint . '%')
                      ->orderBy('id', 'DESC')->take(8)->get();

        return response()->json([
            'status' => 'success',
            'data' => $data,
        ]);
    }
}
