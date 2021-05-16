<?php

namespace App\Http\Controllers\APIs\V1\Rest\Stores;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:create product|list products|edit product|delete product', ['only' => ['getAll','getOne']]);
        $this->middleware('permission:create product', ['only' => ['create']]);
        $this->middleware('permission:edit product', ['only' => ['update']]);
        $this->middleware('permission:delete product', ['only' => ['delete']]);
    }

    /* -------------------------------------------get all products ------------------------------------------------ */
    public function getAll()
    {
        $product = Product::query()->orderByDesc('created_at')->paginate(6)->toJson();
        return response($product, 200);
    }

    /* ------------------------------------- create a product -------------------------------------- */
    public function create(Request $request): \Illuminate\Http\JsonResponse
    {

        $data = $request->all();
        //validator or request validator
        $validator = Validator::make($data, [
            'name' => 'required|max:255',
            'description' => 'required|max:255',
            'price' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()], 'Validation Error');
        }

        $product = Product::create($data);
        return response()->json(["message" => "product record created"], 201);
    }


     /* -------------------------------------get one product -------------------------------------- */
    public function getOne($id)
    {
        if (Product::where('id', $id)->exists()) {
            $product = Product::where('id', $id)->get()->toJson();
            return response($product, 200);
        } else {
            return response()->json(["message" => "Product not found"], 404);
        }
    }

    /* -------------------------------------update one product -------------------------------------- */
    public function update(Request $request, $id): \Illuminate\Http\JsonResponse
    {
        if (Product::where('id', $id)->exists()) {
            $product = Product::find($id);
            $product->name = is_null($request->name) ? $product->name : $request->name;
            $product->description = is_null($request->description) ? $product->description : $request->description;
            $product->description = is_null($request->description) ? $product->description : $request->description;
            $product->photo_path = is_null($request->photo_path) ? $product->photo_path : $request->photo_path;
            $product->available_number = is_null($request->available_number) ? $product->available_number : $request->available_number;
            $product->price = is_null($request->price) ? $product->price : $request->price;
            $product->status = is_null($request->status) ? $product->status : $request->status;
            $product->save();

            return response()->json(["message" => "Product updated successfully"], 200);
        } else {
            return response()->json(["message" => "Product not found"], 404);
        }
    }

    /* -------------------------------------delete product -------------------------------------- */
    public function delete($id): \Illuminate\Http\JsonResponse
    {
        if(Product::where('id', $id)->exists()) {
            $product = Product::find($id);
            $product->delete();
            return response()->json(["message" => "Product record deleted"], 202);
          } else {
            return response()->json(["message" => "Product not found"], 404);
          }
    }

    /* ------------------------------------ rate a product -------------------------------------- */
    public function rate(User $user){

    }







}

