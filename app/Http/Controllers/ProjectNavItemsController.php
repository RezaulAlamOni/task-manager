<?php

namespace App\Http\Controllers;

use App\Multiple_list;
use App\ProjectNavItems;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ProjectNavItemsController extends Controller
{

    public function index($project_id)
    {
        $nevItem = [];
        $nev = ProjectNavItems::where('project_id',$project_id)->orderBy('sort_id','asc')->get();
        foreach ($nev as $item) {
            $item->lists = $this->getList($project_id,$item->id,$item->type);
            $nevItem[] = $item;
        }
        return response()->json($nevItem);
    }

    public function getList($project_id, $nev_id, $type){
        if ($type == 'list'){
            $list = Multiple_list::where(['project_id'=>$project_id,'nav_id'=>$nev_id])->get();
            return $list;
        }else {
            return [];
        }

    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        $data = [
            'project_id'=>$request->project_id,
            'title'=>$request->title,
            'type'=>$request->type,
            'sort_id'=>$request->sort_id,
            'created_at'=>Carbon::now(),
        ];
        $check = ProjectNavItems::where('title',$request->title)->count();
        if ($check <= 0){
            $nev = ProjectNavItems::create($data);
            return response()->json(['success'=>$nev]);
        }else{
            return response()->json(['success'=>'This title is already taken !']);
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\ProjectNavItems  $projectNavItems
     * @return \Illuminate\Http\Response
     */
    public function show(ProjectNavItems $projectNavItems)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\ProjectNavItems  $projectNavItems
     * @return \Illuminate\Http\Response
     */
    public function edit(ProjectNavItems $projectNavItems)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\ProjectNavItems  $projectNavItems
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ProjectNavItems $projectNavItems)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\ProjectNavItems  $projectNavItems
     * @return \Illuminate\Http\Response
     */
    public function destroy(ProjectNavItems $projectNavItems)
    {
        //
    }
}
