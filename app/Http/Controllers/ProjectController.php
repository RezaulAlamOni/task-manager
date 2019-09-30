<?php

namespace App\Http\Controllers;

use App\Project;
use App\Team;
use Carbon\Carbon;
use http\Env\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    protected $actionLog;
    public function __construct()
    {
        date_default_timezone_set('UTC');
        $this->actionLog =new ActionLogController;
        $this->middleware('auth');
    }

    public function getAll(){
        $team = Team::where('owner_id',Auth::id())->first();
        $Projects = Project::where('team_id',$team->id)->get();
        return $this->success(compact($Projects, 'Projects'));
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
        $team = Team::where('owner_id',Auth::id())->first();
        $data = [
            'team_id'=>$team->id,
            'name' => $request->title,
            'description' => $request->description,
            'created_by' => Auth::id(),
            'updated_by' => Auth::id(),
        ];
        $project = Project::create($data);

        if ($project){
            $log_data = [
                'project_id'=>$project->id,
                'multiple_list_id'=>null,
                'task_id'=>null,
                'multiple_board_id'=>null,
                'board_id'=>null,
                'title'=>$request->title,
                'log_type'=>'Create project',
                'action_type'=>'created',
                'action_by'=>Auth::id(),
                'action_at'=>Carbon::now()
            ];
            $this->actionLog->store($log_data);
            return response()->json(['success'=>1]);
        }

    }

    /**
     * Display the specified resource.
     *
     * @param Request $request
     * @return void
     */
    public function show(Request $request)
    {
        $project = Project::findOrFail($request->id);
        $multiple_list = Project::with('multiple_list')->findOrFail($request->id);
        $multiple_list = $multiple_list->multiple_list;

        return response()->json(['success'=>1,'project'=>$project,'multiple_list'=>$multiple_list]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function edit(Project $project)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Project $project)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request
     * @return void
     */
    public function destroy(Request $request)
    {
        Project::findOrFail($request->id)->delete();
        return response()->json(['success'=>1]);
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
}
