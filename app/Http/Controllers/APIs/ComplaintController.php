<?php

namespace App\Http\Controllers\APIs;

use Illuminate\Http\Request;
use App\Models\Complaint;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class ComplaintController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getAll()
    {
        $complaint = Complaint::get()->toJson();
        return response($complaint, 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
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

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getOne($id)
    {
        if (Complaint::where('id', $id)->exists()) {
            $complaint = Complaint::where('id', $id)->get()->toJson();
            return response($complaint, 200);
        } else {
            return response()->json(["message" => "Complaint not found"], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
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

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
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
