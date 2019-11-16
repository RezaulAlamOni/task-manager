<?php

namespace App\Http\Controllers;

use App\Rules;
use App\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Sabberworm\CSS\Rule\Rule;

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
            'assigned_users' => $request->assign_to
        ];
        if (Rules::create($data)) {
            return response()->json(['status' => 'success']);
        } else {
            return response()->json(['status' => 'failed']);
        }

    }

    public function show($id)
    {
        $rule = Rules::findOrFail($id);
        return response()->json(['rule' => $rule]);
    }


    public function edit(Rules $rules)
    {
        //
    }


    public function update(Request $request)
    {
        $data = [
            'name' => $request->name,
            'status' => $request->status,
            'move_from' => $request->move_from,
            'move_to' => $request->move_to,
            'created_by' => Auth::id(),
            'assigned_users' => $request->assign_to,
            'updated_at' => now(),
        ];
        $update = Rules::where('id', $request->id)->update($data);

        return response()->json(['status' => 'success', 'data' => $update]);
    }


    public function delete(Request $request)
    {
        if (Rules::find($request->id)->delete()) {
            return response()->json(['status' => 'success']);
        }
    }

    public function MoveAllCard($move_from, $move_to)
    {
        $move_to_column = Task::where('id', $move_to)->first();
        $check = Task::where('board_parent_id', $move_from)
            ->Update([
                    'board_parent_id' => $move_to,
                    'multiple_board_id' => $move_to_column->multiple_board_id
                ]
            );
        $all_task_for_rule_update = Task::where('board_parent_id', $move_to)->get();
        if ($check) {
            return true;
        } else {
            return false;
        }
    }
}
