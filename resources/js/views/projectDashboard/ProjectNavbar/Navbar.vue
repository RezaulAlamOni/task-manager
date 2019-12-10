<template>

    <div class="align-self-center" @click="HideDetails">
        <nav class="navbar-expand-md navbar-spark">
            <div class="container-fluid">
                <div class="collapse navbar-collapse show">
                    <!--                    <li class="nav-item ml-4" style="list-style: none;">-->
                    <!--                        <a @click="setOverview" class="d-block d-md-flex text-center nav-link"-->
                    <!--                           href="Javascript:void(0)">-->
                    <!--                            <span class="d-none d-md-block">Overview</span>-->
                    <!--                        </a>-->
                    <!--                    </li>-->
                    <div class="nav-train-station">
                        <div class="nav-train">
                            <ul class="navbar-nav navbar-nav-cabin ml-4 float-sm-left">
                                <li class="nav-item dropdown" @click="setOverview">
                                    <a aria-expanded="false" aria-haspopup="true"
                                       class="d-block d-md-flex text-center nav-link " href="#">
                                        <span class="d-none d-md-block">Overview</span>
                                    </a>
                                </li>
                            </ul>
                            <template v-for="nav in AllNavItems">
                                <ul class="navbar-nav navbar-nav-cabin ml-4 float-sm-left">
                                    <li class="nav-item dropdown">
                                        <a aria-expanded="false" aria-haspopup="true"
                                           class="d-block d-md-flex text-center nav-link dropdown-toggle"
                                           data-toggle="dropdown" href="#">
                                            <span class="d-none d-md-block">{{nav.title}}</span>
                                        </a>
                                        <div aria-labelledby="dropdownMenuButton"
                                             class="dropdown-menu dropdown-menu-left"
                                             style="max-height: calc(100vh - 130px);transform: translate3d(0px, 50px, 0px);overflow-y: auto;overflow-x: hidden;min-width: 145px;">

                                            <h6 class="dropdown-header" v-if="nav.type === 'list'"> Lists</h6>
                                            <h6 class="dropdown-header" v-else-if="nav.type === 'board'"> Board</h6>

                                            <!--                                        <a style="max-height: calc(100vh - 190px);transform: translate3d(0px, 50px, 0px);overflow: auto;">-->
                                            <span v-for="nav_list in nav.lists">
                                                <span :id="'list'+nav_list.id"
                                                      v-if="nav_list.is_delete == 2 || nav_list.is_delete == 0"
                                                      :class="(nav.type === 'board') ? 'board'+nav_list.id : '' "
                                                      @click="setListId(nav_list,nav.id,nav_list.description,nav.type)"
                                                      class="dropdown-item"> {{nav_list.is_delete}}
                                                    <a href="javascript:void(0)" v-if="nav.type === 'list'">{{nav_list.list_title}} </a>
                                                     <a href="javascript:void(0)" v-else>{{nav_list.board_title}}</a>
                                                 </span>
                                            </span>
                                            <!--                                        </a>-->


                                            <div class="dropdown-divider"></div>

                                            <a @click="addListModel(nav.id)" class="dropdown-item"
                                               href="Javascript:void(0)"
                                               v-if="nav.type === 'list'">
                                                <!--                                            <i class="fa fa-fw text-left fa-btn fa-plus-circle"></i>-->
                                                <img src="/img/task-icon/add-list.png"
                                                     class="icon-image-preview li-opacity-sub ">
                                                Create  <!--<b>{{nav.title}}</b>--> List
                                            </a>

                                            <a @click="addBoardModel(nav.id)" class="dropdown-item"
                                               href="Javascript:void(0)"
                                               v-else-if="nav.type === 'board'">
                                                <!--                                            <i class="fa fa-fw text-left fa-btn fa-plus-circle"></i>-->
                                                <img src="/img/task-icon/add-list.png"
                                                     class="icon-image-preview li-opacity-sub ">
                                                Create <!--<b>{{nav.title}}</b>--> Board
                                            </a>
                                        </div>
                                    </li>
                                </ul>
                            </template>
                        </div>
                    </div>
                    <div>
                        <ul class="navbar-nav ml-4 nav-bar-right" style="padding-right: 10px;">
                            <li class="nav-item search-nav-icon">
                                <!--                            v-if="list_selected.type === 'list'"-->
                                <button class="btn btn-default" type="submit"
                                        @click="showSearchInputField"
                                        style="padding-right: 32px; padding-left: 7px;background: none">
                                    <img :src="baseUrl+'/img/task-icon/search.png'" height="30" width="30" alt=""
                                         title="Find Task" data-toggle="tooltip">
                                </button>
                            </li>
                            <li class="nav-item search-nav-icon" v-if="list_selected.type === 'board'">
                                <button class="btn btn-default" @click="HideShowBoardTitle" type="submit"
                                        style="padding-right: 32px; padding-left: 7px;background: none;">
                                    <img :src="baseUrl+'/img/task-icon/title_show.png'" height="30" width="30" alt=""
                                         title="'Hide' or 'Show' Board Title" data-toggle="tooltip"
                                         style="margin: -2px;">
                                </button>
                            </li>
                            <li class="nav-item" style="margin-right:10px;">
                                <a @click="" class="text-center nav-link"
                                   href="Javascript:void(0)">
                                    <span class="pull-right dropdown-toggle" data-toggle="dropdown">Option</span>
                                    <div aria-labelledby="dropdownMenuButton"
                                         class="dropdown-menu dropdown-menu-right dropdown-menu-custom">


                                        <template
                                            v-if="overview === 'overview' && list_selected.type !== 'list' && list_selected.type !== 'board'">
                                            <h6 class="dropdown-header text-uppercase">Action For Overview</h6>
                                            <span class="dropdown-item custom-dropdown-item">
                                                <a :href="'/list-pdf-create/overview/'+ projectId" target="_blank">
                                                    <img :src="baseUrl+'/img/task-icon/pdf.png'" alt="" height="20px"
                                                         width="20px" class="mr-2">
                                                    Create Overview PDF </a>
                                           </span>
                                            <span class="dropdown-item custom-dropdown-item">
                                            <a @click="addListFromOverview">
                                                <img :src="baseUrl+'/img/task-icon/addList.png'" alt="" height="20px"
                                                     width="20px" class="mr-2">
                                                Add New List</a>
                                        </span>
                                        </template>
                                        <template v-else>
                                            <h6 class="dropdown-header text-uppercase">Action For <span
                                                v-if="list_selected.type === 'board'">Board</span> <span
                                                v-else>List</span></h6>
                                            <div class="dropdown-divider"></div>
                                            <span class="dropdown-item custom-dropdown-item" @click="UpdateListModel">
                                        <a href="javascript:void(0)">
                                            <img :src="baseUrl+'/img/task-icon/edit.png'" alt="" height="20px"
                                                 width="20px" class="mr-2">
                                            Edit  <span
                                            v-if="list_selected.type === 'board'">Board</span>  <span v-else>List</span></a>
                                    </span>
                                            <span class="dropdown-item custom-dropdown-item">
                                        <a href="javascript:void(0)" @click="MoveListTOAnotherNav(list_selected.type)">
                                            <img :src="baseUrl+'/img/task-icon/move.png'" alt="" height="20px"
                                                 width="20px" class="mr-2">
                                            Move
                                            <span v-if="list_selected.type === 'board'">Board</span>  <span
                                            v-else>List</span> to Another Nav </a>
                                    </span>
                                            <span class="dropdown-item custom-dropdown-item"
                                                  @click="DeleteListOrBoard(list_selected.type,'delete')">
                                        <a href="javascript:void(0)">
                                            <img :src="baseUrl+'/img/task-icon/trash.png'" alt="" height="20px"
                                                 width="20px" class="mr-2">
                                            Delete with all <span
                                            v-if="list_selected.type === 'board'">Card</span>  <span v-else> Task</span></a>
                                    </span>
                                            <span class="dropdown-item custom-dropdown-item"
                                                  @click="DeleteListOrBoard(list_selected.type,'move')">
                                        <a href="javascript:void(0)">
                                            <img :src="baseUrl+'/img/task-icon/move-1.png'" alt="" height="20px"
                                                 width="20px" class="mr-2">
                                            Delete & move <span
                                            v-if="list_selected.type === 'board'">Card</span>  <span v-else>Task</span> </a>
                                    </span>
                                            <span class="dropdown-item custom-dropdown-item"
                                                  v-if="list_selected.type === 'list'">
                                        <a :href="'/list-pdf-create/list/'+list_selected.id" target="_blank">
                                            <img :src="baseUrl+'/img/task-icon/pdf.png'" alt="" height="20px"
                                                 width="20px" class="mr-2">
                                            Create PDF </a>
                                    </span>
                                        </template>


                                    </div>
                                </a>
                            </li>

                            <li class="nav-item" style="margin-right:10px;">
                                <a @click="shortcutModel" class="d-block d-md-flex text-center nav-link"
                                   href="Javascript:void(0)">
                                    <span class="d-none d-md-block dropdown-toggle">Shortcuts</span>
                                </a>
                            </li>
                            <li class="nav-item" style="margin-right:10px;">
                                <a @click="" class="text-center nav-link"
                                   href="Javascript:void(0)">
                                    <span class="pull-right" @click="CreateRule">Rules</span>
                                    <div aria-labelledby="dropdownMenuButton"
                                         class="dropdown-menu dropdown-menu-right dropdown-menu-custom">
                                        <div class="dropdown-divider"></div>
                                        <h6 class="dropdown-header text-uppercase">Manage Rules</h6>
                                        <a class="dropdown-item" href="javascript:void(0)" id="create-rule"
                                           @click="CreateRule">
                                            <i class="fa fa-fw text-left fa-btn fa-plus-circle compltit-blue"></i>
                                            Create Rules
                                        </a>
                                        <div class="dropdown-divider"></div>
                                        <h6 class="dropdown-header text-uppercase"> Update Rules</h6>
                                        <span v-for="rule in rules">
                                         <a class="dropdown-item" @click="UpdateRule(rule.id)"
                                            href="javascript:void(0)"><span> {{rule.name}} </span>
                                         </a>
                                    </span>
                                    </div>
                                </a>
                            </li>

                            <li class="nav-item dropdown">
                                <a aria-expanded="false" aria-haspopup="true"
                                   class="d-block d-md-flex text-center nav-link dropdown-toggle" data-toggle="dropdown"
                                   href="#">
                                <span class="d-none d-md-block"><i class="fa fa-fw fa-filter"
                                                                   style="font-size: 26px;"></i></span>
                                </a>
                                <div aria-labelledby="dropdownMenuButton" class="dropdown-menu dropdown-menu-right">

                                    <h6 class="dropdown-header"> Filters</h6>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item active" href="javascript:void(0)">
                                        <i class="fa fa-fw fa-tasks"></i>
                                        Show All Tasks
                                    </a>
                                    <a class="dropdown-item" href="javascript:void(0)">
                                        <i class="fa fa-user"></i>
                                        Show My Tasks
                                    </a>
                                    <a class="dropdown-item" href="javascript:void(0)">
                                        <i class="fa fa-users"></i>
                                        Show Users Tasks
                                    </a>
                                    <a class="dropdown-item" href="javascript:void(0)">
                                        <i class="fa fa-clipboard-check"></i>
                                        Show Completed Tasks
                                    </a>
                                    <a class="dropdown-item" href="javascript:void(0)">
                                        <i class="fa fa-eye-slash"></i>
                                        Hide Completed Tasks
                                    </a>
                                    <h6 class="dropdown-header"> Sort</h6>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item active" href="javascript:void(0)">
                                        <i class="fa fa-sort"></i>
                                        Default
                                    </a>
                                    <a class="dropdown-item" href="javascript:void(0)">
                                        <i class="fa fa-sort-up"></i>
                                        Oldest
                                    </a>
                                    <a class="dropdown-item" href="javascript:void(0)">
                                        <i class="fa fa-sort-down"></i>
                                        Newest
                                    </a>
                                    <a class="dropdown-item" href="javascript:void(0)">
                                        <i class="fa fa-calendar-alt"></i>
                                        By Due Date
                                    </a>
                                </div>
                            </li>

                            <li class="nav-item dropdown">
                                <a aria-expanded="false" aria-haspopup="true"
                                   class="d-block d-md-flex text-center nav-link"
                                   data-toggle="dropdown" href="#">
                                        <span class="d-none d-md-block">
                                           <i class="fa fa-fw fa-plus-circle compltit-blue"
                                              style="font-size: 26px;"></i>
                                        </span>
                                </a>
                                <div aria-labelledby="dropdownMenuButton" class="dropdown-menu dropdown-menu-right">

                                    <h6 class="dropdown-header text-uppercase"> Manage Nav</h6>
                                    <a @click="showModelForNavItem" class="dropdown-item" href="javascript:void(0)">
                                        <i class="fa fa-fw text-left fa-btn fa-plus-circle compltit-blue"></i>
                                        Create Task View
                                    </a>
                                    <div class="dropdown-divider"></div>
                                    <h6 class="dropdown-header text-uppercase"> Update Nav Item</h6>
                                    <span @click="updateNavbarModel(nav)" v-for="nav in AllNavItems">
                                     <a class="dropdown-item"
                                        href="javascript:void(0)"><span>{{nav.title}}</span></a>
                                </span>
                                </div>
                            </li>
                        </ul>
                    </div>

                </div>
            </div>
        </nav>


        <div aria-hidden="true" aria-labelledby="exampleModalLabel" class="modal fade" id="addListModel" role="dialog"
             tabindex="-1">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title pl-3"> Add List</h5>
                        <button aria-label="Close" class="close" data-dismiss="modal" type="button">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p>Add your new list here !</p>
                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label">List Title</label>
                            <div class="col-sm-8">
                                <input class="form-control" type="text" v-model="list.name">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label">List Description</label>
                            <div class="col-sm-8">
                                <textarea class="form-control" cols="40" id="" name="" rows="3"
                                          v-model="list.description"></textarea>
                            </div>
                        </div>
                        <!--                        <p v-if="addField.error" class="text-danger"></p>-->
                    </div>
                    <div class="modal-footer">
                        <button @click="AddNewList" class="btn btn-primary" type="button">Add</button>
                        <button aria-label="Close" class="btn btn-secondary" data-dismiss="modal" type="button">Cancel
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div aria-hidden="true" aria-labelledby="exampleModalLabel" class="modal fade" id="addBoardModel" role="dialog"
             tabindex="-1">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title pl-3"> Add List</h5>
                        <button aria-label="Close" class="close" data-dismiss="modal" type="button">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p>Add your new list here !</p>
                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label">Board Title</label>
                            <div class="col-sm-8">
                                <input class="form-control" type="text" v-model="list.name">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label">Board Description</label>
                            <div class="col-sm-8">
                                <textarea class="form-control" cols="40" name="" rows="3"
                                          v-model="list.description"></textarea>
                            </div>
                        </div>
                        <!--                        <p v-if="addField.error" class="text-danger"></p>-->
                    </div>
                    <div class="modal-footer">
                        <button @click="AddNewBoard" class="btn btn-primary" type="button">Add</button>
                        <button aria-label="Close" class="btn btn-secondary" data-dismiss="modal" type="button">Cancel
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div aria-hidden="true" aria-labelledby="exampleModalLabel" class="modal fade" id="shortcutModel" role="dialog"
             tabindex="-1">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title pl-3"> Shortcuts</h5>
                        <button aria-label="Close" class="close" data-dismiss="modal" type="button">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <ul class="list-group list-group-horizontal multi-list-group"
                            style="margin-left: 0px !important;">
                            <li class="list-group-item">
                                <span class="badge-pill badge-default">ENTER</span>
                            </li>
                            <li class="list-group-item">Save and Create New Task</li>
                        </ul>
                        <ul class="list-group list-group-horizontal multi-list-group"
                            style="margin-left: 0px !important;">
                            <li class="list-group-item">
                                <span class="badge-pill badge-default">TAB</span>
                            </li>
                            <li class="list-group-item">Make Sub Task</li>
                        </ul>
                        <ul class="list-group list-group-horizontal multi-list-group"
                            style="margin-left: 0px !important;">
                            <li class="list-group-item">
                                <span class="badge-pill badge-default">SHIFT</span>
                                +
                                <span class="badge-pill badge-default">TAB</span>
                            </li>
                            <li class="list-group-item">Make Parent Task</li>
                        </ul>
                        <ul class="list-group list-group-horizontal multi-list-group"
                            style="margin-left: 0px !important;">
                            <li class="list-group-item">
                                <span class="badge-pill badge-default">CTRL</span>
                                +
                                <span class="badge-pill badge-default">C</span>
                            </li>
                            <li class="list-group-item">Copy Task</li>
                        </ul>
                        <ul class="list-group list-group-horizontal multi-list-group"
                            style="margin-left: 0px !important;">
                            <li class="list-group-item">
                                <span class="badge-pill badge-default">CTRL</span>
                                +
                                <span class="badge-pill badge-default">V</span>
                            </li>
                            <li class="list-group-item">Paste Task</li>
                        </ul>
                        <ul class="list-group list-group-horizontal multi-list-group"
                            style="margin-left: 0px !important;">
                            <li class="list-group-item">
                                <span class="badge-pill badge-default">CTRL</span>
                                +
                                <span class="badge-pill badge-default">X</span>
                            </li>
                            <li class="list-group-item">Cut Task</li>
                        </ul>
                        <ul class="list-group list-group-horizontal multi-list-group"
                            style="margin-left: 0px !important;">
                            <li class="list-group-item">
                                <span class="badge-pill badge-default">CTRL</span>
                                +
                                <span class="badge-pill badge-default">M</span>
                            </li>
                            <li class="list-group-item">Move task</li>
                        </ul>
                        <ul class="list-group list-group-horizontal multi-list-group"
                            style="margin-left: 0px !important;">
                            <li class="list-group-item">
                                <span class="badge-pill badge-default">CTRL</span>
                                +
                                <span class="badge-pill badge-default">S</span>
                            </li>
                            <li class="list-group-item">Search</li>
                        </ul>
                        <ul class="list-group list-group-horizontal multi-list-group"
                            style="margin-left: 0px !important;">
                            <li class="list-group-item">
                                <span class="badge-pill badge-default">CTRL</span>
                                +
                                <span class="badge-pill badge-default">i</span>
                            </li>
                            <li class="list-group-item">Upload Image/File</li>
                        </ul>
                        <ul class="list-group list-group-horizontal multi-list-group"
                            style="margin-left: 0px !important;">
                            <li class="list-group-item">
                                <span class="badge-pill badge-default">CTRL</span>
                                +
                                <span class="badge-pill badge-default">U</span>
                            </li>
                            <li class="list-group-item">Assign User</li>
                        </ul>
                        <ul class="list-group list-group-horizontal multi-list-group"
                            style="margin-left: 0px !important;">
                            <li class="list-group-item">
                                <span class="badge-pill badge-default">Shift</span>
                                +
                                <span class="badge-pill badge-default">#</span>
                            </li>
                            <li class="list-group-item">Add Tag</li>
                        </ul>
                        <ul class="list-group list-group-horizontal multi-list-group"
                            style="margin-left: 0px !important;">
                            <li class="list-group-item">
                                <span class="badge-pill badge-default">Shift</span>
                                +
                                <span class="badge-pill badge-default">B</span>
                            </li>
                            <li class="list-group-item">Add Don't Forget Tag</li>
                        </ul>
                        <ul class="list-group list-group-horizontal multi-list-group"
                            style="margin-left: 0px !important;">
                            <li class="list-group-item">
                                <span class="badge-pill badge-default">Delete</span>
                            </li>
                            <li class="list-group-item">Delete Task</li>
                        </ul>
                        <ul class="list-group list-group-horizontal multi-list-group"
                            style="margin-left: 0px !important;">
                            <li class="list-group-item">
                                <span class="badge-pill badge-default"><i class="fa fa-caret-up"></i></span>
                            </li>
                            <li class="list-group-item">
                                <span class="badge-pill badge-default"><i class="fa fa-caret-down"></i></span>
                            </li>
                            <li class="list-group-item">Move Task Up &amp; Down</li>
                        </ul>

                        <ul class="list-group list-group-horizontal multi-list-group"
                            style="margin-left: 0px !important;">
                            <li class="list-group-item">
                                <span class="badge-pill badge-default"><i class="fa fa-caret-right"></i></span>
                            </li>
                            <li class="list-group-item">
                                <span class="badge-pill badge-default"><i class="fa fa-caret-left"></i></span>
                            </li>
                            <li class="list-group-item">Open &amp; Close Task Details</li>
                        </ul>

                    </div>
                    <div class="modal-footer">
                        <button aria-label="Close" class="btn btn-secondary" data-dismiss="modal" type="button">
                            Close
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div aria-hidden="true" aria-labelledby="exampleModalLabel" class="modal fade" id="addNavItem" role="dialog"
             tabindex="-1">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title pl-3"> Add Nav Item</h5>
                        <button aria-label="Close" class="close" data-dismiss="modal" type="button">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group row">
                            <div class="col-sm-4">
                                <label class="control-label float-right m-t-ng-8 txt_media1">Nav Title</label>
                            </div>
                            <div class="col-sm-8">
                                <input class="form-control" type="text" v-model="navItem.title">
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-4">
                                <label class="control-label float-right m-t-ng-8 txt_media1">Sort Number</label>
                            </div>
                            <div class="col-sm-8">
                                <input class="form-control" min="0" type="number" v-model="navItem.sort_id">
                            </div>
                        </div>

                        <div class="row form-group">
                            <div class="col-sm-4">
                                <label class="control-label float-right m-t-ng-8 txt_media1">Select Type</label>
                            </div>
                            <div class="col-sm-8">
                                <div class="iradio">
                                    <label>
                                        <input id="optionsRadios1" name="optionsRadios" type="radio" value="list">
                                        &nbsp; List view
                                    </label>
                                </div>
                                <div class="iradio">
                                    <label>
                                        <input id="optionsRadios2" name="optionsRadios" type="radio" value="board">
                                        &nbsp; Board View
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button @click="AddNavItem" class="btn btn-primary" type="button">Add</button>
                        <button aria-label="Close" class="btn btn-secondary" data-dismiss="modal" type="button">Cancel
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div aria-hidden="true" aria-labelledby="exampleModalLabel" class="modal fade" id="updateNavItem" role="dialog"
             tabindex="-1">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title pl-3 text-uppercase"> Update <span v-if="update_navItem.type === 'list'">List</span>
                            <span v-else>Board</span> Nav Item</h5>
                        <button aria-label="Close" class="close" data-dismiss="modal" type="button">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group row">
                            <div class="col-sm-4">
                                <label class="control-label float-right m-t-ng-8 txt_media1">Nav Title</label>
                            </div>
                            <div class="col-sm-8">
                                <input class="form-control" type="text" v-model="update_navItem.title">
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-4">
                                <label class="control-label float-right m-t-ng-8 txt_media1">Sort Number</label>
                            </div>
                            <div class="col-sm-8">
                                <input class="form-control" min="0" type="number" v-model="update_navItem.sort_id">
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <div class="action-task">
                            <h2>
                                <span class="btn btn-default pull-right dropdown-toggle action-list-board"
                                      data-toggle="dropdown">
                                    Option
                                </span>

                                <div aria-labelledby="dropdownMenuButton"
                                     class="dropdown-menu dropdown-menu-right dropdown-menu-custom">
                                    <h6 class="dropdown-header text-uppercase">
                                        Action For <span v-if="update_navItem.type === 'list'">List</span> <span v-else>Board</span>
                                    </h6>
                                    <div class="dropdown-divider"></div>
                                    <span class="dropdown-item custom-dropdown-item">
                                        <a href="javascript:void(0)"> <i class="fa fa-trash"></i> Delete with all <span
                                            v-if="update_navItem.type === 'list'">List</span> <span v-else>Board</span></a>
                                    </span>
                                    <span class="dropdown-item custom-dropdown-item">
                                        <a href="javascript:void(0)"> <i class="fa fa-arrows"></i> Delete & move <span
                                            v-if="update_navItem.type === 'list'">List</span> <span v-else>Board</span> </a>
                                    </span>

                                </div>

                            </h2>
                        </div>
                        <button @click="updateNavItem" class="btn btn-primary" type="button">Update</button>
                        <button aria-label="Close" class="btn btn-secondary" data-dismiss="modal" type="button">Cancel
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div aria-hidden="true" aria-labelledby="exampleModalLabel" class="modal fade" id="addListFromOverviewModel"
             role="dialog"
             tabindex="-1">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title pl-3"> Add List</h5>
                        <button aria-label="Close" class="close" data-dismiss="modal" type="button">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p>Add your new list here !</p>
                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label">Select List Nav </label>
                            <div class="col-sm-8">
                                <select class="form-control" v-model="list.nav_id">
                                    <option disabled value="0">Select List Nav
                                    </option>
                                    <option :key="index" v-bind:value="navs.id" v-for="(navs, index) in AllNavItems"
                                            v-if="navs.type === 'list'">
                                        {{navs.title}}
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label">List Title</label>
                            <div class="col-sm-8">
                                <input class="form-control" type="text" v-model="list.name">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label">List Description</label>
                            <div class="col-sm-8">
                                <textarea class="form-control" cols="40" name="" rows="3"
                                          v-model="list.description"></textarea>
                            </div>
                        </div>
                        <!--                        <p v-if="addField.error" class="text-danger"></p>-->
                    </div>
                    <div class="modal-footer">
                        <button @click="AddNewListOverview" class="btn btn-primary" type="button">Add</button>
                        <button aria-label="Close" class="btn btn-secondary" data-dismiss="modal" type="button">Cancel
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>


</template>
<style>
    .list-group-item {
        border: none !important;
    }

    #shortcutModel .modal-body {
        padding: 0 20px;
    }
</style>

<script>
    export default {
        name: "Navbar",
        props: ['projectId', 'AllNavGet', 'lists'],
        data() {
            return {
                baseUrl: window.location.origin,
                AllNavItems: this.AllNavGet,
                list_selected: this.lists,
                list: {
                    name: null,
                    description: null,
                    nav_id: 0,
                },
                navItem: {
                    title: null,
                    type: null,
                    sort_id: null,
                    project_id: null,
                },
                rules: null,
                overview: null,
                update_navItem: {
                    title: null,
                    type: null,
                    sort_id: null,
                    project_id: null,
                },
            }
        },
        mounted() {
            this.AllNavItem();
            this.ShowOverView();
        },
        methods: {

            showModelForNavItem() {
                $("#addNavItem").modal('show');
                $('input[name="optionsRadios"]').iCheck({
                    checkboxClass: 'icheckbox_square-blue',
                    radioClass: 'iradio_square-blue',
                    increaseArea: '20%' // optional
                });
            },
            shortcutModel() {
                $("#shortcutModel").modal('show');
            },
            addListFromOverview() {


                $("#addListFromOverviewModel").modal('show');
            },
            AddNavItem() {
                var _this = this;
                _this.navItem.project_id = _this.projectId;
                _this.navItem.type = $('input[name="optionsRadios"]:checked').val();

                axios.post('/api/nav-item/add-new', _this.navItem)
                    .then(response => response.data)
                    .then(response => {
                        if (response.status == 'exists') {
                            swal("Already Exist! ", "Enter Another Board Name !", "warning")
                        } else {
                            _this.AllNavItem();
                            $("#addNavItem").modal('hide');
                            _this.navItem.title = null;
                            _this.navItem.type = null;
                            _this.navItem.sort_id = null;
                            _this.navItem.project_id = null;
                            // swal("Created", "Nav Create Successful", "success")
                        }

                    })
                    .catch(error => {
                        console.log('Api for move down task not Working !!!')
                    });


                // console.log(_this.navItem)
            },
            AllNavItem() {
                var _this = this;
                axios.get('/api/nav-item/' + _this.projectId)
                    .then(response => response.data)
                    .then(response => {
                        _this.AllNavItems = response.success;
                        _this.rules = response.rules;
                        _this.$emit('getNavBars', {AllNavItem: _this.AllNavItems});
                        setTimeout(function () {
                            $('.dropdown').on('show.bs.dropdown', function () {
                                $('.nav-train-station').addClass('stop-train');
                            });
                            $('.dropdown').on('hide.bs.dropdown', function () {
                                $('.nav-train-station').removeClass('stop-train');
                            });
                        }, 500);

                    })
                    .catch(error => {
                        console.log('Api for move down task not Working !!!')
                    });


                // console.log(_this.navItem)
            },
            updateNavbarModel(data) {
                this.nav_id = data.id;
                this.update_navItem.title = data.title;
                this.update_navItem.type = data.type;
                this.update_navItem.sort_id = data.sort_id;
                this.update_navItem.nav_id = data.id;
                this.update_navItem.project_id = data.project_id;

                $("#updateNavItem").modal('show');
                $('input[name="optionsRadios"]').iCheck({
                    checkboxClass: 'icheckbox_square-blue',
                    radioClass: 'iradio_square-blue',
                    increaseArea: '20%' // optional
                });

            },
            updateNavItem() {
                var _this = this;
                axios.post('/api/nav-item/update', _this.update_navItem)
                    .then(response => response.data)
                    .then(response => {
                        console.log(response.success);
                        _this.AllNavItem();
                        $("#updateNavItem").modal('hide');

                    })
                    .catch(error => {
                        console.log('Api for move down task not Working !!!')
                    });
            },

            addListModel(id) {
                this.nav_id = id;
                $("#addListModel").modal('show');
            },
            addBoardModel(id) {
                this.list.name = '';
                this.list.description = '';
                this.nav_id = id;
                $("#addBoardModel").modal('show');
            },
            showSearchInputField() {
                this.$emit('showSearchInputField')
            },

            HideShowBoardTitle: () => {
                $('#col10').css('height', 'calc(100vh - 177px)');
                $('.smooth-dnd-container').css('height', 'calc(100vh - 262px)');

                $('#board_title').fadeIn();
                setTimeout(function () {
                    $('#board_title').fadeOut(1000);
                    $('#col10').css('height', 'calc(100vh - 104px)');
                    $('.smooth-dnd-container').css('height', 'calc(100vh - 188px)');
                }, 2000)

            },

            setListId(navList, nav_id, description, type) {

                this.list_id = navList.id;
                this.nav_id = nav_id;
                var title = (type === 'list') ? navList.list_title : navList.board_title;
                this.$emit('getList', {
                    list_id: navList.id,
                    nav_id: nav_id,
                    title: title,
                    description: description,
                    type: type
                })
            },

            ShowOverView() {
                // localStorage.selected_nav = JSON.stringify({
                //     list_id: null,
                //     nav_id: null,
                //     project_id: this.projectId,
                //     type: 'overview'
                // });
                this.overview = 'overview';
                this.$emit('getList', {
                    list_id: 0,
                    nav_id: 0,
                    title: 0,
                    description: '',
                    type: 'overview'
                })
            },
            setOverview() {
                localStorage.selected_nav = JSON.stringify({
                    list_id: null,
                    nav_id: null,
                    project_id: this.projectId,
                    type: 'overview'
                });
                this.ShowOverView();
            },
            CreateRule() {
                this.$emit('getList', {
                    list_id: 0,
                    nav_id: 0,
                    title: 0,
                    description: '',
                    type: 'rules',
                    action_type: 'rules',
                })
            },
            UpdateRule(id) {
                this.$emit('getList', {
                    list_id: 0,
                    nav_id: 0,
                    title: 0,
                    description: '',
                    type: 'rules',
                    rules_id: id,
                    action_type: 'update',
                })
            },


            AddNewList() {
                this.list.project_id = this.projectId;
                this.list.nav_id = this.nav_id;
                axios.post('/api/list-add', this.list)
                    .then(response => response.data)
                    .then(response => {

                        if (response.status == 'exists') {
                            swal("Already Exist! ", "Enter Another Board Name !", "warning")
                        } else {
                            this.multiple_list = response.multiple_list;
                            this.AllNavItem();
                            setTimeout(function () {
                                $('#list' + response.id.id).click();
                            }, 300);
                            $("#addListModel").modal('hide');
                            this.list.name = null;
                            this.list.description = null;
                            // swal("Created", "Board Create Successful", "success")
                        }
                    })
                    .catch(error => {
                        console.log('Add list api not working!!')
                    });
            },
            AddNewListOverview() {
                var _this = this;
                this.list.project_id = this.projectId;
                if (_this.list.name !== null && _this.list.description !== null && _this.list.nav_id !== 0) {
                    axios.post('/api/list-add', this.list)
                        .then(response => response.data)
                        .then(response => {

                            if (response.status == 'exists') {
                                swal("Already Exist! ", "Enter Another Board Name !", "warning")
                            } else {
                                _this.multiple_list = response.multiple_list;
                                _this.AllNavItem();
                                $("#addListFromOverviewModel").modal('hide');
                                _this.list.name = null;
                                _this.list.nav_id = 'Select list Nav';
                                _this.list.description = null;
                                _this.$emit('update_overview');
                                // swal("Created", "Board Create Successful", "success")
                            }
                        })
                        .catch(error => {
                            console.log('Add list api not working!!')
                        });
                }

            },

            AddNewBoard() {
                this.list.project_id = this.projectId;
                this.list.nav_id = this.nav_id;
                axios.post('/api/board-add', this.list)
                    .then(response => response.data)
                    .then(response => {
                        $("#addBoardModel").modal('hide');
                        if (response.status == 'exists') {
                            swal("Already Exist! ", "Enter Another Board Name !", "warning")
                        } else {
                            this.multiple_list = response.multiple_board;
                            this.AllNavItem();
                            this.list.name = null;
                            this.list.description = null;
                            // swal("Created", "Board Create Successful", "success")
                            setTimeout(function () {
                                $('.board' + response.id.id).click();
                            }, 300);
                        }
                    })
                    .catch(error => {
                        console.log('Add list api not working!!')
                    });
            },
            UpdateListModel() {
                $("#updateListBoardModel").modal('show');
            },
            MoveListTOAnotherNav(type) {
                this.$emit('MoveListTOAnotherNav', {type: type})
            },
            DeleteListOrBoard(type, action) {

                this.$emit('DeleteListOrBoard', {type: type, action: action})
            },
            DownloadTaskPDF() {
                this.$emit('DownloadTaskPDF')
            },
            HideDetails() {
                // this.getBoardTask();
                $('#task_width').addClass('task_width');
                $('#task_width').removeClass('task_widthNormal');
                $('#details').addClass('details');
                $('#details').removeClass('detailsShow');
            }
        },
        directives: {},
        watch: {
            AllNavGet: function (val) {
                if (val === null) {
                    this.AllNavItem()
                } else {
                    this.AllNavItems = val;
                }

            }
        }

    }
</script>

<style scoped>
</style>
