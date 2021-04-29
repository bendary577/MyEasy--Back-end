<?php

namespace App\Http\Controllers\APIs\V1\Rest\Accounts;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Notification;
use Illuminate\Support\Facades\Validator;

class NotificationController extends Controller
{

    public function getAll()
    {
        $noti = Notification::get()->toJson();
        return response($noti, 200);
    }


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


    public function getOne($id)
    {
        if (Notification::where('id', $id)->exists()) {
            $noti = Notification::where('id', $id)->get()->toJson();
            return response($noti, 200);
        } else {
            return response()->json(["message" => "Notification not found"], 404);
        }
    }


    public function update($request, $id)
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
