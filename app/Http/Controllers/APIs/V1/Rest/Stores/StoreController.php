<?php

namespace App\Http\Controllers\APIs\V1\Rest\Stores;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Store;

use Illuminate\Support\Facades\Validator;

class StoreController extends Controller
{

    public function __construct()
    {
        /*
        $this->middleware('permission:create store|list stores|edit store|delete store', ['only' => ['getAll','getOne']]);
        $this->middleware('permission:create store', ['only' => ['create']]);
        $this->middleware('permission:edit store', ['only' => ['update']]);
        $this->middleware('permission:delete store', ['only' => ['delete']]);*/
    }

    /* -------------------------------------------get all store ------------------------------------------------ */
    public function getAll()
    {
        $stores = Store::all();
        return response([
            'message'   => 'Return All Stores',
            'data'      => $stores
        ], 200);
    }

    /* ------------------------------------- create an store -------------------------------------- */
    public function create(Request $request)
    {
        $data = $request->all();

        //validator or request validator
        $validator = Validator::make($data, [
            'name' => 'required|max:255',
        ]);

        if ($validator->fails()) {
            return response(['message' => $validator->errors()], 'Validation Error');
        }

        $store = Store::create([
            'name'  => $data['name'],
            'user_id'  => $data['user_id'],
            'category_id'  => $data['category_id']
        ]);
        return response(["message" => "store record created"], 201);
    }


    /* -------------------------------------get one store -------------------------------------- */
    public function getOne($id)
    {
        if (Store::where('id', $id)->exists()) {
            $store = Store::where('id', $id)->get();
            return response([
                'message'   => 'Return One Store',
                'data'      => $store
            ], 200);
        } else {
            return response(["message" => "store not found"], 404);
        }
    }

    /* -------------------------------------update one store -------------------------------------- */
    public function update(Request $request, $id)
    {
        if (Store::where('id', $id)->exists()) {
            $store = Store::find($id);
            $store->name = is_null($request->name) ? $store->name : $request->name;
            $store->save();

            return response(["message" => "store updated successfully"], 200);
        } else {
            return response(["message" => "store not found"], 404);
        }
    }

    /* -------------------------------------delete store -------------------------------------- */
    public function delete($id)
    {
        if(Store::where('id', $id)->exists()) {
            $store = Store::find($id);
            $store->delete();
            return response(["message" => "store record deleted"], 202);
        } else {
            return response(["message" => "store not found"], 404);
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
