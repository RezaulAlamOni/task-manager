<?php

namespace App\Http\Controllers;

use App\AssignedUser;
use App\Files;
use App\Multiple_list;
use App\Project;
use App\Tags;
use App\Task;
use Carbon\Carbon;
use App\User;
use http\Env\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Intervention\Image\File;
use function GuzzleHttp\Promise\all;

class TaskController extends Controller
{
    protected $actionLog;

    public function __construct()
    {
        date_default_timezone_set('UTC');
        $this->actionLog = new ActionLogController;
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
                'tag' => '',
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
            Task::where(['title' => '', 'parent_id' => $request->parent_id, 'project_id' => $request->project_id])->delete();
            return response()->json(['success' => ['id' => $request->id]]);
        } else if ($etask->count() > 0 && $request->text != '') {

            Task::where('id', $request->id)
                ->update(['title' => $request->text]);
            $this->createLog($request->id, 'updated', 'Update task', $request->text);
            Task::where(['title' => '', 'parent_id' => $request->parent_id, 'project_id' => $request->project_id])->delete();

            Task::where('parent_id', $request->parent_id)
                ->where('sort_id', '>', $request->sort_id)
                ->where('list_id', $list_id)
                ->increment('sort_id');
            $sort_id = ($request->sort_id < 0 ) ? 0 : $request->sort_id + 1 ;
            $data = [
                'sort_id' => $sort_id,
                'parent_id' => $request->parent_id,
                'project_id' => $request->project_id,
                'list_id' => $list_id,
                'created_by' => Auth::id(),
                'updated_by' => Auth::id(),
                'title' => '',
                'tag' => '',
                'date' => '0000-00-00',
                'created_at' => Carbon::now(),
            ];
            $task = Task::create($data);
            $this->createLog($task->id, 'created', 'Create task', $request->text);
            return response()->json(['success' => $task]);

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
            'tag' => '',
            'date' => '0000-00-00',
            'created_at' => Carbon::now(),
        ];
        $task = Task::create($data);
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
                $child = Task::where('id', $request->id)->update(['parent_id' => $task->id, 'sort_id' => $sort_id]);

                $this->updateTagWithDataMove($request->id,$task->id);
                $this->createLog($request->id, 'updated', 'Update parent', $request->text);
            }
            return response()->json(['success' => $request->id]);
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
                $sort_id = ($task->sort_id < 0 ) ? 1 : $task->sort_id + 1;
                Task::where('id', $request->id)->update(['parent_id' => $task->parent_id, 'sort_id' => $sort_id]);

                $this->updateTagWithDataMove($request->id,$task->parent_id);
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
                'title' => $past->title.' -copy',
                'tag' => $past->tag,
                'date' => $past->date,
                'created_at' => Carbon::now(),
            ];
            $task = Task::create($data);
            $this->updateTagWithDataMove($task->id,$target->parent_id);
            $this->createLog($task->id, 'copy', 'Copy', $request->text);
            return response()->json(['success' => $task->id]);

        } else if ($request->type == 'cut') {
            $target = Task::where('id', $request->target_id)->first();
            Task::where('project_id', $target->project_id)
                ->where('sort_id', '>', $target->sort_id)
                ->where('parent_id', $target->parent_id)
                ->increment('sort_id');
            $past = Task::where('id', $request->copy_id)
                ->update(['parent_id' => $target->parent_id, 'sort_id' => $target->sort_id + 1]);

            $this->updateTagWithDataMove($request->copy_id,$target->parent_id);
            //check the target task id in the dont forget section and update tag for necessary
            $this->createLog($request->copy_id, 'cut', 'Cut and past tsk', $request->text);
            return response()->json(['success' => $request->copy_id]);
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
        Task::where('id', $request->id)->update(['tag' => $request->tags]);
        return response()->json(['success' => 1]);
    }

    public function moveTask(Request $request)
    {
        if ($request->type == 'up') {
            if ($request->sort_id <= 0){
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
        } else if ($request->type == 'down') {
            if ($request->sort_id < 0){
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

        return response()->json($pre_task);
    }

    public function taskDragDrop(Request $request){
        $id = $request->id;
        $parent_id = $request->parent_id;
        $sort_id = $request->sort_id;
        $child_length = Task::where('parent_id',$parent_id)->count();
        if ($child_length > 0){
            Task::where('parent_id',$parent_id)->where('sort_id','>=',$sort_id)->increment('sort_id');
        }
        Task::where('id',$id)->update(['parent_id'=>$parent_id,'sort_id'=>$sort_id]);
        $this->updateTagWithDataMove($id,$parent_id);
        return \response()->json(['success'=>1]);
    }

    public function deleteTaskWithChild($id)
    {
        Tags::where('task_id', $id)->delete();
        Files::where('tasks_id', $id)->delete();
        $check_dontForgetSection = Task::where('id', $id)->first();
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
        foreach ($childrens as $children) {
            $this->deleteTaskWithChild($children->id);
        }
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
        } elseif (isset($request->files)) {
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
            $info['clicked'] = 0;
            $info['date'] = $task->date;
            $allTags = Tags::where('task_id', $task->id)->get();
            $tags = [];
            $tagTooltip = '';
            if ($allTags->count() > 0) {
                foreach ($allTags as $key => $tag) {
                    $tags[$key]['id'] = $tag->id;
                    $tags[$key]['task_id'] = $tag->task_id;
                    $tags[$key]['text'] = $tag->title;
                    $tags[$key]['classes'] = '';
                    $tags[$key]['style'] = 'background-color: ' . $tag->color;
                    $tags[$key]['color'] = $tag->color;
                    $tagTooltip .= '#' . $tag->title . ' ';
                }
            }

            $info['tags'] = $tags;
            $info['tagTooltip'] = $tagTooltip;
            $info['description'] = $task->description;
            $info['files'] = $task->files;
            $info['assigned_user'] = AssignedUser::join('users','task_assigned_users.user_id','users.id')->where('task_id',$task->id)->get()->toArray();
            $team_id = DB::table('team_users')->where('user_id',Auth::id())->first();
            $info['users'] = User::join('team_users','team_users.user_id','users.id')->where('team_users.team_id',$team_id->team_id)->get()->toArray();

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

    public function updateTagWithDataMove($task_id,$target_parent_id)
    {
        $parent = Task::join('tags', 'task_lists.id', 'tags.task_id')->where(['task_lists.id' => $target_parent_id, 'tags.title' => 'Dont Forget'])->get();
        if ($parent->count() <= 0 ){
            Tags::where(['title'=>'Dont Forget','task_id'=>$task_id])->delete();
        }else{
            $self = Task::join('tags', 'task_lists.id', 'tags.task_id')->where(['task_lists.id' => $task_id, 'tags.title' => 'Dont Forget'])->get();
            if ($self->count() <= 0){
                $TagData = [
                    'color' => '#ff0000',
                    'task_id' => $task_id,
                    'title' => 'Dont Forget',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ];
                Tags::create($TagData);
            }
        }
        $childrens = Task::where('parent_id',$task_id)->get();
        foreach ($childrens as $children) {
            $this->updateTagWithDataMove($children->id,$task_id);
        }

    }
}
