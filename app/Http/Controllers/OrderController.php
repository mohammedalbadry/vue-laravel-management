<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $data = Order::when($request->search, function($query) use ($request){
            return $query->where('status', 'like', '%' . $request->search . '%');
        })->with('clint', 'items')->orderBy('id', 'DESC')->paginate(10);

        return response()->json([
            'status' => 'success',
            'data' => $data,
        ]);
    }

    
    public function store(Request $request)
    {
                $validator = Validator::make($request->all(), [
            'total' => 'required',
            'discount' => 'nullable',
            'note' => 'nullable',

            'clint_id' => 'required|integer',

            'order_items' => 'required|array',

            "order_items.*.*.id"  => "required",
            "order_items.*.*.quantity"  => "required|integer",
            "order_items.*.*.name"  => "required",
            "order_items.*.*.itemPrice"  => "required",
            "order_items.*.*.price"  => "required",

        ]);

        if($validator->fails()){
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ]);
        }

        $order = Order::create([
            'total' => $request->total,
            'clint_id' => $request->clint_id,
            'employee_id' => auth()->user()->id,
            'code' =>  Str::random(5) . "-" . substr(time(),4),
            'discount' => $request->discount == "null" ?  null: $request->discount,
            'note' => $request->note == "null" ?  null: $request->note,
        ]);

        $allItem = json_decode( $request->order_items[0] );
        for ($i=0; $i < count($allItem);  $i++) { 

            $item = $allItem[$i];    
            OrderItem::create([
                'order_id' =>  $order->id,
                'quantity' => $item->quantity,
                'name' => $item->name,
                'itemPrice' => $item->itemPrice,
                'price' => $item->price,
                'product_id' => $item->id,
            ]);
        }

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
        $data = Order::where('id',$id)->with('clint', 'items')->first();

        return response()->json([
            'status' => 'success',
            'data' => $data,
        ]);

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
            'total' => 'required',
            'discount' => 'nullable',
            'note' => 'nullable',
            'order_items' => 'required|array',

            'status' => "required",

            "order_items.*.*.id"  => "required",
            "order_items.*.*.quantity"  => "required|integer",
            "order_items.*.*.name"  => "required",
            "order_items.*.*.itemPrice"  => "required",
            "order_items.*.*.price"  => "required",

        ]);

        if($validator->fails()){
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ]);
        }

        $order = Order::find($id)->update([
            'total' => $request->total,
            'status' => $request->status,
            'discount' => $request->discount == "null" ?  null: $request->discount,
            'note' => $request->note == "null" ?  null: $request->note,
        ]);

        $oldItem = Order::find($id)->items;
        foreach ($oldItem as $item) {
            $item->delete();
        }

        $allItem = json_decode( $request->order_items[0] );

        for ($i=0; $i < count($allItem);  $i++) { 

            $item = $allItem[$i];

            OrderItem::create([
                'order_id' =>  $id,
                'quantity' => $item->quantity,
                'name' => $item->name,
                'itemPrice' => $item->itemPrice,
                'price' => $item->price,
                'product_id' => $item->product_id,
            ]);
        }

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
            Order::find($id)->delete();

            return response()->json([
                'status' => 'success',
                'message' => "deleted successfuly"
            ]);
        }
}
