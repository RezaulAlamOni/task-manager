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
    public $TaskController;
    public function __construct()
    {
        $this->TaskController = new TaskController();
    }

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
            'title'=>$request->tags,
            'created_at'=>Carbon::now(),
            'updated_at'=>Carbon::now(),
        ];
        if (isset($request->type) && $request->type == 'board') {
            $data['board_id'] = $request->id;
        } else {
            $data['task_id'] = $request->id;
        }
        $check_exists = Tags::where(['title'=>'Dont Forget','task_id'=>$request->id])->get();

        if ($check_exists->count() <= 0 ) {
            Tags::create($data);
            if ($request->tags == 'Dont Forget') {
                $task = Task::where('id', $request->id)->first();
                $taskDontForget = Task::where([
                    'title' => 'Dont Forget Section',
                    'project_id' => $task->project_id,
                    'list_id' => $task->list_id,
                ])->get();
                if ($taskDontForget->count() <= 0) {
                    $data = [
                        'sort_id' => -2,
                        'parent_id' => 0,
                        'project_id' => $task->project_id,
                        'list_id' => $task->list_id,
                        'created_by' => Auth::id(),
                        'updated_by' => Auth::id(),
                        'title' => 'Dont Forget Section',
                        'tag' => 'Dont Forget',
                        'date' => $task->date,
                        'created_at' => Carbon::now(),
                    ];
                    $NewTask = Task::create($data);
                    $TagData = [
                        'color' => $request->color,
                        'task_id' => $NewTask->id,
                        'title' => 'Dont Forget',
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now(),
                    ];
                    Tags::create($TagData);
                    $taskUpdate = Task::where('id', $request->id)->update(['parent_id' => $NewTask->id]);
                    //update task child tag
                    $this->TaskController->updateTagWithDataMove($request->id,$NewTask->id);
                    return response()->json(['success' => $taskUpdate]);
                } elseif ($request->id != $taskDontForget[0]->id) {
                    $parent = Task::join('tags', 'task_lists.id', 'tags.task_id')->where(['task_lists.id' => $task->parent_id, 'tags.title' => 'Dont Forget'])->get();
                    if ($parent->count() <= 0) {
                    $sort = Task::where(['parent_id' => $taskDontForget[0]->id])->max('sort_id');
                        $taskUpdate = Task::where('id', $request->id)->update(['parent_id' => $taskDontForget[0]->id,'sort_id'=>$sort+1]);
                        //update task child tag
                        $this->TaskController->updateTagWithDataMove($request->id,$taskDontForget[0]->id);
                        return response()->json(['success' => $taskUpdate]);
                    }
                    return response()->json(['success' => $parent, $task->parent_id]);
                }
            } else {
                return response()->json(['success' => 1]);
            }
        }
    }

    public function addTagToMultipleTask(Request $request){
        $ids = $request->ids;
        $success = 0;
        foreach ($ids as $id) {
            $data = [
                'color'=>$request->color,
                'task_id'=>$id ,
                'title'=>$request->tags,
                'created_at'=>Carbon::now(),
                'updated_at'=>Carbon::now(),
            ];


            $check_exists = Tags::where(['title'=>'Dont Forget','task_id'=>$id])->get();
            if ($check_exists->count() <= 0 ) {
                Tags::create($data);
                $success = $this->dontForgetTagProcess($id);
            }
        }
        return ($success)? response()->json(['success'=>1]) : response()->json(['success'=>0]);
    }

    public function dontForgetTagProcess($id){

        $task = Task::where('id', $id)->first();
        if ($task) {
            $taskDontForget = Task::where([
                'title' => 'Dont Forget Section',
                'project_id' => $task->project_id,
                'list_id' => $task->list_id,
            ])->get();
            if ($taskDontForget->count() <= 0) {
                $data = [
                    'sort_id' => -2,
                    'parent_id' => 0,
                    'project_id' => $task->project_id,
                    'list_id' => $task->list_id,
                    'created_by' => Auth::id(),
                    'updated_by' => Auth::id(),
                    'title' => 'Dont Forget Section',
                    'tag' => 'Dont Forget',
                    'date' => $task->date,
                    'created_at' => Carbon::now(),
                ];
                $NewTask = Task::create($data);
                $TagData = [
                    'color' => '#ff0000',
                    'task_id' => $NewTask->id,
                    'title' => 'Dont Forget',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ];
                Tags::create($TagData);
                $taskUpdate = Task::where('id', $id)->update(['parent_id' => $NewTask->id]);
                $this->TaskController->updateTagWithDataMove($id, $NewTask->id);
                return true;
            } elseif ($id != $taskDontForget[0]->id) {
                $parent = Task::join('tags', 'task_lists.id', 'tags.task_id')->where(['task_lists.id' => $task->parent_id, 'tags.title' => 'Dont Forget'])->get();
                $sort = Task::where(['parent_id' => $taskDontForget[0]->id])->max('sort_id');
                if ($parent->count() <= 0) {
                    $taskUpdate = Task::where('id', $id)->update(['parent_id' => $taskDontForget[0]->id,'sort_id'=>$sort+1]);

                    $this->TaskController->updateTagWithDataMove($id, $taskDontForget[0]->id);
                    return true;
                }
                return response()->json(['success'=>$parent,$task->parent_id, 'data' => '']);
            }
        }else{
            return response()->json(['success'=>1, 'data' => '']);
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
        Tags::where('id',$request->id)->delete();
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
