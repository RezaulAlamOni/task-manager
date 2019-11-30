<?php

namespace App\Http\Controllers;

use App\Comment;
use App\Files;
use App\Multiple_list;
use App\ProjectNavItems;
use App\Task;
use http\Env\Response;
use Illuminate\Http\Request;

class OverviewController extends Controller
{
    protected $TaskController;

    public function __construct()
    {
        $this->middleware('auth');
        $this->TaskController = New TaskController();
    }

    public function index($project_id)
    {

        $all_list_navs = ProjectNavItems::where(['type' => 'list', 'project_id' => $project_id])->with('All_list')->get();
        foreach ($all_list_navs as $all_list_nav) {
            foreach ($all_list_nav->all_list as $item) {
                $item->tasks = $this->TaskController->decorateData($item->tasks_list, 'drag');
            }
        }
        return response()->json(['data' => $all_list_navs]);
    }

    public function ListSort(Request $request)
    {
        if (!isset($request->data)) {
            return \response()->json(['status' => 'failed']);
        }
        $data = $request->data;
        foreach ($data as $key => $item) {
            Multiple_list::where('id', (int)$item)->update(['sort_id' => $key]);
        }
        return response()->json(['status' => 'success']);

    }

    public function ListToggle(Request $request)
    {
        if (!isset($request->list_id)) {
            return \response()->json(['status' => 'failed']);
        }
        $list_check = Multiple_list::where('id', $request->list_id)->first();
        $open = ($list_check->open) ? 0 : 1;
        $update_toggle = Multiple_list::where('id', $request->list_id)->update(['open' => $open]);
        if ($update_toggle) {
            return \response()->json(['status' => 'success']);
        } else {
            return \response()->json(['status' => 'failed']);
        }

    }

    public function All_files($project_id)
    {
        $all_files = Files::select('task_files.*','task_lists.title','task_lists.list_id')
            ->join('task_lists','task_files.tasks_id','task_lists.id')
            ->where('task_lists.project_id',$project_id)->orderBy('task_files.created_at','desc')
            ->with('user')->paginate(18);
        return \response()->json(['files'=>$all_files,'status'=>'success']);
    }
    public function AllComments($project_id)
    {
        $all_comments = Comment::select('comments.*','task_lists.title','task_lists.list_id')
            ->join('task_lists','comments.task_id','task_lists.id')->orderBy('comments.created_at','desc')
            ->where('task_lists.project_id',$project_id)->with('user')->get();
        return \response()->json(['comments'=>$all_comments,'status'=>'success']);
    }

}
