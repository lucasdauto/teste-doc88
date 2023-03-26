<?php

namespace App\Http\Controllers;

use App\Http\Requests\CustomerRequest;
use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{

    function __construct(private Customer $customer, private Request $request)
    {
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json($this->customer::all(), 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CustomerRequest $customerRequest)
    {
        $customer = $this->customer::create($customerRequest->all());

        if ($customer)
            return response()->json($customer, 201);

        return response()->json("Customer not created", 400);
    }

    /**
     * Display the specified resource.
     */
    public function show($customer_id)
    {
        $customer = $this->customer::find($customer_id);
        if ($customer)
            return response()->json($customer, 200);

        return response()->json("Customer not found", 404);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update($id, CustomerRequest $customerRequest)
    {
        $customer = $this->customer::find($id);
        if ($customer) {
            $update = $customer->update($customerRequest->all());
            if ($update)
                return response()->json($customer, 200);

            return response()->json("Customer not updated", 400);
        }

        return response()->json("Customer not found", 404);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $customer = $this->customer::find($id);
        if ($customer) {
            $delete = $customer->delete();
            if ($delete)
                return response()->json("Customer deleted", 204);

            return response()->json("Customer not deleted", 400);
        }

        return response()->json("Customer not found", 404);
    }
}
