<?php

namespace App\Http\Controllers;

use App\Models\Locations;
use App\Models\Order;
use App\Models\OrderItems;
use App\Models\Products;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{


    public function index()
    {

        $orders = Order::with('user')->paginate(20);
        if ($orders) {
            foreach ($orders as $order) {
                foreach ($order->items as $order_items) {
                    $product = Products::where('id', $order_items->product_id)->pluck('name');
                    $order_items->product_name = $product[0];
                }
            }
            return response()->json($orders, 200);
        } else {
            return response()->json('Order not found', 400);
        }
    }

    public function show($id)
    {
        $order = Order::findOrFail($id);
        return response()->json($order, 200);
    }

    public function store(Request $request)
   
    {
        ;
        try {
            $locations = Locations::where('user_id', Auth::id());
        
            $request->validate([
                'order_items' => 'required',
                'total_price' => 'required',
                'quantity' => 'required',
                'date_of_delivery' => 'required',
            ]);

            $order = new Order();
            $order->user_id = Auth::id();
            $order->location_id = $locations->id;
            $order->total_price = $request->total_price;
            $order->date_of_delivery = $request->date_of_delivery;
            $order->save();

            foreach ($request->order_items as $order_items) {
                $item = new OrderItems();
                $item->order_id = $order->id;
                $item->price = $order_items['price'];
                $item->quantity = $order_items['quantity'];
                $item->product_id = $order_items['product_id'];
                $item->save();

                $product = Products::where('id', $order_items['product_id'])->first();
                $product->quantity = $order_items['quantity'];
                $product->save();
            }
            return response()->json('order added successsfully', 201);
        } catch (Exception $e) {
            return response()->json($e, 500);
        }
    }

    public function getOrderItems($id)
    {
        $order_items = OrderItems::where('order_id', $id)->get();
        if ($order_items) {
            foreach ($order_items as $order_item) {
                $product = Products::where('id', $order_item->product_id)->pluck('name');
                $order_item->product_name = $product[0];
            }
            return response()->json($order_items, 200);
        } else {
            return response()->json('No items found', 400);
        }
    }

    public function getUserOrders($id)
    {
        $orders = Order::where('id', $id)::with('items', function ($query) {
            $query->orderBy('created_at')->desc;
        })->get();
        if ($orders) {
            foreach ($orders->items as $order) {
                $product = Products::where('id', $order->product_id)->pluck('name');
                $order->product_name = $product[0];
            }
            return response()->json($orders, 200);
        } else {
            return response()->json('No orders found for this user', 400);
        }
    }


    public function changeOrderStaus($id, Request $request)
    {
        $order = Order::find($id);
        if ($order) {
            $order->update(['status' => $request->status]);
            return response()->json("Status changed successfully", 200);
        } else return response()->json("Order Id was not found", 400);
    }
}
