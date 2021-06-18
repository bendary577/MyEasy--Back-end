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
use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{

    public function __construct()
    {
        /*
        $this->middleware('permission:create order|list orders|edit order|delete order', ['only' => ['getAll','getOne']]);
        $this->middleware('permission:create order', ['only' => ['create']]);
        $this->middleware('permission:edit order', ['only' => ['update']]);
        $this->middleware('permission:delete order', ['only' => ['delete']]);
        */
    }

    /* -------------------------------------------get all Orders ------------------------------------------------ */
    public function getAll()
    {
        $arr = [];
        
        // Auth::user()->id
        $orders = Order::where('user_id', '1')->get();
        
        foreach($orders as $order){
            array_push($arr, [
                'order_id'=> $order->id,
                'total'     => $order->total,
                'date'      => $order->created_at,
            ]);
        }
        
        return response([
            'message'   => 'Return All Orders',
            'data'      => $arr
        ]);
    }

    /* ------------------------------------- Get One Order ---------------------------------------- */
    public function get_order($id){
        $order = Order::find($id);
        
        $arr = [
            'order_code'=> $order->id,
            'user_id'   => $order->user_id,
            'state'     => $order->state,
            'address'   => $order->address,
            'total'     => $order->total,
            'Creation'  => $order->created_at,
            'products'  => json_decode($order->products)
        ];
        
        return response([
            'message'   => 'Return One Order',
            'data'      => $arr
        ]);
    }

    /* ------------------------------------- create an Order -------------------------------------- */
    public function create(Request $request)
    {
        $arr = [];
        $tot = 0;
        
        // Auth::user()->id
        $cart = Cart::where('user_id', '1')->get();
        // return $cart;
        if(count($cart) === 0){
            return response(['message' => 'Cart Is Empty']);
        }

        foreach($cart as $c){
            $pro = Product::find($c->product_id);
            array_push($arr, [
                'product_id'=> $pro->id,
                'name'      => $pro->name,
                'price'     => $c->price,
                'quantity'  => $c->quantity,
                'total'     => $c->total,
            ]);
            $tot = $tot + $c->total;
            Cart::find($c->id)->delete();
        }

        // return $tot;

        Order::create([
            'total'     => $tot,
            'user_id'   => '1',//Auth::user()->id,
            'products'  => json_encode($arr),
            'state'     => 'pending',
        ]);

        //notify seller than a customer make a new order to one of his products
        // Event::fire(new NewOrderEvent($order));

        return response(["message" => "order made successfully"], 201);
    }



    /* -------------------------------------update one order -------------------------------------- */
    /*
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
    */
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
    public function delete($id)
    {
        if(Order::where('id', $id)->exists()) {
            $order = Order::find($id);
            $order->delete();
            // Event::fire(new OrderCanceledEvent($order));
            return response(["message" => "Order record deleted"], 202);
        } else {
            return response(["message" => "Order not found"], 404);
        }
    }
}
