<?php

namespace App\Http\Controllers\APIs\V1\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AuthenticationController extends Controller
{
    //----------------------------------- REGISTER -------------------------
    public function register(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:55',
            'email' => 'email|required|string|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'type' => 'integer',
        ]);

        if ($validatedData->fails())
        {
            return response(['errors'=>$validatedData->errors()->all()], 422);
        }

        $request['password'] = Hash::make($request->password);
        $request['remember_token'] = Str::random(10);
        $request['type'] = $request['type'] ? $request['type']  : 0;

        $user = User::create($validatedData);

        $accessToken = $user->createToken('accessToken')->accessToken;

        return response([ 'user' => $user, 'access_token' => $accessToken], 200);
    }

    //----------------------------------------- LOGIN ------------------------
    public function login(Request $request)
    {
        $validator = $request->validate([
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|min:6|confirmed',
        ]);

        if ($validator->fails())
        {
            return response(['errors'=>$validator->errors()->all()], 422);
        }

        $user = User::where('email', $request->email)->first();

        if ($user) {
            if (Hash::check($request->password, $user->password)) {
                $token = $user->createToken('accessToken')->accessToken;
                $response = ['token' => $token];
                return response($response, 200);
            } else {
                $response = ["message" => "Password mismatch"];
                return response($response, 422);
            }
        } else {
            $response = ["message" =>'User does not exist'];
            return response($response, 422);
        }
    }

    //--------------------------------------- LOGOUT ------------------------------

    public function logout (Request $request) {
        $token = $request->user()->token();
        $token->revoke();
        $response = ['message' => 'You have been successfully logged out!'];
        return response($response, 200);
    }


}
