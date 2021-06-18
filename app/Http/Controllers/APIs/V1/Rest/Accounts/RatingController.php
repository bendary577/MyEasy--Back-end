<?php

namespace App\Http\Controllers\APIs\V1\Rest\Accounts;

use App\Http\Controllers\Controller;
use App\Models\Rating;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Auth;

class RatingController extends Controller
{

    public function getAll()
    {
        $rating = Rating::get()->toJson();
        return response($rating, 200);
    }


    public function Create(Request $request)
    {
        $data = $request->all();
        $avg = 0;
        
        //validator or request validator
        $validator = Validator::make($data, [
            'rate' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()], 'Validation Error');
        }
        
        if($data['rate']>5 || $data['rate']<0){
            return response(['message' => 'Rating Must Be Between 0 and 5']);
        }

        $product = Product::find($data['product_id']);
        if(!$product){
            return response(['message' => 'Product NotFound']);
        }
        $rating = Rating::create([
            'user_id'   => Auth::user()->id,
            'product_id'=> $product->id,
            'rate'      => $data['rate']
        ]);

        $num = 1 + $product->ratings_number;
        $product->ratings_number = $num;
        $product->save();
        
        $rates = Rating::where('product_id', $data['product_id'])->get();
        foreach($rates as $rate){
            $avg = $avg + $rate->rate;
        }
        $product->rating = $avg / $num;
        $product->save();
        
        return response(["message" => "Rating record created"], 201);
    }


    public function getOne($id)
    {
        if (Rating::where('id', $id)->exists()) {
            $rating = Rating::where('id', $id)->get()->toJson();
            return response($rating, 200);
        } else {
            return response()->json(["message" => "Rating not found"], 404);
        }
    }

    public function update(Request $request, $id)
    {
        if (Rating::where('id', $id)->exists()) {
            $rating = Rating::find($id);
            $rating->rate = is_null($request->rate) ? $rating->rate : $request->rate;
            $rating->save();

            return response()->json(["message" => "Rating updated successfully"], 200);
        } else {
            return response()->json(["message" => "Rating not found"], 404);
        }
    }


    public function destroy($id)
    {
        if(Rating::where('id', $id)->exists()) {
            $rating = Rating::find($id);
            $rating->delete();
            return response()->json(["message" => "Rating record deleted"], 202);
        } else {
            return response()->json(["message" => "Rating not found"], 404);
        }
    }
}
