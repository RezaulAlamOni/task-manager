<?php

namespace App\Http\Controllers;

use App\Tags;
use App\Task;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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


            $task = Task::where('id',$request->id)->first();
            $taskDontForget = Task::where([
                'title' => 'Dont Forget Section',
                'project_id' => $task->project_id,
                'list_id' => $task->list_id,
                'nav_id' => $task->nav_id
                ])
                ->get();
            if ($taskDontForget->count() <= 0){
                $data = [
                    'sort_id' => 0,
                    'parent_id' => 0,
                    'project_id' => $task->project_id,
                    'list_id' => $task->list_id,
                    'nav_id' => $task->nav_id,
                    'created_by' => Auth::id(),
                    'updated_by' => Auth::id(),
                    'title' => 'Dont Forget Section',
                    'tag' =>'Dont Forget',
                    'date' => $task->date,
                    'created_at' => Carbon::now(),
                ];
                $NewTask = Task::create($data);
                $TagData = [
                    'color'=>$request->color,
                    'task_id'=>$NewTask->id ,
                    'title'=>'Dont Forget',
                    'created_at'=>Carbon::now(),
                    'updated_at'=>Carbon::now(),
                ];
                Tags::create($TagData);
                $taskUpdate = Task::where('id',$request->id)->update(['parent_id'=>$NewTask->id]);
                return response()->json(['success'=>$taskUpdate]);
            }elseif($request->id != $taskDontForget[0]->id){
                $parent = Task::join('tags','task_lists.id','tags.task_id')->where(['task_lists.id'=>$task->parent_id,'tags.title' => 'Dont Forget'])->get();
                if ($parent->count() <= 0){
                    $taskUpdate = Task::where('id',$request->id)->update(['parent_id'=>$taskDontForget[0]->id]);
                    return response()->json(['success'=>$taskUpdate]);
                }
                return response()->json(['success'=>$parent,$task->parent_id]);
            }

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
            $tags =  Tags::where('task_id','!=',null)->groupBy('title')->get();
            return response()->json(['success'=>1,'tags'=>$tags]);
        }elseif (isset($request->color)){
            Tags::where('id',$request->id)->update(['color'=>$request->color]);
            $tags =  Tags::where('task_id','!=',null)->groupBy('title')->get();
            return response()->json(['success'=>1,'tags'=>$tags]);
        }

    }

    public function destroy(Request $request)
    {
        Tags::where('title',$request->title)->delete();
        $tags =  Tags::where('task_id','!=',null)->groupBy('title')->get();
        return response()->json(['success'=>1,'tags'=>$tags]);
    }
    public function delete(Request $request)
    {
        Tags::where('id',$request->id)->delete();
        $tags =  Tags::where('task_id','!=',null)->groupBy('title')->get();
        return response()->json(['success'=>1,'tags'=>$tags]);
    }


}
