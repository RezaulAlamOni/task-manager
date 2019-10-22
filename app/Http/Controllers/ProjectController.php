<?php

namespace App\Http\Controllers;

use App\Project;
use App\Team;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    protected $actionLog;

    public function __construct()
    {
        date_default_timezone_set('UTC');
        $this->actionLog = new ActionLogController;
        $this->middleware('auth');
    }

    public function getAll(Request $request)
    {
        $team_id = Auth::user()->current_team_id;
        $Projects = Project::where('team_id', $team_id)->get();
        if (isset($request->render)) {
            $project = view('vendor.spark.layouts.leftmenuProjects', ['projects' => $Projects])->render();
            return $project;
        } else {
            return $this->success(compact($Projects, 'Projects'));
        }

    }

    public function success($items = null, $status = 200)
    {
        $data = ['status' => 'success'];

        if ($items instanceof Arrayable) {
            $items = $items->toArray();
        }

        if ($items) {
            foreach ($items as $key => $item) {
                $data[$key] = $item;
            }
        }

        return response()->json($data, $status)->setEncodingOptions(JSON_NUMERIC_CHECK);
    }

    public function index()
    {
        return view('projects');
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        $team_id = Auth::user()->current_team_id;
        $data = [
            'team_id' => $team_id,
            'name' => $request->title,
            'description' => $request->description,
            'created_by' => Auth::id(),
            'updated_by' => Auth::id(),
        ];
        $check_project = Project::Where(['team_id' => $team_id, 'name' => $request->title])->count();
        if ($check_project <= 0) {
            $project = Project::create($data);
            if ($project) {
                $log_data = [
                    'project_id' => $project->id,
                    'multiple_list_id' => null,
                    'task_id' => null,
                    'multiple_board_id' => null,
                    'board_id' => null,
                    'title' => $request->title,
                    'log_type' => 'Create project',
                    'action_type' => 'created',
                    'action_by' => Auth::id(),
                    'action_at' => Carbon::now()
                ];
                $this->actionLog->store($log_data);
                return response()->json(['success' => 1]);
            }
        }

    }


    public function show(Request $request)
    {
        $project = Project::findOrFail($request->id);
        $multiple_list = Project::with('multiple_list')->findOrFail($request->id);
        $multiple_list = $multiple_list->multiple_list;

        return response()->json(['success' => 1, 'project' => $project, 'multiple_list' => $multiple_list]);
    }

    public function UpdateUserCurrentTeam(Request $request)
    {
        if (isset($request->team_id)) {
            User::where('id', Auth::id())->update(['current_team_id' => $request->team_id]);
            return \response()->json(['status' => 'success', 'data' => 1]);
        }
    }


    public function update(Request $request, Project $project)
    {
        //
    }


    public function destroy(Request $request)
    {
        Project::findOrFail($request->id)->delete();
        return response()->json(['success' => 1]);
    }
}
