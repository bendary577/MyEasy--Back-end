<?php

namespace App\Http\Controllers\APIs;

use Illuminate\Http\Request;
use App\Models\Ratings;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class RatingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getAll()
    {
        $rating = Rating::get()->toJson();
        return response($rating, 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
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

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getOne($id)
    {
        if (Rating::where('id', $id)->exists()) {
            $rating = Rating::where('id', $id)->get()->toJson();
            return response($rating, 200);
        } else {
            return response()->json(["message" => "Rating not found"], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
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

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
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
