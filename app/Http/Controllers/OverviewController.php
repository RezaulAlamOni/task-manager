<?php

namespace App\Http\Controllers;

use App\ProjectNavItems;
use Illuminate\Http\Request;

class OverviewController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index($project_id){
        $all_list_nav = ProjectNavItems::where('type','list')->with('ALL_list')->get();

        return response()->json(['data'=>$all_list_nav]);
    }
}
