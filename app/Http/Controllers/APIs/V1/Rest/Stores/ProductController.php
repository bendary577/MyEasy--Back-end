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
        /*
        $this->middleware('permission:create product|list products|edit product|delete product', ['only' => ['getAll','getOne']]);
        $this->middleware('permission:create product', ['only' => ['create']]);
        $this->middleware('permission:edit product', ['only' => ['update']]);
        $this->middleware('permission:delete product', ['only' => ['delete']]);
        */
    }

    /* -------------------------------------------get all products ------------------------------------------------ */
    public function getAll()
    {
        $products = Product::all();
        return response([
            'message'   => 'Return All Products',
            'data'      => $products
        ], 200);
    }

    /* ------------------------------------- get products by store -------------------------------------- */
    public function get_product_store($id){
        $products = Product::where('store_id', $id)->get();
        if($products){
            return response([
                'message'   => 'Return Products By Store',
                'data'      => $products
            ], 200);
        }else{
            return response(['message' => 'No Products In This Store'],404);
        }
    }

    /* ------------------------------------- create a product -------------------------------------- */
    public function create(Request $request)
    {
        $data = $request->all();
        //validator or request validator
        $validator = Validator::make($data, [
            'name' => 'required|max:255',
            'description' => 'required|max:255',
            'price' => 'required'
        ]);

        if ($validator->fails()) {
            return response(['message' => $validator->errors()], 'Validation Error');
        }
        $product = new Product;
        $product->name              = $data['name'];
        $product->store_id          = $data['store_id'];
        $product->description       = $data['description'];
        $product->photo_path        = $data['photo_path'];
        $product->price             = $data['price'];
        $product->available_number  = $data['available_number'];
        $product->status            = $data['status'];
        $product->save();
        
        return response(['message' => 'Product Record Created'], 201);
        Product::create([
            'name'              => $data['name'],
            'store_id'          => $data['store_id'],
            'description'       => $data['description'],
            'photo_path'        => $data['photo_path'],
            'price'             => $data['price'],
            'available_number'  => $data['available_number'],
            'status'            => $data['status']
        ]);

        return 0;
        return response(['message' => 'Product Record Created'], 201);
    }

    /* -------------------------------------get one product -------------------------------------- */
    public function getOne($id)
    {
        if (Product::where('id', $id)->exists()) {
            $product = Product::where('id', $id)->first();
            return response([
                'message'   => 'Return One Product',
                'data'      => $product
            ], 200);
        } else {
            return response(["message" => "Product not found"], 404);
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

            return response(["message" => "Product updated successfully"], 200);
        } else {
            return response(["message" => "Product not found"], 404);
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







}
