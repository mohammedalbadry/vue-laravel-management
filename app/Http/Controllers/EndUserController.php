<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class EndUserController extends Controller
{
    public function index($id){
        
        $data = Order::where('code', $id)->with('clint', 'items')->first();

        if($data != null){
            return response()->json([
                'status' => 'success',
                'data' => $data,
            ]);
        }
        return response()->json([
            'status' => 'error',
            'data' => null,
            'message' => "make sure to write the correct ID"
        ]);
        
    }
}
