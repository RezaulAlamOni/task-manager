<?php

namespace App\Http\Controllers;

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

        $data = $request->data;
        foreach ($data as $key => $item) {
            Multiple_list::where('id', (int)$item)->update(['sort_id' => $key]);
        }
        return response()->json(['status' => 'success']);


    }
}
