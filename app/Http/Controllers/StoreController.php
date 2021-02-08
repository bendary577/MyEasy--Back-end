<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Store;

class StoreController extends Controller
{
    public function index()
    {
        return response()->json(Store::get(), 200);
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        $store = Store::create($request->all());
        return response()->json($store, 201);
    }

    public function show($id)
    {
        $store = Store::find($id);
        return response()-json($store, 200);
    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        $store = Store::find($id);
        $store->update($request->all());
	    return response()->json($store, 200);
    }

    public function destroy($id)
    {
        $store = Store::find($id);
        $store->delete();
        return response()->json(null, 204);
    }
}
