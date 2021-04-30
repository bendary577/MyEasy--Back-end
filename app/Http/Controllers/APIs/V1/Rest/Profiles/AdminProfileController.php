<?php

namespace App\Http\Controllers\APIs\V1\Rest\Profiles;


use App\Http\Controllers\Controller;
use App\Models\AdminProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AdminProfileController extends Controller
{
    /* -------------------------------------------get all AdminProfiles ------------------------------------------------ */
    public function getAll()
    {
        $adminProfile = AdminProfile::get()->toJson();
        return response($adminProfile, 200);
    }

    /* ------------------------------------- create a AdminProfile -------------------------------------- */
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

        $adminProfile = AdminProfile::create($data);
        return response()->json(["message" => "AdminProfile record created"], 201);
    }


    /* -------------------------------------get one AdminProfile -------------------------------------- */
    public function getOne($id)
    {
        if (AdminProfile::where('id', $id)->exists()) {
            $adminProfile = AdminProfile::where('id', $id)->get()->toJson();
            return response($adminProfile, 200);
        } else {
            return response()->json(["message" => "AdminProfile not found"], 404);
        }
    }

    /* -------------------------------------update one AdminProfile -------------------------------------- */
    public function update(Request $request, $id): \Illuminate\Http\JsonResponse
    {
        if (AdminProfile::where('id', $id)->exists()) {
            $adminProfile = AdminProfile::find($id);
            $adminProfile->specialization = is_null($request->specialization) ? $adminProfile->specialization : $request->specialization;
            $adminProfile->save();

            return response()->json(["message" => "AdminProfile updated successfully"], 200);
        } else {
            return response()->json(["message" => "AdminProfile not found"], 404);
        }
    }

    /* -------------------------------------delete AdminProfile -------------------------------------- */
    public function delete($id): \Illuminate\Http\JsonResponse
    {
        if(AdminProfile::where('id', $id)->exists()) {
            $adminProfile = AdminProfile::find($id);
            $adminProfile->delete();
            return response()->json(["message" => "AdminProfile record deleted"], 202);
        } else {
            return response()->json(["message" => "AdminProfile not found"], 404);
        }
    }
}
