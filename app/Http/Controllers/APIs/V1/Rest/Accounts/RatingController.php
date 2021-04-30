<?php

namespace App\Http\Controllers\APIs\V1\Rest\Accounts;

use App\Http\Controllers\Controller;
use App\Models\Rating;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

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
        //validator or request validator
        $validator = Validator::make($data, [
            'rate' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()], 'Validation Error');
        }

        $rating = Rating::create($data);
        return response()->json(["message" => "Rating record created"], 201);
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
