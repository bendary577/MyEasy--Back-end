<?php

namespace App\Http\Controllers\APIs\V1\Rest\Accounts;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Complaint;
use Illuminate\Support\Facades\Validator;

class ComplaintController extends Controller
{
    public function getAll()
    {
        $complaint = Complaint::get()->toJson();
        return response($complaint, 200);
    }


    public function Create(Request $request)
    {
        $data = $request->all();
        //validator or request validator
        $validator = Validator::make($data, [
            'content' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()], 'Validation Error');
        }

        $complaint = Complaint::create($data);
        return response()->json(["message" => "Complaint record created"], 201);
    }


    public function getOne($id)
    {
        if (Complaint::where('id', $id)->exists()) {
            $complaint = Complaint::where('id', $id)->get()->toJson();
            return response($complaint, 200);
        } else {
            return response()->json(["message" => "Complaint not found"], 404);
        }
    }


    public function update($request, $id)
    {
        if (Complaint::where('id', $id)->exists()) {
            $complaint = Complaint::find($id);
            $complaint->content = is_null($request->content) ? $complaint->content : $request->content;
            $complaint->save();

            return response()->json(["message" => "Complaint updated successfully"], 200);
        } else {
            return response()->json(["message" => "Complaint not found"], 404);
        }
    }


    public function destroy($id)
    {
        if(Complaint::where('id', $id)->exists()) {
            $complaint = Complaint::find($id);
            $complaint->delete();
            return response()->json(["message" => "Complaint record deleted"], 202);
        } else {
            return response()->json(["message" => "Complaint not found"], 404);
        }
    }
}
