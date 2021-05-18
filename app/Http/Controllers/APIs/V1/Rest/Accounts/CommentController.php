<?php

namespace App\Http\Controllers\APIs\V1\Rest\Accounts;

use App\Events\NewCommentEvent;
use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\Comment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Validator;

class CommentController extends Controller
{

    public function getAll()
    {
        $comment = Comment::get()->toJson();
        return response($comment, 200);
    }


    public function Create(Request $request, $id)
    {
        $data = $request->all();
        //validator or request validator
        $validator = Validator::make($data, [
            'content' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()], 'Validation Error');
        }

        $user = Auth::user();
        if (Product::where('id', $id)->exists()) {
            $product = Product::where('id', $id)->get();
            $comment = Comment::create($data);
            Event::fire(new NewCommentEvent($user, $product));
            return response()->json(["message" => "Comment record created"], 201);
        }

        return response()->json(["message" => "product is not found"], 201);
    }


    public function getOne($id)
    {
        if (Comment::where('id', $id)->exists()) {
            $comment = Comment::where('id', $id)->get()->toJson();
            return response($comment, 200);
        } else {
            return response()->json(["message" => "Comment not found"], 404);
        }
    }

    public function update($request, $id)
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
