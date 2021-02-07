<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    public function register(Request $request)
    {
        $input = new User;
        $input->first_name  = $request['first_name'];
        $input->last_name   = $request['last_name'];
        $input->email       = $request['email'];
        $input->password    = $request['password'];
        $input->adress      = $request['adress'];
        $input->zipcode     = $request['zipcode'];
        $input->photo_path  = $request['photo_path'];
        $input->bio         = $request['bio'];
        $input->type        = $request['type'];
        $input->save();
        
        return route('/login');
    }
}
