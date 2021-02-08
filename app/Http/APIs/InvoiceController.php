<?php

namespace App\Http\APIs;

use Illuminate\Http\Request;
use App\Models\Invoice;
use APP\HTTP\Controller;

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

    public function show($id)
    {
        $invoice = Invoice::find($id);
        return response()-json($invoice, 200);
    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        $invoice = Invoice::find($id);
        $invoice->update($request->all());
	    return response()->json($invoice, 200);
    }

    public function destroy($id)
    {
        $invoice = Invoice::find($id);
        $invoice->delete();
        return response()->json(null, 204);
    }
}
