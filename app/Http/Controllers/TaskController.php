<?php

namespace App\Http\Controllers;

use App\Task;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    public function __construct()
    {
    }

    public function getAll(Request $request){
        $tasks = Task::where('parent_id',0)->orderBy('sort_id','ASC')->get();
        $data = $this->decorateData($tasks);
        return response()->json($data);
    }

    public function decorateData($obj){
        $data = [];
        foreach ($obj as $key => $task) {
            $data[$key]['id'] = $task->id;
            $data[$key]['parent_id'] = $task->parent_id;
            $data[$key]['sort_id'] = $task->sort_id;
            $data[$key]['text'] = $task->title;
            $data[$key]['clicked'] = 0;
            $data[$key]['date'] = $task->date;
            $data[$key]['tags'] = [$task->tag];

            $childrens = Task::where('parent_id',$task->id)->orderBy('sort_id','ASC')->get();
            $data[$key]['children'] = $this->decorateData($childrens);
        }
        return $data;
    }

    public function index()
    {
        //
    }

    public function addTask(Request $request)
    {
        $empty_task = Task::where(['title'=>'','parent_id'=>$request->parent_id])->count();

        if($empty_task < 0){
            Task::where('id',$request->id)
                ->update(['title'=>$request->text]);

            Task::where('parent_id',$request->parent_id)
                ->where('sort_id','>',$request->sort_id)
                ->increment('sort_id');
            $data = [
                'sort_id' => $request->sort_id+1,
                'parent_id'=> $request->parent_id,
                'project_id' => $request->project_id,
                'created_by'=>Auth::id(),
                'updated_by'=>Auth::id(),
                'title'=>'',
                'tag'=>'',
                'date'=>Carbon::today(),
                'created_at'=>Carbon::now(),
            ];
            $task = Task::create($data);
            return response()->json(['success'=>$task]);
        } else {
            return response()->json(['success'=> 'empty']);
        }



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
