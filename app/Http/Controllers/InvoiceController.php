<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Invoice;

class InvoiceController extends Controller
{
    public function invoice()
    {
        return response()->json(Invoice::get(), 200);
    }
}
