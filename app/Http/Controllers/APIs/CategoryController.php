<?php

namespace App\Http\Controllers\APIs;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getAll()
    {
        $categories = Category::query()->orderByDesc('created_at')->paginate(6)->toJson();
        return response($categories, 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
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

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getOne($id)
    {
        if (Category::where('id', $id)->exists()) {
            $category = Category::where('id', $id)->get()->toJson();
            return response($category, 200);
        } else {
            return response()->json(["message" => "Category not found"], 404);
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
        if (Category::where('id', $id)->exists()) {
            $category = Category::find($id);
            $category->name = is_null($request->name) ? $category->name : $request->name;
            $category->save();

            return response()->json(["message" => "Category updated successfully"], 200);
        } else {
            return response()->json(["message" => "Category not found"], 404);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
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
