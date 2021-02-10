<?php

namespace App\Http\Controllers\APIs;

use App\Models\SellerProfile;
use Illuminate\Http\Request;
use App\Models\Seller;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class SellerProfileController extends Controller
{
    /* -------------------------------------------get all SellerProfiles ------------------------------------------------ */
    public function getAll()
    {
        $sellerProfile = SellerProfile::get()->toJson();
        return response($sellerProfile, 200);
    }

    /* ------------------------------------- create SellerProfile -------------------------------------- */
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

        $sellerProfile = SellerProfile::create($data);
        return response()->json(["message" => "SellerProfile record created"], 201);
    }


    /* -------------------------------------get one SellerProfile -------------------------------------- */
    public function getOne($id)
    {
        if (SellerProfile::where('id', $id)->exists()) {
            $sellerProfile = SellerProfile::where('id', $id)->get()->toJson();
            return response($sellerProfile, 200);
        } else {
            return response()->json(["message" => "SellerProfile not found"], 404);
        }
    }

    /* -------------------------------------update one SellerProfile -------------------------------------- */
    public function update(Request $request, $id): \Illuminate\Http\JsonResponse
    {
        if (SellerProfile::where('id', $id)->exists()) {
            $sellerProfile = SellerProfile::find($id);
            $sellerProfile->specialization = is_null($request->specialization) ? $sellerProfile->specialization : $request->specialization;
            $sellerProfile->save();

            return response()->json(["message" => "SellerProfile updated successfully"], 200);
        } else {
            return response()->json(["message" => "SellerProfile not found"], 404);
        }
    }

    /* -------------------------------------delete SellerProfile -------------------------------------- */
    public function delete($id): \Illuminate\Http\JsonResponse
    {
        if(SellerProfile::where('id', $id)->exists()) {
            $sellerProfile = SellerProfile::find($id);
            $sellerProfile->delete();
            return response()->json(["message" => "SellerProfile record deleted"], 202);
        } else {
            return response()->json(["message" => "SellerProfile not found"], 404);
        }
    }
}
