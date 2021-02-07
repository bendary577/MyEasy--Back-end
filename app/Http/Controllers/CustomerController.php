<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;

class CustomerController extends Controller
{
    public function index()
    {
        return response()->json(Customer::get(), 200);
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        $customer = Customer::create($request->all());
        return response()->json($customer, 201);
    }

    public function show(Customer $customer)
    {
        return response()-json($customer, 200);
    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request, Customer $customer)
    {
        $customer->update($request->all());
	    return response()->json($customer, 200);
    }

    public function destroy(Customer $customer)
    {
        $customer->delete();
        return response()->json(null, 204);
    }
}
