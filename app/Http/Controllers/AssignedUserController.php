<?php

namespace App\Http\Controllers;

use App\AssignedUser;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AssignedUserController extends Controller
{

    public function index()
    {
        //
    }


    public function create()
    {
        //
    }


    public function store(Request $request)
    {
        $checkIsUserAssigned = AssignedUser::where(['task_id'=>$request->task_id,'user_id'=>$request->user_id])->count();
        if ($checkIsUserAssigned <= 0){
            AssignedUser::create([
                'task_id'=>$request->task_id,
                'user_id'=>$request->user_id,
                'created_at'=>Carbon::now(),
                'updated_at'=>Carbon::now(),
                'created_by'=>Auth::id(),
                'updated_by'=>Auth::id(),
            ]);
            return response()->json('success');
        }else{
            return response()->json('already added');
        }

    }

    public function delete(Request $request)
    {
        AssignedUser::where($request->all())->delete();
        return response()->json('success');
    }


    public function edit(AssignedUser $assignedUser)
    {
        //
    }

    public function update(Request $request, AssignedUser $assignedUser)
    {
        //
    }


    public function destroy(AssignedUser $assignedUser)
    {
        //
    }
}
