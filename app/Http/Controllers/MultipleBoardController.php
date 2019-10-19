<?php

namespace App\Http\Controllers;

use App\ExistingTasksInBoard;
use App\Multiple_board;
use App\Multiple_list;
use App\TaskBoard;
use App\Tags;
use App\Task;
use App\User;
use App\AssignedUser;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use DB;

class MultipleBoardController extends Controller
{
    protected $actionLog;

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
                ->where('project_id', $request->projectId)
                // ->where('nav_id', $request->nav_id)
                ->where('multiple_board_id', $request->board_id)
                ->orderby('board_sort_id', 'ASC')
                ->get();

        foreach ($board as $key => $value) {
            $keys = -1;
            $boards[$key]['id'] = $value['id'];
            $boards[$key]['column'] = $value['title'];
            $boards[$key]['hidden'] = $value['hidden'];
            $boards[$key]['progress'] = $value['progress'];
            $boards[$key]['color'] = $value['color'];
            if (!empty($value['task']) && count($value['task']) > 0) {
                foreach ($value['task'] as $keys => $values) {
                    $tagTooltip = '';
                    $tags = [];
                    if (!empty($values['tags']) && count($values['tags']) > 0) {
                        foreach ($values['tags'] as $tagkey => $tag) {
                            $tags[$tagkey]['id'] = $tag->id;
                            $tags[$tagkey]['board_id'] = $tag->board_id;
                            $tags[$tagkey]['text'] = $tag->title;
                            $tags[$tagkey]['classes'] = '';
                            $tags[$tagkey]['style'] = 'background-color: ' . $tag->color;
                            $tags[$tagkey]['color'] = $tag->color;
                            $tagTooltip .= '#' . $tag->title . ' ';
                        }
                    }

                    $boards[$key]['task'][$keys]['assigned_user'] = AssignedUser::join('users', 'task_assigned_users.user_id','users.id')->where('task_id', $values['id'])->get()->toArray();
                    $team_id = DB::table('team_users')->where('user_id', Auth::id())->first();
                    $boards[$key]['task'][$keys]['users'] = User::join('team_users', 'team_users.user_id', 'users.id')
                                                            ->where('team_users.team_id', $team_id->team_id)->get()->toArray();


                    $boards[$key]['task'][$keys]['tags'] = $tags;
                    $boards[$key]['task'][$keys]['tagTooltip'] = $tagTooltip;

                    $boards[$key]['task'][$keys]['id'] = $values['id'];
                    $boards[$key]['task'][$keys]['name'] = $values['title'];
                    if ($values['list_id'] != '') {
                        $boards[$key]['task'][$keys]['type'] = 'task';
                    } else {
                        $boards[$key]['task'][$keys]['type'] = 'card';
                    }
                    $boards[$key]['task'][$keys]['date'] = date('d M', strtotime($values['date']));
                    $boards[$key]['task'][$keys]['existing_tags'] = Tags::select('tags.*')
                                                                    ->join('task_lists', 'tags.task_id', 'task_lists.id')
                                                                    ->where('tags.task_id', '!=', $values->id)
                                                                    ->where('tags.title', '!=', 'Dont Forget')
                                                                    ->where('task_lists.multiple_board_id', $value->multiple_board_id)
                                                                    ->groupBy('tags.title')
                                                                    ->get()->toArray();
                }
            } else {
                $boards[$key]['task'] = [];
            }

            
            // $existingTask = ExistingTasksInBoard::with('task')->where('board_id', $value->id)->get();

            // if ($existingTask->count() > 0) {
            //     foreach ($existingTask as $ExTaskkey => $ExTaskvalue) {
            //         $keys++;
            //         foreach ($ExTaskvalue['task'] as $TaskKey => $TaskValue) {
            //             $tagTooltip = '';
            //             $tags = [];
            //             if (!empty($TaskValue['tags']) && count($TaskValue['tags']) > 0) {
            //                 foreach ($TaskValue['tags'] as $tagkey => $tag) {
            //                     $tags[$tagkey]['id'] = $tag->id;
            //                     $tags[$tagkey]['board_id'] = $tag->board_id;
            //                     $tags[$tagkey]['text'] = $tag->title;
            //                     $tags[$tagkey]['classes'] = '';
            //                     $tags[$tagkey]['style'] = 'background-color: ' . $tag->color;
            //                     $tags[$tagkey]['color'] = $tag->color;
            //                     $tagTooltip .= '#' . $tag->title . ' ';
            //                 }
            //             }
            //             if(!empty($TaskValue['boardTasksTags']) && count($TaskValue['boardTasksTags']) > 0){
            //                 foreach ($TaskValue['boardTasksTags'] as $tagExkey => $tag) {
            //                     $tagkey++;
            //                     $tags[$tagkey]['id'] = $tag->id;
            //                     $tags[$tagkey]['board_id'] = $tag->board_id;
            //                     $tags[$tagkey]['text'] = $tag->title;
            //                     $tags[$tagkey]['classes'] = '';
            //                     $tags[$tagkey]['style'] = 'background-color: ' . $tag->color;
            //                     $tags[$tagkey]['color'] = $tag->color;
            //                     $tagTooltip .= '#' . $tag->title . ' ';
            //                 }
            //             }
            //             $boards[$key]['task'][$keys]['tags'] = $tags;
            //             $boards[$key]['task'][$keys]['tagTooltip'] = $tagTooltip;
            //             $boards[$key]['task'][$keys]['id'] = $ExTaskvalue->id;
            //             $boards[$key]['task'][$keys]['name'] = $TaskValue['title'];
            //             $boards[$key]['task'][$keys]['type'] = 'task';
            //             $boards[$key]['task'][$keys]['date'] = date('d M', strtotime($TaskValue['date']));
            //         }
            //     }
            // }
        }
        // return $boards;
        return response()->json(['success' => $boards]);
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
        if (!$sortNo) {
            $sortNo = 0;
        }
        $data = [
            'title' => '',
            'board_sort_id' => $sortNo + 1,
            'board_parent_id' => $parent->id,
            'project_id' => $parent->project_id,
            'multiple_board_id' => $parent->multiple_board_id,
            'hidden' => 0,
            'board_flag' => 1,
            'date' => '',//Carbon::now(),
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
         $request->all();
         $update = Task::where('id',$request->id)
                    ->where('board_parent_id',"!=",0)
                    ->update([
                        'board_parent_id' => $request->board_parent_id
                    ]);
        if ($update) {
            $this->createLog($request->id, 'Update', 'Parent changed', 'Board Card Parent Changed');
            return response()->json(['success' => true, 'data' => $update]);
        }
        return response()->json(['success' => false]); 
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
            if ($update) {
                $this->createLog($request->boardId, 'Update', 'Column Update', 'Board Column Updated');
                return response()->json(['success' => true, 'data' => $update]);
            }
        }
        return response()->json(['success' => false]);
    }

    public function destroy($id)
    {
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
        $delete = Task::Where('board_parent_id', $id)->delete();
        if ($delete) {
            $this->createLog($id, 'Delete', 'Card Deleted', 'Board All Card Deleted');
            return response()->json(['success' => true]);
        } else {
            return response()->json(['success' => false]);
        }
    }

    public function cardDelete($id)
    {
        $delete = Task::where('id', $id)->delete();
        if ($delete) {
            $this->createLog($request->boardId, 'Delete', 'Card Deleted', 'Board Single Card Deleted');
            return response()->json(['success' => true]);
        } else {
            return response()->json(['success' => false]);
        }
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
            $this->createLog($request->boardId, 'Delete', 'Card Deleted', 'Board Existing Task Card Deleted');
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
                                'task_flag' => 1,
                                'multiple_board_id' => $request->multiple_board_id
                            ]);
            // $insert = ExistingTasksInBoard::create($data);
            $task[$key] = Task::where('id', $value)->first();
            $tagTooltip = '';
            $allTags = Tags::where('task_id', $task[$key]->id)->get();
            $tags = [];

            if ($allTags->count() > 0) {
                foreach ($allTags as $tagkey => $tag) {
                    $tags[$tagkey]['id'] = $tag->id;
                    $tags[$tagkey]['board_id'] = $tag->board_id;
                    $tags[$tagkey]['text'] = $tag->title;
                    $tags[$tagkey]['classes'] = '';
                    $tags[$tagkey]['style'] = 'background-color: ' . $tag->color;
                    $tags[$tagkey]['color'] = $tag->color;
                    $tagTooltip = '#' . $tag->title . ' ';
                }
                // return $tagTooltip;
            }
            $task[$key]['tag'] = $tags;
            $task[$key]['tagTooltip'] = $tagTooltip;
        }
        $this->createLog($board_id, 'Created', 'Card Create', 'Board Card Create From Existing Task');
        return response()->json(['success' => true, 'data' => $task]);
    }

    public function cardSort(Request $request)
    { 
        if(!empty($request->children) && count($request->children) > 0){
            $ids = '';
            $caseString = '';
            foreach ($request->children as $key => $item) {
                if ($item['types'] == 'card' || $item['types'] == 'task') {
                    $id = $item['cardId'];
                    $caseString .= " when id = '".$id."' then '".$key."'";
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
