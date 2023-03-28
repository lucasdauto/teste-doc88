<?php

namespace App\Http\Controllers;

use App\Http\Requests\OrderRequest;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{

    public function __construct(private Order $order, private Request $request)
    {
    }

    public function index()
    {
        return response()->json($this->order::all(), 200);
    }

    public function show(string $id)
    {
        $order = $this->order::with(['orderItems.product'])->find($id);
        return response()->json($order, 200);
    }

    public function store(OrderRequest $request)
    {
        $order = $this->order::create([
            'customer_id' => $request->customer_id,
        ]);

        foreach ($request->order_items as $item) {
            $order->orderItems()->create([
                'product_id' => $item['product_id'],
                'quantity' => $item['quantity'],
            ]);
        }

        return response()->json($order, 201);
    }

    public function destroy(string $id)
    {
        $order = $this->order::find($id);

        if ($order) {
            $delete = $order->delete();
            if ($delete) {
                return response()->json(["message" => "Order cancelled."], 204);
            }
            return response()->json(["message" => "Order not cancelled."], 500);
        }

        return response()->json(["message" => "Order not found."], 404);
    }
}
