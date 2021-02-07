<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Company;

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

    public function show(Company $company)
    {
        return response()-json($company, 200);
    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request, Company $company)
    {
        $company->update($request->all());
	    return response()->json($company, 200);
    }

    public function destroy(Company $company)
    {
        $company->delete();
        return response()->json(null, 204);
    }
}
