<?php

namespace App\Http\APIs;

use Illuminate\Http\Request;
use App\Models\Item;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class ItemController extends Controller
{
    /* -------------------------------------------get all items ------------------------------------------------ */
    public function getAll()
    {
        $items = Item::get()->toJson();
        return response($items, 200);
    }

    /* ------------------------------------- create an item-------------------------------------- */
    public function create(Request $request)
    {

        $data = $request->all();
        //validator or request validator
        $validator = Validator::make($data, [
            'name' => 'required|max:255',
            'description' => 'required|max:255',
            'price' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()], 'Validation Error');
        }

        $item = Item::create($data);
        return response()->json(["message" => "item record created"], 201);
    }


     /* -------------------------------------get one item -------------------------------------- */
    public function getOne($id)
    {
        if (Item::where('id', $id)->exists()) {
            $item = Item::where('id', $id)->get()->toJson();
            return response($item, 200);
          } else {
            return response()->json(["message" => "item not found"], 404);
          }
    }

    /* -------------------------------------update one item -------------------------------------- */
    public function update(Request $request, $id)
    {
        if (Item::where('id', $id)->exists()) {
            $item = Item::find($id);
            $item->name = is_null($request->name) ? $item->name : $request->name;
            $item->course = is_null($request->course) ? $item->course : $request->course;
            $item->save();
    
            return response()->json(["message" => "item updated successfully"], 200);
            } else {
            return response()->json(["message" => "item not found"], 404);
            }
    }

    /* -------------------------------------delete item -------------------------------------- */
    public function delete($id)
    {
        if(Item::where('id', $id)->exists()) {
            $item = Item::find($id);
            $item->delete();
            return response()->json(["message" => "record deleted"], 202);
          } else {
            return response()->json(["message" => "item not found"], 404);
          }
    }

}

