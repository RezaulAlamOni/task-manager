<?php

namespace App\Http\Controllers;

use App\ExistingTasksInBoard;
use App\Multiple_board;
use App\Multiple_list;
use App\TaskBoard;
use App\Tags;
use App\Task;
use App\Project;
use App\User;
use App\AssignedUser;
use App\AssignTag;
use App\LinkListToColumn;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use DB;

class MultipleBoardController extends Controller
{
    protected $actionLog;
    protected $dont_forget_tag;
    protected $totalChild = 0;
    protected $childIds = [];

    public function __construct()
    {
        date_default_timezone_set('UTC');
        $this->actionLog = new ActionLogController;
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $boards = [];
        $board = Task::where('board_parent_id', 0)
                ->with('task')
                ->with('linkToList')
                ->where('project_id', $request->projectId)
                // ->where('nav_id', $request->nav_id)
                ->where('multiple_board_id', $request->board_id)
                ->orderby('board_sort_id', 'ASC')
                ->orderby('parent_id', 'ASC')
                // ->orderby('sort_id', 'ASC')
                ->get();
        $team_id = Auth::user()->current_team_id;
        foreach ($board as $key => $value) {
            $keys = -1;
            $boards[$key]['id'] = $value['id'];
            $boards[$key]['parent_id'] = $value['parent_id'];
            $boards[$key]['column'] = $value['title'];
            $boards[$key]['hidden'] = $value['hidden'];
            $boards[$key]['progress'] = $value['progress'];
            $boards[$key]['linkToList'] = $value['linkToList'];
            $boards[$key]['color'] = $value['color'];
            
            if (!empty($value['task']) && count($value['task']) > 0) {
                foreach ($value['task'] as $keys => $values) {
                                        
                    $tagTooltip = '';
                    $tags = [];
                    if (!empty($values['Assign_tags']) && count($values['Assign_tags']) > 0) {
                        foreach ($values['Assign_tags'] as $tagkey => $tag) {
                                if (!empty($tag->tag)){
                                    $infoTags = array(
                                        'assign_id' => $tag->id,
                                        'id' => $tag->tag->id,
                                        'board_id' => $tag->task_id,
                                        'text' => $tag->tag->title,
                                        'classes' => '',
                                        'style' => 'background-color: ' . $tag->tag->color,
                                        'color' => $tag->tag->color,
                                    );
                                    $tagTooltip .= '#' . $tag->tag->title . ' ';
                                    $tags[$tagkey] = $infoTags;
                                }
                        }
                    }

                    $boards[$key]['task'][$keys]['assigned_user'] = AssignedUser::join('users', 'task_assigned_users.user_id','users.id')->where('task_id', $values['id'])->get()->toArray();

                    $assigned_user_ids = [];
                    foreach ($boards[$key]['task'][$keys]['assigned_user'] as $id) {
                        $assigned_user_ids[] = $id['id'];
                    }

                    $boards[$key]['task'][$keys]['assigned_user_ids'] = $assigned_user_ids;
                    $team = DB::table('team_users')->where('user_id', Auth::id())->first();
                    $boards[$key]['task'][$keys]['users'] = User::join('team_users', 'team_users.user_id', 'users.id')
                                                            ->where('team_users.team_id', $team->team_id)->get()->toArray();


                    $boards[$key]['task'][$keys]['tags'] = $tags;
                    $boards[$key]['task'][$keys]['tagTooltip'] = $tagTooltip;

                    if( $values['childTask'] !== null ){
                        $this->totalChild = 0;
                        $boards[$key]['task'][$keys]['child'] = $this->recurChild($values['childTask']);
                        
                    } else {
                        $boards[$key]['task'][$keys]['child'] = 0;
                    }

                    $boards[$key]['task'][$keys]['children'] = $values['childTask'];
                    $boards[$key]['task'][$keys]['parents'] = $values['parents'];
                    $boards[$key]['task'][$keys]['id'] = $values['id'];
                    $boards[$key]['task'][$keys]['parent_id'] = $values['parent_id'];
                    $boards[$key]['task'][$keys]['name'] = $values['title'];
                    $boards[$key]['task'][$keys]['textareaShow'] = ($values['title'] !== '')? false : true;
                    $boards[$key]['task'][$keys]['progress'] = $values['progress'];
                    if ($values['list_id'] != '') {
                        $boards[$key]['task'][$keys]['type'] = 'task';
                    } else {
                        $boards[$key]['task'][$keys]['type'] = 'card';
                    }
                    $boards[$key]['task'][$keys]['date'] = ($values['date'] == '0000-00-00')? $values['date'] : date('d M', strtotime($values['date']));
                    $boards[$key]['task'][$keys]['existing_tags'] = Tags::where('team_id', $team_id)->where('title','!=', $this->dont_forget_tag)->get()->toArray();

                }
            } else {
                $boards[$key]['task'] = [];
            }
        }
        return response()->json(['success' => $boards]);
    }

    public function recurChild($child)
    {   
        $this->totalChild += count($child);
        foreach ($child as $key => $value) {
            if ($value['childTask'] !== null) {
                if (count($value['childTask']) > 0) {
                    $this->recurChild($value['childTask']);
                }
            }
        }
        return $this->totalChild;
    }
  
    public function create(Request $request)
    {
        // return $request->all();
        $sortNo = Task::where('board_parent_id', 0)->max('board_sort_id');
        $data = Task::create([
            'multiple_board_id' => $request->multiple_board_id,
            'project_id' => $request->project_id,
            'board_flag' => 1,
            'title' => $request->title,
            'color' => $request->color,
            'progress' => $request->progress,
            'board_parent_id' => 0,
            'hidden' => 0,
            'board_sort_id' => $sortNo + 1,
            'created_by' => Auth::id(),
            'updated_by' => Auth::id(),
            'date' => Carbon::today(),
            'created_at' => Carbon::today(),
        ]);
        if ($data) {
            $this->createLog($data->id, 'created', 'Create Column', 'Board Column Created');
            return response()->json(['success' => true, 'data' => $data]);
        } else {
            return response()->json(['success' => false]);
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
            'multiple_board_id' => $id->id,
            'title' => $request->name,
            'log_type' => 'Create board',
            'action_type' => 'created',
            'action_by' => Auth::id(),
            'action_at' => Carbon::now()
        ];
        $this->actionLog->store($log_data);

        return response()->json(['multiple_board' => $multiple_board, 'id' => $id]);
    }


    public function cardAdd(Request $request)
    {
        $id = $request->id;
        $parent = Task::find($id);
        $sortNo = Task::where('board_parent_id', $parent->id)->max('board_sort_id');
        // if ($sortNo == '') {
        //     $sortNo = 0;
        // }
        $data = [
            'title' => '',
            'board_sort_id' => ++$sortNo,
            'board_parent_id' => $parent->id,
            'project_id' => $parent->project_id,
            'multiple_board_id' => $parent->multiple_board_id,
            'hidden' => 0,
            'board_flag' => 1,
            'progress'=>$parent->progress,
            'date' => '0000-00-00',//Carbon::now(),
            'created_by' => Auth::id(),
            'updated_by' => Auth::id(),
            'created_at' => Carbon::now()
        ];
        $child = Task::create($data);
        if ($child) {
            $this->createLog($child->id, 'created', 'Create Card', 'Board Card Created');
            return response()->json(['success' => true, 'data' => $child]);
        }
        return response()->json(['success' => false]);
    }

    public function changeParentId(Request $request)
    {
        //  $request->all();
        $parent = Task::find($request->board_parent_id);
        $parent_task = Task::find($request->id);
        $data = Task::where('id',$request->id)
                        ->with('childTask')
                        ->get();
        $ids[] = $request->id;
        foreach ($data as $childs) {
            if (count($childs['childTask']) > 0) {
                $ids = $this->recurChildIds($childs);
            }
        } 
        // dd($data);
        $update = Task::where('board_parent_id',"!=",0)
                    ->whereIn('id', $ids)
                    ->update([
                        'board_sort_id' => $parent_task->board_sort_id,
                        'board_parent_id' => $request->board_parent_id,
                        'progress'=> $parent->progress
                    ]);
        if ($update) {
            $this->createLog($request->id, 'Update', 'Parent changed', 'Board Card Parent Changed');
            return response()->json(['success' => true, 'data' => $update]);
        }
        return response()->json(['success' => false]);
    }

    public function recurChildIds($child)
    {
        $this->childIds[] = $child['id'];
        if(count($child['childTask']) > 0){
            foreach ($child['childTask'] as $key => $value) {
                $this->recurChildIds($value);
            }
        }
        return $this->childIds;
    }

    public function cardEdit($id, Request $request)
    {
        $data = [];
        foreach ($request->all() as $key => $value) {
            if ($key == 'date') {
                $value = date('Y-m-d', strtotime($value));
            }
            $data[$key] = $value;
        }
        $child = Task::where('id', $id)->update($data);
        if ($child) {
            $datas = Task::find($id);
            $this->createLog($id, 'Update', 'Card Update', 'Board Card Updated');
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
        $data = [
            'title' => $request->name,
            'color' => $request->color,
            'progress' => $request->progress
        ];
        if ($request->boardId || $request->boardId != '') {
            $update = Task::where('id', $request->boardId)->update($data);
            $update = Task::Where('board_parent_id',$request->boardId)->update(['progress' => $request->progress]);
            if ($update) {
                $this->createLog($request->boardId, 'Update', 'Column Update', 'Board Column Updated');
                return response()->json(['success' => true, 'data' => $update]);
            }
        }
        return response()->json(['success' => false]);
    }

    public function destroy($id)
    {
        $child  = Task::where('board_parent_id', $id)->get();
        foreach ($child as $key => $value) {
            $this->cardDelete($value->id);
        }
        $delete = Task::where('id', $id)
            ->orWhere('board_parent_id', $id)
            ->delete();
        if ($delete) {
            $this->createLog($id, 'Delete', 'Column Deleted', 'Board Column Deleted With All Card');
            return response()->json(['success' => true]);
        } else {
            return response()->json(['success' => false]);
        }
    }


    public function deleteAllBoardWiseCards($id)
    {
        $cards = Task::Where('board_parent_id', $id)->get();
        foreach ($cards as $key => $value) {
            $delete = $this->cardDelete($value->id);
        }
        if ($delete) {
            // $this->createLog($id, 'Delete', 'Card Deleted', 'Board All Card Deleted');
            return response()->json(['success' => true]);
        } else {
            return response()->json(['success' => false]);
        }
    }

    public function cardDelete($id)
    {
        $cards = Task::where('id', $id)->first();
        if ($cards->list_id === null) {
            $assiagnUser = AssignedUser::where('task_id', $id)->delete();
            $assiagnTag = AssignTag::where('task_id', $id)->delete();
            $delete = Task::where('id', $id)->delete();
            if ($delete) {
                $this->createLog($id, 'Delete', 'Card Deleted', 'Board Single Card Deleted');
                return response()->json(['success' => true]);
            } else {
                return response()->json(['success' => false]);
            }
        } else {
            $delete = $this->existingTaskDelete($id);
            if ($delete) {
                return response()->json(['success' => true]);
            } else {
                return response()->json(['success' => false]);
            }
        }
        return response()->json(['success' => false]);
    }

    public function existingTaskDelete($id)
    {
        $delete = Task::where('id',$id)->update([
            'board_parent_id' => null,
            'board_flag' => null,
            'task_flag' => 1,
            'multiple_board_id' => null
        ]);
        if($delete){
            $this->createLog($id, 'Delete', 'Card Deleted', 'Board Existing Task Card Deleted');
            return response()->json(['success' => true]);
        } else {
            return response()->json(['success' => false]);
        }
    }

    public function hideColumn($id, Request $request)
    {
        $hide = Task::where('id', $id)
            ->update([
                'hidden' => $request->hide
            ]);
        if ($hide) {
            $this->createLog($id, 'Update', 'Column Update', 'Board Column Hide/Show');
            return response()->json(['success' => true]);
        } else {
            return response()->json(['success' => false]);
        }
    }

    public function addExistingTasks(Request $request)
    {
        $board_id = $request->id;
        $parent = Task::find($board_id);
        foreach ($request->tasks as $key => $value) {
            $data = [
                'board_id' => $board_id,
                'task_id' => $value,
                'date' => Carbon::today()
            ];
            $update = Task::where('id', $value)
                            ->update([
                                'board_parent_id' => $board_id,
                                'board_flag' => 1,
                                'progress'=>$parent->progress,
                                'task_flag' => 1,
                                'multiple_board_id' => $parent->multiple_board_id
                            ]);
            // $insert = ExistingTasksInBoard::create($data);
            $task[$key] = Task::where('id', $value)->first();
            $tagTooltip = '';
            $allTags = AssignTag::where('task_id', $task[$key]->id)->with('tag')->get();
            $tags = [];

            if ($allTags->count() > 0) {
                foreach ($allTags as $tagkey => $tag) {
                    $tags[$tagkey]['assign_id'] = $tag->id;
                    $tags[$tagkey]['id'] = $tag->tag->id;
                    $tags[$tagkey]['board_id'] = $tag->task_id;
                    $tags[$tagkey]['text'] = $tag->tag->title;
                    $tags[$tagkey]['classes'] = '';
                    $tags[$tagkey]['style'] = 'background-color: ' . $tag->tag->color;
                    $tags[$tagkey]['color'] = $tag->tag->color;
                    $tagTooltip = '#' . $tag->tag->title . ' ';
                }
                // return $tagTooltip;
            }
            $task[$key]['tag'] = $tags;
            $task[$key]['tagTooltip'] = $tagTooltip;
        }
        $this->createLog($board_id, 'Created', 'Card Create', 'Board Card Create From Existing Task');
        return response()->json(['success' => true, 'data' => $task]);
    }

    // public function cardSort(Request $request)
    // {   
    //     return $request->children;
    //     if(!empty($request->children) && count($request->children) > 0){
    //         $ids = ''; //recurChildIds
    //         $chids = [];
    //         $caseString = '';
    //         $myArray = [];
    //         $myArrays = $request->children;
    //         foreach ($request->children as $key => $item) {
    //             // return $item;
    //             // echo count($myArrays); exit;
    //         // for($key = 0; $key < count($myArrays); $key++ ){
    //             $myArray[] = $item;//$item;
    //             // echo count($myArray); exit;
    //             // $this->childIds[] = $item['cardId'];
    //             $child = Task::where('id',$item['cardId'])
    //                         ->with('childTask')
    //                         ->first();
    //             if (count($child['childTask']) > 0) {
    //                 $datas = $this->recurChildIds($child);
    //                 // return $this->childIds;
    //                 // $array = $request->children;
    //                 // $x = count($request->children);
    //                 // foreach ($child['childTask'] as $keys => $values) {
    //                 //     $chids[] = $values['id'];
    //                 //     $myArray[] = [
    //                 //         // 'childTask' => $values['childTask'],
    //                 //         'cardId' => $values['id'],
    //                 //         'types' => 'type',
    //                 //     ];
    //                 // }
    //             }

    //             if ($request->children[$key]['types'] == 'card' || $request->children[$key]['types'] == 'task') {
    //                 $id = $request->children[$key]['cardId'];
    //                 $caseString .= " when id = '".$id."' then '".$key."'";
    //                 $ids .= " $id,";
    //             }
    //             // return $myArray;
    //             if (count($myArray) > 1) {
    //                 echo $key;
    //                 return  $myArrays = $myArray;
    //             }else {
    //                 break;
    //             }
    //         }
    //         // return $chids;
    //         return $myArrays;
    //         $ids = trim($ids, ',');
    //         $update = DB::update("update task_lists set board_sort_id = CASE $caseString END where id in ($ids) and board_parent_id = $request->boardId");
    //         if ($update) {
    //             $this->createLog($request->boardId, 'Updated', 'Card Updated', 'Board Card sorting');
    //             return response()->json(['success' => true, 'data' => $update]);
    //         } else {
    //             return response()->json(['success' => false, 'data' => $update]);
    //         }
    //     }
    // }


    function findTopParent($data, $d, $parents){
        $key = array_search($d['cardId'], array_column($data, 'cardId'));
        $keyP = array_search($d['parent_id'], array_column($data, 'cardId'));
        $keySP = array_search($d['parent_id'], $parents);
        if($d['parent_id'] !== 0){
            if((string)$keySP == '' && (string)$keyP !== '' && $d['sort_id'] > 0){
                $data[$key]['sort_id'] = $data[$keyP]['sort_id'];
            }
            elseif((string)$keySP !== '' && (string)$keyP !== '' && $d['sort_id'] > 0){
                $keyDP = array_search($parents[$keySP], array_column($data, 'cardId'));
                $data[$key]['sort_id'] = $data[$keyDP]['sort_id'];
            } else {
                $keySP = array_search($data[$key]['cardId'], $parents);
                $data[$key]['sort_id'] = $keySP;
            }
        }
        return $data;    
    }
    public function cardSort(Request $request)
    {   
        // return $request->children;
        if(!empty($request->children) && count($request->children) > 0){
            $ids = '';
            $caseString = '';

            $allIds = array_column($request->children, 'cardId');
            $parents = [];
            $allData = $request->children;
            foreach ($request->children as $key=>$item) {
                if($item['parent_id'] == 0){
                    $parents[] = $item['cardId'];
                } else {
                    $ind = (string)array_search($item['parent_id'], $allIds);
                    if($ind == ""){
                        $parents[] = $item['cardId'];
                    }
                }
                $allData[$key]['sort_id'] = $key;
            }

            foreach ($allData as $d) {
                $allData = $this->findTopParent($allData, $d, $parents);
            }
            // return $allData;
            foreach ($allData as $key => $item) {
                if ($item['types'] == 'card' || $item['types'] == 'task') {
                    $id = $item['cardId'];
                    $caseString .= " when id = '".$id."' then '".$item['sort_id']."'";
                    $ids .= " $id,";
                }
            }

            $ids = trim($ids, ',');
            $update = DB::update("update task_lists set board_sort_id = CASE $caseString END where id in ($ids) and board_parent_id = $request->boardId");
            if ($update) {
                $this->createLog($request->boardId, 'Updated', 'Card Updated', 'Board Card sorting');
                return response()->json(['success' => true, 'data' => $update]);
            } else {
                return response()->json(['success' => false, 'data' => $update]);
            }
        }
    }

    public function columnSort(Request $request)
    {
        if(!empty($request->children) && count($request->children) > 0){
            $ids = '';
            $caseString = '';
            foreach ($request->children as $key => $item) {
                $id = $item['boardId'];
                $caseString .= " when id = '".$id."' then '".$key."'";
                $ids .= " $id,";
            }
            $ids = trim($ids, ',');
            $update = DB::update("update task_lists set board_sort_id = CASE $caseString END where id in ($ids) and board_parent_id = 0");
            if ($update) {
                $this->createLog($id, 'Updated', 'Column Updated', 'Board Column sorting');
                return response()->json(['success' => true, 'data' => $update]);
            } else {
                return response()->json(['success' => false, 'data' => $update]);
            }
        }
    }

    public function getAllColumnBylist(Request $request){

        $column = Task::where('board_parent_id',0)->where('multiple_board_id',$request->list_id)->get();
        if ($column) {
            // $this->createLog($id, 'Updated', 'Column Updated', 'Board Column sorting');
            return response()->json(['success' => true, 'data' => $column]);
        } else {
            return response()->json(['success' => false]);
        }
    }

    public function transferToAnotherBoard(Request $request)
    {
        $sortNo = Task::where('board_parent_id', $request->board_parent_id)->max('board_sort_id');
        $data = Task::where('id', $request->cardId)
                ->update([
                    'board_parent_id' => $request->board_parent_id,
                    'board_sort_id' => $sortNo+1
                ]);
        if ($data) {
            // $this->createLog($id, 'Updated', 'Column Updated', 'Board Column sorting');
            return response()->json(['success' => true, 'data' => $data]);
        } else {
            return response()->json(['success' => false]);
        }
    }

    public function transferColumnToAnotherBoard(Request $request){
        // return $request->all();
        $sortNo = Task::where('board_parent_id', 0)->max('board_sort_id');
        $data = Task::where('id', $request->columnId)
                    ->update([
                        'multiple_board_id' => $request->multiple_board_id,
                        'board_sort_id' => $sortNo+1
                    ]);
        if ($data) {
            // $this->createLog($id, 'Updated', 'Column Updated', 'Board Column sorting');
            return response()->json(['success' => true, 'data' => $data]);
        } else {
            return response()->json(['success' => false]);
        }
    }

    public function linkListToColumn(Request $request)
    {
        $data = [
            'multiple_list_id' => $request->multiple_list,
            'task_list_id' => $request->columnId,
        ];

        $insert = LinkListToColumn::create($data);
        if ($insert) {
            $col = Task::where('id', $request->columnId)->first();
            $update = Task::where('project_id',$request->projectId)
                        ->where('list_id',$request->multiple_list)
                        ->where('board_parent_id',null)
                        ->update([
                            'board_parent_id' => $request->columnId,
                            'progress' => $col->progress
                        ]);
            return response()->json(['success' => true, 'data' => $insert]);
        } else {
            return response()->json(['success' => false]);
        }
    }

    public function unlinkListToColumn(Request $request)
    {   
        $delete = LinkListToColumn::where('task_list_id',$request->columnId)->first();
        if ($delete) {
            // $update = Task::where('project_id',$request->projectId)
            //             ->where('list_id',$delete->multiple_list_id)
            //             ->where('board_parent_id',$request->columnId)
            //             ->update([
            //                 'board_parent_id' => null
            //             ]);
            $delete->delete();
            return response()->json(['success' => true, 'data' => $delete]);
        } else {
            return response()->json(['success' => false]);
        }
    }

    public function isLinked(Request $request) 
    {   
        $isLink = LinkListToColumn::where('multiple_list_id', $request->multiple_list)->get();
        
        if ($isLink->count() > 0) {
            return response()->json(['success' => true, 'data' => $isLink]);
        }
        return response()->json(['success' => false]);
    }



























    public function test(){

        $data = array(
            [
                'id' => 100,
                'title' => '100',
                'parent_id' => 0
            ],
            
            [
                'id' => 101,
                'title' => '100',
                'parent_id' => 100
            ],
            
            [
                'id' => 102,
                'title' => '100',
                'parent_id' => 200
            ],
            
            [
                'id' => 103,
                'title' => '100',
                'parent_id' => 101
            ],
            
            [
                'id' => 104,
                'title' => '100',
                'parent_id' => 103
            ]
          );
          $allIds = array_column($data, 'id');
          $parents = [];
          
          
          
          foreach ($data as $item) {
            if($item['parent_id'] == 0){
                $parents[] = $item['id'];
              } else {
                $ind = (string)array_search($item['parent_id'], $allIds);
                if($ind == ""){
                    $parents[] = $item['id'];
                }
              }
          }
        //   print_r('<pre>');
        //   print_r($parents);
          
          
          
          foreach ($data as $item) {
            $parent_id = $this->findTopParents($data, $item, $parents);
            print_r($parent_id.'<br>');
            
          }
    }

    function findTopParents($allData, $item, $parents){
        if($item['parent_id'] == 0){
            return $item['id'];
        }
        foreach($allData as $d){
            if($d['id'] == $item['parent_id']){
                $ind = (string)array_search($d['parent_id'], $parents);
                echo $ind.' ';
                if ($ind == "") {
                }
                return $d['id'];

                // echo $d['id'].' ';
                // $ind = (string)array_search($item['id'], $parents);
                // if($ind !== ""){
                //     return $item['id'];
                // } else {
                //     $this->findTopParents($allData, $d['parent_id'],$parents);
                // }
                                                                              
                          
                // $ddId = findTopParents($allData, $d['parent_id']);
                //           if($ddId = 
            } else {

            }
        }
        return $item['parent_id'].' 55555';
    }












































    protected function createLog($task_id, $type, $message, $title)
    {
        $log_data = [
            'task_id' => $task_id,
            'title' => $title,
            'log_type' => $message,
            'action_type' => $type,
            'action_by' => Auth::id(),
            'action_at' => Carbon::now()
        ];
        $this->actionLog->store($log_data);
    }
}
