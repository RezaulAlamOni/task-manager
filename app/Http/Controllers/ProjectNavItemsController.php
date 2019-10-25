<?php

namespace App\Http\Controllers;

use App\Multiple_board;
use App\Multiple_list;
use App\ProjectNavItems;
use Carbon\Carbon;
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
            $item->lists = $this->getList($project_id, $item->id, $item->type);
            $navItem[] = $item;
        }
        return response()->json(['success' => $navItem]);
    }

    public function allBoard($project_id)
    {
        $navItem = [];
        $nav = ProjectNavItems::where('project_id', $project_id)->where('type','board')->orderBy('sort_id', 'asc')->get();
        foreach ($nav as $item) {
            $item->lists = $this->getList($project_id, $item->id, $item->type);
            $navItem[] = $item;
        }
        return response()->json(['success' => $navItem]);
    }

    public function getList($project_id, $nav_id, $type)
    {
        if ($type == 'list') {
            $list = Multiple_list::where(['project_id' => (int)$project_id, 'nav_id' => $nav_id])->get();
            $list->type = $type;
            return $list;
        } else {
            $board = Multiple_board::where(['project_id' => (int)$project_id, 'nav_id' => $nav_id])->get();
            $board->type = $type;
            return $board;
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
            return response()->json(['success' => $nav ,'status'=>'create']);
        } else {
            return response()->json(['success' => 'This title is already taken !','status'=>'exists']);
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

    public function update(Request $request, ProjectNavItems $projectNavItems)
    {
        //
    }

    public function destroy(ProjectNavItems $projectNavItems)
    {
        //
    }
}
