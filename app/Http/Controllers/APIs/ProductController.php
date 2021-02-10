<?php

namespace App\Http\Controllers\APIs;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    /* -------------------------------------------get all products ------------------------------------------------ */
    public function getAll()
    {
        $product = Product::get()->toJson();
        return response($product, 200);
    }

    /* ------------------------------------- create an product -------------------------------------- */
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
    public function update(Request $request, $id)
    {
        if (Product::where('id', $id)->exists()) {
            $product = Product::find($id);
            $product->name = is_null($request->name) ? $product->name : $request->name;
            $product->course = is_null($request->course) ? $product->course : $request->course;
            $product->save();

            return response()->json(["message" => "Product updated successfully"], 200);
            } else {
            return response()->json(["message" => "Product not found"], 404);
            }
    }

    /* -------------------------------------delete product -------------------------------------- */
    public function delete($id)
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

