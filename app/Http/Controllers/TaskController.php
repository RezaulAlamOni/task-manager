<?php

namespace App\Http\Controllers;

use App\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function __construct()
    {
    }

    public function getAll(Request $request){
        $tasks = Task::where('parent_id',0)->get();
        $data = $this->decorateData($tasks);
        return response()->json($data);
    }

    public function decorateData($obj){
        $data = [];
        foreach ($obj as $key => $task) {
            $data[$key]['id'] = $task->id;
            $data[$key]['parent'] = $task->parent_id;
            $data[$key]['text'] = $task->title;
            $data[$key]['clicked'] = 0;
            $data[$key]['date'] = $task->date;
            $data[$key]['tags'] = [$task->tag];

            $childrens = Task::where('parent_id',$task->id)->get();
            $data[$key]['children'] = $this->decorateData($childrens);
        }
        return $data;
    }

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
        //
    }

    public function show(Task $task)
    {
        //
    }

    public function edit(Task $task)
    {
        //
    }

    public function update(Request $request, Task $task)
    {
        //
    }

    public function destroy(Task $task)
    {
        //
    }


}
