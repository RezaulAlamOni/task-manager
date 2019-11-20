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

        $all_list_nav = ProjectNavItems::where('type','list')->with('All_list')->get();

//        $total_nav_list = $all_list_nav->count();
//        $list = array();
//        for ($i = 0;$i < $total_nav_list ; $i++ ){
//            foreach ($all_list_nav[$i]->All_list as $item) {
//
//            }
//        }
        return response()->json(['data'=>$all_list_nav[0]->All_list]);
    }
}
