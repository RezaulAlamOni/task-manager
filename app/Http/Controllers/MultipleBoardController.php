<?php

namespace App\Http\Controllers;

use App\Multiple_board;
use App\Multiple_list;
use App\TaskBoard;
use App\Tags;
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
            $boards[$key]['hidden'] = $value['hidden'];
            $boards[$key]['progress'] = $value['progress'];
            $boards[$key]['color'] = $value['color'];
            $tasks = TaskBoard::with('tags')->where('parent_id',$value->id)->orderby('sort_id','ASC')->get();
            if(count($tasks) > 0){
                $tagTooltip = '';
                foreach ($tasks as $keys => $values) {
                    $allTags = Tags::where('board_id',$values['id'])->get();
                    $tags = [];

                    if ($allTags->count() > 0){
                        foreach ($allTags as $tagkey => $tag) {
                            $tags[$tagkey]['id'] = $tag->id;
                            $tags[$tagkey]['board_id'] = $tag->board_id;
                            $tags[$tagkey]['text'] = $tag->title;
                            $tags[$tagkey]['classes'] = '';
                            $tags[$tagkey]['style'] = 'background-color: '.$tag->color;
                            $tags[$tagkey]['color'] = $tag->color;
                            $tagTooltip .= '#'.$tag->title.' ';
                        }
                        // return $tagTooltip;
                    }
                    $boards[$key]['task'][$keys]['tags'] = $tags;
                    $boards[$key]['task'][$keys]['tagTooltip'] = $tagTooltip;

                    $boards[$key]['task'][$keys]['id'] = $values['id'];
                    $boards[$key]['task'][$keys]['name'] = $values['title'];
                    $boards[$key]['task'][$keys]['date'] = date('d M', strtotime($values['date']));
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
        $sortNo = TaskBoard::where('parent_id',0)->max('sort_id')   ;
        $data = TaskBoard::create([
            'multiple_board_id' => $request->multiple_board_id,
            'nav_id' => $request->nav_id,
            'project_id' => $request->project_id,
            'title' => $request->title,
            'color' => $request->color,
            'progress' => $request->progress,
            'parent_id' => 0,
            'hidden' => 0,
            'sort_id' => $sortNo+1,
            'created_by' => Auth::id(),
            'updated_by' => Auth::id(),
            'date' => Carbon::today(),
            'created_at' => Carbon::today(),
        ]);
        // return $data;
        if($data){
            return response()->json(['success'=> true, 'data' => $data]);
        }else{
            return response()->json(['success'=> false]);
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
        $sortNo = TaskBoard::where('parent_id', $parent->id)->max('sort_id');
        if(!$sortNo){
            $sortNo = 0;
        }
        $data = [
            'title' => '',
            'sort_id' => $sortNo+1,
            'parent_id' => $parent->id,
            'project_id' =>  $parent->project_id,
            'multiple_board_id' =>  $parent->multiple_board_id,
            'hidden' =>  0,
            'date' =>  Carbon::now(),
            'created_by' => Auth::id(),
            'updated_by' => Auth::id(),
            'created_at' => Carbon::now()
        ];
        $child = TaskBoard::create($data);
        if($child){
            return response()->json(['success' => true, 'data' => $child]);
        }
        return response()->json(['success' => false]);
    }

    public function cardEdit($id,Request $request)
    {
        $data = [];
        foreach ($request->all() as $key => $value) {
            if($key == 'date'){
                $value = date('Y-m-d',strtotime($value));
            }
            $data[$key] = $value;
        }
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
            'title' => $request->name,
            'color' => $request->color,
            'progress' => $request->progress
        ];
        if($request->boardId || $request->boardId != ''){
            $update = TaskBoard::where('id', $request->boardId)->update($data);
            if($update){
                return response()->json(['success' => true, 'data' => $update]);
            }
        }
        return response()->json(['success' => false]);
    }

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


    public function deleteAllBoardWiseCards($id)
    {
        $delete = TaskBoard::Where('parent_id',$id)->delete();
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

    public function hideColumn($id, Request $request)
    {
        $hide = TaskBoard::where('id',$id)
                ->update([
                    'hidden' => $request->hide
                ]);
        if($hide){
            return response()->json(['success' => true]);
        } else {
            return response()->json(['success' => false]);
        }
    }
}
