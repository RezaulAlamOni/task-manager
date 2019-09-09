<?php

namespace App\Http\Controllers;

use App\Multiple_list;
use App\Project;
use App\Task;
use Carbon\Carbon;
use http\Env\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    protected $actionLog;
    public function __construct()
    {
        date_default_timezone_set('UTC');
        $this->actionLog =new ActionLogController;
        $this->middleware('auth');
    }

    public function getAll(Request $request)
    {
        if ($request->list_id == null){
            $list = Multiple_list::where('project_id', $request->id)->orderBy('id', 'ASC')->first();
            $list_id = $list->id;
        } else {
            $list_id = $request->list_id;
        }
        $tasks = Task::where('parent_id', 0)
            ->where('project_id', $request->id)
            ->where('list_id', $list_id)
            ->orderBy('sort_id', 'ASC')
            ->get();
        $task= [];
        if ($tasks->count() <= 0){
            $data = [
                'sort_id' =>0,
                'parent_id' =>0,
                'project_id' => $request->id,
                'list_id' => $list_id,
                'created_by' => Auth::id(),
                'updated_by' => Auth::id(),
                'title' => '',
                'tag' => '',
                'date' => Carbon::today(),
                'created_at' => Carbon::now(),
            ];
            $task = Task::create($data);
            $this->createLog($task->id,'created','Create empty task','');
            $tasks = Task::where('parent_id', 0)
                ->where('project_id', $request->id)
                ->where('list_id', $list_id)
                ->orderBy('sort_id', 'ASC')->get();

        }

        $data = $this->decorateData($tasks);

        $multiple_list = Project::with('multiple_list')->findOrFail($request->id);
        $multiple_list = $multiple_list->multiple_list;
        return response()->json(['task_list' => $data, 'multiple_list' => $multiple_list,'empty_task'=>$task]);
    }
    public function addTask(Request $request)
    {
        $list_id = $this->checkListId($request->list_id,$request->project_id);

        $etask = Task::where(['id' => $request->id])->get();
        if ($etask->count() > 0 && $request->text != '') {

            Task::where('id', $request->id)
                ->update(['title' => $request->text]);
            $this->createLog($request->id,'updated','Update task',$request->title);

            Task::where('parent_id', $request->parent_id)
                ->where('sort_id', '>', $request->sort_id)
                ->where('list_id', $list_id)
                ->increment('sort_id');
            $data = [
                'sort_id' => $request->sort_id + 1,
                'parent_id' => $request->parent_id,
                'project_id' => $request->project_id,
                'list_id' => $list_id,
                'created_by' => Auth::id(),
                'updated_by' => Auth::id(),
                'title' => '',
                'tag' => '',
                'date' => Carbon::today(),
                'created_at' => Carbon::now(),
            ];
            $task = Task::create($data);
            $this->createLog($task->id,'created','Create empty task',$request->title);
            return response()->json(['success' => $task]);

        } else if ($request->text == '') {
            Task::where(['id' => $request->id, 'project_id' => $request->project_id])->delete();
            $this->createLog($request->id,'deleted','Delete task','');
            Task::where(['title' => '', 'parent_id' => $request->parent_id, 'project_id' => $request->project_id])->delete();
            return response()->json(['success' => ['id' => $request->id]]);
        }
    }
    public function addChildTask(Request $request){
        $tsk_id = Task::where('title','')->where('parent_id',$request->id)->first();
        if ($tsk_id){
            Task::where('title','')->where('parent_id',$request->id)->delete();
            $this->createLog($tsk_id->id,'deleted','Delete empty task','');
        }
        $sort_id = Task::where('parent_id',$request->id)->orderBy('id','desc')->first();
        $data = [
            'sort_id' => $sort_id ? $sort_id->sort_id+1 : 0,
            'parent_id' => $request->id,
            'project_id' => $request->project_id,
            'list_id' =>$request->list_id,
            'created_by' => Auth::id(),
            'updated_by' => Auth::id(),
            'title' => '',
            'tag' => '',
            'date' => Carbon::today(),
            'created_at' => Carbon::now(),
        ];
        $task = Task::create($data);
        $this->createLog($task->id,'created','create empty task','');
        return response()->json(['success'=>$task]);
    }
    public function makeChild(Request $request){
        if (isset($request->parent_id)){
            $task = Task::where('parent_id',$request->parent_id)
                ->where('project_id',$request->project_id)
                ->where('list_id',$request->list_id)
                ->where('sort_id','<',$request->sort_id)
                ->orderBy('sort_id','desc')->first();
            if ($task){
                $taskChild = Task::where('parent_id',$task->id)
                    ->where('project_id',$request->project_id)
                    ->where('list_id',$request->list_id)
                    ->orderBy('sort_id','desc')->first();
                if ($taskChild){
                    $sort_id = $taskChild->sort_id+1;
                    $child = Task::where('id',$request->id)->update(['parent_id'=>$task->id,'sort_id'=>$sort_id]);
                }else{
                    $child = Task::where('id',$request->id)->update(['parent_id'=>$task->id,'sort_id'=>1]);
                }
            }

            return response()->json(['success'=>$request->id]);
        }
    }



    public function decorateData($obj)
    {
        $data = [];
        foreach ($obj as $key => $task) {
            $data[$key]['id'] = $task->id;
            $data[$key]['parent_id'] = $task->parent_id;
            $data[$key]['sort_id'] = $task->sort_id;
            $data[$key]['list_id'] = $task->list_id;//list_id
            $data[$key]['text'] = $task->title;
            $data[$key]['clicked'] = 0;
            $data[$key]['date'] = $task->date;
            $data[$key]['tags'] = $task->tag;

            $childrens = Task::where('parent_id', $task->id)
                ->where('list_id', $task->list_id)->orderBy('sort_id', 'ASC')->get();
            if (!empty($childrens)) {
                $data[$key]['children'] = $this->decorateData($childrens);
            } else {
                $data[$key]['children'] = [];
            }

        }
        return $data;
    }
    public function checkListId($list_id,$project_id){
        if ($list_id == null) {
            $list = Multiple_list::where('project_id', $project_id)->orderBy('id', 'ASC')->first();
            return $list->id;
        } else {
            return $list_id;
        }
    }
    protected function createLog($task_id,$type,$message,$title){
        $log_data = [
            'task_id'=>$task_id,
            'title'=>$title,
            'log_type'=>$message,
            'action_type'=>$type,
            'action_by'=>Auth::id(),
            'action_at'=>Carbon::now()
        ];
        $this->actionLog->store($log_data);
    }


}
