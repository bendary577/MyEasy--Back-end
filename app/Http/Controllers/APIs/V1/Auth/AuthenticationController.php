<?php

namespace App\Http\Controllers\APIs\V1\Auth;

use App\Http\Controllers\Controller;
use App\Models\AdminProfile;
use App\Models\CompanyProfile;
use App\Models\CustomerProfile;
use App\Models\PasswordReset;
use App\Models\SellerProfile;
use App\Models\User;
use App\Notifications\MailActivateAccountRequestNotification;
use App\Notifications\MailPasswordResetSuccessNotification;
use App\Notifications\MailResetPasswordRequestNotification;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
                                  'list orders', 'create product', 'list products', 'edit product', 'delete product', 'list orders',
                                  'edit order', 'delete order', 'create invoice', 'list invoices', 'edit invoice', 'delete invoice'];

    public $customer_permissions = ['list categories', 'list stores','rate store', 'list products','rate product', 'create order',
                                    'create order','list orders','edit order', 'delete order', 'add to cart', 'list carts', 'edit cart',
                                    'remove from cart','create complaint', 'list complaints', 'edit complaint'];

    //----------------------------------- REGISTER -------------------------
    public function register(Request $request)
    {
        //validate returned data from registration request
        $validatedData = Validator::make($request->all(),[
            'first_name' => 'required|string|max:55',
            'second_name' => 'required|string|max:55',
            'email' => 'email|required|string|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'type' => 'integer',
            'phone_number' => 'required|string|min:11|max:11'
        ]);

        //check data validation
        if ($validatedData->fails())
        {
            return response()->json(['message'=>$validatedData->errors()->all()], $this->unauthorizedCode);
        }

        $request['password'] = Hash::make($request->password);
        $request['remember_token'] = Str::random(10);

        /*
         check request user type
         type 0 === admin
         type 1 === customer
         type 2 === seller individual
         type 3 === company
        */

        //create user object and fill basic info
        $user = new User;
        $user->first_name = $request['first_name'];
        $user->second_name = $request['second_name'];
        $user->email = $request['email'];
        $user->password = $request['password'];
        $user->phone_number = $request['phone_number'];
        $user->address = $request['address'];
        $user->zipcode = $request['zipcode'];
        $user->activation_token = Str::random(60);

        if($request['type'] == 0){
            //create admin profile
            $profile = new AdminProfile;
            $profile->admin_name = $request['first_name'];
            $profile->user()->save($user);

            //create admin roles and permissions
            $role = Role::create(['name' => 'admin']);
            $user->assignRole($role);
            foreach ($this->admin_permissions as $admin_permission) {
                $permission = Permission::create(['name' => $admin_permission]);
                $role->givePermissionTo($permission);
            }
        }else if($request['type'] == 1){
            //create customer profile
            $profile = new CustomerProfile;
            $profile->gender = $request['gender'];
            $profile->birth_date = $request['birth_date'];
            $profile->user()->save($user);
            //create customer roles and permissions
            $role = Role::create(['name' => 'customer']);
            $user->assignRole($role);
            foreach ($this->customer_permissions as $customer_permission) {
                $permission = Permission::create(['name' => $customer_permission]);
                $role->givePermissionTo($permission);
            }
        }else if($request['type'] == 2){
            //create seller profile
            $profile = new SellerProfile;
            $profile->gender = $request['gender'];
            $profile->specialization = $request['specialization'];
            $profile->has_store = false;
            $profile->customers_number = 0;
            $profile->orders_number = 0;
            $profile->user()->save($user);
            //create seller roles and permissions
            $role = Role::create(['name' => 'seller']);
            $user->assignRole($role);
            foreach ($this->seller_permissions as $seller_permission) {
                $permission = Permission::create(['name' => $seller_permission]);
                $role->givePermissionTo($permission);
            }
        }else{
            //create company profile
            $profile = new CompanyProfile;
            $profile->specialization = $request['specialization'];
            $profile->has_store = false;
            $profile->customers_number = 0;
            $profile->orders_number = 0;
            $profile->user()->save($user);
            //create seller roles and permissions
            $role = Role::create(['name' => 'seller']);
            $user->assignRole($role);
            foreach ($this->seller_permissions as $seller_permission) {
                $permission = Permission::create(['name' => $seller_permission]);
                $role->givePermissionTo($permission);
            }
        }

        //return jwt access token
        //$accessToken = $user->createToken('accessToken')->accessToken;
        //return response(['user' => $user, 'access_token' => $accessToken], $this->successCode);

        $user->save();

        //store avatar temporariy in project storage
        $avatar = Avatar::create($user->name)->getImageObject()->encode('png');
        Storage::disk('s3')->put('avatars/'.$user->id.'/', file_get_contents($avatar));

        $user->notify(new MailActivateAccountRequestNotification($user));

        $oClient = OClient::where('password_client', 1)->first();
        return $this->getTokenAndRefreshToken($oClient, $user->email, $user->password);
    }


    //------------------------------------------ ACTIVATE ACCOUNT ------------
    public function activate($token)
    {
        $user = User::where('activation_token', $token)->first();

        if (!$user) {
            return response()->json([
                'message' => 'This activation token is invalid.'
            ], $this->notFoundCode);
        }
        $user->account_activated = true;
        $user->activation_token = '';
        $user->save();
        return response()->json(['message'=>"account was activated successfully", 'user'=>$user], $this->createdCode);
    }

    //----------------------------------------- LOGIN ------------------------
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|min:6|confirmed',
        ]);

        if ($validator->fails())
        {
            return response()->json(['errors'=>$validator->errors()->all()], $this->unprocessableCode);
        }

        $user = User::where('email', $request->email)->first();

        if ($user) {
            if($user->is_blocked){
                return response()->json(['message'=>"sorry, your account is blocked"], $this->unprocessableCode);
            }else if (Hash::check($request->password, $user->password)) {
                //$token = $user->createToken('accessToken')->accessToken;
                //$response = ['message' =>"user has returned successfully", 'token' => $token];
                //return response($response, $this->successCode);
                $oClient = OClient::where('password_client', 1)->first();
                return $this->getTokenAndRefreshToken($oClient, request('email'), request('password'));
            } else {
                return response()->json(["message" => "Password mismatch"], $this->unauthorizedCode);
            }
        } else {
            return response()->json(["message" =>'User does not exist'], $this->unprocessableCode);
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
            $user->notify(new MailResetPasswordRequestNotification($passwordReset->token));
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

        $user->notify(new MailPasswordResetSuccessNotification($passwordReset));
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
