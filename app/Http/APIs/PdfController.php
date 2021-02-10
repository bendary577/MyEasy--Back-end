<?php

namespace App\Http\APIs;

use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PdfController extends Controller
{
    public function download(/*$data*/): \Illuminate\Http\Response
    {
        $data = [
            'Seller'    => 'mosad',
            'Customer'  => 'Bendary',
            'Price'     => 13.5
        ];
        $pdf = PDF::loadView('pdf', compact('data'));
        return $pdf->download(/*$Auth::user()->name*/'username.pdf');
    }
}
