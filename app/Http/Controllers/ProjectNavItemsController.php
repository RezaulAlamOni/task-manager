<?php

namespace App\Http\Controllers;

use App\Multiple_board;
use App\Multiple_list;
use App\ProjectNavItems;
use App\Rules;
use App\Task;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProjectNavItemsController extends Controller
{
    protected $actionLog;

    public function __construct()
    {
        $this->actionLog = new ActionLogController;
        $this->middleware('auth');
    }

    public function index($project_id)
    {
        $navItem = [];
        $nav = ProjectNavItems::where('project_id', $project_id)->orderBy('sort_id', 'asc')->get();
        foreach ($nav as $item) {
            $nav_ = $this->getList($project_id, $item->id, $item->type);
            $item->lists = $nav_[0];
            $navItem[] = $item;
        }
//        $rules = Rules::where('project_id',$project_id)->get();
        return response()->json(['success' => $navItem,'rules'=>[]]);


    }
    public function GetBoardsAndColumn($project_id)
    {
        $boards = [];
        $navs = ProjectNavItems::where(['project_id'=> $project_id,'type'=>'board'])->orderBy('sort_id', 'asc')->get();
        if ($navs->count() > 0){
            foreach ($navs as $item) {
                $nav  = Multiple_board::where(['project_id' => (int)$project_id, 'nav_id' => $item->id])->get()->toArray();
                foreach ($nav as $item1) {
                    $boards[] = $item1;
                }
            }
            foreach ($boards as $key => $board) {
                $boards[$key]['columns'] = Task::where('board_parent_id',0)->where('multiple_board_id',$board['id'])->get();
            }


        }
        $team_id = Auth::user()->current_team_id;
        $user = User::join('team_users', 'team_users.user_id', 'users.id')
            ->where('team_users.team_id', $team_id)->get()->toArray();
        $rules = Rules::where('project_id',(int)$project_id)->with('move_from')->with('move_to')->get();
        foreach ($rules as $rule) {
            $us = [];
            foreach (json_decode($rule->assigned_users)  as $item) {
                if ((int)$item !== 0 && !empty($user)){
                    foreach ($user as $item1) {
                        if ((int)$item == $item1['id']){
                            $uin['id'] = $item1['id'];
                            $uin['name'] = $item1['name'];
                        }
                        $us[] = $uin;
                    }
                }

            }
            $rule->assigned_users = $us;
        }


        return response()->json(['status' => 'success', 'data' => $boards,'users'=>$user,'rules'=>$rules]);
    }



    public function getList($project_id, $nav_id, $type)
    {
        $nav = ProjectNavItems::where('id', $nav_id)->first();
        $type = $nav->type;
        if ($type == 'list') {
            $list = Multiple_list::where(['project_id' => (int)$project_id, 'nav_id' => $nav_id])->get();
            $list->type = $type;
            return [$list, $nav->type];
        } else {
            $board = Multiple_board::where(['project_id' => (int)$project_id, 'nav_id' => $nav_id])->get();
            $board->type = $type;
            return [$board, $nav->type];
        }

    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        $data = [
            'project_id' => $request->project_id,
            'title' => $request->title,
            'type' => $request->type,
            'sort_id' => $request->sort_id,
            'created_at' => Carbon::now(),
        ];
        $check = ProjectNavItems::where('title', $request->title)->where('project_id', $request->project_id)->count();
        if ($check <= 0) {
            $nav = ProjectNavItems::create($data);
            $this->createLog($nav->id, 'created', 'Navbar Create', $request->title);
            return response()->json(['success' => $nav, 'status' => 'create']);
        } else {
            return response()->json(['success' => 'This title is already taken !', 'status' => 'exists']);
        }

    }

    protected function createLog($nav_id, $type, $message, $title)
    {
        $log_data = [
            'nav_id' => $nav_id,
            'title' => $title,
            'log_type' => $message,
            'action_type' => $type,
            'action_by' => Auth::id(),
            'action_at' => Carbon::now()
        ];
        $this->actionLog->store($log_data);
    }

    public function navList(Request $request)
    {
        // return $request->all();
        $nav = Multiple_list::where('project_id', $request->projectId)->where('nav_id', $request->navId)->get();

        return response()->json(['success' => $nav]);
    }

    public function boardList(Request $request)
    {
        // return $request->all();
        $nav = Multiple_board::where('project_id', $request->projectId)->where('nav_id', $request->boardId)->get();

        return response()->json(['success' => $nav]);
    }

    public function multipleList(Request $request)
    {
        $nav = $this->getList($request->projectId, $request->listId, $request->type);
        return response()->json(['success' => $nav[0], 'type' => $nav[1]]);
    }

    public function edit(Request $request)
    {
        $check = ProjectNavItems::where('title', $request->title)->where('project_id', $request->project_id)->count();
        if ($check <= 1) {
            $nab = ProjectNavItems::findOrFail($request->nav_id);

            if ($nab->sort_id > $request->sort_id) {
                ProjectNavItems::where('project_id', $request->project_id)
                    ->where('sort_id', '<', $nab->sort_id)
                    ->where('sort_id', '>=', $request->sort_id)
                    ->increment('sort_id');
            } else {
                if ($nab->sort_id < $request->sort_id) {
                    ProjectNavItems::where('project_id', $request->project_id)
                        ->where('sort_id', '>', $nab->sort_id)
                        ->where('sort_id', '<=', $request->sort_id)
                        ->decrement('sort_id');
                }
            }
            $data = [
                'title' => $request->title,
                'sort_id' => $request->sort_id,
                'updated_at' => Carbon::now(),
            ];
            $nav = ProjectNavItems::where('id', $request->nav_id)->update($data);
            $this->createLog($request->nav_id, 'updated', 'Navbar updated', $request->title);
            return response()->json(['status' => 200, 'success' => $request->nav_id]);
        } else {
            return response()->json(['status' => 404, 'error' => 'This title is already taken !']);
        }
    }

    public function boardListMove(Request $request)
    {
        $id = $request->id;
        $type = $request->type;
        $nav_id = $request->target;

        if ($type == 'list') {
            Multiple_list::where('id', $id)->update(['nav_id' => $nav_id]);
        } else {
            Multiple_board::where('id', $id)->update(['nav_id' => $nav_id]);
        }

        return response()->json(['success' => 1]);

    }

    public function moveSelectedTask(Request $request)
    {
        $ids = $request->ids;
        $target_nav_id = $request->nav;
        $target_listOrBoard = $request->target;
        $nav = ProjectNavItems::where('id', $target_nav_id)->first();
        if ($nav->type == 'board') {

            foreach ($ids as $id) {
                $task_child = Task::where(['multiple_board_id' => $target_listOrBoard, 'board_parent_id' => $request->column_id])->orderBy('board_sort_id', 'desc')->first();
                $column = Task::where(['id' => $request->column_id])->first();

                if ($task_child) {
                    $board_sort_id = $task_child->board_sort_id;
                } else {
                    $board_sort_id = 0;
                }
                Task::where('id', $id)
                    ->update(['multiple_board_id' => $target_listOrBoard, 'board_parent_id' => $request->column_id, 'board_sort_id' => $board_sort_id + 1,'progress'=>$column->progress]);
            }
            return response()->json(['status' => 'success', 'board']);
        } elseif ($nav->type == 'list') {
            $task = Task::where(['list_id' => $target_listOrBoard, 'parent_id' => 0])->orderBy('sort_id', 'desc')->first();
            $sort_id = $task->sort_id + 1;
            foreach ($ids as $id) {
                Task::where('id', $id)->update(['list_id' => $target_listOrBoard, 'parent_id' => 0, 'sort_id' => $sort_id]);
                $childs = Task::where('parent_id', $id)->get();
                $this->moveWithChild($childs, $target_listOrBoard);
                $sort_id++;
            }
            return response()->json(['status' => 'success', 'list']);
        }


    }

    public function moveWithChild($childs, $list_id)
    {
        foreach ($childs as $child) {
            Task::where('id', $child->id)->update(['list_id' => $list_id]);
            $childrens = Task::where('parent_id', $child->id)->get();
            if ($childrens->count() > 0) {
                $this->moveWithChild($childrens, $list_id);
            }
        }
    }
}
