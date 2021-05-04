<?php

namespace App\Http\Controllers\APIs\V1\Rest\Stores;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Store;

use Illuminate\Support\Facades\Validator;

class StoreController extends Controller
{
    /* -------------------------------------------get all store ------------------------------------------------ */
    public function getAll()
    {
        $store = Store::get()->toJson();
        return response($store, 200);
    }

    /* ------------------------------------- create an store -------------------------------------- */
    public function create(Request $request): \Illuminate\Http\JsonResponse
    {

        $data = $request->all();
        //validator or request validator
        $validator = Validator::make($data, [
            'name' => 'required|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()], 'Validation Error');
        }

        $store = Store::create($data);
        return response()->json(["message" => "store record created"], 201);
    }


    /* -------------------------------------get one store -------------------------------------- */
    public function getOne($id)
    {
        if (Store::where('id', $id)->exists()) {
            $store = Store::where('id', $id)->get()->toJson();
            return response($store, 200);
        } else {
            return response()->json(["message" => "store not found"], 404);
        }
    }

    /* -------------------------------------update one store -------------------------------------- */
    public function update(Request $request, $id): \Illuminate\Http\JsonResponse
    {
        if (Store::where('id', $id)->exists()) {
            $store = Store::find($id);
            $store->name = is_null($request->name) ? $store->name : $request->name;
            $store->save();

            return response()->json(["message" => "store updated successfully"], 200);
        } else {
            return response()->json(["message" => "store not found"], 404);
        }
    }

    /* -------------------------------------delete store -------------------------------------- */
    public function delete($id): \Illuminate\Http\JsonResponse
    {
        if(Store::where('id', $id)->exists()) {
            $store = Store::find($id);
            $store->delete();
            return response()->json(["message" => "store record deleted"], 202);
        } else {
            return response()->json(["message" => "store not found"], 404);
        }
    }
    
    /* -------------------------------------search store -------------------------------------- */
    public function search(Request $request): \Illuminate\Http\JsonResponse
    {
        $search = $request->name;
        
        //return response()->json($search);

        $store = Store::query()
                    ->where("name", "LIKE", "%{$search}%")
                    ->get();
        

        return response()->json($store);
    }
}
