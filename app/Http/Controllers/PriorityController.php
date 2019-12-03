<?php

namespace App\Http\Controllers;

use App\Task;
use Illuminate\Http\Request;

class PriorityController extends Controller
{
    public function AddPriority(Request $request){
        $ids = $request->ids;
        $priority = $request->priority;

        foreach ($ids as $id) {
            Task::where('id',$id)->update(['priority_label'=>$priority]);
        }

        return response()->json(['status'=>'success']);
    }
}
