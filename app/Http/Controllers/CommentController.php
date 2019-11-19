<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Comment;
use Carbon\Carbon;

class CommentController extends Controller
{
    public function addComment(Request $request)
    {
        $data = [
            'task_id' => $request->task_id,
            'user_id' => Auth::id(),
            'comment' => $request->comment,
            'created_at' => Carbon::now()
        ]; 

        $insert = Comment::create($data);
        if ($insert) {
            $insert = Comment::where('id',$insert->id)->with('user')->first();
        }
        return response()->json(['success' => true, 'Data' => $insert]);
    }
}
