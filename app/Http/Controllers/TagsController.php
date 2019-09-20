<?php

namespace App\Http\Controllers;

use App\Tags;
use Carbon\Carbon;
use Illuminate\Http\Request;

class TagsController extends Controller
{

    public function index()
    {
        //
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
        return response()->json(['success'=>1]);
    }

    public function show(Tags $tags)
    {
        //
    }

    public function edit(Tags $tags)
    {
        //
    }

    public function update(Request $request, Tags $tags)
    {
        //
    }

    public function destroy(Tags $tags)
    {
        //
    }
}
