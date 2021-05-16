<?php

namespace App\Http\Controllers\APIs\V1\Rest\Stores;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:create category|list categories|edit category|delete category', ['only' => ['getAll','getOne']]);
        $this->middleware('permission:create category', ['only' => ['create']]);
        $this->middleware('permission:edit category', ['only' => ['update']]);
        $this->middleware('permission:delete category', ['only' => ['delete']]);
    }

    public function getAll()
    {
        $categories = Category::query()->orderByDesc('created_at')->paginate(6)->toJson();
        return response($categories, 200);
    }


    public function create(Request $request): \Illuminate\Http\JsonResponse
    {
        $data = $request->all();
        $validator = Validator::make($data, [
            'name' => 'required|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()], 'Validation Error');
        }

        $category = Category::create($data);
        return response()->json(["message" => "Category record created"], 201);
    }


    public function getOne($id)
    {
        if (Category::where('id', $id)->exists()) {
            $category = Category::where('id', $id)->get()->toJson();
            return response($category, 200);
        } else {
            return response()->json(["message" => "Category not found"], 404);
        }
    }


    public function update(Request $request, $id)
    {
        if (Category::where('id', $id)->exists()) {
            $category = Category::find($id);
            $category->name = is_null($request->name) ? $category->name : $request->name;
            $category->save();

            return response()->json(["message" => "Category updated successfully"], 200);
        } else {
            return response()->json(["message" => "Category not found"], 404);
        }
    }


    public function delete($id)
    {
        if(Category::where('id', $id)->exists()) {
            $category = Category::find($id);
            $category->delete();
            return response()->json(["message" => "Category record deleted"], 202);
          } else {
            return response()->json(["message" => "Category not found"], 404);
          }
    }
}
