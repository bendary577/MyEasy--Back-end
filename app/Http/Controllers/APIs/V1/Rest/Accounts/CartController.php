<?php

namespace App\Http\Controllers\APIs\V1\Rest\Accounts;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CartController extends Controller
{

    public function __construct()
    {
        $this->middleware('permission:add to cart|list carts|edit cart|remove from cart', ['only' => ['getAll','getOne']]);
        $this->middleware('permission:add to cart', ['only' => ['create']]);
        $this->middleware('permission:edit cart', ['only' => ['update']]);
        $this->middleware('permission:remove from cart', ['only' => ['delete']]);
    }

    public function getAll($id)
    {
        $carts = Cart::query()->where('customer', $id)->orderByDesc('created_at')->paginate(6)->toJson();
        return response($carts, 200);
    }


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


    public function update($request, $product)
    {
        if (Cart::where('product', $product)->exists()) {
            $cart = cart::query()->where('product', $product);
            $cart->num = is_null($request->num) ? $cart->num : $request->num;
            $cart->save();

            return response()->json(["message" => "Cart updated successfully"], 200);
        } else {
            return response()->json(["message" => "Cart not found"], 404);
        }
    }


    public function destroy($id)
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
