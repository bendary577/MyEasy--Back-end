<?php

namespace App\Http\Controllers\APIs\V1\Rest\Profiles;

use App\Http\Controllers\Controller;
use App\Models\CustomerProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CustomerProfileController extends Controller
{
    /* -------------------------------------------get all CustomerProfiles ------------------------------------------------ */
    public function getAll()
    {
        $customerProfile = CustomerProfile::get()->toJson();
        return response($customerProfile, 200);
    }

    /* ------------------------------------- create CustomerProfile -------------------------------------- */
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

        $customerProfile = CustomerProfile::create($data);
        return response()->json(["message" => "customerProfile record created"], 201);
    }


    /* -------------------------------------get one CustomerProfile -------------------------------------- */
    public function getOne($id)
    {
        if (CustomerProfile::where('id', $id)->exists()) {
            $customerProfile = CustomerProfile::where('id', $id)->get()->toJson();
            return response($customerProfile, 200);
        } else {
            return response()->json(["message" => "customerProfile not found"], 404);
        }
    }

    /* -------------------------------------update one CustomerProfile -------------------------------------- */
    public function update(Request $request, $id): \Illuminate\Http\JsonResponse
    {
        if (CustomerProfile::where('id', $id)->exists()) {
            $customerProfile = CustomerProfile::find($id);
            $customerProfile->specialization = is_null($request->specialization) ? $customerProfile->specialization : $request->specialization;
            $customerProfile->save();

            return response()->json(["message" => "CustomerProfile updated successfully"], 200);
        } else {
            return response()->json(["message" => "CustomerProfile not found"], 404);
        }
    }

    /* -------------------------------------delete CustomerProfile -------------------------------------- */
    public function delete($id): \Illuminate\Http\JsonResponse
    {
        if(CustomerProfile::where('id', $id)->exists()) {
            $customerProfile = CustomerProfile::find($id);
            $customerProfile->delete();
            return response()->json(["message" => "CustomerProfile record deleted"], 202);
        } else {
            return response()->json(["message" => "CustomerProfile not found"], 404);
        }
    }
}
