<?php

namespace App\Http\APIs;

use Illuminate\Http\Request;
use App\Models\Item;
use App\Http\Controllers\Controller;

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
        $item = new Item;
        $student->name = $request->name;
        $student->course = $request->course;
        $student->save();
        return response()->json([
            "message" => "item record created"
        ], 201);
    }

    public function show($id)
    {
        $item = Item::find($id);
        return response()-json($item, 200);
    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        $item = Item::find($id);
        $item->update($request->all());
	    return response()->json($item, 200);
    }

    public function destroy($id)
    {
        $item = Item::find($id);
        $item->delete();
        return response()->json(null, 204);
    }
}
