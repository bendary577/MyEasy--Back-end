<?php

namespace App\Http\Controllers\APIs;

use Illuminate\Http\Request;
use App\Models\Invoice;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class InvoiceController extends Controller
{
    /* -------------------------------------------get all Invoices ------------------------------------------------ */
    public function getAll()
    {
        $invoice = Invoice::get()->toJson();
        return response($invoice, 200);
    }

    /* ------------------------------------- create an Invoice -------------------------------------- */
    public function create(Request $request): \Illuminate\Http\JsonResponse
    {

        $data = $request->all();
        //validator or request validator
        $validator = Validator::make($data, [
            'name' => 'required|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()], 'Validation Error');
        }

        $invoice = Invoice::create($data);
        return response()->json(["message" => "invoice record created"], 201);
    }


    /* -------------------------------------get one Invoice -------------------------------------- */
    public function getOne($id)
    {
        if (Invoice::where('id', $id)->exists()) {
            $invoice = Invoice::where('id', $id)->get()->toJson();
            return response($invoice, 200);
        } else {
            return response()->json(["message" => "Invoice not found"], 404);
        }
    }

    /* -------------------------------------update one Invoice -------------------------------------- */
    public function update(Request $request, $id): \Illuminate\Http\JsonResponse
    {
        if (Invoice::where('id', $id)->exists()) {
            $invoice = Invoice::find($id);
            $invoice->price = is_null($request->price) ? $invoice->price : $request->price;
            $invoice->save();

            return response()->json(["message" => "Invoice updated successfully"], 200);
        } else {
            return response()->json(["message" => "Invoice not found"], 404);
        }
    }

    /* -------------------------------------delete Invoice -------------------------------------- */
    public function delete($id): \Illuminate\Http\JsonResponse
    {
        if(Invoice::where('id', $id)->exists()) {
            $invoice = Invoice::find($id);
            $invoice->delete();
            return response()->json(["message" => "Invoice record deleted"], 202);
        } else {
            return response()->json(["message" => "Invoice not found"], 404);
        }
    }
}
