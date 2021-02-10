<?php

namespace App\Http\Controllers\APIs;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Auth;


class AuthenticationController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    private $guard;

    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login']]);
        $this->guard = "api";
    }

     /* -------------------------------- register ------------------------------------------ */
    public function register(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        }

        $user = User::create([
            'name' => $request->get('name'),
            'email' => $request->get('email'),
            'password' => Hash::make($request->get('password')),
        ]);

        $token = JWTAuth::fromUser($user);

        return response()->json(compact('user', 'token'), 201);
    }

    /**
     * Get a JWT token via given credentials.
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function login(Request $request): JsonResponse
    {
        $credentials = $request->only('email', 'password');

        if ($token = auth($this->guard)->attempt($credentials)) {
            return $this->respondWithToken($token);
        }

        return response()->json(['error' => 'Unauthorized'], 401);
    }

    /**
     * Get the authenticated User
     *
     * @return JsonResponse
     */
    public function me(): JsonResponse
    {
        return response()->json(auth($this->guard)->user());
    }

    /**
     * Log the user out (Invalidate the token)
     *
     * @return JsonResponse
     */
    public function logout(): JsonResponse
    {
        auth($this->guard)->logout();
        return response()->json(['message' => 'Successfully logged out']);
    }

     /* -------------------------------- getUser ------------------------------------------ */
    public function getAuthenticatedUser(): JsonResponse
    {
        try {

            if (!$user = JWTAuth::parseToken()->authenticate()) {
                return response()->json(['user_not_found'], 404);
            }
        } catch (Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {

            return response()->json(['token_expired'], $e->getStatusCode());
        } catch (Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {

            return response()->json(['token_invalid'], $e->getStatusCode());
        } catch (Tymon\JWTAuth\Exceptions\JWTException $e) {

            return response()->json(['token_absent'], $e->getStatusCode());
        }

        return response()->json(compact('user'));
    }

    /**
     * Refresh a token.
     *
     * @return JsonResponse
     */
    public function refresh(): JsonResponse
    {
        return $this->respondWithToken(auth($this->guard)->refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param string $token
     *
     * @return JsonResponse
     */
    protected function respondWithToken(string $token): JsonResponse
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth($this->guard)->factory()->getTTL() * 60
        ]);
    }


    /**
     * Get the guard to be used during authentication.
     *
     * @return Guard
     */
    public function guard(): Guard
    {
        return Auth::guard();
    }

}
