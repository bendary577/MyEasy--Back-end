<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Invoice;

class InvoiceController extends Controller
{
    public function index()
    {
        return response()->json(Invoice::get(), 200);
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        $invoice = Invoice::create($request->all());
        return response()->json($invoice, 201);
    }

    public function show(Invoice $invoice)
    {
        return response()-json($invoice, 200);
    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request, Invoice $invoice)
    {
        $invoice->update($request->all());
	    return response()->json($invoice, 200);
    }

    public function destroy(Invoice $invoice)
    {
        $invoice->delete();
        return response()->json(null, 204);
    }
}
