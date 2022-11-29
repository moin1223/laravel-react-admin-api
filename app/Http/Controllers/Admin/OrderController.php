<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Orderitem;
use App\Models\User;
use Illuminate\Http\Request;

class OrderController extends Controller
{

    public function index()
    {

        $order = User::with('orders')->paginate(2);
//  $orders = Order::query()->orderByDesc('id')->paginate(5);
        return response()->json($order);
    }

    public function store(Request $request)
    {
        try {

            $user_id = $request->user_id;
            $date = $request->date;

            $order = Order::create([
                'user_id' => $user_id,
                'date' => $date,

            ]);

            $order_id = Order::latest()->first()->id;
            $order_items = $request->order_items;
            foreach ($order_items as $product) {
                $order_item = Orderitem::create([

                    'order_id' => $order_id,
                    'product_name' => $product['title'],
                    'product_price' => $product['price'],

                    // 'order_id' =>$order_id,
                    // 'product_name' =>'apple',
                    // 'product_price'=>10,

                ]);
            }

            return response()->json("Order Submitted!");
        } catch (Exception $e) {
        }
    }

    public function orderDetails(Request $request)
    {

        $id = $request->order_id;
        $orderDetails = Order::find($id)->items;
        //  $user = Order::find($id)->user;

        return response()->json([
            "orderDetails" => $orderDetails,
            // "user"=>$user,

        ]);

    }

// test
    public function testOrder(Request $request)
    {
        $data = $request;

        return response()->json($data);
    }

}
