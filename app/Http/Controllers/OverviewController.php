<?php

namespace App\Http\Controllers;

use App\ProjectNavItems;
use App\Task;
use Illuminate\Http\Request;

class OverviewController extends Controller
{
    protected $TaskController;
    public function __construct()
    {
        $this->middleware('auth');
        $this->TaskController = New TaskController();
    }

    public function index($project_id){

        $all_list_navs = ProjectNavItems::where(['type'=>'list','project_id'=>$project_id])->with('All_list')->get();

        foreach ($all_list_navs as $all_list_nav) {
            foreach ($all_list_nav->all_list as $item) {
                $item->tasks = $this->TaskController->decorateData($item->tasks_list,'drag');
            }
        }
        return response()->json(['data'=>$all_list_navs]);
    }
}
