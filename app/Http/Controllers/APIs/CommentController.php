<?php

namespace App\Http\Controllers\APIs;

use Illuminate\Http\Request;
use App\Models\Comment;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getAll()
    {
        $comment = Comment::get()->toJson();
        return response($comment, 200);
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

        $comment = Comment::create($data);
        return response()->json(["message" => "Comment record created"], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getOne($id)
    {
        if (Comment::where('id', $id)->exists()) {
            $comment = Comment::where('id', $id)->get()->toJson();
            return response($comment, 200);
        } else {
            return response()->json(["message" => "Comment not found"], 404);
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
        if (Comment::where('id', $id)->exists()) {
            $comment = Comment::find($id);
            $comment->content = is_null($request->content) ? $comment->content : $request->content;
            $comment->save();

            return response()->json(["message" => "Comment updated successfully"], 200);
        } else {
            return response()->json(["message" => "Comment not found"], 404);
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
        if(Comment::where('id', $id)->exists()) {
            $comment = Comment::find($id);
            $comment->delete();
            return response()->json(["message" => "Comment record deleted"], 202);
        } else {
            return response()->json(["message" => "Comment not found"], 404);
        }
    }
}
