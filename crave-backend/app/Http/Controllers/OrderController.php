<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    // PLACE ORDER
    public function store(Request $request)
    {
        $request->validate([
            'items'        => 'required|array',
            'subtotal'     => 'required|numeric',
            'total'        => 'required|numeric',
            'order_type'   => 'required|in:delivery,pickup'
        ]);

        $order = Order::create([
            'user_id'          => $request->user()->id ?? null,
            'status'           => 'pending',
            'order_type'       => $request->order_type,
            'subtotal'         => $request->subtotal,
            'delivery_fee'     => $request->delivery_fee ?? 0,
            'discount'         => $request->discount ?? 0,
            'total'            => $request->total,
            'promo_code'       => $request->promo_code,
            'delivery_address' => $request->delivery_address
        ]);

        foreach ($request->items as $item) {
            OrderItem::create([
                'order_id'     => $order->id,
                'menu_item_id' => $item['id'],
                'quantity'     => $item['qty'],
                'unit_price'   => $item['price'],
                'subtotal'     => $item['price'] * $item['qty']
            ]);
        }

        return response()->json([
            'message' => 'Order placed successfully',
            'order'   => $order->load('items')
        ], 201);
    }

    // GET USER ORDERS
    public function userOrders(Request $request)
    {
        $orders = Order::where('user_id', $request->user()->id)
                       ->with('items.menuItem')
                       ->latest()
                       ->get();
        return response()->json($orders);
    }

    // GET ALL ORDERS (admin)
    public function index()
    {
        $orders = Order::with('items.menuItem', 'user')
                       ->latest()
                       ->get();
        return response()->json($orders);
    }

    // UPDATE ORDER STATUS (admin)
    public function updateStatus(Request $request, $id)
    {
        $order = Order::findOrFail($id);
        $order->update(['status' => $request->status]);
        return response()->json([
            'message' => 'Order status updated',
            'order'   => $order
        ]);
    }
}