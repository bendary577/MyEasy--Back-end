<?php

namespace App\Http\Controllers\APIs\V1\Rest\Profiles;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CompanyProfile;
use Illuminate\Support\Facades\Validator;

class CompanyProfileController extends Controller
{
    /* -------------------------------------------get all CompanyProfiles ------------------------------------------------ */
    public function getAll()
    {
        $companyProfile = CompanyProfile::get()->toJson();
        return response($companyProfile, 200);
    }

    /* ------------------------------------- create a CompanyProfile -------------------------------------- */
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

        $companyProfile = CompanyProfile::create($data);
        return response()->json(["message" => "companyProfile record created"], 201);
    }


    /* -------------------------------------get one CompanyProfile -------------------------------------- */
    public function getOne($id)
    {
        if (CompanyProfile::where('id', $id)->exists()) {
            $companyProfile = CompanyProfile::where('id', $id)->get()->toJson();
            return response($companyProfile, 200);
        } else {
            return response()->json(["message" => "CompanyProfile not found"], 404);
        }
    }

    /* -------------------------------------update one CompanyProfile -------------------------------------- */
    public function update(Request $request, $id): \Illuminate\Http\JsonResponse
    {
        if (CompanyProfile::where('id', $id)->exists()) {
            $companyProfile = CompanyProfile::find($id);
            $companyProfile->specialization = is_null($request->specialization) ? $companyProfile->specialization : $request->specialization;
            $companyProfile->save();

            return response()->json(["message" => "CompanyProfile updated successfully"], 200);
        } else {
            return response()->json(["message" => "CompanyProfile not found"], 404);
        }
    }

    /* -------------------------------------delete CompanyProfile -------------------------------------- */
    public function delete($id): \Illuminate\Http\JsonResponse
    {
        if(CompanyProfile::where('id', $id)->exists()) {
            $companyProfile = CompanyProfile::find($id);
            $companyProfile->delete();
            return response()->json(["message" => "CompanyProfile record deleted"], 202);
        } else {
            return response()->json(["message" => "CompanyProfile not found"], 404);
        }
    }
}
