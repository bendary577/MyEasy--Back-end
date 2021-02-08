<?php

namespace App\Http\APIs;

use Illuminate\Http\Request;
use App\Models\Seller;
use APP\HTTP\Controller;

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

    public function show($id)
    {
        $seller = Seller::find($id);
        return response()-json($seller, 200);
    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        $seller = Seller::find($id);
        $seller->update($request->all());
	    return response()->json($seller, 200);
    }

    public function destroy($id)
    {
        $seller = Seller::find($id);
        $seller->delete();
        return response()->json(null, 204);

    }
}
