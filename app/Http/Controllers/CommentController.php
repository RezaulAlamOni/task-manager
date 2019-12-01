<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Comment;
use Carbon\Carbon;
use App\Files;
use App\Task;
use Intervention\Image\File;

class CommentController extends Controller
{   
    public function getCardComment(Request $request)
    {   
        $comment = Task::where('id',$request->task_id)
                    ->with('comment')
                    ->first();
        return response()->json(['success' => true, 'comment' => $comment]);
    }

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

    public function fileUpload(Request $request)
    {
        // return $_FILES["file"];
        if (isset($request->file)) {
            $task_id = $request->id;
            $photo = $_FILES['file']['name'];
            $path = public_path() . "/storage/" . $task_id. "/comment" ;
            if (!is_dir($path)) {
                if (!is_dir(public_path() . "/storage/")) {
                    mkdir(public_path() . "/storage/");
                }
                if (!is_dir(public_path() . "/storage/" . $task_id)) {
                    mkdir(public_path() . "/storage/" . $task_id);
                }
                mkdir($path);
            }
            if (move_uploaded_file($_FILES["file"]["tmp_name"], $path."/" . $_FILES['file']['name'])) {
                $data = [
                    'task_id' => $task_id,
                    'user_id' => Auth::id(),
                    'comment' => '',
                    'attatchment' => $photo,
                    'created_at' => Carbon::now()
                ]; 
        
                $insert = Comment::create($data);
                if ($insert) {
                    $insert = Comment::where('id',$insert->id)->with('user')->first();
                }
                return response()->json(['success' => true, 'Data' => $insert]);
            } else {
                return response()->json('failed', 500);
            }
        }
    }

    public function cardCommentDelete(Request $request)
    {
       $delete = Comment::where('id', $request->id)->orwhere('parent_id', $request->id)->delete();
       if ($delete) {
           return response()->json(['success' => true]);
        }else{
            return response()->json(['success' => false]);
       }
    }

    public function saveCommentReply(Request $request)
    {   
        // return $request->all();
        $data = [
            'task_id' => $request->task_id,
            'parent_id' => $request->parent_id,
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

    public function allComment()
    {   
        $user = Auth::user()->id;
        $comment = Comment::where('user_id', $user)->orderBy('id','DESC')->get();
        $project = view('vendor.spark.layouts.commentsNotification', ['comment' => $comment])->render();
        // return response()->json(['success' => true, 'Data' => $project]);
        print_r($project);
    }
}
