<?php

namespace App\Http\Controllers\APIs\V1\Auth;

use App\Events\MailActivateAccountRequestEvent;
use App\Events\MailCompanyRegisteredVerificationEvent;
use App\Events\MailPasswordResetSuccessEvent;
use App\Events\MailResetPasswordRequestEvent;
use App\Events\UserAccountActivatedEvent;
use App\Http\Controllers\Controller;
use App\Models\AdminProfile;
use App\Models\CompanyProfile;
use App\Models\CustomerProfile;
use App\Models\PasswordReset;
use App\Models\SellerProfile;
use App\Models\User;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Laravel\Passport\Client as OClient;
use Laravolt\Avatar\Avatar;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class AuthenticationController extends Controller
{

    public $successCode = 200;
    public $createdCode = 201;
    public $unauthorizedCode = 401;
    public $unprocessableCode = 422;
    public $notFoundCode = 404;

    public $admin_permissions = ['create category','list categories','edit category', 'delete category',
                                 'list stores','delete store', 'list products', 'list orders', 'edit complaint',
                                 'delete complaint', 'list complaints'];

    public $seller_permissions = ['list categories', 'create store','list stores','edit store', 'delete store', 'list products',
                                  'list orders', 'edit order', 'create product', 'list products', 'edit product', 'delete product', 'list orders',
                                  'edit order', 'delete order', 'create invoice', 'list invoices', 'edit invoice', 'delete invoice'];

    public $customer_permissions = ['list categories', 'list stores','rate store', 'list products','rate product', 'create order',
                                    'create order','list orders','delete order', 'add to cart', 'list carts', 'edit cart',
                                    'remove from cart','create complaint', 'list complaints', 'edit complaint'];

    //----------------------------------- REGISTER -------------------------
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'second_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'type' => 'integer',
            'phone_number' => 'required|string|min:11|max:11'
        ]);
        if ($validator->fails())
        {
            return response(['messsge' => $validator->errors()->all()], 422);
        }
        $request['password']=Hash::make($request['password']);
        $request['remember_token'] = Str::random(10);
        // $user = User::create($data);
        
        $user = new User;
        $user->first_name   = $request['first_name'];
        $user->second_name  = $request['second_name'];
        $user->email        = $request['email'];
        $user->password     = $request['password'];
        $user->phone_number = $request['phone_number'];
        $user->address      = $request['address'];
        $user->photo_path   = '1.jpg'/*$request['photo']*/;
        $user->bio          = $request['bio'];
        $user->type         = $request['type'];
        $user->zipcode      = $request['zipcode'];
        $user->activation_token = Str::random(60);
        
        switch ($request->type) {
            case 0:
                // Admin
                $profile = AdminProfile::create([
                    'name'  => 'Admin'
                ]);
                $profile->user()->save($user);
                break;
            
            case 1:
                // Customer
                $profile = CustomerProfile::create([
                    'gender'        => $request['gender'],
                    'orders_number' => 0,
                    'birth_date'    => $request['birth_date']
                ]);
                $profile->user()->save($user);
                break;
            
            case 2:
                // Seller
                $profile = SellerProfile::create([
                    'customers_number'  => 0,
                    'orders_number'     => 0,
                    'delivery_speed'    => 0,
                    'has_store'         => 0,
                    'birth_date'        => $request['birth_date'],
                    'gender'            => $request['gender'],
                    'badge'             => $request['badge'],
                    'specialization'    => $request['specialization'],
                ]);
                $profile->user()->save($user);
                break;

            case 3:
                // Company
                $profile = CompanyProfile::create([
                    'customers_number'  => 0,
                    'orders_number'     => 0,
                    'delivery_speed'    => 0,
                    'has_store'         => 0,
                    'birth_date'        => $request['birth_date'],
                    'badge'             => $request['badge'],
                    'specialization'    => $request['specialization'],
                ]);
                $profile->user()->save($user);
                break;
            default:
                return response(['message' => 'Determind Type of User.']);
                break;
        }
        /*
        $oClient = OClient::where('password_client', 1)->first();
        return $this->getTokenAndRefreshToken($oClient, $user->email, $user->password);
        */
        $token = $user->createToken('password_client')->accessToken;
        $response = ['token' => $token];
        return response($response, 200);
    }


    //------------------------------------------ ACTIVATE ACCOUNT ------------
    public function activate($token)
    {
        $user = User::where('activation_token', $token)->first();

        if (!$user) {
            return response()->json(['message' => 'This activation token is invalid.'], $this->notFoundCode);
        }
        $user->account_activated = true;
        $user->activation_token = '';
        $user->save();

        //fire an event to notify the user that his account was activated
        Event::fire(new UserAccountActivatedEvent($user));
        return response()->json(['message'=>"account was activated successfully", 'user'=>$user], $this->createdCode);
    }

    //----------------------------------------- LOGIN ------------------------
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|min:6',
        ]);
        if ($validator->fails())
        {
            return response(['errors'=>$validator->errors()->all()], 422);
        }

        $user = User::where('email', $request->email)->first();
        
        if ($user) {
            if (Hash::check($request->password, $user->password)) {
                $oClient = OClient::where('password_client', 1)->first();
                return $this->getTokenAndRefreshToken($oClient, request('email'), request('password'));
                $token = $user->createToken('Laravel Password Grant Client')->accessToken;
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
        return response()->json(['message' => 'You have been successfully logged out!'], $this->successCode);
    }

    //-------------------------------------- DETAILS ------------------------------

    public function details()
    {
        $user = Auth::user();
        return response()->json(['message' => "user details returned successfully", 'user' => $user], $this->successCode);
    }


    //------------------------------------ RESET PASSWORD REQUEST ---------------------
    public function resetPasswordRequest(Request $request)
    {
        //validate request content
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email',
        ]);

        //get user by email
        $user = User::where('email', $request['email'])->first();

        //check if user is found
        if (!$user)
            return response()->json([
                'message' => 'We can\'t find a user with that e-mail address.'
            ], $this->notFoundCode);

        //create new passwordReset object
        $passwordReset = PasswordReset::updateOrCreate(
            ['email' => $user->email],
            [
                'email' => $user->email,
                'token' => Str::random(60)
             ]
        );

        //if user and passwordReset objects are set, notify user that we sent an email to him
        if ($user && $passwordReset) {
            //fire event to notify user with mail to reset his password through a link
            Event::fire(new MailResetPasswordRequestEvent($user, $passwordReset));
        }

        //return response
        return response()->json([
            'message' => 'We have e-mailed your password reset link!',
            $this->successCode
        ]);
    }

    //------------------------------------ FIND RESET PASSWORD REQUESTS ---------------
    public function findResetPasswordRequest($token)
    {
        $passwordReset = PasswordReset::where('token', $token)->first();

        if (!$passwordReset) {
            return response()->json([
                'message' => 'This password reset token is invalid.'
            ], $this->notFoundCode);
        }

        //if password reset was sent from more than 12 hours, delete it
        if (Carbon::parse($passwordReset->updated_at)->addMinutes(720)->isPast()) {
            $passwordReset->delete();
            return response()->json([
                'message' => 'This password reset token is invalid.'
            ], $this->notFoundCode);
        }
        return response()->json(['message'=>"password reset request returned successfully", 'password reset request'=>$passwordReset], $this->successCode);
    }

    //------------------------------------- RESET PASSWORD ------------------------------
    public function resetPassword(Request $request)
    {
        $validator = Validator::make( $request->all(), [
            'email' => 'required|string|email',
            'password' => 'required|string|confirmed',
            'token' => 'required|string'
        ]);

        //get password reset request saved in database with sent info
        $passwordReset = PasswordReset::where([
            ['token', $request->token],
            ['email', $request->email]
        ])->first();

        //if no such request, return that token is invalid
        if (!$passwordReset)
            return response()->json([
                'message' => 'This password reset token is invalid.'
            ], 404);

        //else, get the desired user
        $user = User::where('email', $passwordReset->email)->first();

        if (!$user)
            return response()->json([
                'message' => 'We can\'t find a user with that e-mail address.'
            ], 404);

        $user->password = Hash::make($request->password);
        $user->save();
        $passwordReset->delete();

        //fire an event to notify the user that he successfully changed his password
        Event::fire(new MailPasswordResetSuccessEvent($user));
        return response()->json(['message'=>"password changed successfully", 'user' => $user], $this->successCode);
    }


    //------------------------------------ GET ACCESS AND REFRESH TOKENS ---------------
    public function getTokenAndRefreshToken(OClient $oClient, $email, $password) {
        $oClient = OClient::where('password_client', 1)->first();
        $http = new Client;
        $response = $http->request('POST', 'http://mylemp-nginx/oauth/token', [
            'form_params' => [
                'grant_type' => 'password',
                'client_id' => $oClient->id,
                'client_secret' => $oClient->secret,
                'username' => $email,
                'password' => $password,
                'scope' => '*',
            ],
        ]);

        $result = json_decode((string) $response->getBody(), true);
        return response()->json($result, $this->successCode);
    }



}
