<?php

namespace App\Http\Controllers\APIs\V1\SMS;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SMSController extends Controller
{
    public function sendSmsToMobile()
    {
        $basic  = new \Nexmo\Client\Credentials\Basic('d6ca9dfa', 'bwSgRxdhr45kjJlQ');
        $client = new \Nexmo\Client($basic);
        
        $message = $client->message()->send([
            'to' => '201159747840',
            'from' => 'Herry',
            'text' => 'SMS notification sent using Vonage SMS API'
        ]);
        
        return 0;
        dd('SMS has sent.');
    }
}
