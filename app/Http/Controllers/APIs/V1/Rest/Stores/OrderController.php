<?php

namespace App\Http\Controllers\APIs\V1\Rest\Stores;

use App\Events\MailActivateAccountRequestEvent;
use App\Events\NewOrderEvent;
use App\Events\NewOrderNotification;
use App\Events\OrderCanceledEvent;
use App\Events\OrderDeliveredEvent;
use App\Events\OrderStatusChangedEvent;
use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{

    public function __construct()
    {
        $this->middleware('permission:create order|list orders|edit order|delete order', ['only' => ['getAll','getOne']]);
        $this->middleware('permission:create order', ['only' => ['create']]);
        $this->middleware('permission:edit order', ['only' => ['update']]);
        $this->middleware('permission:delete order', ['only' => ['delete']]);
    }

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


        $date = [
            'customer_name' => $request->customer_name,
        ];

        $order = Order::create($data);

        //notify seller than a customer make a new order to one of his products
        Event::fire(new NewOrderEvent($order));

        return response()->json(["message" => "order made successfully"], 201);
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

            Event::fire(new OrderStatusChangedEvent($order));

            return response()->json(["message" => "Order updated successfully"], 200);
        } else {
            return response()->json(["message" => "Order not found"], 404);
        }
    }

    /* -------------------------------------------get all Orders ------------------------------------------------ */
    public function confirmOrder(Request $request, $id)
    {
        if (Order::where('id', $id)->exists()) {
            $order = Order::find($id);
            $user = Auth::user();
            if($user->profile_type == 'App\Models\CompanyProfile' || $user->profile_type == 'App\Models\SellerProfile'){
                //if seller has already confirmed the order return a message
                if($order->seller_confirm == true){
                    return response()->json(["message" => "you have already confirmed the order"], 200);
                }
                //else, confirm the order by the seller
                $order->seller_confirm = true;

                //if the order was confirmed by the customer, notify both of them and return informative message
                if($order->customer_confirm == true){
                    Event::fire(new OrderDeliveredEvent($order));
                    return response()->json(["message" => "Order delivered successfully"], 200);
                }

                //else return a message that you have successfully confirmed the order
                return response()->json(["message" => "you have successfully confirmed the order"], 200);
            }

            //if customer has already confirmed the order return a message
            if($order->customer_confirm == true){
                return response()->json(["message" => "you have already confirmed the order"], 200);
            }
            //else, confirm the order by the seller
            $order->customer_confirm = true;

            //if the order was confirmed by the seller, notify both of them and return informative message
            if($order->seller_confirm == true){
                Event::fire(new OrderDeliveredEvent($order));
                return response()->json(["message" => "Order delivered successfully"], 200);
            }

            //else return a message that you have successfully confirmed the order
            return response()->json(["message" => "you have successfully confirmed the order"], 200);

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
            Event::fire(new OrderCanceledEvent($order));
            return response()->json(["message" => "Order record deleted"], 202);
        } else {
            return response()->json(["message" => "Order not found"], 404);
        }
    }
}
