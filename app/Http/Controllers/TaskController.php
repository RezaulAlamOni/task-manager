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
    public function __construct()
    {

    }

    public function getAll(Request $request)
    {
//        return response()->json($request->all());
        if ($request->list_id == null){
            $list = Multiple_list::where('project_id', $request->id)->orderBy('id', 'ASC')->first();
            $list_id = $list->id;
        }else {
            $list_id = $request->list_id;
        }
        $tasks = Task::where('parent_id', 0)
            ->where('project_id', $request->id)
            ->where('list_id', $list_id)
            ->orderBy('sort_id', 'ASC')
            ->get();
        $data = $this->decorateData($tasks);

        $multiple_list = Project::with('multiple_list')->findOrFail($request->id);
        $multiple_list = $multiple_list->multiple_list;
        return response()->json(['task_list' => $data, 'multiple_list' => $multiple_list]);
    }
    public function getAllTask($id,$list_id)
    {

        $tasks = Task::where('parent_id', 0)
            ->where('project_id', $id)
            ->where('list_id', $list_id)
            ->orderBy('sort_id', 'ASC')->get();
        $data = $this->decorateData($tasks);

        $multiple_list = Project::with('multiple_list')->findOrFail($id);
        $multiple_list = $multiple_list->multiple_list;
        return ['task_list' => $data, 'multiple_list' => $multiple_list];
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
            $data[$key]['tags'] = [$task->tag];

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

    public function addNewTask(Request $request)
    {
        if ($request->list_id == null) {
            $list = Multiple_list::where('project_id', $request->project_id)->orderBy('id', 'ASC')->first();
            $list_id = $list->id;
        } else {
            $list_id = $request->list_id;
        }

        $sort = Task::where('project_id', $request->project_id)->where('list_id', $list_id)->where('parent_id', 0)->count();

        $data = [
            'sort_id' => $sort + 1,
            'parent_id' => 0,
            'project_id' => $request->project_id,
            'list_id' => $list_id,
            'created_by' => Auth::id(),
            'updated_by' => Auth::id(),
            'title' => $request->title,
            'tag' => '',
            'date' => Carbon::today(),
            'created_at' => Carbon::now(),
        ];
        $task = Task::create($data);

        $task_list = $this->getAllTask($request->project_id,$list_id);
        $task_list['success'] = $sort;
        return response()->json($task_list);

    }

    public function addTask(Request $request)
    {
        $list_id = $this->checkListId($request->list_id,$request->project_id);

        $etask = Task::where(['id' => $request->id])->get();
        if ($etask->count() > 0 && $request->text != '') {

            Task::where('id', $request->id)
                ->update(['title' => $request->text]);

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
            return response()->json(['success' => $task]);

        } else if ($request->text == '') {
            Task::where(['id' => $request->id, 'project_id' => $request->project_id])->delete();
            Task::where(['title' => '', 'parent_id' => $request->parent_id, 'project_id' => $request->project_id])->delete();
            return response()->json(['success' => ['id' => $request->id]]);
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

    public function checkListId($list_id,$project_id){
        if ($list_id == null) {
            $list = Multiple_list::where('project_id', $project_id)->orderBy('id', 'ASC')->first();
            return $list->id;
        } else {
            return $list_id;
        }
    }


}
