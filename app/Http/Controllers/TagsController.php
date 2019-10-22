<?php

namespace App\Http\Controllers;

use App\AssignTag;
use App\Tags;
use App\Task;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TagsController extends Controller
{
    public $TaskController;

    public function __construct()
    {
        $this->TaskController = new TaskController();
    }

    public function index($project_id)
    {
        $tags = Tags::select('tags.*')
            ->join('task_lists', 'tags.task_id', 'task_lists.id')
            ->where('task_id', '!=', null)
            ->where('task_lists.project_id', $project_id)
            ->groupBy('tags.title')->get();
        return response()->json(['tags' => $tags]);
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        $team_id = Auth::user()->current_team_id;
        $tags = Tags::where(['title' => $request->tags,'team_id'=>$team_id])->get();

        if ($tags->count() > 0) {
            $check_assign = AssignTag::where(['task_id' => $request->id, 'tag_id' => $tags[0]->id])->get();
            if ($check_assign->count() <= 0) {
                $assign = AssignTag::create(['task_id' => $request->id, 'tag_id' => $tags[0]->id]);
            }
            $tag_id = $tags[0]->id;
//            return response()->json('assign-tag');
        } else {

            $tag_data = [
                'team_id' => $team_id,
                'color' => $request->color,
                'title' => $request->tags,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ];
            $tags = Tags::create($tag_data);
            $tag_id = $tags->id;
            $check_assign = AssignTag::where(['task_id' => $request->id, 'tag_id' => $tags->id])->get();
            if ($check_assign->count() <= 0) {
                $assign = AssignTag::create(['task_id' => $request->id, 'tag_id' => $tags->id]);
            }
//            return response()->json('tag-and-assign-tag-created');
        }

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
                $assign = AssignTag::create(['task_id' => $NewTask->id, 'tag_id' => $tag_id]);

                $taskUpdate = Task::where('id', $request->id)->update(['parent_id' => $NewTask->id]);
                //update task child tag
//                $this->TaskController->updateTagWithDataMove($request->id, $NewTask->id);
                return response()->json(['success' => $taskUpdate]);
            } elseif ($request->id != $taskDontForget[0]->id) {
                $parent = Task::where(['id' => $task->parent_id])->with('Dont_Forget_tag');

//                if ($parent->count() <= 0) {
//                    $sort = Task::where(['parent_id' => $taskDontForget[0]->id])->max('sort_id');
//                    $taskUpdate = Task::where('id', $request->id)->update([
//                        'parent_id' => $taskDontForget[0]->id,
//                        'sort_id' => $sort + 1
//                    ]);
//                    //update task child tag
//                    $this->TaskController->updateTagWithDataMove($request->id, $taskDontForget[0]->id);
//                    return response()->json(['success' => $taskUpdate]);
//                }
                return response()->json([$parent]);
//                return response()->json(['success' => $parent, $task->parent_id, 'data' => $tags]);
            }
        } else {
//            return response()->json(['success' => true, 'data' => $tags]);
        }

    }

    public function addTagToMultipleTask(Request $request)
    {
        $ids = $request->ids;
        $success = 0;
        foreach ($ids as $id) {
            $data = [
                'color' => $request->color,
                'task_id' => $id,
                'title' => $request->tags,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ];
            $check_exists = Tags::where(['title' => 'Dont Forget', 'task_id' => $id])->get();
            if ($check_exists->count() <= 0) {
                Tags::create($data);
                $success = $this->dontForgetTagProcess($id);
            }
        }
        return ($success) ? response()->json(['success' => 1]) : response()->json(['success' => 0]);
    }

    public function dontForgetTagProcess($id)
    {

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
                $parent = Task::join('tags', 'task_lists.id',
                    'tags.task_id')->where(['task_lists.id' => $task->parent_id, 'tags.title' => 'Dont Forget'])->get();
                $sort = Task::where(['parent_id' => $taskDontForget[0]->id])->max('sort_id');
                if ($parent->count() <= 0) {
                    $taskUpdate = Task::where('id', $id)->update([
                        'parent_id' => $taskDontForget[0]->id,
                        'sort_id' => $sort + 1
                    ]);

                    $this->TaskController->updateTagWithDataMove($id, $taskDontForget[0]->id);
                    return true;
                }
                return response()->json(['success' => $parent, $task->parent_id, 'data' => '']);
            }
        } else {
            return response()->json(['success' => 1, 'data' => '']);
        }
    }

    public function update(Request $request)
    {
        if (isset($request->tag)) {
            $taskAndTag = Tags::join('task_lists', 'tags.task_id', 'task_lists.id')
                ->select('tags.*', 'task_lists.project_id')
                ->where('tags.id', $request->id)->first();
            Tags::where('title', $taskAndTag->title)->update(['title' => $request->tag]);
            $tags = $this->getAllTagByProjectID($taskAndTag->project_id);
            return response()->json(['success' => 1, 'tags' => $tags]);
        } elseif (isset($request->color)) {

            $taskAndTag = Tags::join('task_lists', 'tags.task_id', 'task_lists.id')
                ->select('tags.*', 'task_lists.project_id')
                ->where('tags.id', $request->id)->first();
            Tags::where('title', $taskAndTag->title)->update(['color' => $request->color]);
            $tags = $this->getAllTagByProjectID($taskAndTag->project_id);
            return response()->json(['success' => 1, 'tags' => $tags]);
        }
    }

    public function destroy(Request $request)
    {
        if (isset($request->title)) {
            $taskAndTag = Tags::join('task_lists', 'tags.task_id', 'task_lists.id')
                ->where('tags.title', $request->title)->first();
            Tags::where('title', $request->title)->delete();
            $tags = $this->getAllTagByProjectID($taskAndTag->project_id);
            return response()->json(['success' => 1, 'tags' => $tags]);
        } elseif (isset($request->id)) {
            $taskAndTag = Tags::join('task_lists', 'tags.task_id', 'task_lists.id')->select('tags.*', 'task_lists.project_id')
                ->where('tags.id', $request->id)->first();
            $check_tag = Tags::where('title', $taskAndTag->title)->count();
            if ($check_tag > 1) {
                Tags::where('id', $request->id)->delete();
            } else {
                Tags::where('id', $request->id)->update(['status' => 1]);
            }

            $tags = $this->getAllTagByProjectID($taskAndTag->project_id);
            return response()->json(['success' => 1, 'tags' => $tags, $check_tag]);
        }
    }

    public function getAllTagByProjectID($project_id)
    {
        $tags = Tags::select('tags.*')
            ->join('task_lists', 'tags.task_id', 'task_lists.id')
            ->where('task_id', '!=', null)
            ->where('task_lists.project_id', $project_id)
            ->groupBy('tags.title')->get();
        return $tags;
    }

}
