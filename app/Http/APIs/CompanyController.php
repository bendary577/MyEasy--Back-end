<?php

namespace App\Http\APIs;

use Illuminate\Http\Request;
use App\Models\Company;
use APP\HTTP\Controller;

class CompanyController extends Controller
{
    public function index()
    {
        return response()->json(Company::get(), 200);
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        $company = Company::create($request->all());
        return response()->json($company, 201);
    }

    public function show($id)
    {
        $company = Company::find($id);
        return response()-json($company, 200);
    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        $company = Company::find($id);
        $company->update($request->all());
	    return response()->json($company, 200);
    }

    public function destroy($id)
    {
        $company = Company::find($id);
        $company->delete();
        return response()->json(null, 204);
    }
}
