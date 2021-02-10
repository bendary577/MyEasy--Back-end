<?php

namespace App\Http\Controllers\APIs;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    /* -------------------------------------------get all Users ------------------------------------------------ */
    public function getAll()
    {
        $user = User::get()->toJson();
        return response($user, 200);
    }

    /* ------------------------------------- create a User -------------------------------------- */
    public function create(Request $request): \Illuminate\Http\JsonResponse
    {

        $data = $request->all();
        //validator or request validator
        $validator = Validator::make($data, [
            'name' => 'required|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()], 'Validation Error');
        }

        $user = User::create($data);
        return response()->json(["message" => "User record created"], 201);
    }


    /* -------------------------------------get one User -------------------------------------- */
    public function getOne($id)
    {
        if (User::where('id', $id)->exists()) {
            $user = User::where('id', $id)->get()->toJson();
            return response($user, 200);
        } else {
            return response()->json(["message" => "User not found"], 404);
        }
    }

    /* -------------------------------------update one User -------------------------------------- */
    public function update(Request $request, $id): \Illuminate\Http\JsonResponse
    {
        if (User::where('id', $id)->exists()) {
            $user = User::find($id);
            $user->specialization = is_null($request->specialization) ? $user->specialization : $request->specialization;
            $user->save();

            return response()->json(["message" => "User updated successfully"], 200);
        } else {
            return response()->json(["message" => "User not found"], 404);
        }
    }

    /* -------------------------------------delete User -------------------------------------- */
    public function delete($id): \Illuminate\Http\JsonResponse
    {
        if(User::where('id', $id)->exists()) {
            $user = User::find($id);
            $user->delete();
            return response()->json(["message" => "User record deleted"], 202);
        } else {
            return response()->json(["message" => "User not found"], 404);
        }
    }
}
