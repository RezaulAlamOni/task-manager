<?php

namespace App\Http\Controllers;

use App\AssignedUser;
use App\AssignTag;
use App\Files;
use App\Multiple_list;
use App\Project;
use App\Tags;
use App\Task;
use App\User;
use Carbon\Carbon;
//use Http\Env\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Intervention\Image\File;
use function GuzzleHttp\Promise\all;
use function response;

class TaskController extends Controller
{
    protected $actionLog;
    protected $dont_forget_tag;

    public function __construct()
    {
        date_default_timezone_set('UTC');
        $this->actionLog = new ActionLogController;
        $this->dont_forget_tag = 'Dont Forget';
        $this->middleware('auth');
    }

    public function getAllOld(Request $request)
    {
        if ($request->list_id == null) {
            $list = Multiple_list::where('project_id', $request->id)
                ->orderBy('id', 'ASC')->first();
            $list_id = $list->id;
        } else {
            $list_id = $request->list_id;
        }
        $tasks = Task::where('parent_id', 0)
            ->where('project_id', $request->id)
            ->where('list_id', $list_id)
            ->where('is_complete', 0)
            ->orderBy('sort_id', 'ASC')
            ->get();
        $task = [];

        $data = $this->decorateData($tasks);
        $multiple_list = Project::with('multiple_list')->findOrFail($request->id);
        $multiple_list = $multiple_list->multiple_list;
        return response()->json(['task_list' => $data, 'multiple_list' => $multiple_list, 'empty_task' => $task]);
    }

    public function decorateData($obj)
    {
        $data = [];
        foreach ($obj as $key => $task) {
            $info = array();
            $info['id'] = $task->id;
            $info['parent_id'] = $task->parent_id;
            $info['sort_id'] = $task->sort_id;
            $info['list_id'] = $task->list_id;//list_id
            $info['text'] = $task->title;
            if ($task->title == 'Dont Forget Section') {
                $info['draggable'] = false;
                $info['droppable'] = true;
            } else {
                $info['draggable'] = true;
                $info['droppable'] = true;
            }
            $info['clicked'] = 0;
            $info['date'] = $task->date;
            $info['open'] = $task->open;
            $allTags = $task->Assign_tags;
            $infoTags = [];
            $tagTooltip = '';
            if (!empty($allTags) && $allTags->count() > 0) {
                foreach ($allTags as $key => $tag) {
                    if (!empty($tag->tag)){
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
            $info['description'] = $task->description;
            $info['files'] = $task->files;
            $info['assigned_user'] = AssignedUser::join('users', 'task_assigned_users.user_id', 'users.id')
                ->where('task_id', $task->id)->get()->toArray();

            $team_id = Auth::user()->current_team_id;
            $info['users'] = User::join('team_users', 'team_users.user_id', 'users.id')
                ->where('team_users.team_id', $team_id)->get()->toArray();
            $info['existing_tags'] = Tags::where('team_id', $team_id)->where('title','!=', $this->dont_forget_tag)
                ->get()->toArray();

            $childrens = Task::where('parent_id', $task->id)
                ->where('list_id', $task->list_id)
                ->where('is_complete', 0)
                ->orderBy('sort_id', 'ASC')
                ->get();
            if (!empty($childrens)) {
                $info['children'] = $this->decorateData($childrens);
            } else {
                $info['children'] = [];
            }
            $data[] = $info;
        }
        return $data;
    }

    public function getAll(Request $request)
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
            $this->createLog($task->id, 'created', 'Create  task', 'New');
            $tasks = Task::where('parent_id', 0)
                ->where('project_id', $request->id)
                ->where('list_id', $list_id)
                ->orderBy('sort_id', 'ASC')->get();
        }

        $data = $this->decorateData($tasks);

        $multiple_list = Project::with('multiple_list')->findOrFail($request->id);
        $multiple_list = $multiple_list->multiple_list;
        return response()->json(['task_list' => $data, 'multiple_list' => $multiple_list, 'empty_task' => $task]);
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

    public function getAllTask(Request $request)
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
        $data = $this->decorateData($tasks);
        return response()->json(['task_list' => $data]);
    }

    public function addTask(Request $request)
    {
        $list_id = $request->list_id;
        $etask = Task::where(['id' => $request->id])->get();
        if ($request->text == '') {
            Task::where(['id' => $request->id, 'project_id' => $request->project_id])->delete();
            $this->createLog($request->id, 'deleted', 'Delete task', '');
            Task::where([
                'title' => '',
                'parent_id' => $request->parent_id,
                'project_id' => $request->project_id
            ])->delete();
            return response()->json(['success' => ['id' => $request->id]]);
        } else {
            if ($etask->count() > 0 && $request->text != '') {

                Task::where('id', $request->id)
                    ->update(['title' => $request->text]);
                $this->createLog($request->id, 'updated', 'Update task', $request->text);
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
                $task = Task::create($data);
                $this->updateTagWithDataMove($task->id, $request->parent_id);
                $this->createLog($task->id, 'created', 'Create task', $request->text);
                return response()->json(['success' => $task]);
            }
        }
    }

    public function addChildTask(Request $request)
    {
        $task_id = Task::where('title', '')->where('parent_id', $request->id)->first();
        if ($task_id) {
            Task::where('title', '')->where('parent_id', $request->id)->delete();
            $this->createLog($task_id->id, 'deleted', 'Delete empty task', '');
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
        $this->updateTagWithDataMove($task->id, $request->id);
        $this->createLog($task->id, 'created', 'create task', 'new');

        return response()->json(['success' => $task]);
    }

    public function makeChild(Request $request)
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
                $child = Task::where('id', $request->id)->update(['parent_id' => $task->id, 'sort_id' => $sort_id,'title'=>$request->text]);

                $this->updateTagWithDataMove($request->id, $task->id);
                $this->createLog($request->id, 'updated', 'Update parent', $request->text);
            }
            return response()->json(['success' => $request->id]);
        }
    }

    public function updateTagWithDataMove($task_id, $target_parent_id)
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
                if ($taskDontForgetSection != null) {
                    $childrenOfDontForgetSection = Task::where('parent_id', $taskDontForgetSection->id)->get()->toArray();
                    if (count($childrenOfDontForgetSection) <= 0) {
                        AssignedUser::where('task_id', $taskDontForgetSection->id)->delete();
                        AssignTag::where(['task_id' => $taskDontForgetSection->id, 'tag_id' => $tag_ids->id])->delete();
                        Task::where('id', $taskDontForgetSection->id)->delete();
                    }
                }

            } else {
                $self = AssignTag::where(['task_id' => $task_id, 'tag_id' => $tag_ids->id])->get();
                if ($self->count() <= 0) {
                    AssignTag::create(['task_id' => $task_id, 'tag_id' => $tag_ids->id]);
                }
            }
            $childrens = Task::where('parent_id', $task_id)->get();
            foreach ($childrens as $children) {
                $this->updateTagWithDataMove($children->id, $task_id);
            }

        }

    }

    public function reverseChild(Request $request)
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
                Task::where('id', $request->id)->update(['parent_id' => $task->parent_id, 'sort_id' => $sort_id]);

                $this->updateTagWithDataMove($request->id, $task->parent_id);
                $this->createLog($request->id, 'updated', 'Update parent', $request->text);
            }

            return response()->json(['success' => $request->id]);
        }
    }

    public function CopyCutPast(Request $request)
    {
        if ($request->type == 'copy') {
            $target = Task::where('id', $request->target_id)->first();
            $past = Task::where('id', $request->copy_id)->first();

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
                'created_at' => Carbon::now(),
            ];
            $task = Task::create($data);
            $this->updateTagWithDataMove($task->id, $target->parent_id);
            $this->createLog($task->id, 'copy', 'Copy', $request->text);
            return response()->json(['success' => $task->id]);

        } else {
            if ($request->type == 'cut') {
                $target = Task::where('id', $request->target_id)->first();
                Task::where('project_id', $target->project_id)
                    ->where('sort_id', '>', $target->sort_id)
                    ->where('parent_id', $target->parent_id)
                    ->increment('sort_id');
                $past = Task::where('id', $request->copy_id)
                    ->update(['parent_id' => $target->parent_id, 'sort_id' => $target->sort_id + 1]);

                $this->updateTagWithDataMove($request->copy_id, $target->parent_id);
                //check the target task id in the dont forget section and update tag for necessary
                $this->createLog($request->copy_id, 'cut', 'Cut and past tsk', $request->text);
                return response()->json(['success' => $request->copy_id]);
            }
        }
    }

    public function deleteTask(Request $request)
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

    public function ActionSelectedTask(Request $request)
    {
        if (isset($request->type) && $request->type == 'user') {
            $ids = $request->ids;
            foreach ($ids as $id) {
                AssignedUser::create([
                    'task_id' => $id,
                    'user_id' => $request->value,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                    'created_by' => Auth::id(),
                    'updated_by' => Auth::id(),
                ]);
            }
            return response()->json(['success' => 1]);
        } else if (isset($request->type) && $request->type == 'tag') {
            $ids = $request->ids;
            $tag = Tags::where('id', $request->value)->first();
            foreach ($ids as $id) {
                $tag_check = Tags::where(['task_id' => $id, 'title' => $tag->title])->count();
                if ($tag_check <= 0) {
                    Tags::create([
                        'color' => $tag->color,
                        'task_id' => $id,
                        'title' => $tag->title,
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now(),
                    ]);
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


    public function deleteTaskWithChild($id)
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

    public function deleteImg(Request $request)
    {
        $delete = Files::where('file_name', $request->img)->delete();
        if ($delete) {
            $image_path = public_path() . "/images/" . $request->img;  // Value is not URL but directory file path
            if (file_exists($image_path)) {
                $del = unlink($image_path);
                return response()->json(['success' => $del]);
            }
        }

        return response()->json(['success' => 1]);
    }

    public function addTag(Request $request)
    {
//        Task::where('id', $request->id)->update(['tag' => $request->tags]);
//        return response()->json(['success' => 1]);
    }

    public function moveTask(Request $request)
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

    public function taskDragDrop(Request $request)
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

    public function update(Request $request)
    {
        if (isset($request->details)) {
            if (Task::where('id', $request->id)->update(['description' => $request->details])) {
                return response()->json('success', 200);
            }
        } elseif (isset($request->complete)) {
            if (Task::where('id', $request->id)->update(['is_complete' => 1])) {
                return response()->json('success', 200);
            }
        } elseif (isset($request->date)) {
            if (Task::where('id', $request->id)->update(['date' => $request->date])) {
                return response()->json('success', 200);
            }
        } elseif (isset($request->text)) {
            if (Task::where('id', $request->id)->update(['title' => $request->text])) {
                return response()->json('success', 200);
            }
        } elseif (isset($request->open)) {
            if (Task::where('id', $request->id)->update(['open' => $request->open])) {
                return response()->json('success', 200);
            }
        } elseif (isset($request->file)) {
            $task_id = $request->id;
            $photo = $_FILES['file']['name'];

            if (move_uploaded_file($_FILES["file"]["tmp_name"], "images/" . $_FILES['file']['name'])) {
                $file = [
                    'file_name' => $photo,
                    'tasks_id' => $task_id,
                    'created_by' => Auth::id(),
                    'updated_by' => Auth::id(),
                    'created_at' => Carbon::now()
                ];
                Files::create($file);
                return response()->json('success', 200);
            } else {
                return response()->json('failed', 500);
            }
        }
    }


}
