<?php

namespace App\Http\Controllers\APIs;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\Order;

class OrderController extends Controller
{
    public function sent(){
        Mail::to('mosadmohamed3@gmail.com')->send(new Order());
        return redirect('/');
    }
}
