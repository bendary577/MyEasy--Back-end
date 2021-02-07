<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Seller;

class SellerController extends Controller
{
    public function index()
    {
        return response()->json(Seller::get(), 200);
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        $seller = Seller::create($request->all());
        return response()->json($seller, 201);
    }

    public function show(Seller $seller)
    {
        return response()-json($seller, 200);
    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request, Seller $seller)
    {
        $seller->update($request->all());
	    return response()->json($seller, 200);
    }

    public function destroy(Seller $seller)
    {
        $seller->delete();
        return response()->json(null, 204);

    }
}
