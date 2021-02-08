<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PdfController extends Controller
{
    public function download(/*$data*/)
    {
        $data = [
            'Seller'    => 'mosad',
            'Customer'  => 'Bendary',
            'Price'     => 13.5
        ];
        $pdf = \PDF::loadView('pdf', compact('data'));
        return $pdf->download(/*$Auth::user()->name*/'username.pdf');
    }
}
