<?php

namespace App\Http\Controllers\APIs;

use App\Models\Cart;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class CartController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getAll($id)
    {
        $carts = Cart::query()->where('customer', $id)->orderByDesc('created_at')->paginate(6)->toJson();
        return response($carts, 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $data = $request->all();
        $validator = Validator::make($data, [
            'num' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()], 'Validation Error');
        }

        $cart = Cart::create($data);
        return response()->json(["message" => "Cart record created"], 201);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $product)
    {
        if (Cart::where('product', $product)->exists()) {
            $cart = cart::query()->where('product', $product);
            $cart->num = is_null($request->num) ? $category->num : $request->num;
            $cart->save();

            return response()->json(["message" => "Cart updated successfully"], 200);
        } else {
            return response()->json(["message" => "Cart not found"], 404);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($customer, $store, $product)
    {
        if(Cart::where('id', $id)->exists()) {
            $cart = Cart::find($id);
            $cart->delete();
            return response()->json(["message" => "Cart record deleted"], 202);
          } else {
            return response()->json(["message" => "Cart not found"], 404);
          }
    }
}
