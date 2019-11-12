<?php

namespace App\Http\Controllers;

use App\Rules;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RulesController extends Controller
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
        $data = [
            'name' => $request->name,
            'status' => $request->status,
            'project_id' => (int)$request->project_id,
            'move_from' => $request->move_from,
            'move_to' => $request->move_to,
            'created_by' => Auth::id(),
            'assigned_users' => ($request->assign_to != 'Assign To') ? $request->assign_to : 0,
        ];
        if (Rules::create($data)) {
            return response()->json(['status' => 'success']);
        } else {
            return response()->json(['status'=>'failed']);
        }

    }

    public function show(Rules $rules)
    {
        //
    }


    public function edit(Rules $rules)
    {
        //
    }


    public function update(Request $request, Rules $rules)
    {
        //
    }


    public function destroy(Rules $rules)
    {
        //
    }
}
