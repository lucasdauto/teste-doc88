<?php

namespace App\Http\Controllers;

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
}
