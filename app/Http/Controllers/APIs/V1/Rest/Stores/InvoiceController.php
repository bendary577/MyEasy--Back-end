<?php

namespace App\Http\Controllers\APIs\V1\Rest\Stores;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Invoice;
use Illuminate\Support\Facades\Validator;
use Auth;

class InvoiceController extends Controller
{
    public function __construct()
    {
        /*
        $this->middleware('permission:create invoice|list invoices|edit invoice|delete invoice', ['only' => ['getAll','getOne']]);
        $this->middleware('permission:create invoice', ['only' => ['create']]);
        $this->middleware('permission:edit invoice', ['only' => ['update']]);
        $this->middleware('permission:delete invoice', ['only' => ['delete']]);
        */
    }

    /* -------------------------------------------get all Invoices ------------------------------------------------ */
    public function getAll()
    {
        $invoice = Invoice::paginate(10);
        return response([
            'message'   => 'Get All Invoices',
            'data'      => $invoice
        ], 200);
    }

    /* ------------------------------------- get invoice by user ------------------------------------ */
    public function get_invoice_user()
    {
        $invoice = Invoice::where('user_id', Auth::user()->id)->paginate(10);
        return response([
            'message'   => 'Your Invoice Returned',
            'data'      => $invoice
        ], 200);
    }

    /* ------------------------------------- create an Invoice -------------------------------------- */
    public function create(Request $request)
    {

        $data = $request->all();
        //validator or request validator
        $validator = Validator::make($data, [
            'title' => 'required|max:255',
            'price' => 'required|integer'
        ]);

        if ($validator->fails()) {
            return response(['message' => $validator->errors()], 'Validation Error');
        }

        $invoice = Invoice::create([
            'user_id'   => Auth::user()->id,
            'title'     => $data['title'],
            'price'     => $data['price'],
            'expiration'=> $data['expiration']
        ]);

        return response(["message" => "invoice record created"], 201);
    }


    /* -------------------------------------get one Invoice -------------------------------------- */
    public function getOne($id)
    {
        if(Invoice::where('id', $id)->exists()) {
            $invoice = Invoice::where('id', $id)->get();
            return response([
                'message'   => "one invoice returned",
                'data'      => $invoice
            ], 200);
        } else {
            return response(["message" => "Invoice not found"], 404);
        }
    }

    /* -------------------------------------update one Invoice -------------------------------------- */
    public function update(Request $request, $id)
    {
        if (Invoice::where('id', $id)->exists()) {
            $invoice = Invoice::find($id);
            $invoice->price = is_null($request->price) ? $invoice->price : $request->price;
            $invoice->save();

            return response(["message" => "Invoice updated successfully"], 200);
        } else {
            return response(["message" => "Invoice not found"], 404);
        }
    }

    /* -------------------------------------delete Invoice -------------------------------------- */
    public function delete($id)
    {
        if(Invoice::where('id', $id)->exists()) {
            $invoice = Invoice::find($id);
            $invoice->delete();
            return response(["message" => "Invoice record deleted"], 202);
        } else {
            return response(["message" => "Invoice not found"], 404);
        }
    }
}
