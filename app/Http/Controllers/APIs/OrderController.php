<?php

namespace App\Http\Controllers\APIs;

use App\Events\NewOrderNotification;
use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{
    /* -------------------------------------------get all Orders ------------------------------------------------ */
    public function getAll()
    {
        $order = Order::get()->toJson();
        return response($order, 200);
    }

    /* ------------------------------------- create an Order -------------------------------------- */
    public function create(Request $request): \Illuminate\Http\JsonResponse
    {

        $data = $request->all();
        //validator or request validator
        $validator = Validator::make($data, [
            'customer_name' => 'required|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()], 'Validation Error');
        }

        $order = Order::create($data);

        $date = [
            'customer_name' => $request->customer_name,
        ];

        event(new NewOrderNotification($data));
        return response()->json(["message" => "Order record created"], 201);
    }


    /* -------------------------------------get one Order -------------------------------------- */
    public function getOne($id)
    {
        if (Order::where('id', $id)->exists()) {
            $order = Order::where('id', $id)->get()->toJson();
            return response($order, 200);
        } else {
            return response()->json(["message" => "Order not found"], 404);
        }
    }

    /* -------------------------------------update one order -------------------------------------- */
    public function update(Request $request, $id): \Illuminate\Http\JsonResponse
    {
        if (Order::where('id', $id)->exists()) {
            $order = Order::find($id);
            $order->status = is_null($request->status) ? $order->status : $request->status;
            $order->save();

            return response()->json(["message" => "Order updated successfully"], 200);
        } else {
            return response()->json(["message" => "Order not found"], 404);
        }
    }

    /* -------------------------------------delete order -------------------------------------- */
    public function delete($id): \Illuminate\Http\JsonResponse
    {
        if(Order::where('id', $id)->exists()) {
            $order = Order::find($id);
            $order->delete();
            return response()->json(["message" => "Order record deleted"], 202);
        } else {
            return response()->json(["message" => "Order not found"], 404);
        }
    }
}
