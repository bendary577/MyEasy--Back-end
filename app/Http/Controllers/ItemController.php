<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;

class ItemController extends Controller
{
    public function index()
    {
        return response()->json(Item::get(), 200);
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        $item = Item::create($request->all());
        return response()->json($item, 201);
    }

    public function show(Item $item)
    {
        return response()-json($item, 200);
    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request, Item $item)
    {
        $item->update($request->all());
	    return response()->json($item, 200);
    }

    public function destroy(Item $item)
    {
        $item->delete();
        return response()->json(null, 204);
    }
}
