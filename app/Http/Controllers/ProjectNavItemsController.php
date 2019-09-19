<?php

namespace App\Http\Controllers;

use App\Multiple_board;
use App\Multiple_list;
use App\ProjectNavItems;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PhpParser\Node\Expr\New_;

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
        $nevItem = [];
        $nev = ProjectNavItems::where('project_id',$project_id)->orderBy('sort_id','asc')->get();
        foreach ($nev as $item) {
            $item->lists = $this->getList($project_id,$item->id,$item->type);
            $nevItem[] = $item;
        }
        return response()->json(['success'=>$nevItem]);
    }

    public function getList($project_id, $nev_id, $type){
        if ($type == 'list'){
            $list = Multiple_list::where(['project_id'=>(int)$project_id,'nav_id'=>$nev_id])->get();
            return $list;
        }else {
            $board = Multiple_board::where(['project_id'=>(int)$project_id,'nav_id'=>$nev_id])->get();
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
            'project_id'=>$request->project_id,
            'title'=>$request->title,
            'type'=>$request->type,
            'sort_id'=>$request->sort_id,
            'created_at'=>Carbon::now(),
        ];
        $check = ProjectNavItems::where('title',$request->title)->where('project_id',$request->project_id)->count();
        if ($check <= 0){
            $nev = ProjectNavItems::create($data);
            $this->createLog($nev->id,'created','Navbar Create',$request->title);
            return response()->json(['success'=>$nev]);
        }else{
            return response()->json(['success'=>'This title is already taken !']);
        }

    }


    public function show(ProjectNavItems $projectNavItems)
    {
        //
    }


    public function edit(Request $request)
    {
        $check = ProjectNavItems::where('title',$request->title)->where('project_id',$request->project_id)->count();
        if ($check <= 1){
            $nab = ProjectNavItems::findOrFail($request->nev_id);

            if ($nab->sort_id > $request->sort_id){
                ProjectNavItems::where('project_id', $request->project_id)
                    ->where('sort_id', '<', $nab->sort_id)
                    ->where('sort_id', '>=', $request->sort_id)
                    ->increment('sort_id');
            }else if ($nab->sort_id < $request->sort_id){
                ProjectNavItems::where('project_id', $request->project_id)
                    ->where('sort_id', '>', $nab->sort_id)
                    ->where('sort_id', '<=', $request->sort_id)
                    ->decrement('sort_id');
            }
            $data = [
                'title'=>$request->title,
                'sort_id'=>$request->sort_id,
                'updated_at'=>Carbon::now(),
            ];
            $nev = ProjectNavItems::where('id',$request->nev_id)->update($data);
            $this->createLog($request->nev_id,'updated','Navbar updated',$request->title);
            return response()->json(['status'=>200, 'success'=>$request->nev_id]);
        }else{
            return response()->json(['status'=>404,'error'=>'This title is already taken !']);
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
}
