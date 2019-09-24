<?php

namespace App\Http\Controllers;

use App\Tags;
use Carbon\Carbon;
use Illuminate\Http\Request;
use phpDocumentor\Reflection\DocBlock\Tag;

class TagsController extends Controller
{

    public function index()
    {
        $tags =  Tags::where('task_id','!=',null)->groupBy('title')->get();
        return response()->json(['tags'=>$tags]);
    }


    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        $data = [
            'color'=>$request->color,
            'task_id'=>$request->id ,
            'title'=>$request->tags,
            'created_at'=>Carbon::now(),
            'updated_at'=>Carbon::now(),
        ];
        Tags::create($data);

        if ($request->tags == 'Dont Forget' ){

        }else{
            return response()->json(['success'=>1]);
        }

    }

    public function show(Tags $tags)
    {
        //
    }

    public function edit(Tags $tags)
    {
        //
    }

    public function update(Request $request)
    {
        if (isset($request->tag)){
            Tags::where('id',$request->id)->update(['title'=>$request->tag]);
        }elseif (isset($request->color)){
            Tags::where('id',$request->id)->update(['color'=>$request->color]);
        }
        $tags =  Tags::where('task_id','!=',null)->groupBy('title')->get();
        return response()->json(['success'=>1,'tags'=>$tags]);
    }

    public function destroy(Request $request)
    {
        Tags::where('title',$request->title)->delete();
        $tags =  Tags::where('task_id','!=',null)->groupBy('title')->get();
        return response()->json(['success'=>1,'tags'=>$tags]);
    }
}
