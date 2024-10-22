<?php

namespace App\Http\Controllers;

use App\AssignedUser;
use App\AssignTag;
use App\Files;
use App\Multiple_list;
use App\Project;
use App\Tags;
use App\Task;
use App\LinkListToColumn;
use App\User;
use Carbon\Carbon;
//use Http\Env\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Intervention\Image\File;
use Laravel\Spark\Notification;
use function GuzzleHttp\Promise\all;
use function response;

class TaskController extends Controller
{
    protected $actionLog;
    protected $dont_forget_tag;
    protected $all_ids = [];

    public function __construct ()
    {
        date_default_timezone_set('UTC');
        $this->actionLog = new ActionLogController;
        $this->dont_forget_tag = 'Dont Forget';
        $this->middleware('auth');
    }

    public function decorateData ($obj, $drag = null, $filter = null, $tz = null)
    {
        //        $team_id = Auth::user()->current_team_id;
        //        $allTeamUsers = User::join('team_users', 'team_users.user_id', 'users.id')
        //                            ->where('team_users.team_id', $team_id)->get()->toArray();
        //        $allTeamTags = Tags::where('team_id', $team_id)->where('title', '!=', $this->dont_forget_tag)
        //                            ->get()->toArray();
        $allTeamUsers = [];
        $allTeamTags = [];
        $data = [];
        foreach ($obj as $key => $task) {

            $info = array();
            array_push($this->all_ids, $task->id);
            $info['id'] = $task->id;
            $info['parent_id'] = $task->parent_id;
            $info['sort_id'] = $task->sort_id;
            $info['board_parent_id'] = $task->board_parent_id;
            $info['multiple_board_id'] = $task->multiple_board_id;
            $info['priority_label'] = null;
            if ($task->priority_label == 3 || $task->priority_label == 'high') {
                $info['priority_label'] = 'high';
            } else if ($task->priority_label == 2 || $task->priority_label == 'medium') {
                $info['priority_label'] = 'medium';
            } else if ($task->priority_label == 1 || $task->priority_label == 'low') {
                $info['priority_label'] = 'low';
            }
            $info['list_id'] = $task->list_id;//list_id
            $info['text'] = $task->title;
            if ($task->title == 'Dont Forget Section' || $drag != null) {
                $info['draggable'] = false;
                $info['droppable'] = false;
            } else {
                $info['draggable'] = true;
                $info['droppable'] = true;
            }
            $info['droppable'] = true;
            $info['clicked'] = 0;
            $info['count_child'] = 0;
            $date = Carbon::parse($task->date, 'UTC')->setTimezone($tz);
            $info['date'] = $date;
            $info['progress'] = $task->progress;
            $info['open'] = $task->open;
            $allTags = $task->Assign_tags;
            $infoTags = [];
            $tagTooltip = '';
            if (!empty($allTags) && $allTags->count() > 0) {
                foreach ($allTags as $key => $tag) {
                    if (!empty($tag->tag)) {
                        $infoTags[] = array(
                            'assign_id' => $tag->id,
                            'id' => $tag->tag->id,
                            'text' => $tag->tag->title,
                            'classes' => '',
                            'style' => 'background-color: ' . $tag->tag->color,
                            'color' => $tag->tag->color,
                        );
                        $tagTooltip .= '#' . $tag->tag->title . ' ';
                    }
                }
            }
            $info['tags'] = $infoTags;
            $info['tagTooltip'] = $tagTooltip;
            $info['complete_tooltip'] = ($task->column != null) ? '#Board : ' . $task->column->MultipleBord->board_title . " #Column : " . $task->column->title : '';
            $info['description'] = $task->description;
            $info['files'] = $task->files;
            $info['assigned_user'] = AssignedUser::join('users', 'task_assigned_users.user_id', 'users.id')
                ->where('task_id', $task->id)->get()->toArray();
            $assigned_user_ids = [];
            foreach ($info['assigned_user'] as $id) {
                $assigned_user_ids[] = $id['id'];
            }
            $info['assigned_user_ids'] = $assigned_user_ids;


            $info['users'] = $allTeamUsers;
            $info['existing_tags'] = $allTeamTags;
            $info['comment'] = $task->comment;


            if ($filter === null) {
                $childrens = Task::where('parent_id', $task->id)
                    ->where('list_id', $task->list_id)
                    ->where('is_deleted', '!=', 1)
                    ->orderBy('sort_id', 'ASC')
                    ->get();
                if (!empty($childrens)) {
                    $info['children'] = $this->decorateData($childrens, $drag);
                } else {
                    $info['children'] = [];
                }
            } elseif ($filter === 'date') {
                $childrens = Task::where('parent_id', $task->id)
                    ->where('list_id', $task->list_id)
                    ->where('is_deleted', '!=', 1)
                    ->orderBy('date', 'ASC')
                    ->get();
                if (!empty($childrens)) {
                    $info['children'] = $this->decorateData($childrens, $drag);
                } else {
                    $info['children'] = [];
                }
            } else {
                $info['children'] = [];
            }

            $data[] = $info;


        }
        return $data;
    }

    public function getAll (Request $request)
    {
        $tz = $request->tz;
        if ($request->list_id == null) {
            $list = Multiple_list::where('project_id', $request->id)->orderBy('id', 'ASC')->first();
            $list_id = $list->id;
        } else {
            $list_id = $request->list_id;
            $list = Multiple_list::where('id', $list_id)->first();
        }
        $tasks = Task::where('parent_id', 0)
            ->where('project_id', $request->id)
            ->where('is_deleted', '!=', 1)
            ->where('list_id', $list_id)->with('column')
            ->orderBy('sort_id', 'ASC')
            ->with('comment')
            ->get();
        $task = [];
        if ($tasks->count() <= 0) {
            $data = [
                'sort_id' => 0,
                'parent_id' => 0,
                'project_id' => $request->id,
                'list_id' => $list_id,
                'created_by' => Auth::id(),
                'updated_by' => Auth::id(),
                'title' => '',
                'date' => '0000-00-00',
                'created_at' => Carbon::now(),
            ];
            $task = Task::create($data);
            $this->createLog($task->id, 'created', 'Create task', 'New');
            $tasks = Task::where('parent_id', 0)
                ->where('project_id', $request->id)
                ->where('list_id', $list_id)
                ->with('column')
                ->orderBy('sort_id', 'ASC')->get();

        }
        $this->all_ids = [];
        $data = $this->decorateData($tasks, null, null, $tz);
        $userName = Auth::user();
        $multiple_list = Project::with('multiple_list')->findOrFail($request->id);
        $multiple_list = $multiple_list->multiple_list;

        $team_id = Auth::user()->current_team_id;
        $allTeamUsers = User::join('team_users', 'team_users.user_id', 'users.id')
            ->where('team_users.team_id', $team_id)->get()->toArray();
        $allTeamTags = Tags::where('team_id', $team_id)->where('title', '!=', $this->dont_forget_tag)
            ->get()->toArray();

        return response()->json([
            'task_list' => $data,
            'multiple_list' => $multiple_list,
            'empty_task' => $task,
            'userName' => $userName,
            'allTeamUsers' => $allTeamUsers,
            'allTeamTags' => $allTeamTags,
            'all_ids' => $this->all_ids,
            'list' => $list
        ]);
    }

    public function getAllFilter (Request $request)
    {
        $tz = $request->tz;
        if ($request->list_id == null) {
            $list = Multiple_list::where('project_id', $request->id)
                ->orderBy('id', 'ASC')->first();
            $list_id = $list->id;
        } else {
            $list_id = $request->list_id;
        }
        $tasks = Task::where('project_id', $request->id)
            ->where('is_deleted', '!=', 1)
            ->where('list_id', $list_id)
            ->with('column');
        //  Task::where('parent_id', 0)
        //      ->where('project_id', $request->id)
        //      ->where('is_deleted', '!=', 1)
        //      ->where('list_id', $list_id)->with('column')
        //      ->orderBy('sort_id', 'ASC')
        //      ->get();

        if ($request->filter_type === 'my' || $request->filter_type === 'users_task') {
            if (count($request->ids) > 0) {
                $ids = $request->ids;
            } else {
                $ids = [Auth::id()];
            }
            //            return $ids;
            $tasks = $tasks->whereHas('Assign_user', function ($q) use ($ids) {
                $q->whereIn('user_id', $ids);
            })
                ->orderBy('sort_id', 'ASC')
                ->get();
            $data = $this->decorateData($tasks, 'drag', 'filter');
        } elseif ($request->filter_type === 'not_assign') {
            $tasks = $tasks->whereDoesntHave('Assign_user')
                ->orderBy('sort_id', 'ASC')
                ->get();
            $data = $this->decorateData($tasks, 'drag', 'filter');
        } elseif ($request->filter_type === 'completed') {
            $tasks = $tasks->where('progress', 100)
                ->orderBy('sort_id', 'ASC')
                ->get();
            $data = $this->decorateData($tasks, 'drag', 'filter');
        } elseif ($request->filter_type === 'hide_completed') {
            $tasks = Task::where('project_id', $request->id)
                ->where('is_deleted', '!=', 1)
                ->where('list_id', $list_id)
                ->with('column')
                ->where(function ($q) {
                    $q->where('progress', '!=', 100)
                        ->orWhere('progress', null);
                })
                ->orderBy('sort_id', 'ASC')
                ->get();
            $data = $this->decorateData($tasks, 'drag', 'filter', $tz);
        } elseif ($request->filter_type === 'date') {

            //  $tasks = Task::where('parent_id', 0)
            //      ->where('project_id', $request->id)
            //      ->where('is_deleted', '!=', 1)
            //      ->where('list_id', $list_id)->with('column')
            //      ->orderBy('date', 'ASC')
            //      ->get();
            //  $data = $this->decorateData($tasks, null,'date');

            $tasks = $tasks->orderBy('date', 'DESC')
                ->get();
            $data = $this->decorateData($tasks, 'drag', 'filter');

        } elseif ($request->filter_type === 'asc') {

            $tasks = $tasks->orderBy('id', 'ASC')
                ->get();
            $data = $this->decorateData($tasks, 'drag', 'filter');
        } elseif ($request->filter_type === 'desc') {

            $tasks = $tasks->orderBy('id', 'desc')
                ->get();
            $data = $this->decorateData($tasks, 'drag', 'filter');
        } elseif ($request->filter_type === "priority") {

            $tasks = $tasks->orderBy('priority_label', 'desc')
                ->get();
            $data = $this->decorateData($tasks, 'drag', 'filter');
        } elseif ($request->filter_type === "p_hide") {
            $filter = $request->filter;
            $tasks = $tasks->where(function ($q) use ($filter) {
                $q->whereNotIn('priority_label', $filter)
                    ->orWhere('priority_label', null);
            })->get();
            //            return ($tasks);
            $data = $this->decorateData($tasks, 'drag', 'filter');
        } elseif ($request->filter_type === "p_show") {

            $filter = $request->filter;
            $tasks = $tasks->whereIn('priority_label', $filter)->get();
            $data = $this->decorateData($tasks, 'drag', 'filter');
        } else {

            $tasks = Task::where('parent_id', 0)
                ->where('project_id', $request->id)
                ->where('is_deleted', '!=', 1)
                ->where('list_id', $list_id)->with('column')
                ->orderBy('sort_id', 'ASC')
                ->get();
            $data = $this->decorateData($tasks, null);
        }


        //        return $tasks;
        $task = [];
        $this->all_ids = [];
        $userName = Auth::user();
        $multiple_list = Project::with('multiple_list')->findOrFail($request->id);
        $multiple_list = $multiple_list->multiple_list;

        $team_id = Auth::user()->current_team_id;
        $allTeamUsers = User::join('team_users', 'team_users.user_id', 'users.id')
            ->where('team_users.team_id', $team_id)->get()->toArray();
        $allTeamTags = Tags::where('team_id', $team_id)->where('title', '!=', $this->dont_forget_tag)
            ->get()->toArray();

        return response()->json([
            'task_list' => $data,
            'multiple_list' => $multiple_list,
            'empty_task' => $task,
            'userName' => $userName,
            'allTeamUsers' => $allTeamUsers,
            'allTeamTags' => $allTeamTags,
            'all_ids' => $this->all_ids,
        ]);
    }

    protected
    function createLog ($task_id, $type, $message, $title)
    {
        $task = Task::where('id', $task_id)->first();
        $log_data = [
            'project_id' => $task->project_id,
            'task_id' => $task_id,
            'title' => $title,
            'log_type' => $message,
            'action_type' => $type,
            'action_by' => Auth::id(),
            'action_at' => Carbon::now()
        ];
        $this->actionLog->store($log_data);
    }

    public
    function getAllTask (Request $request)
    {
        if ($request->list_id == null) {
            $list = Multiple_list::where('project_id', $request->id)->orderBy('id', 'ASC')->first();
            $list_id = $list->id;
        } else {
            $list_id = $request->list_id;
        }
        $tasks = Task::where('parent_id', 0)
            ->where('project_id', $request->id)
            ->where('list_id', $list_id)
            ->orderBy('sort_id', 'ASC')
            ->get();
        $data = $this->decorateData($tasks, null);
        return response()->json(['task_list' => $data]);
    }

    public
    function addTask (Request $request)
    {
        $list_id = $request->list_id;
        $etask = Task::where(['id' => $request->id])->first();
        if ($request->text == '') {
            $this->deleteTaskWithChild($request->id);
            $this->createLog($request->id, 'deleted', 'Delete task', $etask->title);
            return response()->json(['success' => ['id' => $request->id]]);
        } else {
            if ($etask && $request->text != '') {
                if ($etask->title !== $request->text) {
                    Task::where('id', $request->id)
                        ->update(['title' => $request->text]);
                    $this->createLog($request->id, 'updated', 'Update task', $request->text);
                }

                Task::where([
                    'title' => '',
                    'parent_id' => $request->parent_id,
                    'project_id' => $request->project_id
                ])->delete();

                Task::where('parent_id', $request->parent_id)
                    ->where('sort_id', '>', $request->sort_id)
                    ->where('list_id', $list_id)
                    ->increment('sort_id');
                $sort_id = ($request->sort_id < 0) ? 0 : $request->sort_id + 1;
                $data = [
                    'sort_id' => $sort_id,
                    'parent_id' => $request->parent_id,
                    'project_id' => $request->project_id,
                    'list_id' => $list_id,
                    'created_by' => Auth::id(),
                    'updated_by' => Auth::id(),
                    'title' => '',
                    'date' => '0000-00-00',
                    'created_at' => Carbon::now(),
                ];
                $link = LinkListToColumn::where('multiple_list_id', $list_id)->first();
                if ($link) {

                    $data['board_parent_id'] = $link->task_list_id;
                    $progress = Task::where('id', $link->task_list_id)->first();
                    $data['progress'] = $progress->progress;
                    $data['multiple_board_id'] = $progress->multiple_board_id;
                }
                $task = Task::create($data);
                $this->createLog($task->id, 'created', 'Create task', $task->title == '' ? 'Empty Task' : $task->title);
                $this->updateTagWithDataMove($task->id, $request->parent_id);
                return response()->json(['success' => $task]);
            }
        }
    }

    public
    function addChildTask (Request $request)
    {
        $task_id = Task::where('title', '')->where('parent_id', $request->id)->first();
        if ($task_id) {
            Task::where('title', '')->where('parent_id', $request->id)->delete();
            $this->createLog($task_id->id, 'deleted', 'Delete empty task', $task_id->title);
        }
        $sort_id = Task::where('parent_id', $request->id)->orderBy('id', 'desc')->first();
        $data = [
            'sort_id' => $sort_id ? $sort_id->sort_id + 1 : 0,
            'parent_id' => $request->id,
            'project_id' => $request->project_id,
            'list_id' => $request->list_id,
            'created_by' => Auth::id(),
            'updated_by' => Auth::id(),
            'title' => '',
            'date' => '0000-00-00',
            'created_at' => Carbon::now(),
        ];
        $task = Task::create($data);
        $this->createLog($task->id, 'created', 'create task', 'Empty Task');
        $this->updateTagWithDataMove($task->id, $request->id);

        return response()->json(['success' => $task]);
    }

    public
    function makeChild (Request $request)
    {
        if (isset($request->parent_id)) {
            $task = Task::where('parent_id', $request->parent_id)
                ->where('project_id', $request->project_id)
                ->where('list_id', $request->list_id)
                ->where('sort_id', '<', $request->sort_id)
                ->orderBy('sort_id', 'desc')->first();
            if ($task) {
                $taskSortId = Task::where('parent_id', $task->id)->max('sort_id');
                $sort_id = ($taskSortId > 0) ? $taskSortId + 1 : 1;
                $child = Task::where('id', $request->id)->update(['parent_id' => $task->id, 'sort_id' => $sort_id, 'title' => $request->text]);
                $this->updateTagWithDataMove($request->id, $task->id);

                $this->createLog($request->id, 'updated', 'Update parent', $request->text);
            }
            return response()->json(['success' => $request->id]);
        }
    }

    public
    function updateTagWithDataMove ($task_id, $target_parent_id)
    {

        $tag_ids = $tags = Tags::where(['title' => $this->dont_forget_tag, 'team_id' => Auth::user()->current_team_id])->first();
        if ($tag_ids) {

            $check_tag_assign = AssignTag::where(['task_id' => $target_parent_id, 'tag_id' => $tag_ids->id])->count();

            if ($check_tag_assign <= 0) {
                AssignTag::where(['task_id' => $task_id, 'tag_id' => $tag_ids->id])->delete();
                $task_find = Task::where('id', $task_id)->first();
                $taskDontForgetSection = Task::where([
                    'title' => 'Dont Forget Section',
                    'project_id' => $task_find->project_id,
                    'list_id' => $task_find->list_id,
                ])->first();

                if ($taskDontForgetSection) {
                    $childrenOfDontForgetSection = Task::where('parent_id', $taskDontForgetSection->id)
                        ->where('is_deleted', 0)->get()->toArray();
                    if (count($childrenOfDontForgetSection) <= 0) {
                        AssignedUser::where('task_id', $taskDontForgetSection->id)->delete();
                        AssignTag::where(['task_id' => $taskDontForgetSection->id, 'tag_id' => $tag_ids->id])->delete();
                        Task::where('id', $taskDontForgetSection->id)->delete();
                    }
                }

            } else {
                $self = AssignTag::where(['task_id' => $task_id, 'tag_id' => $tag_ids->id])->with('task')->first();
                if (!$self) {
                    AssignTag::create(['task_id' => $task_id, 'tag_id' => $tag_ids->id]);
                    $self = AssignTag::where(['task_id' => $task_id, 'tag_id' => $tag_ids->id])->with('task')->first();
                    $this->createLog($task_id, 'updated', 'Add dont\'t forget tag', $self->task->title);
                }
            }
            $children = Task::where('parent_id', $task_id)->get();
            foreach ($children as $child) {
                $this->updateTagWithDataMove($child->id, $task_id);
            }
        }
    }

    public
    function reverseChild (Request $request)
    {
        if (isset($request->parent_id) && $request->parent_id != 0) {
            $task = Task::where('id', $request->parent_id)->first();
            if ($task) {
                $taskChild = Task::where('parent_id', $task->parent_id)
                    ->where('project_id', $task->project_id)
                    ->where('list_id', $task->list_id)
                    ->where('sort_id', '>', $task->sort_id)
                    ->increment('sort_id');
                $sort_id = ($task->sort_id < 0) ? 1 : $task->sort_id + 1;
                Task::where('id', $request->id)->update(['parent_id' => $task->parent_id, 'sort_id' => $sort_id, 'title' => $request->text]);

                $this->updateTagWithDataMove($request->id, $task->parent_id);
                $this->createLog($request->id, 'updated', 'Update parent', $request->text);
            }

            return response()->json(['success' => $request->id]);
        }
    }

    public
    function CopyCutPast (Request $request)
    {
        if ($request->type == 'copy') {

            $target_id = $request->target_id;
            $copy_ids = $request->copy_ids;

            $target_list_id = Task::select('list_id')->where(['id' => $target_id])->first();
            foreach ($copy_ids as $copy_id) {
                $target_id = $this->CopayPast($target_id, $copy_id);
            }
            return response()->json(['success' => $target_id, 'list_id' => $target_list_id->list_id]);

        } else {
            if ($request->type == 'cut') {
                $target = Task::where('id', $request->target_id)->first();
                Task::where('project_id', $target->project_id)
                    ->where('sort_id', '>', $target->sort_id)
                    ->where('parent_id', $target->parent_id)
                    ->increment('sort_id');
                $past = Task::where('id', $request->copy_ids[0])
                    ->update(['parent_id' => $target->parent_id, 'sort_id' => $target->sort_id + 1]);

                $past = Task::where('id', $request->copy_ids[0])->first();
                $this->updateTagWithDataMove($past->id, $target->parent_id);
                //check the target task id in the dont forget section and update tag for necessary
                $this->createLog($past->id, 'cut', 'Cut and past tsk', $past->title);
                return response()->json(['success' => $past->id, 'list_id' => $target->list_id]);
            }
        }
    }

    public
    function CopyPastMultipleTaskToAnotherList (Request $request)
    {
        $task_ids = $request->task_ids;
        $target_nav_id = $request->nav_id;
        $target_list_id = $request->list_id;

        $target_list_sort_id = Task::where(['list_id' => $target_list_id])->orderBy('sort_id', 'desc')->first();

        $check_links = LinkListToColumn::where('multiple_list_id', $target_list_id)->first();
        $link_column = $check_links ? Task::where('id', $check_links->task_list_id)->first() : null;

        $sort_id = $target_list_sort_id->sort_id;
        foreach ($task_ids as $id) {
            $past = Task::where('id', $id)->with('List')->first();
            $sort_id = $sort_id + 1;
            $data = [
                'sort_id' => $sort_id,
                'parent_id' => 0,
                'project_id' => $past->project_id,
                'list_id' => $target_list_id,
                'created_by' => Auth::id(),
                'updated_by' => Auth::id(),
                'title' => $past->title . '-copy',
                'date' => $past->date,
                'board_parent_id' => $check_links && $link_column ? $link_column->board_parent_id : null,
                'progress' => $check_links && $link_column ? $link_column->progress : null,
                'created_at' => Carbon::now(),
            ];
            $task = Task::create($data);
            $task = Task::where('id', $task->id)->with('List')->first();

            $this->createLog($task->id, 'copy', 'Copy task from ' . $past->List->list_title . ' to ' . $task->List->list_title, $task->title);
        }
        return response()->json([$task]);

        //        $data = [
        //            'sort_id' => $target->sort_id + 1,
        //            'parent_id' => $target->parent_id,
        //            'project_id' => $past->project_id,
        //            'list_id' => $past->list_id,
        //            'created_by' => Auth::id(),
        //            'updated_by' => Auth::id(),
        //            'title' => $past->title . ' -copy',
        //            'date' => $past->date,
        //            'created_at' => Carbon::now(),
        //        ];
        //        $task = Task::create($data);
        //        $this->updateTagWithDataMove($task->id, $target->parent_id);
        //        $this->createLog($task->id, 'copy', 'Copy', $task->title);
    }

    function CopayPast ($target_id, $copy_id)
    {

        $target = Task::where('id', $target_id)->first();
        $check_links = LinkListToColumn::where('multiple_list_id', $target->list_id)->first();
        $link_column = $check_links ? Task::where('id', $check_links->task_list_id)->first() : null;
        $past = Task::where('id', $copy_id)->first();

        Task::where('parent_id', $target->parent_id)
            ->where('project_id', $target->project_id)
            ->where('list_id', $target->list_id)
            ->where('sort_id', '>', $target->sort_id)
            ->increment('sort_id');

        $data = [
            'sort_id' => $target->sort_id + 1,
            'parent_id' => $target->parent_id,
            'project_id' => $past->project_id,
            'list_id' => $past->list_id,
            'created_by' => Auth::id(),
            'updated_by' => Auth::id(),
            'title' => $past->title . ' -copy',
            'date' => $past->date,
            'board_parent_id' => $check_links && $link_column ? $link_column->board_parent_id : null,
            'progress' => $check_links && $link_column ? $link_column->progress : null,
            'created_at' => Carbon::now(),
        ];
        $task = Task::create($data);
        $this->createLog($task->id, 'copy', 'Copy', $task->title);
        $this->updateTagWithDataMove($task->id, $target->parent_id);
        return $task->id;
    }

    public
    function parmanentDeleteTask (Request $request)
    {
        if (isset($request->ids)) {
            $ids = $request->ids;
            foreach ($ids as $id) {
                $this->deleteTaskWithChild($id);
            }
            return response()->json(['success' => 1]);
        } else {
            $this->deleteTaskWithChild($request->id);
            return response()->json(['success' => 1]);
        }

    }

    public
    function deleteTask (Request $request)
    {
        if (isset($request->ids)) {
            $ids = $request->ids;
            foreach ($ids as $id) {
                //                $this->deleteTaskWithChild($id);
                $delete = Task::where('id', $id)->update([
                    'is_deleted' => 1,
                    'deleted_at' => carbon::now()
                ]);
                $task = Task::find($id);
                $this->createLog($id, 'softdelete', 'Task Softdeleted', $task->title);
            }
        } else {
            $delete = Task::where('id', $request->id)->update([
                'is_deleted' => 1,
                'deleted_at' => carbon::now()
            ]);
            $task = Task::find($request->id);
            $this->createLog($request->id, 'softdelete', 'Task Softdeleted', $task->title);
            // $this->deleteTaskWithChild($request->id);
        }

        $taskDontForgetSection = Task::where([
            'title' => 'Dont Forget Section',
            'project_id' => $task->project_id,
            'list_id' => $task->list_id,
        ])->first();
        $tag_ids = $tags = Tags::where(['title' => $this->dont_forget_tag, 'team_id' => Auth::user()->current_team_id])->first();
        if ($taskDontForgetSection) {
            $childrenOfDontForgetSection = Task::where('parent_id', $taskDontForgetSection->id)
                ->where('is_deleted', 0)->get()->toArray();

            if (count($childrenOfDontForgetSection) <= 0) {
                AssignedUser::where('task_id', $taskDontForgetSection->id)->delete();
                AssignTag::where(['task_id' => $taskDontForgetSection->id, 'tag_id' => $tag_ids->id])->delete();
                Task::where('id', $taskDontForgetSection->id)->delete();
            }
        }

        return response()->json(['success' => 1]);

    }


    public
    function ActionSelectedTask (Request $request)
    {
        if (isset($request->type) && $request->type == 'user') {
            $ids = $request->ids;
            foreach ($ids as $id) {
                $checkIsUserAssigned = AssignedUser::where([
                    'task_id' => $id,
                    'user_id' => $request->value
                ])->count();
                if ($checkIsUserAssigned <= 0) {
                    AssignedUser::create([
                        'task_id' => $id,
                        'user_id' => $request->value,
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now(),
                        'created_by' => Auth::id(),
                        'updated_by' => Auth::id(),
                    ]);
                }
            }
            if ($request->value != Auth::id()){
                Notification::create([
                    'user_id' =>$request->value,
                    'created_by' => Auth::id(),
                    'body' => Auth::user()->name." assign on a task.",
                    'action_text' => 'View',
                ]);
            }
            return response()->json(['success' => 1]);
        } else if (isset($request->type) && $request->type == 'tag') {
            $ids = $request->ids;
            $tag_id = $request->value;
            foreach ($ids as $id) {
                $check_assign = AssignTag::where(['task_id' => $id, 'tag_id' => $tag_id])->get();
                if ($check_assign->count() <= 0) {
                    $assign = AssignTag::create(['task_id' => $id, 'tag_id' => $tag_id]);
                }
            }
            return response()->json(['success' => 1]);
        } else if (isset($request->type) && $request->type == 'date') {
            $ids = $request->ids;
            foreach ($ids as $id) {
                Task::where('id', $id)->update(['date' => $request->value]);
            }
            return response()->json(['success' => "Date Update Success"]);
        }
    }

    public
    function deleteTaskWithChild ($id)
    {
        $check_dontForgetSection = Task::where('id', $id)->first();
        if ($check_dontForgetSection == null) {
            return true;
        }
        AssignedUser::where('task_id', $id)->delete();
        AssignTag::where('task_id', $id)->delete();
        Files::where('tasks_id', $id)->delete();
        Task::findOrFail($id)->delete();

        if ($check_dontForgetSection) {
            $taskDontForget = Task::where([
                'title' => 'Dont Forget Section',
                'project_id' => $check_dontForgetSection->project_id,
                'list_id' => $check_dontForgetSection->list_id
            ])->first();
            if ($taskDontForget) {
                $check_child = Task::where('parent_id', $taskDontForget->id)->count();
                if ($check_child <= 0) {
                    Task::findOrFail($taskDontForget->id)->delete();
                }
            }
        }

        $childrens = Task::where('parent_id', $id)->get();
        if ($childrens->count() > 0) {
            foreach ($childrens as $children) {
                $this->deleteTaskWithChild($children->id);
            }
        }
    }

    public
    function deleteImg (Request $request)
    {
        if (isset($request->img) && isset($request->id)) {
            $task_id = $request->id;//we need a check to make sure this is set.
            $delete = Files::where('file_name', $request->img)->delete();
            if ($delete) {
                $image_path = public_path() . "/storage/" . $task_id . "/" . $request->img;  // Value is not URL but directory file path
                if (file_exists($image_path)) {
                    $del = unlink($image_path);
                    return response()->json(['success' => $del]);
                }
                //                return response()->json(['success' => 1]);
            }
        } else {
            return response()->json(['success' => 0]);
        }
    }


    public
    function moveTask (Request $request)
    {
        if ($request->type == 'up') {
            if ($request->sort_id <= 0) {
                return false;
            }
            $pre_task = Task::where(['parent_id' => $request->parent_id])
                ->where('sort_id', '<', $request->sort_id)
                ->where('project_id', $request->project_id)
                ->where('list_id', $request->list_id)
                ->orderBy('sort_id', 'desc')->first();

            if (!empty($pre_task)) {
                $pre_sort_id = $pre_task->sort_id;
                Task::where('id', $pre_task->id)->update(['sort_id' => $request->sort_id]);
                Task::where('id', $request->id)->update(['sort_id' => $pre_sort_id]);
            }
        } else {
            if ($request->type == 'down') {
                if ($request->sort_id < 0) {
                    return false;
                }
                $pre_task = Task::where(['parent_id' => $request->parent_id])
                    ->where('sort_id', '>', $request->sort_id)
                    ->where('project_id', $request->project_id)
                    ->where('list_id', $request->list_id)
                    ->orderBy('sort_id', 'asc')->first();
                if (!empty($pre_task)) {
                    $pre_sort_id = $pre_task->sort_id;
                    Task::where('id', $request->id)->update(['sort_id' => $pre_sort_id]);
                    Task::where('id', $pre_task->id)->update(['sort_id' => $request->sort_id]);
                }
            }
        }

        return response()->json($pre_task);
    }

    public
    function taskDragDrop (Request $request)
    {
        $id = $request->id;
        $parent_id = $request->parent_id;
        $sort_id = $request->sort_id;
        $pre_sort = (isset($request->pre_sort) && $request->pre_sort > 0) ? $request->pre_sort : 0;
        $child_length = Task::where('parent_id', $parent_id)->count();
        if ($child_length > 0) {
            Task::where('parent_id', $parent_id)->where('sort_id', '>', $pre_sort)->increment('sort_id');
        }
        Task::where('id', $id)->update(['parent_id' => $parent_id, 'sort_id' => $pre_sort + 1]);
        $this->updateTagWithDataMove($id, $parent_id);
        return response()->json(['success' => 1]);
    }

    public
    function deleteEmptyTask (Request $request)
    {
        $this->deleteTaskWithChild($request->id);
        return response()->json(['success' => 1, 'id' => $request->id]);
    }

    public
    function update (Request $request)
    {
        if (isset($request->details)) {
            if (Task::where('id', $request->id)->update(['description' => $request->details])) {
                $users = $this->addNotification($request->id,'Update a task description you are assigned !');
                return response()->json(['status'=>'success','users'=>$users]);
            }
        } elseif (isset($request->complete)) {
            $find = Task::find($request->id);
            if ($find->board_parent_id != null) {
                $board_parent = Task::where(['board_parent_id' => 0, 'progress' => 100, 'multiple_board_id' => $find->multiple_board_id])->first();
                if ($board_parent) {
                    Task::where('id', $request->id)->update(['progress' => $board_parent->progress, 'board_parent_id' => $board_parent->id]);
                    $users = $this->addNotification($request->id,'Update a task to complete that you are assigned on!');
                    return response()->json(['status' => 1,'users'=>$users]);
                } else {
                    return response()->json(['status' => 2]);
                }
            } else {
                return response()->json(['status' => 0]);
            }
        } elseif (isset($request->date)) {
            $d = $request->date;
            $tz = $request->tz;

            $date = Carbon::parse($d, $tz)->setTimezone('UTC');
            //            $date = Carbon::parse($date, 'UTC')->setTimezone('Asia/Dhaka');
            if (Task::where('id', $request->id)->update(['date' => $date])) {
                $users = $this->addNotification($request->id,'Update a dua date on a task that you are assigned on!');
                return response()->json(['status'=>'success','users'=>$users]);
            }
        } elseif (isset($request->text)) {
            $check_is_empty = Task::where('id', $request->id)->first();
            if (empty($check_is_empty->title)) {
                $empty = true;
            } else {
                $empty = false;
            }
            ($request->text == null) ? $title = '' : $title = $request->text;

            if ($check_is_empty->title == $request->text) {
                return response()->json(['success' => 20, 'empty' => 'not change']);
            }

            if (Task::where('id', $request->id)->update(['title' => $title])) {
                $users = [];
                if ($empty) {
                    $this->createLog($request->id, 'updated', 'Update From empty task', $title);
                } else {
                    $this->createLog($request->id, 'updated', 'Update task', $title);
                    $users = $this->addNotification($request->id,'Update a task title on a task that you are assigned on!');
                }
                return response()->json(['success' => 1, 'empty' => $empty,'users'=>$users]);
            } else {
                return response()->json(['success' => 0, 'empty' => $empty]);
            }
        } elseif (isset($request->open)) {
            if (Task::where('id', $request->id)->update(['open' => $request->open])) {
                return response()->json('success', 200);
            }
        } elseif (isset($request->file)) {
            $task_id = $request->id;
            $photo = $_FILES['file']['name'];
            $path = public_path() . "/storage/" . $task_id;
            if (!is_dir($path)) {
                if (!is_dir(public_path() . "/storage")) {
                    mkdir(public_path() . "/storage/");
                }
                mkdir($path);
            }
            if (move_uploaded_file($_FILES["file"]["tmp_name"], $path . "/" . $_FILES['file']['name'])) {
                $file = [
                    'file_name' => $photo,
                    'tasks_id' => $task_id,
                    'created_by' => Auth::id(),
                    'updated_by' => Auth::id(),
                    'created_at' => Carbon::now()
                ];
                Files::create($file);
                $users = $this->addNotification($request->id,'Added file on a task that you are assigned on!');
                return response()->json(['status'=>'success','users'=>$users]);
            } else {
                return response()->json('failed', 500);
            }
        }
    }

    public function addNotification ($task_id,$notification_body,$action_url = null)
    {
        $all_Assign_users = Task::where('id', $task_id)->with('Assign_user')->first();
        $user_ids = [];
        foreach ($all_Assign_users->Assign_user as $item) {
            if ($item->user_id != Auth::id()){
                $user_ids[] = $item->user_id;
                Notification::create([
                    'user_id' => $item->user_id,
                    'created_by' => Auth::id(),
                    'body' => $notification_body,
                    'action_text' => 'View',
                    'action_url' => ($action_url == null ) ? '/project-dashboard/'.$all_Assign_users->project_id : $action_url,
                ]);
            }
        }
        return $user_ids;
    }
}
