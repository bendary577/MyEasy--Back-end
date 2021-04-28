<?php

namespace App\Http\Controllers\APIs;

use Illuminate\Http\Request;
use App\Models\Notification;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class NotificationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getAll()
    {
        $noti = Notification::get()->toJson();
        return response($noti, 200);
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
            'content' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()], 'Validation Error');
        }

        $noti = Notification::create($data);
        return response()->json(["message" => "Notification record created"], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getOne($id)
    {
        if (Notification::where('id', $id)->exists()) {
            $noti = Notification::where('id', $id)->get()->toJson();
            return response($noti, 200);
        } else {
            return response()->json(["message" => "Notification not found"], 404);
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
        if (Notification::where('id', $id)->exists()) {
            $noti = Notification::find($id);
            $noti->content = is_null($request->content) ? $noti->content : $request->content;
            $noti->save();

            return response()->json(["message" => "Notification updated successfully"], 200);
        } else {
            return response()->json(["message" => "Notification not found"], 404);
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
        if(Notification::where('id', $id)->exists()) {
            $noti = Notification::find($id);
            $noti->delete();
            return response()->json(["message" => "Notification record deleted"], 202);
        } else {
            return response()->json(["message" => "Notification not found"], 404);
        }
    }
}
