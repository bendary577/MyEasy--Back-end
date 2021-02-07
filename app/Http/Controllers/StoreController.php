<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Store;

class StoreController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
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

    public function show(Store $store)
    {
        return response()-json($store, 200);
    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request, Store $store)
    {
        $store->update($request->all());
	    return response()->json($store, 200);
    }

    public function destroy(Store $store)
    {
        $store->delete();
        return response()->json(null, 204);
    }
}
