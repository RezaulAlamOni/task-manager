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
    public $dont_Forget_tag = 'Dont Forget';
    public $dont_Forget_Section = 'Dont Forget Section';

    public function __construct()
    {
        $this->TaskController = new TaskController();
    }

    public function index()
    {
        $tags = Tags::where('team_id', Auth::user()->current_team_id)
            ->groupBy('title')->orderBy('id','DESC')->get();
        return response()->json(['tags' => $tags]);
    }

    public function store(Request $request)
    {
        $team_id = Auth::user()->current_team_id;
        $tags = Tags::where(['title' => $request->tags,'team_id'=>$team_id])->first();
        if ($tags) {
            $tag_id = $tags->id;
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
        }
        $check_assign = AssignTag::where(['task_id' => $request->id, 'tag_id' => $tag_id])->count();
        if ($check_assign <= 0) {
            $check_assign = AssignTag::create(['task_id' => $request->id, 'tag_id' => $tag_id]);
            $tags->assign_id = $check_assign->id;
            $tags->board_id = $check_assign->task_id;
        }

        if ($request->tags == $this->dont_Forget_tag) {
            $this->dontForgetTagProcess($request->id,$tag_id);
        }else {
            return response()->json(['success' => true, 'data' => $tags]);
        }

    }

    public function addTagToMultipleTask(Request $request)
    {
        $team_id = Auth::user()->current_team_id;
        $tags = Tags::where(['title' => $request->tags,'team_id'=>$team_id])->first();
        $ids = $request->ids;
        if ($tags) {
            $tag_id = $tags->id;
        } else {
            $tags = Tags::create([
                'team_id' => $team_id,
                'color' => $request->color,
                'title' => $request->tags,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
            $tag_id = $tags->id;
        }

        foreach ($ids as $id) {
            $this->dontForgetTagProcess($id,$tag_id);
        }

    }

    public function dontForgetTagProcess($id,$tag_id)
    {
        $check_assign = AssignTag::where(['task_id' => $id, 'tag_id' => $tag_id])->count();
        if ($check_assign <= 0) {
            AssignTag::create(['task_id' => $id, 'tag_id' =>$tag_id]);
        }

        $task = Task::where('id', $id)->first();
        $taskDontForget = Task::where([
            'title' => $this->dont_Forget_Section,
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
                'title' => $this->dont_Forget_Section,
                'date' => $task->date,
                'created_at' => Carbon::now(),
            ];
            $NewTask = Task::create($data);
            $assign = AssignTag::create(['task_id' => $NewTask->id, 'tag_id' => $tag_id]);

            $taskUpdate = Task::where('id', $id)->update(['parent_id' => $NewTask->id]);
            //update task child tag
            $this->TaskController->updateTagWithDataMove($id, $NewTask->id);
            return response()->json(['success' => $taskUpdate]);
        } elseif ($id != $taskDontForget[0]->id) {
            $parent_assign_dont_Forget_tag = AssignTag::where(['task_id' => $task->parent_id , 'tag_id' => $tag_id])->count();

            if ($parent_assign_dont_Forget_tag <= 0) {
                $sort = Task::where(['parent_id' => $taskDontForget[0]->id])->max('sort_id');
                $taskUpdate = Task::where('id', $id)->update([
                    'parent_id' => $taskDontForget[0]->id,
                    'sort_id' => $sort + 1
                ]);
                //update task child tag
                $this->TaskController->updateTagWithDataMove($id, $taskDontForget[0]->id);
                return response()->json(['success' => $taskUpdate]);
            }
            return response()->json(['success']);
        }

    }

    public function update(Request $request)
    {
        if (isset($request->tag)) {
            Tags::where('id', $request->id)->update(['title' => $request->tag]);
            $tags = $this->getAllTagByTeamId();
            return response()->json(['success' => 1, 'tags' => $tags]);
        } elseif (isset($request->color)) {
            Tags::where('id', $request->id)->update(['color' => $request->color]);
            $tags = $this->getAllTagByTeamId();
            return response()->json(['success' => 1, 'tags' => $tags]);
        }
    }

    public function destroy(Request $request)
    {
        if (isset($request->assign_id)) {
            AssignTag::where('id', $request->assign_id)->delete();
            return response()->json(['success' => 1, 'tags' => []]);
        } elseif (isset($request->id)) {
            Tags::where('id', $request->id)->delete();
            AssignTag::where('tag_id',$request->id)->delete();
            $tags = $this->getAllTagByTeamId();
            return response()->json(['success' => 1, 'tags' => $tags]);
        }
    }

    public function getAllTagByTeamId()
    {
        $tags = Tags::where('team_id', Auth::user()->current_team_id)->groupBy('title')->orderBy('id','DESC')->get();
        return $tags;
    }

}
