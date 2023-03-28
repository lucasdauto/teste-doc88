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
        return response()->json('foi', 201);
    }
}
