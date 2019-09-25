<?php

namespace App\Http\Controllers;

use App\Multiple_board;
use App\Multiple_list;
use App\TaskBoard;
use App\Project;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MultipleBoardController extends Controller
{
    protected $actionLog;
    public function __construct()
    {
        date_default_timezone_set('UTC');
        $this->actionLog =new ActionLogController;
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $boards = [];
        $board = TaskBoard::where('parent_id',0)
                        ->where('project_id',$request->projectId)
                        ->where('nav_id',$request->nav_id)
                        ->where('multiple_board_id',$request->board_id)
                        ->orderby('sort_id','ASC')
                        ->get();
        foreach ($board as $key => $value) {
            $boards[$key]['id'] = $value['id'];
            $boards[$key]['column'] = $value['title'];
            $boards[$key]['hidden'] = 0;
            $boards[$key]['progress'] = $value['progress'];
            $boards[$key]['color'] = $value['color'];
            $tasks = TaskBoard::where('parent_id',$value->id)->get();
            if(count($tasks) > 0){
                foreach ($tasks as $keys => $values) {
                    $boards[$key]['task'][$keys]['id'] = $values['id'];
                    $boards[$key]['task'][$keys]['name'] = $values['title'];
                    $boards[$key]['task'][$keys]['date'] = date('d M', strtotime($values['date']));
                    $boards[$key]['task'][$keys]['tags'] = json_decode($values['tags']);
                }
            } else {
                $boards[$key]['task'] = [];
            }
        }
        // return $boards;
        return response()->json(['success'=>$boards]);
    }


    public function create(Request $request)
    {
        // return $request->all();
        $sortNo = TaskBoard::max('sort_id');
        $data = TaskBoard::create([
            'multiple_board_id' => $request->multiple_board_id,
            'nav_id' => $request->nav_id,
            'project_id' => $request->project_id,
            'title' => $request->title,
            'color' => $request->color,
            'parent_id' => 0,
            'hidden' => 0,
            'sort_id' => $sortNo+1,
            'created_by' => Auth::id(),
            'date' => Carbon::today(),
            'created_at' => Carbon::today(),
        ]);
        // return $data;
        if($data){
            return response()->json(['success'=> true, 'data' => $data]);
        }
    }

    public function store(Request $request)
    {
        $id = Multiple_board::create([
            'project_id' => $request->project_id,
            'nav_id' => $request->nav_id,
            'board_title' => $request->name,
            'description' => $request->description,
            'created_at' => Carbon::today(),
        ]);
        $multiple_board = Project::findOrFail($request->project_id);
        $multiple_board = $multiple_board->multiple_board;
        $log_data = [
            'multiple_board_id'=>$id->id,
            'title'=>$request->name,
            'log_type'=>'Create board',
            'action_type'=>'created',
            'action_by'=>Auth::id(),
            'action_at'=>Carbon::now()
        ];
        $this->actionLog->store($log_data);

        return response()->json(['multiple_board' => $multiple_board,'id'=>$id]);
    }


    public function cardAdd(Request $request)
    {
        $id = $request->id;
        $parent = TaskBoard::find($id);
        $sortNo = TaskBoard::max('sort_id');
        $data = [
            'title' => '',
            'sort_id' => $sortNo+1,
            'parent_id' => $parent->id,
            'project_id' =>  $parent->project_id,
            'nav_id' =>  $parent->nav_id,
            'multiple_board_id' =>  $parent->multiple_board_id,
            'hidden' =>  0,
            'date' =>  Carbon::now(),
            'created_by' => Auth::id(),
            'created_at' => Carbon::now()
        ];
        $child = TaskBoard::create($data);
        if($child){
            return response()->json(['success' => true, 'data' => $child]);
        }
        return response()->json(['success' => false]);
    }

    public function cardEdit(Request $request)
    {
        $id = $request->cardId;
        $data = [
            'title' => $request->data,
        ];
        $child = TaskBoard::where('id', $id)->update($data);
        if($child){
            $datas = TaskBoard::find($id);
            return response()->json(['success' => true, 'data' => $datas]);
        }
        return response()->json(['success' => false]);
    }

    public function edit(Multiple_board $multiple_board)
    {
        //
    }

    public function update(Request $request)
    {
        // return $request->all();
        $data = [
            'title' => $request->column,
            'color' => $request->color,
            'progress' => $request->progress
        ];
        if($request->id || $request->id != ''){
            $update = TaskBoard::where('id', $request->id)->update($data);
            if($update){
                return response()->json(['success' => true, 'data' => $update]);
            }
        }
        return response()->json(['success' => false]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Multiple_board  $multiple_board
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $delete = TaskBoard::where('id',$id)
                ->orWhere('parent_id',$id)
                ->delete();
        if($delete){
            return response()->json(['success' => true]);
        } else {
            return response()->json(['success' => false]);
        }
    }

    public function cardDelete($id)
    {
        $delete = TaskBoard::where('id',$id)
                ->delete();
        if($delete){
            return response()->json(['success' => true]);
        } else {
            return response()->json(['success' => false]);
        }
    }
}
