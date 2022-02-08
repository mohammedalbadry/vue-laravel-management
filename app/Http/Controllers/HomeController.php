<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Clint;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $clints_count = Clint::count();
        $products_count = Product::count();
        $orders_count = OrderItem::count();
        $categories_count = Category::count();

        return response()->json([
            'status' => 'success',
            'clints_count' => $clints_count,
            'products_count' => $products_count,
            'orders_count' => $orders_count,
            'categories_count' => $categories_count,
        ]);
    }
}
