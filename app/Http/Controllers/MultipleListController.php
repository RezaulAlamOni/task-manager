<?php

namespace App\Http\Controllers;

use App\Multiple_board;
use App\Multiple_list;
use App\Project;
use App\Task;
use Barryvdh\DomPDF\Facade as PDF;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class MultipleListController extends Controller
{
    protected $actionLog;
    protected $NavBar;
    protected $Task_Controller;
    protected $MultipleBoardController;

    public function __construct()
    {
        date_default_timezone_set('UTC');
        $this->actionLog = new ActionLogController;
        $this->NavBar = new ProjectNavItemsController();
        $this->Task_Controller = new TaskController();
        $this->MultipleBoardController = new MultipleBoardController();
        $this->middleware('auth');
    }

    public function index()
    {

    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        $check_exist = Multiple_list::where(['project_id' => $request->project_id, 'nav_id' => $request->nav_id, 'list_title' => $request->name])->count();
        if ($check_exist > 0) {
            return response()->json(['status' => 'exists']);
        } else {
            $id = Multiple_list::create([
                'project_id' => $request->project_id,
                'nav_id' => $request->nav_id,
                'list_title' => $request->name,
                'description' => $request->description,
                'created_at' => Carbon::today(),
            ]);
            $multiple_list = Project::with('multiple_list')->findOrFail($request->project_id);
            $multiple_list = $multiple_list->multiple_list;
            $log_data = [
                'multiple_list_id' => $id->id,
                'title' => $request->name,
                'log_type' => 'Create list',
                'action_type' => 'created',
                'action_by' => Auth::id(),
                'action_at' => Carbon::now()
            ];
            $this->actionLog->store($log_data);

            return response()->json(['multiple_list' => $multiple_list, 'id' => $id, 'status' => 'create']);

        }
    }

    public function show(Multiple_list $multiple_list)
    {
        //
    }

    public function edit(Multiple_list $multiple_list)
    {
        //
    }

    public function update(Request $request)
    {
        $id = $request->id;
        $title = $request->name;
        $description = $request->description;
        if ($request->type == 'list') {
            Multiple_list::where('id', $id)->update([
                'list_title' => $title,
                'description' => $description,
                'updated_at' => Carbon::now()
            ]);
        } else {
            Multiple_board::where('id', $id)->update([
                'board_title' => $title,
                'description' => $description,
                'updated_at' => Carbon::now()
            ]);
        }

        $navBar = $this->NavBar->index($request->project_id);

        return response()->json(['success' => 1, 'navItems' => $navBar]);
    }

    public function delete(Request $request)
    {
        $id = $request->id;
        if ($request->type == 'list') {
            if ($request->action == 'delete') {
                $tasks = Task::where(['list_id'=>$id,'parent_id'=>0])->get();
                foreach ($tasks as $task) {
                    $this->Task_Controller->deleteTaskWithChild($task->id);
                }
                Multiple_list::where('id', $id)->delete();
            } elseif ($request->action == 'move') {
                (Task::where(['list_id'=>$id])->update(['list_id'=>$request->target])) ? Multiple_list::where('id', $id)->delete() : '';
            }

        } else {
            if ($request->action == 'delete') {
                $tasks = Task::where(['multiple_board_id'=>$id,'board_parent_id'=>0])->get();
                foreach ($tasks as $task) {
                    $this->MultipleBoardController->destroy($task->id);
                }
                Multiple_board::where('id', $id)->delete();

            } elseif ($request->action == 'move') {

                (Task::where(['multiple_board_id'=>$id])->update(['multiple_board_id'=>$request->target])) ? Multiple_board::where('id', $id)->delete() : '';

            }
        }
        $navBar = $this->NavBar->index($request->project_id);

        return response()->json(['success' => 1, 'navItems' => $navBar]);
//        return response()->json(['success' =>$tasks]);
    }


    public function ListPdfCreate($list_id)
    {
        $multiple_list = Multiple_list::findOrFail($list_id);

        $tasks = Task::where('parent_id', 0)
            ->where('project_id', $multiple_list->project_id)
            ->where('list_id', $list_id)
            ->orderBy('sort_id', 'ASC')
            ->get();
        $data = $this->Task_Controller->decorateData($tasks);
//        dd($data);
//        return view('TaskListPdf',['list'=>$multiple_list,'tasks'=>$data]);
        $pdf = PDF::loadView('TaskListPdf',['list'=>$multiple_list,'tasks'=>$data]);
        return $pdf->download($multiple_list->list_title.'.pdf');
//        return $pdf->save(public_path('pdf/abc.pdf'))->stream('download.pdf');
    }
}
