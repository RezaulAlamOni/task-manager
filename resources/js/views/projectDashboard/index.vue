<template>
    <div>
        <div class="page-titles">
            <!-- Navbar Component-->
            <Navbar :AllNavGet="AllNavItems"
                    :projectId="$route.params.projectId"
                    :lists="list"
                    @getList="showTask"
                    @showSearchInputField="showSearchInputField"
                    @MoveListTOAnotherNav="MoveListTOAnotherNav"
                    @DeleteListOrBoard="DeleteListOrBoard"
                    @DownloadTaskPDF="DownloadTaskPDF"
                    @getNavBars="getNavbar">
            </Navbar>

            <div class="input-group col-sm-4 searchList">

                <input class="form-control searchTaskList"
                       type="text" id="myInput"
                       placeholder="Search for names.."
                       title="Type in a name"
                       autocomplete="off"
                       @keyup="searchDataFormTask($event)"
                >

                <ul class="myUL" id="myUL">
                    <template v-for="task in searchData.tasks" v-if="searchData.tasks.length > 0">
                        <li>
                            <a @mouseover="selectTaskFromTaskTreeList(task)" style="word-break: break-all"
                               @click="SearchResultClick(task)"
                               href="Javascript:void(0)">
                                {{task.title}}
                            </a>
                        </li>
                    </template>
                    <template v-else>
                        <li>
                            <a href="javascript:void(0)">
                                No task found!
                            </a>
                        </li>
                    </template>

                </ul>
                <ul class="myUL-user-hide" id="myUL-user">
                    <template v-for="user in searchData.users"
                              v-if="searchData.users.length > 0 && searchData.users !== undefined">
                        <li @click="SearchTaskByAssignedUser(user.id,user.name)">
                            <a href="javascript:void(0)">
                                <span class="assignUser-suggest-photo">
                                        {{(user.name !== null) ? user.name.substring(0,2) : ''}}
                                </span>
                                {{user.name}}
                            </a>
                        </li>
                    </template>
                    <template v-else>
                        <li>
                            <a href="javascript:void(0)">
                                No user found!
                            </a>
                        </li>
                    </template>
                </ul>


            </div>
        </div>

<!--        <div class="col-md-8 text-center pt-5" v-if="AllNavItems === null">-->
<!--            <h2 style="color: #d1a894">Create Task View.</h2>-->
<!--        </div>-->
<!--        <div class="col-md-8 text-center pt-5" v-else-if="AllNavItems !== null && list.type === null">-->
<!--            <h2 style="color: #d1a894">Select Task View.</h2>-->
<!--        </div>-->
        <div :class="(list.type === 'list') ? 'list-task-details' : 'board-card-details'" v-if="list.type === 'list' || list.type === 'board'">

            <div style="padding-left: 5%" v-if="list.type === 'list'">

                <div class="col-12 action-task-list">
                    <h2 class="p-t-10" v-if="list.type === 'list'">
                        {{list.name}}
                    </h2>
                    <p class="compltit-p" v-if="list.type === 'list'">{{list.description}}</p>
                </div>
            </div>
            <div style="padding-left: 2%" v-else-if="list.type === 'board'">
                <div class="col-12 action-task" id="board_title">
                    <h2 class="p-t-10" v-if="list.type !== null">
                        {{list.name}}
                    </h2>
                    <p class="compltit-p" v-if="list.description != null">{{list.description}}</p>
                </div>
            </div>


            <!--        //tree view component section-->
            <div class="TaskListAndDetails container-fluid" v-if="list.type === 'list'" id="TaskListAndDetails">
                <div class="task_width" id="task_width">
                    <div class="col-11" id="tree_view_list">

                        <Tree :data="treeList" :indent="2" :space="0" @change="ChangeNode"
                              v-click-outside="DeleteEmptyTask"
                              @drop="dropNode"
                              class="tree4"
                              @drag="dragNode"
                              @moving="dragNode"
                              cross-tree="cross-tree"
                              draggable="draggable"
                              v-if="list.type === 'list'">
                            <div :class="{eachItemRow: true}" :id="'click'+data.id"
                                 @contextmenu="makeItClick($event, data,vm)"
                                 slot-scope="{data, _id,store,vm}"
                                 @click="makeItClick($event, data,vm)"
                                 @keyup="makeItClick($event, data,vm)"
                                 style="font-size: 12px" v-on:dblclick="showLog">
                                <template v-html="data.html" v-if="!data.isDragPlaceHolder">

                                <span class="progress-bar-custom">
                                    <img :src="baseUrl+'/img/'+data.progress+'.png'" alt="" height="28" width="28"
                                         v-if="(data.progress !== null)"
                                         class="task-complete-progress left-content">

                                    <img v-else :src="baseUrl+'/img/task-icon/circle.png'" alt="" height="28" width="28"
                                         class="task-complete-progress left-content li-opacity ">
                                    <!--                                    <div class="task-complete-progress empty-progress left-content li-opacity "></div>-->

                                    <img :src="baseUrl+'/img/'+data.progress+'.png'" alt="" height="28" width="28"
                                         v-if="data.progress === '100'"
                                         :title="(data.complete_tooltip !== null) ? data.complete_tooltip : 'Complete'"
                                         data-toggle="tooltip"
                                         class="task-complete left-content li-opacity ">

                                    <img :src="baseUrl+'/img/task-icon/circle-check.png'" alt="" height="28" width="28"
                                         v-else
                                         @click="addTaskToComplete(data)"
                                         :title="(data.complete_tooltip !== '') ? data.complete_tooltip : 'Complete'"
                                         data-toggle="tooltip"
                                         class="task-complete left-content li-opacity">
                                </span>

                                    <b @click="HideShowChild(store , data)"
                                       v-if="data.children && data.children.length && data.open"><i
                                        class="fa fa-fw fa-minus"></i></b>
                                    <b @click="HideShowChild(store , data)"
                                       v-else-if="data.children && data.children.length && !data.open"><i
                                        class="fa fa-fw fa-plus"></i></b>
                                    <span>
                                        <input :id="data.id"
                                               @blur="showItem($event,data)"
                                               @click="makeInput($event,data)"
                                               @focus="hideItem($event,data)"
                                               @keydown="keyDownAction($event,data)"
                                               @keypress="saveData($event,data)"

                                               class="inp input-hide input-title"
                                               type="text" v-model="data.text">
                                    </span>

                                    <a class="attach-icon hide-item-res" style="width: auto !important;">
                                        <span v-if="data.files && data.files.length !== 0">
                                            <template v-for="(fl,file_id ) in data.files">
                                                <img :src="'/storage/'+data.id+'/'+fl.file_name" v-if="file_id < 2"
                                                     @click="showImage(data.files, fl.file_name,data.id)"
                                                     class="task-img">
                                            </template>
                                        </span>

                                        <img :src="baseUrl+'/img/task-icon/upload.png'"
                                             @click="addAttachment(data)"
                                             title="File" data-toggle="tooltip"
                                             class="icon-image-preview li-opacity pull-right">

                                        <!--                                    <i @click="addAttachment(data)"-->
                                        <!--                                       class="cloud-image-upload icon-image-preview dropdown-toggle-split li-opacity"-->
                                        <!--                                       title="File" data-toggle="tooltip"></i>-->

                                        <input :id="'file'+data._id" @change="updatePicture($event,data)" ref="file"
                                               style="display: none; "
                                               type="file">
                                    </a>


                                    <a class="tag-icon hide-item-res" style="width: auto !important;">
                                        <i class="dropdown-toggle-split"
                                           data-toggle="dropdown"
                                           v-if="data.tags.length > 0">
                                            <template v-for="(tag ,index) in data.tags">
                                                <template v-if="index < 2">
                                                    <span :title="data.tagTooltip" class="badge badge-warning"
                                                          data-placement="bottom" data-toggle="tooltip"
                                                          v-bind:style="[{'background':tag.color},{'margin-left' : 1 +'px'},{'float' : 'left'}]"
                                                          v-if="tag.text !== null">
                                                        {{(data.tags.length > 2 ) ? tag.text.substring(0,3) : tag.text.substring(0,3) }}
                                                    </span>
                                                    <span class="badge badge-warning"
                                                          v-bind:style="[{'background':tag.color},{'margin-left' : 1 +'px'}]"
                                                          v-else>
                                                        ::
                                                    </span>
                                                </template>
                                            </template>
                                        </i>
                                        <span :id="'tag-'+data._id" data-toggle="dropdown" v-else>
                                        <img :src="baseUrl+'/img/task-icon/tag-add.png'" style="margin-right: 19px;"
                                             class="icon-image-preview li-opacity pull-right" data-toggle="tooltip"
                                             title="Add Tag">
                                    </span>

                                        <div :id="'dropdown'+data._id"
                                             class="dropdown-menu dropdown-menu-tag dropdown-menu-right ">

                                            <diV class="collapse show switchToggle" style="">
                                                <div class="container-fluid">
                                                    <vue-tags-input
                                                        :allow-edit-tags="true"
                                                        :tags="data.tags"
                                                        @before-deleting-tag="DeleteTag"
                                                        @tags-changed="newTags => (changeTAg(newTags))"
                                                        v-model="tag1"
                                                    />
                                                    <div class="row">
                                                        <div class="col-12">
                                                            <template v-for="tag in data.existing_tags">
                                                                <li class="badge-pill tags"
                                                                    @click="addExistingTag(data , tag.title,tag.color)"
                                                                    v-bind:style="[{'background': tag.color },{'margin-left' : 1 +'px'}]"
                                                                    v-if="tag.text !== 'Dont Forget'">
                                                                    {{(tag.title !== undefined)
                                                                    ?tag.title.substring(0,12) :
                                                                    ''}}
                                                                </li>
                                                            </template>
                                                            <li @click="addExistingTag(data ,'Dont Forget','')"
                                                                class="badge-pill tags"
                                                                style="background: #FB8678">
                                                                Dont Forget
                                                            </li>
                                                        </div>
                                                    </div>
                                                    <hr>
                                                    <div class="col-xs-12" style="margin-top:10px;width: 100%;">
                                                        <button @click="showTagManageModel"
                                                                class="btn btn-small btn-primary pull-right"
                                                                type="submit">Manage Tag
                                                        </button>
                                                    </div>
                                                </div>

                                            </diV>
                                        </div>
                                    </a>

                                    <div class="hide-item-res" @click="openPicker()">
                                        <a class="calender li-opacity clickHide" v-if="data.date === '0000-00-00'"
                                           title="Due Date">
                                            <img :src="baseUrl+'/img/task-icon/date-plus.png'"
                                                 class="icon-image-preview">
                                            <!--                                        <i class="outline-event icon-image-preview" data-toggle-->
                                            <!--                                           title="toggle"></i>-->

                                        </a>
                                        <datepicker
                                            :disabled-dates="disabledDates"
                                            @selected="updateDate"
                                            calendar-button-icon='<i class="outline-event icon-image-preview"></i>'
                                            format='dd MMM'
                                            input-class="dateCal"
                                            title="Due Date" data-toggle="tooltip" data-placement="left"
                                            v-model="data.date">
                                        </datepicker>


                                    </div>
                                    <div class="hide-item-res-user">
                                        <a class="user dropdown-hide-with-remove-icon">
                                            <template v-if="data.assigned_user.length > 0">
                                            <span class="assigned_user dropdown-toggle-split "
                                                  data-toggle="dropdown" v-for="(assign,keyId) in data.assigned_user">
                                                <p :title="assign.name"
                                                   class="assignUser-photo-for-selected text-uppercase"
                                                   data-placement="bottom" data-toggle="tooltip"
                                                   v-if="keyId <= 1">{{(assign.name !== null) ? assign.name.substring(0,2) : ''}}
                                                </p>

                                            </span>
                                            </template>
                                            <span data-toggle="dropdown" class=" dropdown-toggle-split" v-else>
<!--                                            <i class="outline-person icon-image-preview li-opacity "-->
                                                <!--                                               data-toggle="tooltip" title="Assignee">-->
                                                <!--                                            </i>-->
                                            <img :src="baseUrl+'/img/task-icon/add-user.png'"
                                                 class="icon-image-preview li-opacity assign-user-"
                                                 data-toggle="tooltip" title="Assignee user">
                                        </span>

                                            <div class="dropdown-menu dropdown-menu-right" style="z-index: 1;">
                                                <diV class="collapse show switchToggle">
                                                    <li class="assignUser">
                                                        <input class="input-group searchUser"
                                                               placeholder="Assign by name and email"
                                                               type="text">
                                                        <label class="pl-2 label-text">
                                                        <span class="assign-user-drop-down-text">
                                                            Or invite a new member by email address
                                                        </span>
                                                        </label>
                                                    </li>
                                                    <li class="assignUser">
                                                        <template v-for="user in data.users"
                                                                  v-if="data.users !== undefined">
                                                            <div
                                                                @click="(data.assigned_user_ids.includes(user.id)) ? '' : assignUserToTask(user,data) "
                                                                :class="(data.assigned_user_ids.includes(user.id)) ? 'active-user disabled' : 'users-select'"
                                                                class=" row"
                                                                v-bind:disabled="(data.assigned_user_ids.includes(user.id)) ? true : false">
                                                                <div class="col-md-3 pt-1 pl-4">
                                                                    <p class="assignUser-photo">
                                                                        {{(user.name !== null) ?
                                                                        user.name.substring(0,2) :
                                                                        ''}}</p>
                                                                </div>
                                                                <div class="col-md-9 assign-user-name-email">
                                                                    <h5>{{user.name}}<br>
                                                                        <small>{{user.email}}</small>
                                                                    </h5>
                                                                </div>
                                                                <a :id="'remove-assign-user'+user.id"
                                                                   v-if="data.assigned_user_ids.includes(user.id)"
                                                                   @click="removeAssignedUser(user.id, data.id)"
                                                                   data-toggle="tooltip"
                                                                   title="Remove user from assigned !"
                                                                   class="remove-assign-user badge badge-danger"
                                                                   href="javascript:void(0)">
                                                                    <i class="fa fa-user-times remove-assign-user-icon"></i>
                                                                </a>
                                                                <a :id="'remove-assign-user'+user.id" v-else
                                                                   data-toggle="tooltip" title="Assign user to task!"
                                                                   class="remove-assign-user badge badge-success"
                                                                   href="javascript:void(0)">
                                                                    <i class="fa fa-user-plus remove-assign-user-icon"></i>
                                                                </a>
                                                            </div>

                                                        </template>
                                                    </li>
                                                </diV>
                                                <li @click="switchEvent($event)"
                                                    class="border-top pl-2 assign-user-drop-down-footer">

                                                    <span
                                                        class="assign-user-drop-down-text">Assign an external team</span>
                                                    <switches class="assign-user-switch-for-dropdown"
                                                              color="success"
                                                              theme="bootstrap"
                                                              v-model="id">
                                                    </switches>
                                                </li>
                                            </div>
                                        </a>
                                    </div>
                                    <a @click="addChild(data)" class="subTask_plus li-opacity li-opacity-sub clickHide "
                                       data-toggle="tooltip" title="Add Child">
                                        <!--                                    <i class="baseline-playlist_add icon-image-preview"></i>-->
                                        <img :src="baseUrl+'/img/task-icon/add-Sublist.png'"
                                             class="icon-image-preview li-opacity-sub ">
                                    </a>
                                    <a @click="addNode(data)" class="task_plus li-opacity li-opacity-sub clickHide"
                                       data-toggle="tooltip"
                                       title="Add Task Bellow">
                                        <!--                                    <i class="baseline-add icon-image-preview"></i>-->
                                        <img :src="baseUrl+'/img/task-icon/add-list.png'"
                                             class="icon-image-preview li-opacity-sub ">
                                    </a>
                                </template>
                            </div>

                        </Tree>

                        <div class="jquery-accordion-menu" id="jquery-accordion-menu" v-click-outside="HideCustomMenu">
                            <ul>
                                <li>
                                    <a href="javascript:void(0)" class="dropdown-toggle-split "
                                       v-if="selectedData.text !== 'Dont Forget Section'"
                                       @click="copyTask"
                                       data-toggle="dropdown">
                                        <img :src="baseUrl+'/img/task-icon/copy-1.png'" class="contex-menu-icon">
                                        Copy </a>
                                    <a href="javascript:void(0)" class="dropdown-toggle-split disabled" v-else
                                       style="color: gray"
                                       data-toggle="dropdown">
                                        <img :src="baseUrl+'/img/task-icon/copy-1.png'" class="contex-menu-icon">
                                        Copy </a>

                                    <span class="contex-menu-sortcut">
                                    <span class="badge-pill badge-default">Ctrl</span>+<span
                                        class="badge-pill badge-default">C</span>
                                </span>

                                </li>
                                <li>
                                    <a href="javascript:void(0)" class="dropdown-toggle-split "
                                       v-if="selectedData.text !== 'Dont Forget Section'"
                                       @click="cutTask"
                                       data-toggle="dropdown">

                                        <img :src="baseUrl+'/img/task-icon/cut.png'" class="contex-menu-icon">
                                        Cut </a>
                                    <a href="javascript:void(0)" class="dropdown-toggle-split disabled"
                                       v-else style="color: gray"
                                       data-toggle="dropdown">
                                        <img :src="baseUrl+'/img/task-icon/cut.png'" class="contex-menu-icon">
                                        Cut </a>
                                    <span class="contex-menu-sortcut">
                                    <span class="badge-pill badge-default">Ctrl</span>+<span
                                        class="badge-pill badge-default">X</span>
                                </span>
                                </li>
                                <li>
                                    <a href="javascript:void(0)" class="dropdown-toggle-split "
                                       @click="pastCopyAndCut"
                                       data-toggle="dropdown"
                                       v-if="selectedCopy !== null || selectedCut !== null">
                                        <img :src="baseUrl+'/img/task-icon/past.png'" class="contex-menu-icon">
                                        Paste </a>
                                    <a href="javascript:void(0)" class="dropdown-toggle-split disabled"
                                       style="color: gray"
                                       data-toggle="dropdown"
                                       v-else>
                                        <img :src="baseUrl+'/img/task-icon/past.png'" class="contex-menu-icon">
                                        Paste </a>
                                    <span class="contex-menu-sortcut">
                                    <span class="badge-pill badge-default">Ctrl</span>+<span
                                        class="badge-pill badge-default">v</span>
                                </span>
                                </li>
                                <li>
                                    <a @click="CopyToSelectedTask" href="javascript:void(0)">
                                        <img :src="baseUrl+'/img/task-icon/copy-to.png'" class="contex-menu-icon">
                                        Copy Selected To
                                    </a>
                                    <span class="contex-menu-sortcut">
                                   <span class="badge-pill badge-default">Ctrl</span>+<span
                                        class="badge-pill badge-default">T</span>
                                </span>
                                </li>
                                <li>
                                    <a @click="MoveSelectedTask" href="javascript:void(0)">
                                        <img :src="baseUrl+'/img/task-icon/move.png'" class="contex-menu-icon">
                                        Move Selected
                                    </a>
                                    <span class="contex-menu-sortcut">
                                   <span class="badge-pill badge-default">Ctrl</span>+<span
                                        class="badge-pill badge-default">M</span>
                                </span>
                                </li>
                                <li>
                                    <a @click="deleteSelectedTask" href="javascript:void(0)">
                                        <img :src="baseUrl+'/img/task-icon/trash.png'" class="contex-menu-icon">

                                        Delete Selected <span
                                        class="badge-pill badge-default contex-menu-sortcut">Delete</span></a>
                                </li>
                                <li>
                                    <a href="javascript:void(0)" class="dropdown-toggle-split " data-toggle="dropdown">
                                        <img :src="baseUrl+'/img/task-icon/add-user.png'" class="contex-menu-icon">
                                        Assign User to Selected </a>
                                    <span class="contex-menu-sortcut">
                                    <span class="badge-pill badge-default">Ctrl</span>+<span
                                        class="badge-pill badge-default">U</span>
                                </span>

                                    <div class="dropdown-menu dropdown-menu-right">
                                        <diV class="collapse show switchToggle">

                                            <ul>
                                                <li class="assignUser">
                                                    <label class="pl-2 label-text">
                                                    <span class="assign-user-drop-down-text">
                                                        Select User For Assign To Selected Task.
                                                    </span>
                                                    </label>
                                                </li>
                                                <li class="assignUser">
                                                    <template v-if="treeList[0].users && treeList[0].users.length > 0"
                                                              v-for="user in treeList[0].users">
                                                        <div @click="ActionToSelectedTask(user.id,'user')"
                                                             class="users-select row">
                                                            <div class="col-md-3 pt-1 pl-4">
                                                                <p class="assignUser-photo">
                                                                    {{(user.name !== null) ? user.name.substring(0,2) :
                                                                    ''}}</p>
                                                            </div>
                                                            <div class="col-md-9 assign-user-name-email">
                                                                <h5>{{user.name}}<br>
                                                                    <small>{{user.email}}</small>
                                                                </h5>
                                                            </div>
                                                        </div>
                                                    </template>
                                                </li>
                                            </ul>
                                        </diV>
                                    </div>
                                </li>
                                <li>
                                    <a href="javascript:void(0)" class="dropdown-toggle-split " data-toggle="dropdown">
                                        <img :src="baseUrl+'/img/task-icon/tag-add.png'" class="contex-menu-icon">
                                        Add Tags to Selected
                                    </a>
                                    <span class="contex-menu-sortcut">
                                    <span class="badge-pill badge-default">Shift</span>+<span
                                        class="badge-pill badge-default">#</span>
                                </span>

                                    <div class="dropdown-menu dropdown-menu-right">
                                        <diV class="collapse show switchToggle">

                                            <ul>
                                                <li class="assignUser">
                                                    <label class="pl-2 label-text">
                                                    <span class="assign-user-drop-down-text">
                                                        Select Tag For Add tag To Selected Task.
                                                    </span>
                                                    </label>
                                                </li>
                                                <li class="assignUser">
                                                    <template v-if="treeList[0].existing_tags.length > 0"
                                                              v-for="user in treeList[0].existing_tags">
                                                        <div @click="ActionToSelectedTask(user.id,'tag')"
                                                             class="users-select row">
                                                            <div class="col-md-9 add-tag-to-selected">
                                                            <span
                                                                class="badge badge-default tag-color-custom-contextmenu"
                                                                :style="{'background' : user.color}"
                                                            >.</span>
                                                                <h5>{{user.title}}</h5>
                                                            </div>
                                                        </div>
                                                    </template>
                                                </li>
                                            </ul>
                                        </diV>
                                    </div>
                                </li>
                                <li style="position: relative" @click="openPicker()">

                                    <datepicker
                                        :disabled-dates="disabledDates"
                                        v-model="date_for_selected"
                                        @selected="ActionToSelectedTask('','date')"
                                        calendar-button-icon='<i class="outline-event icon-image-preview"></i>'
                                        format='dd MMM'
                                        input-class="dateCal-selected">

                                    </datepicker>

                                    <a class="calender li-opacity clickHide">
                                        <img :src="baseUrl+'/img/task-icon/date-plus.png'" class="contex-menu-icon">
                                        Set Due Date
                                    </a>


                                </li>
                                <li><a @click="AddDontForgetTagToSelectedIds" href="javascript:void(0)">

                                    <img :src="baseUrl+'/img/task-icon/move-1.png'" class="contex-menu-icon">
                                    Move To Dont Forget Section </a>
                                </li>

                            </ul>
                        </div>
                    </div>
                    <div style="height: 50px;">

                    </div>
                </div>
                <div class="details" id="details" v-click-outside="HideDetails">
                    <TaskDetails
                        :selectedData="selectedData"
                        :task_logs="task_logs"
                        @textArea="ShowTextArea">
                    </TaskDetails>
                </div>
            </div>
            <div class="boardView" v-if="list.type === 'board'">
                <!-- Board View Component -->
                <BoardView
                    :board_id="list_id"
                    :nav_id="nav_id"
                    :projectId="projectId">
                </BoardView>

            </div>

        </div>

        <div class="boardView" v-if="list.type === 'rules'">
            <Rules
            :project_id="projectId"
            :id="rule.id"
            :action_type="rule.action_type"
            @ruleUpdate="RuleUpdate">
            </Rules>
        </div>
        <div class="project-overview" v-if="list.type === 'overview'">
            <Overview
            :projectID="projectId">
            </Overview>
        </div>


        <div aria-hidden="true" aria-labelledby="exampleModalLabel" class="modal fade" id="imageModal" role="dialog"
             tabindex="-1">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Image Show</h5>
                        <span @click="deletePhoto(modalImg[0],modalImg[1])" class="badge badge-warning file-delete">Delete Photo</span>
                        <button aria-label="Close" class="close" data-dismiss="modal" type="button">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <img :src="'/storage/'+modalImg[1]+'/'+modalImg[0]" class="image-auto">
                    </div>
                </div>
            </div>
        </div>
        <div aria-hidden="true" aria-labelledby="exampleModalLabel" class="modal fade" id="TagManage" role="dialog"
             tabindex="-1">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h3 class="text-center text-uppercase">Manage All Tag</h3>
                        <button aria-label="Close" class="close" data-dismiss="modal" type="button">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="table-responsive" id="table">
                            <table class="table" data-v-095ab3dc="">
                                <thead data-v-095ab3dc="">
                                <tr data-v-095ab3dc="">
                                    <th>Tag Title</th>
                                    <th>Color</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                <template v-for="tag in manageTag">
                                    <tr v-if="tag.title !== 'Dont Forget'">
                                        <td @keydown="newLineoff($event)" @keyup="updateTagName($event,tag)"
                                            class="pt-3-half"
                                            contenteditable="true">
                                            {{tag.title}}
                                        </td>
                                        <td class="pt-3-half">
                                            <input :value="tag.color" @change="updateTagColor($event,tag)"
                                                   style="cursor: pointer;background-color: #fff;border: none;"
                                                   type="color">
                                        </td>
                                        <td>
                                            <a @click="DeleteTagFromModal(tag)"
                                               class="compltit-blue-a badge badge-danger"
                                               href="javascript:void(0)">
                                                Delete
                                            </a>
                                        </td>
                                    </tr>
                                </template>

                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button aria-label="Close" class="btn btn-secondary" data-dismiss="modal" type="button">Cancel
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div aria-hidden="true" aria-labelledby="exampleModalLabel" class="modal fade" id="updateListBoardModel"
             role="dialog"
             tabindex="-1">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title pl-3"> Update {{list.name}} <span
                            class="text-uppercase">[{{list.type}}]</span></h5>
                        <button aria-label="Close" class="close" data-dismiss="modal" type="button">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p>Add your new list here !</p>
                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label">Title</label>
                            <div class="col-sm-8">
                                <input class="form-control" type="text" v-model="list.name">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label">Description</label>
                            <div class="col-sm-8">
                                <textarea class="form-control" cols="40" id="" name="" rows="3"
                                          v-model="list.description"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <!--                        <button @click="UpdateListOrBoard" class="btn btn-primary" type="button">Update</button>-->
                        <button @click="UpdateListOrBoard" class="btn btn-primary ladda-button ladda_update_list_board"
                                data-style="expand-right">
                            Update
                        </button>
                        <button aria-label="Close" class="btn btn-secondary" data-dismiss="modal" type="button">Cancel
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div aria-hidden="true" aria-labelledby="exampleModalLabel" class="modal fade" id="transAndMoveTAsk"
             role="dialog"
             tabindex="-1">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header" style="border-radius: 13px;">
                        <h4 class="text-center ">Delete And Move <span v-if="type_T === 'board'">Card</span> <span
                            v-else>Task</span>
                            To Another <span v-if="type_T === 'board'">Board</span> <span v-else>List</span></h4>
                        <button aria-label="Close" class="close" data-dismiss="modal" type="button">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label">Select <span v-if="type_T === 'board'">Board</span>
                                <span v-else>List</span> Nav :</label>
                            <div class="col-sm-8">
                                <select @change="showSubList_T" class="form-control" v-model="selectedListNav">
                                    <option disabled value="Select list Nav">Select <span v-if="type_T === 'board'">Board</span>
                                        <span v-else>List</span> Nav
                                    </option>
                                    <option :key="index" v-bind:value="navs.id" v-for="(navs, index) in nav_T"
                                            v-if="navs.type === type_T">{{navs.title}}
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row" v-if="list_T.length > 0">
                            <label class="col-sm-4 col-form-label">Select <span v-if="type_T === 'board'">Board</span>
                                <span v-else>List</span> :</label>
                            <div class="col-sm-8">
                                <select class="form-control" v-model="selectedSubList" @change="get_T_Bttn()">
                                    <option disabled value="Select list">Select <span
                                        v-if="type_T === 'board'">Board</span> <span v-else>List</span></option>
                                    <option :key="index" v-bind:value="navList.id" v-for="(navList, index) in list_T"
                                            :disabled="((navList.id !== list_id) ? false : true)">
                                        <span v-if="type_T === 'board'">{{navList.board_title}}</span> <span v-else>{{navList.list_title}}</span>
                                    </option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button v-if="transferBtn" aria-label="Close" @click="DeleteAndMoveAllTask"
                                class="btn btn-danger" data-dismiss="modal" type="button">Delete & Move All <span
                            v-if="type_T === 'board'">Card</span> <span v-else>Task</span>
                        </button>
                        <button aria-label="Close" class="btn btn-secondary" data-dismiss="modal" type="button">Cancel
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div aria-hidden="true" aria-labelledby="exampleModalLabel" class="modal fade" id="MoveTAsk"
             role="dialog"
             tabindex="-1">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header" style="border-radius: 13px;">
                        <h4 class="text-center ">Move Task To Board / List </h4>
                        <button aria-label="Close" class="close" data-dismiss="modal" type="button">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label">Select Board Or List Nav </label>
                            <div class="col-sm-8">
                                <select @change="showSubList_T" class="form-control" v-model="selectedListNav">
                                    <option disabled value="Select list Nav">Select Board Or List Nav
                                    </option>
                                    <option :key="index" v-bind:value="navs.id" v-for="(navs, index) in nav_T">
                                        {{navs.title}}
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row" v-if="list_T.length > 0">
                            <label class="col-sm-4 col-form-label">Select Board or List:</label>
                            <div class="col-sm-8">
                                <select class="form-control" v-model="selectedSubList"
                                        @change="getColumnAndConfirmButton()">
                                    <option disabled value="Select list">Select
                                        Board or List
                                    </option>
                                    <option :key="index" v-bind:value="navList.id" v-for="(navList, index) in list_T"
                                            :disabled="((navList.id !== list_id) ? false : true)">
                                        <span v-if="type_T === 'board'">{{navList.board_title}}</span> <span v-else>{{navList.list_title}}</span>
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row" v-if="column_T.length > 0">
                            <label class="col-sm-4 col-form-label">Select Column:</label>
                            <div class="col-sm-8">
                                <select class="form-control" v-model="selectedColumn" @change="get_T_Bttn()">
                                    <option disabled value="Select column">Select column
                                    </option>
                                    <option :key="index" v-bind:value="navList.id" v-for="(navList, index) in column_T">
                                        {{navList.title}}
                                    </option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button v-if="transferBtn" aria-label="Close" @click="MoveTaskToListOrBoard"
                                class="btn btn-warning" data-dismiss="modal" type="button">Move Selected
                        </button>
                        <button aria-label="Close" class="btn btn-secondary" data-dismiss="modal" type="button">Cancel
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div aria-hidden="true" aria-labelledby="exampleModalLabel" class="modal fade" id="CopyTaskTo"
             role="dialog"
             tabindex="-1">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header" style="border-radius: 13px;">
                        <h4 class="text-center ">Select List To Past Selected Task</h4>
                        <button aria-label="Close" class="close" data-dismiss="modal" type="button">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label">Select List Nav </label>
                            <div class="col-sm-8">
                                <select @change="showSubList_T" class="form-control" v-model="selectedListNav">
                                    <option disabled value="Select list Nav">
                                        Select List Nav
                                    </option>
                                    <option :key="index" v-bind:value="navs.id" v-for="(navs, index) in nav_T" v-if="navs.type === 'list'">
                                        {{navs.title}}
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row" v-if="list_T.length > 0">
                            <label class="col-sm-4 col-form-label">Select Board or List:</label>
                            <div class="col-sm-8">
                                <select class="form-control" v-model="selectedSubList"
                                        @change="get_T_Bttn()">
                                    <option disabled value="Select list">Select
                                        Board or List
                                    </option>
                                    <option :key="index" v-bind:value="navList.id" v-for="(navList, index) in list_T"
                                            :disabled="((navList.id !== list_id) ? false : true)">
                                        <span>{{navList.list_title}}</span>
                                    </option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button v-if="transferBtn" aria-label="Close" @click="PastTaskToList"
                                class="btn btn-warning" data-dismiss="modal" type="button">Past Selected
                        </button>
                        <button aria-label="Close" class="btn btn-secondary" data-dismiss="modal" type="button">Cancel
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div aria-hidden="true" aria-labelledby="exampleModalLabel" class="modal fade" id="transToNav" role="dialog"
             tabindex="-1">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header" style="border-radius: 13px;">
                        <h4 class="text-center ">Move <span v-if="type_T === 'board'">Board</span> <span
                            v-else>List</span></h4>
                        <button aria-label="Close" class="close" data-dismiss="modal" type="button">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label">Select <span v-if="type_T === 'board'">Board</span>
                                <span v-else>List</span> Nav :</label>
                            <div class="col-sm-8">
                                <select @change="get_T_Bttn()" class="form-control" v-model="selectedListNav">
                                    <option disabled value="Select list Nav">Select <span v-if="type_T === 'board'">Board</span>
                                        <span v-else>List</span> Nav
                                    </option>
                                    <option :key="index" v-bind:value="navs.id" v-for="(navs, index) in nav_T"
                                            v-if="navs.type === type_T"
                                            :disabled="((navs.id !== nav_id) ? false : true)">{{navs.title}}
                                    </option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button v-if="transferBtn" aria-label="Close" @click="MoveAllTask"
                                class="btn btn-danger" data-dismiss="modal" type="button">Move <span
                            v-if="type_T === 'board'">Board</span> <span v-else>List</span>
                        </button>
                        <button aria-label="Close" class="btn btn-secondary" data-dismiss="modal" type="button">Cancel
                        </button>
                    </div>
                </div>
            </div>
        </div>

    </div>

</template>
<style>

</style>
<script>
    import draggableHelper from 'draggable-helper';
    import {DraggableTree} from 'vue-draggable-nested-tree';
    import switches from 'vue-switches';
    import hotkeys from 'hotkeys-js';
    import ClickOutside from 'vue-click-outside';
    import Datepicker from 'vuejs-datepicker';
    import VueTagsInput from '@johmun/vue-tags-input';
    import TaskDetails from "./TaskDetails";
    import Navbar from "./ProjectNavbar/Navbar";
    import BoardView from "./board";
    import Rules from "../Rules/Rules";
    import Overview from "../OverView/OverView";
    import * as Ladda from 'ladda';

    export default {
        components: {
            Tree: DraggableTree,
            thy: draggableHelper,
            switches,
            Datepicker,
            VueTagsInput,
            TaskDetails,
            Navbar, BoardView,
            Ladda,
            Rules,
            Overview
        },
        data() {
            return {
                baseUrl: window.location.origin,
                disabledDates: {
                    id: null,
                },
                id: 0,
                treeList: [],
                date_config: {
                    enableTime: false,
                    wrap: true,
                    disableMobile: true,
                    dateFormat: 'd M',
                },
                date: '',
                modalImg: '',
                selectedData: {},
                selectedCopy: null,
                selectedCut: null,
                makeChildText: null,
                tabKey: 0,
                reselectParentId: null,
                tag: null,
                projectId: null,
                list_id: null,
                newEmptyTaskID: null,
                multiple_list: null,
                list: {
                    id: null,
                    name: null,
                    description: null,
                    nav_id: null,
                    type: null
                },
                nav_id: null,
                AllNavItems: null,
                task_logs: null,
                file: null,
                tag1: '',
                manageTag: null,
                selectedIds: [],
                searchData: {
                    tasks: [],
                    users: []
                },
                transferBtn: false,
                date_for_selected: null,
                dNode: null,
                dNodeInterval: null,
                dNodeHeght: 0,
                context_menu_flag: 0,
                selectedListNav: 'Select List Nav',
                selectedSubList: 'Select List',
                selectedColumn: 'Select column',
                nav_T: [],
                list_T: [],
                column_T: [],
                boardColumn: [],
                action_T: '',
                type_T: '',
                delete_popup: 0,
                empty_task_delete_flag: 0,
                rule : {
                    id : null,
                    action_type : null,
                    update : null
                },
                overview : ''

            }
        },
        mounted() {
            let _this = this;
            this.projectId = this.$route.params.projectId;
            $('.searchList').hide();
            $('.SubmitButton').hide();
            $('.submitdetails').hide();
            if (localStorage.selected_nav !== undefined) {
                var session_data = JSON.parse(localStorage.selected_nav);
                if (session_data.type === 'list') {
                    setTimeout(function () {
                        $('#list' + session_data.list_id).click();
                    }, 1000)
                } else {
                    setTimeout(function () {
                        $('.board' + session_data.list_id).click();
                    }, 1000)
                }
            }
        },
        created() {
            let _this = this;
            hotkeys('enter,tab,shift+tab,up,down,left,right,ctrl+c,ctrl+x,ctrl+a,ctrl+v,ctrl+u,delete,ctrl+b,ctrl+s,ctrl+i,shift+3,ctrl+m', function (event, handler) {
                event.preventDefault();
                switch (handler.key) {
                    case "enter" :
                        if (_this.delete_popup === 1) {
                            setTimeout(function () {
                                $('.confirm').click();
                                swal.close()
                            }, 100);
                            _this.delete_popup = 0;
                            _this.selectedIds = [];
                        } else {
                            if (_this.selectedIds.length === 1) {
                                _this.addNode(_this.selectedData);
                                swal.close();
                            } else {
                                swal.close();
                            }
                        }
                        break;
                    case "tab" :
                        _this.makeChild(_this.selectedData);
                        break;
                    case "shift+tab":
                        _this.unMakeChild(_this.selectedData);
                        break;
                    case "up" :
                        if (Object.keys(_this.selectedData).length > 0) {
                            _this.moveItemUp(_this.selectedData);
                        }
                        _this.selectedData = {};
                        break;
                    case "down" :
                        if (Object.keys(_this.selectedData).length > 0) {
                            _this.moveItemDown(_this.selectedData);
                        }
                        _this.selectedData = {};
                        break;
                    case "left" :
                        _this.HideDetails(_this.selectedData);
                        break;
                    case "right" :
                        if (_this.selectedIds.length >= 1) {
                            _this.showLog();
                            _this.task_logs = null;
                            _this.ShowDetails(_this.selectedData);
                            setTimeout(function () {
                                $('#_details').click();
                            }, 500);
                        }
                        break;
                    case "ctrl+a":
                        break;
                    case "ctrl+c":
                        _this.copyTask();
                        break;
                    case "ctrl+x":
                        _this.cutTask()
                        break;
                    case "ctrl+v":
                        _this.pastCopyAndCut();
                        break;
                    case "delete":
                        _this.delete_popup = 1;
                        _this.RemoveNodeAndChildren(_this.selectedData);
                        break;
                    case "ctrl+u":
                        _this.shwAssignUserDropDown(_this.selectedData);
                        break;
                    case "ctrl+b":
                        _this.AddDontForgetTagToSelectedIds();//add DON'T FORGET SECTION
                        break;
                    case "ctrl+s":
                        _this.showSearchInputField();
                        break;
                    case "ctrl+i":
                        _this.addAttachment(_this.selectedData);
                        break;
                    case "ctrl+m":
                        if (_this.selectedIds.length > 0) {
                            _this.MoveSelectedTask()
                        }
                        break;
                    case "shift+3":
                        $('#tag-' + _this.selectedData._id).click();
                        console.log(_this.selectedData);
                        break;
                }
            });
        },

        methods: {
            grow: function (text, options) {
                var height = options.height || '100px';
                var maxHeight = options.maxHeight || '500px';
                text.style.height = 'auto';
                var curHeight = text.scrollHeight;
                if (curHeight > maxHeight) {
                    curHeight = maxHeight;
                } else {
                }
                if (curHeight < height) {
                    curHeight = height;
                }
                text.style.height = curHeight + 'px';
            },
            growInit: function (options) {
                let _this = this;
                var locInputs = document.querySelectorAll('[data-grow="auto"]');
                for (var i = 0; i < locInputs.length; i++) {
                    var target = locInputs[i];
                    var height = options.height || '100px';
                    var maxHeight = options.maxHeight || '500px';
                    target.style.resize = 'none';
                    target.style.height = height + 'px';
                    target.style.maxHeight = maxHeight + 'px';

                    target.onkeydown = function () {
                        _this.grow(this, options);
                    };
                    target.onkeyup = function () {
                        _this.grow(this, options);
                    };
                }
            },
            dropNode(node, targetTree, oldTree) {
                let THIS = this;
                clearInterval(THIS.dNodeInterval);
            },
            dragNode(node) {
                let THIS = this;
                this.dNode = node;
                this.dNodeHeght = $('#' + node._id)[0].getBoundingClientRect().top + window.scrollY;
                this.dNodeInterval = setInterval(function () {
                    var target = document.getElementById('TaskListAndDetails');
                    var top = $('#' + node._id)[0].getBoundingClientRect().top + target.scrollTop - 241;
                    // var cssTop = top+241+THIS.dNodeHeght;
                    // $('#' + node._id).css({top: cssTop+'px'});
                    target.scrollTo(0, top);
                }, 100);
            },
            ChangeNode(data, taskAfterDrop) {
                if (data.sort_id === -2) {
                    return false
                }
                var afterDrop = taskAfterDrop.getPureData();
                var id = data.id;
                let _this = this;
                var position = this.FindDopedTask(0, data, afterDrop);

                axios.post('/api/task-list/task-drag-drop', position)
                    .then(response => response.data)
                    .then(response => {
                        _this.getTaskList()
                    })
                    .catch(error => {
                        console.log('Api is drag and drop not Working !!!')
                    });
            },
            FindDopedTask(parent, data, tasks) {
                for (var i = 0; i < tasks.length; i++) {
                    if (data.id === tasks[i].id) {
                        if (i >= 1) {
                            var s_id = tasks[i - 1].sort_id;
                        }
                        return {sort_id: i, pre_sort: s_id, id: data.id, parent_id: parent};
                    } else if (tasks[i].children !== undefined) {
                        if (tasks[i].children.length > 0) {
                            var ret = this.FindDopedTask(tasks[i].id, data, tasks[i].children);
                            if (ret !== undefined) {
                                return ret;
                            }
                        }
                    }
                }
            },
            showSearchInputField() {
                if (this.list.type === 'list') {
                    $('.searchList').toggle();
                    $('.searchTaskList').focus();
                }
            },
            SearchTaskByAssignedUser(id, name) {
                $('.searchTaskList').val('@' + name);
                var _this = this;
                axios.post('/api/task-list/suggest-user', {'user_id': id, p_id: _this.projectId})
                    .then(response => response.data)
                    .then(response => {
                        _this.searchData.tasks = response.search_tasks;
                        console.log(_this.searchData.tasks)
                        $('#myUL-user').removeClass('myUL-user');
                        $('#myUL').removeClass('myUL');
                        $('#myUL').addClass('myUL-show');

                    })
                    .catch(error => {
                        console.log('Api is drag and drop not Working !!!')
                    });
            },
            searchDataFormTask(e) {
                var value = e.target.value;
                var _this = this;
                if (value.charAt(0) === '@') {
                    value = value.substr(1)
                    _this.searchData.users = (_this.treeList[0].users.length > 0) ? _this.treeList[0].users : [];
                    if (value.length > 0) {
                        axios.post('/api/task-list/suggest-user', {'user_name': value})
                            .then(response => response.data)
                            .then(response => {
                                _this.searchData.users = response.search_user;
                            })
                            .catch(error => {
                                console.log('Api is drag and drop not Working !!!')
                            });
                    }
                    $('#myUL').removeClass('myUL-show');
                    $('#myUL').addClass('myUL');
                    $('#myUL-user').addClass('myUL-user');
                } else if (value.charAt(0) === '') {
                    $('#myUL-user').removeClass('myUL-user');
                    $('#myUL-user').addClass('myUL-user-hide');
                    $('#myUL').removeClass('myUL-show');
                    $('#myUL').addClass('myUL');
                } else {
                    if (value.length >= 2) {
                        axios.post('/api/task-list/suggest-user', {'text': value, 'project_id': _this.projectId})
                            .then(response => response.data)
                            .then(response => {
                                _this.searchData.tasks = response.search_tasks;
                                // console.log(response.search_tasks);
                            })
                            .catch(error => {
                                console.log('Api is drag and drop not Working !!!')
                            });

                        $('#myUL').removeClass('myUL');
                        $('#myUL').addClass('myUL-show');
                    }
                }
            },
            selectTaskFromTaskTreeList(task) {
                $('.eachItemRow').removeClass('clicked');
                $('#click' + task.id).addClass('clicked');
                var target = document.getElementById('TaskListAndDetails');
                if ($('#click' + task.id).length > 0) {
                    var top = $('#click' + task.id)[0].getBoundingClientRect().top + target.scrollTop - 241;
                    target.scrollTo(0, top);
                }
            },
            SearchResultClick(task) {
                if ($('#click' + task.id).length > 0) {
                    var target = document.getElementById('TaskListAndDetails');
                    var top = $('#click' + task.id)[0].getBoundingClientRect().top + target.scrollTop - 241;
                    target.scrollTo(0, top);
                }
                $('#myUL').addClass('myUL');
                $('#myUL').removeClass('myUL-show');
            },
            HideShowChild(store, data) {
                var _this = this;
                var postData = {
                    id: data.id,
                    open: (data.open === 1) ? 0 : 1
                };
                axios.post('/api/task-list/update', postData)
                    .then(response => response.data)
                    .then(response => {
                        store.toggleOpen(data)
                    })
                    .catch(error => {
                        console.log('Api for complete task not Working !!!')
                    });
            },
            assignUserToTask(user, data) {
                var _this = this;
                var postData = {
                    task_id: data.id,
                    user_id: user.id
                };
                axios.post('/api/task-list/assign-user', postData)
                    .then(response => response.data)
                    .then(response => {
                        _this.getTaskList()
                    })
                    .catch(error => {
                        console.log('Api is not Working !!!')
                    });
            },
            removeAssignedUser(user_id, task_id) {

                // console.log(user.id, user.task_id);
                var _this = this;
                var postData = {
                    user_id: user_id,
                    task_id: task_id
                };
                console.log(postData)
                axios.post('/api/task-list/assign-user-remove', postData)
                    .then(response => response.data)
                    .then(response => {
                        // console.log(response);
                        if (response === 'success') {
                            _this.getTaskList()
                        }
                    })
                    .catch(error => {
                        console.log('Api assign-user-remove is not Working !!!')
                    });
            },
            makeItClick(e, data, vm) {
                var _this = this;
                if (e.ctrlKey && e.which === 1) {
                    if (data.text !== '' && _this.selectedIds.length <= 1) {
                        _this.DeleteEmptyTask();
                    }
                    var index = _this.selectedIds.indexOf(data.id);
                    if (index > -1) {
                        _this.selectedIds.splice(index, 1);
                        $('#click' + data.id).removeClass('clicked');

                    } else {
                        _this.selectedIds.push(data.id);
                        $('#click' + data.id).addClass('clicked');
                    }
                    $('.jquery-accordion-menu').hide();
                    if (_this.selectedIds.length > 1) {
                        _this.selectedData = {};
                        _this.context_menu_flag = 0;
                    }


                } else if (e.shiftKey && e.which === 1) {

                    $('#click' + data.id).addClass('clicked');

                } else if (e.which === 1) {
                    if (data.text !== '') {
                        _this.DeleteEmptyTask();
                    }
                    _this.selectedIds = [];
                    _this.selectedIds.push(data.id);
                    this.selectedData = data;
                    this.tags = data.tags;
                    $('.eachItemRow').removeClass('clicked');
                    $(e.target).addClass('clicked');
                    $(e.target).closest('.eachItemRow').addClass('clicked');
                    if (data.text !== 'Dont Forget Section') {
                        // data.draggable = true;
                    }
                    $('.jquery-accordion-menu').hide();


                } else if (e.which === 3 && data.draggable == true) {
                    e.preventDefault();
                    e.stopPropagation();
                    if (_this.context_menu_flag !== 1) {
                        $('#rmenu').addClass('menu-show');
                        let target = $(e.target);
                        let w = target.closest('#tree_view_list').width();
                        let h = target.closest('#tree_view_list').height();
                        let p = target.closest('#tree_view_list').offset();
                        let left = e.clientX - p.left;
                        let top = e.clientY - p.top;

                        let clickH = $('.jquery-accordion-menu').height();
                        clickH = clickH < 150 ? 400 : clickH;
                        if ((w - left) < 230) {
                            left = w - 250;
                        }
                        if (h < top + clickH) {
                            top = top - (top + clickH - h);
                        }
                        if (top < 10) {
                            top = 10;
                        }

                        let ttarget = target.closest('#tree_view_list').find('.jquery-accordion-menu');
                        if (_this.selectedIds.length > 0) {
                            var index = _this.selectedIds.indexOf(data.id);
                            if (index > -1) {
                                ttarget.css({
                                    top: top,
                                    left: left,
                                }).fadeIn();
                            } else {
                                $('.eachItemRow').removeClass('clicked');

                                $('.jquery-accordion-menu').hide();
                                _this.selectedIds = [];
                            }
                        }
                    }
                } else if (e.ctrlKey && e.which === 65) {
                    e.target.setSelectionRange(0, data.text.length);
                }
            },
            makeInput(e, data) {
                var _this = this;
                this.selectedData = data;
                _this.empty_task_delete_flag = data.id;
                if (data.text === 'Dont Forget Section') {
                    $(e.target).attr('disabled', 'disabled');
                } else {
                    $('.inp').addClass('input-hide');
                    $('.inp').removeClass('form-control');
                    $(e.target).removeClass('input-hide');
                    $(e.target).addClass('form-control');
                }
            },
            hideItem(e, data) {
                data.draggable = false;
                this.context_menu_flag = data.id;
                // data.draggable = false;
                // $(e.target).closest('.eachItemRow').find('.task-complete').hide();
                $(e.target).closest('.eachItemRow').find('.tag-icon').hide();
                $(e.target).closest('.eachItemRow').find('.attach-icon').hide();
                $(e.target).closest('.eachItemRow').find('.subTask_plus').hide();
                $(e.target).closest('.eachItemRow').find('.task_plus').hide();
                $(e.target).closest('.eachItemRow').find('.calender').hide();
                $(e.target).closest('.eachItemRow').find('.user').hide();
                $(e.target).closest('.eachItemRow').find('.dateCal').hide();
                // $(e.target).closest('.eachItemRow').find('.delete-icon').show();
            },
            showItem(e, data) {

                this.context_menu_flag = data.id;
                this.SaveDataWithoutCreateNewNode(data);
                setTimeout(function () {
                    // $(e.target).closest('.eachItemRow').find('.delete-icon').hide();
                    $(e.target).closest('.eachItemRow').find('.task-complete').show();
                    $(e.target).closest('.eachItemRow').find('.tag-icon').show();
                    $(e.target).closest('.eachItemRow').find('.attach-icon').show();
                    $(e.target).closest('.eachItemRow').find('.subTask_plus').show();
                    $(e.target).closest('.eachItemRow').find('.task_plus').show();
                    $(e.target).closest('.eachItemRow').find('.calender').show();
                    $(e.target).closest('.eachItemRow').find('.user').show();
                    $(e.target).closest('.eachItemRow').find('.dateCal').show();
                    data.draggable = true;
                    data.droppable = true;
                }, 500);

                $('.inp').addClass('input-hide');
                $('.inp').removeClass('form-control');

            },
            copyTask() {
                var _this = this;
                if (_this.selectedData.text !== 'Dont Forget Section') {
                    _this.selectedCopy = _this.selectedIds;
                    _this.selectedCut = null;
                    $('.jquery-accordion-menu').hide();
                    console.log(_this.selectedData)
                    console.log(_this.selectedIds)
                } else {
                    swal('Sorry!!', 'You can\'t do copy  Dont Forget Section! task', 'warning')
                }
            },
            cutTask() {
                var _this = this;
                if (_this.selectedIds.length > 1) {
                    swal('Sorry!!', 'You can\'t do Cut  more then 1 task', 'warning')
                } else {
                    if (_this.selectedData.text !== 'Dont Forget Section') {
                        _this.selectedCut = _this.selectedIds;
                        _this.selectedCopy = null;
                        $('.jquery-accordion-menu').hide();
                    } else {
                        swal('Sorry!!', 'You can\'t do Cut Dont Forget Section! task', 'warning')
                    }
                }
            },
            CopyToSelectedTask(){

                var _this = this;
                var postData = {
                    ids: _this.selectedIds,
                };
                $('.jquery-accordion-menu').hide();
                _this.list_T = [];
                _this.nav_T = [];
                _this.column_T = [];
                _this.column = "Select Column";
                _this.selectedListNav = 'Select list Nav';
                _this.transferBtn = false;

                axios.get('/api/nav-item/' + _this.projectId)
                    .then(response => response.data)
                    .then(response => {
                        _this.nav_T = response.success;
                        console.log(response)
                        setTimeout(() => {
                            $('#CopyTaskTo').modal('show');
                        }, 200);
                    })
                    .catch(error => {
                    });
                // swal("Under Process!", "Working under process", "success");
            },
            pastCopyAndCut() {
                var _this = this;
                var data = _this.selectedData;
                // if (_this.selectedIds.length > 1) {
                //     return false;
                // }
                var postData = {
                    target_id: data.id,
                    copy_ids: (this.selectedCopy === null) ? this.selectedCut : this.selectedCopy,
                    type: (this.selectedCopy === null) ? 'cut' : 'copy',
                    nav_id: _this.nav_id
                };

                axios.post('/api/task-list/copy-cut-past', postData)
                    .then(response => response.data)
                    .then(response => {
                        _this.getTaskList();
                        $('.jquery-accordion-menu').hide();
                        _this.selectedIds = [];
                        _this.selectedCopy = null;
                        _this.selectedCut = null;

                    })
                    .catch(error => {
                        console.log('Api is copy and cut not Working !!!')
                    });
            },
            addEmptyNode(data) {
                let _this = this;
                var children = data.parent.children;
                var date = Math.round(new Date().getTime() / 1000);
                _this.reselectParentId = data;

                var newEmty = {
                    children: [],
                    clicked: 0,
                    date: "",
                    description: null,
                    id: date,
                    list_id: _this.list_id,
                    nav_id: data.nav_id,
                    parent_id: data.parent_id,
                    sort_id: data.sort_id,
                    tags: [],
                    text: ""
                };

                for (var i = 0; i < children.length; i++) {
                    if (children[i].text == '') {
                        children.splice(i, 1);
                    }
                }
                for (var i = 0; i < children.length; i++) {

                    if (children[i].id == data.id) {
                        children.splice(i + 1, 0, newEmty);
                        setTimeout(function () {
                            $("#" + date).click();
                            $("#" + date).focus();
                            $("#" + date).addClass('form-control');
                            $("#" + date).removeClass('input-hide');
                        }, 100)
                    }
                }
            },
            addEmptyChild(data) {
                let _this = this;
                var children = data.children;
                var date = Math.round(new Date().getTime() / 1000);

                var newEmty = {
                    children: [],
                    clicked: 0,
                    date: "",
                    description: null,
                    id: date,
                    list_id: _this.list_id,
                    nav_id: data.nav_id,
                    parent_id: data.id,
                    sort_id: 0,
                    tags: "",
                    text: ""
                };
                for (var i = 0; i < children.length; i++) {
                    if (children[i].text == '') {
                        children.splice(i, 1);
                    }
                }
                children.splice(0, 0, newEmty);
                setTimeout(function () {
                    $("#" + date).click();
                    $("#" + date).focus();
                    $("#" + date).addClass('form-control');
                    $("#" + date).removeClass('input-hide');
                }, 100)
            },
            addNode(data) {
                let _this = this;
                var text = data.text;
                let postData = {
                    id: data.id,
                    text: text,
                    parent_id: data.parent_id,
                    sort_id: data.sort_id,
                    project_id: _this.projectId,
                    list_id: _this.list_id,
                    nav_id: _this.nav_id
                };
                // console.log(postData);
                axios.post('/api/task-list/add-task', postData)
                    .then(response => response.data)
                    .then(response => {
                        _this.newEmptyTaskID = response.success.id;
                        _this.getTaskList();
                        setTimeout(function () {
                            $("#" + _this.newEmptyTaskID).click();
                            $("#" + _this.newEmptyTaskID).focus();
                            $("#" + _this.newEmptyTaskID).addClass('form-control');
                            $("#" + _this.newEmptyTaskID).removeClass('input-hide');
                            // alert( $("#" + _this.newEmptyTaskID).val())
                        }, 1000)
                        _this.empty_task_delete_flag = _this.newEmptyTaskID;

                    })
                    .catch(error => {
                        console.log('Api is not Working !!!')
                    });
            },
            addChild(data) {
                let _this = this;
                let postData = {
                    id: data.id,
                    project_id: _this.projectId,
                    list_id: _this.list_id,
                    nav_id: _this.nav_id
                };
                axios.post('/api/task-list/add-child-task', postData)
                    .then(response => response.data)
                    .then(response => {
                        // console.log(response);
                        _this.newEmptyTaskID = response.success.id;
                        _this.getTaskList();
                        setTimeout(function () {
                            $("#" + _this.newEmptyTaskID).click();
                            $("#" + _this.newEmptyTaskID).focus();
                            $("#" + _this.newEmptyTaskID).addClass('form-control');
                            $("#" + _this.newEmptyTaskID).removeClass('input-hide');
                        }, 1000)
                        _this.empty_task_delete_flag = _this.newEmptyTaskID
                    })
                    .catch(error => {
                        console.log('Api is not Working !!!')
                    });
            },
            makeChild(data) {
                var children = data.parent.children;
                let _this = this;
                let postData = {
                    id: data.id,
                    parent_id: data.parent_id,
                    project_id: _this.projectId,
                    list_id: _this.list_id,
                    sort_id: data.sort_id,
                    text: data.text,
                    nav_id: _this.nav_id
                };

                axios.post('/api/task-list/task-make-child', postData)
                    .then(response => response.data)
                    .then(response => {
                        _this.newEmptyTaskID = response.success;
                        _this.getTaskList();
                        setTimeout(function () {
                            $("#" + _this.newEmptyTaskID).click();
                            $("#" + _this.newEmptyTaskID).focus();
                        }, 1000)
                        _this.empty_task_delete_flag = _this.newEmptyTaskID
                    })
                    .catch(error => {
                        console.log('Api is task-make-child not Working !!!')
                    });
            },
            unMakeChild(data) {

                let _this = this;
                let postData = {
                    id: data.id,
                    parent_id: data.parent_id,
                    project_id: _this.projectId,
                    list_id: _this.list_id,
                    sort_id: data.sort_id,
                    text: data.text
                };
                axios.post('/api/task-list/reverse-child', postData)
                    .then(response => response.data)
                    .then(response => {
                        _this.newEmptyTaskID = response.success;
                        _this.getTaskList();
                        setTimeout(function () {
                            $("#" + _this.newEmptyTaskID).click();
                            $("#" + _this.newEmptyTaskID).focus();
                        }, 500)
                        _this.empty_task_delete_flag = _this.newEmptyTaskID

                    })
                    .catch(error => {
                        console.log('Api is task-unmake-child not Working !!!')
                    });
            },
            generateColor() {
                var myColor = '#000000';
                myColor = '#' + (Math.random() * 0xFFFFFF << 0).toString(16);
                return myColor;
            },
            addTag(e, data) {

                var _this = this;
                if (e.which === 13) {
                    var color = (_this.tag === 'Dont Forget') ? '#ff0000' : _this.generateColor();
                    var postData = {
                        id: data.id,
                        tags: _this.tag,
                        color: color
                    };
                    axios.post('/api/task-list/add-tag', postData)
                        .then(response => response.data)
                        .then(response => {
                            _this.getTaskList();
                            $('#dropdown' + data._id).toggle();
                            _this.selectedData = data;
                            _this.tag = null
                        })
                        .catch(error => {
                            console.log('Api for move down task not Working !!!')
                        });
                }
            },
            addExistingTag(data, tag, color) {

                var _this = this;
                var color = (tag === 'Dont Forget') ? '#ff0000' : color;
                var postData = {
                    id: data.id,
                    tags: tag,
                    color: color
                };
                axios.post('/api/task-list/add-tag', postData)
                    .then(response => response.data)
                    .then(response => {

                        _this.getTaskList();
                        $('#dropdown' + data._id).toggle();
                        _this.selectedData.tags[0] = tag
                    })
                    .catch(error => {
                        console.log('Api for add tag not Working !!!')
                    });
            },
            changeTAg(tags) {
                var _this = this;
                var old = this.tags.length;
                var newl = tags.length;

                if (newl > old) {
                    this.tags = tags;

                    var color = (this.tags[newl - 1].text === 'Dont Forget') ? '#ff0000' : _this.generateColor();
                    var postData = {
                        id: _this.selectedData.id,
                        tags: _this.tags[newl - 1].text,
                        color: color
                    };
                    axios.post('/api/task-list/add-tag', postData)
                        .then(response => response.data)
                        .then(response => {

                            _this.getTaskList();
                            _this.tag = null
                        })
                        .catch(error => {
                            console.log('Api for move down task not Working !!!')
                        });
                }
            },
            DeleteTag(obj) {
                var _this = this;
                var postData = {
                    assign_id: obj.tag.assign_id
                };
                if (obj.tag.text !== 'Dont Forget') {
                    axios.post('/api/task-list/delete-tag', postData)
                        .then(response => response.data)
                        .then(response => {
                            _this.getTaskList();
                            _this.tag = null
                        })
                        .catch(error => {
                            console.log('Api for move down task not Working !!!')
                        });
                }
            },
            showTagManageModel() {
                var _this = this;
                axios.get('/api/task-list/all-tag-for-manage')
                    .then(response => response.data)
                    .then(response => {
                        _this.manageTag = response.tags;
                        $('#TagManage').modal('show');
                    })
                    .catch(error => {
                        console.log('Api for move down task not Working !!!')
                    });
            },
            updateTagColor(e, tag) {
                var color = e.target.value;
                var _this = this;
                var postData = {
                    id: tag.id,
                    color: color,
                };
                axios.post('/api/task-list/update-tag', postData)
                    .then(response => response.data)
                    .then(response => {
                        _this.manageTag = response.tags;
                        _this.getTaskList();
                        // $('#dropdown' + data._id).toggle();
                        // _this.selectedData = data
                        _this.tag = null
                    })
                    .catch(error => {
                        console.log('Api for move down task not Working !!!')
                    });
            },
            updateTagName(e, tag) {
                var newTag = e.target.innerText;
                if (e.which == 13) {
                    var _this = this;
                    var postData = {
                        id: tag.id,
                        tag: newTag,
                    };
                    axios.post('/api/task-list/update-tag', postData)
                        .then(response => response.data)
                        .then(response => {
                            _this.manageTag = response.tags;
                            _this.getTaskList();
                            _this.tag = null
                        })
                        .catch(error => {
                            console.log('Api for update tag not Working !!!')
                        });
                }
            },
            newLineoff(e) {
                if (e.which == 13) {
                    e.preventDefault();
                }
            },
            DeleteTagFromModal(tag) {
                var _this = this;
                var postData = {
                    id: tag.id,
                    title: tag.title,
                };
                axios.post('/api/task-list/delete-tag', postData)
                    .then(response => response.data)
                    .then(response => {
                        _this.manageTag = response.tags;
                        _this.getTaskList();
                        _this.tag = null
                    })
                    .catch(error => {
                        console.log('Api for delete tag not Working !!!')
                    });
            },
            //task operation/Action
            updateDescription() {
                var _this = this;
                var postData = {
                    id: _this.selectedData.id,
                    details: _this.selectedData.description
                };
                axios.post('/api/task-list/update', postData)
                    .then(response => response.data)
                    .then(response => {
                        // _this.getTaskList()
                        // $('#dropdown' + data._id).toggle();
                        // _this.selectedData.tags = tag
                    })
                    .catch(error => {
                        console.log('Api for move down task not Working !!!')
                    });
            },
            addTaskToComplete(data) {
                var _this = this;
                var postData = {
                    id: data.id,
                    complete: 1
                };
                swal({
                        title: "Are you sure?",
                        text: "Is this task is complete !!!",
                        type: "warning",
                        showCancelButton: true,
                        confirmButtonClass: "btn-danger",
                        confirmButtonText: "Yes, Complete it!",
                        closeOnConfirm: false
                    },
                    function () {
                        axios.post('/api/task-list/update', postData)
                            .then(response => response.data)
                            .then(response => {
                                if (response.status === 1) {
                                    _this.getTaskList();
                                    swal.close();
                                    // swal("Complete!", "This task is added to complete", "success");
                                } else if (response.status === 2) {
                                    swal("Sorry!", "The board dont have any 100% progress column !!", "warning");
                                } else if (response.status === 0) {
                                    swal("Sorry!", "This task is not Live in any board yet", "warning");
                                }

                            })
                            .catch(error => {
                                console.log('Api for complete task not Working !!!')
                            });

                    });


            },
            moveItemUp(data) {
                if (data.sort_id <= 0) {
                    return false;
                }
                var _this = this;
                var postData = {
                    id: data.id,
                    text: data.text,
                    parent_id: data.parent_id,
                    sort_id: data.sort_id,
                    project_id: _this.projectId,
                    list_id: data.list_id,
                    nav_id: _this.nav_id,
                    type: 'up'
                };
                axios.post('/api/task-list/move-task', postData)
                    .then(response => response.data)
                    .then(response => {
                        _this.getTaskList();
                        _this.selectedData = data;
                        setTimeout(function () {
                            $("#click" + data.id).click();
                        }, 300)
                    })
                    .catch(error => {
                        console.log('Api for move up task not Working !!!')
                    });
            },
            moveItemDown(data) {
                if (data.sort_id < 0) {
                    return false;
                }
                var _this = this;
                var postData = {
                    id: data.id,
                    text: data.text,
                    parent_id: data.parent_id,
                    sort_id: data.sort_id,
                    project_id: _this.projectId,
                    list_id: data.list_id,
                    nav_id: _this.nav_id,
                    type: 'down'
                };
                axios.post('/api/task-list/move-task', postData)
                    .then(response => response.data)
                    .then(response => {
                        _this.getTaskList();
                        setTimeout(function () {
                            $("#click" + data.id).click();
                        }, 300)
                    })
                    .catch(error => {
                        console.log('Api for move down task not Working !!!')
                    });

            },
            RemoveNodeAndChildren(data) {
                var _this = this;
                var postData = {
                    id: data.id,
                    text: data.text
                };
                swal({
                        title: "Are you sure?",
                        text: "You want to delete this task !!!",
                        type: "warning",
                        showCancelButton: true,
                        confirmButtonClass: "btn-danger btn",
                        confirmButtonText: "Yes, delete it!",
                        closeOnConfirm: false
                    },
                    function () {
                        axios.post('/api/task-list/delete-task', postData)
                            .then(response => response.data)
                            .then(response => {
                                _this.getTaskList()
                                _this.delete_popup = 0;
                                swal.close()
                                // swal("Deleted!", "Successfully delete task !", "success");
                            })
                            .catch(error => {
                                console.log('Api for delete task not Working !!!')
                            });

                    });


            },
            ActionToSelectedTask(value, type) {
                var _this = this;
                setTimeout(function () {
                    if (type === 'date') {
                        var date = new Date(_this.date_for_selected)
                        var month = (parseFloat(date.getMonth() + 1) > 9) ? parseFloat(date.getMonth() + 1) : '0' + parseFloat(date.getMonth() + 1);
                        var day = (parseFloat(date.getDate() + 1) > 9) ? parseFloat(date.getDate()) : '0' + parseFloat(date.getDate());
                        var date_for_selected = date.getFullYear() + '-' + month + '-' + day;
                    }
                    var postData = {
                        ids: _this.selectedIds,
                        type: type,
                        value: type === 'date' ? date_for_selected : value,
                    };

                    axios.post('/api/task-list/assign-user-add-tag', postData)
                        .then(response => response.data)
                        .then(response => {
                            _this.getTaskList();
                            $('.jquery-accordion-menu').hide();
                        })
                        .catch(error => {
                            console.log('Api for delete task not Working !!!')
                        });
                }, 500)
            },
            MoveSelectedTask() {
                var _this = this;
                var postData = {
                    ids: _this.selectedIds,
                };
                $('.jquery-accordion-menu').hide();
                _this.list_T = [];
                _this.nav_T = [];
                _this.column_T = [];
                _this.column = "Select Column";
                _this.selectedListNav = 'Select list Nav';
                _this.transferBtn = false;

                axios.get('/api/nav-item/' + _this.projectId)
                    .then(response => response.data)
                    .then(response => {
                        _this.nav_T = response.success;
                        // console.log(response)
                        setTimeout(() => {
                            $('#MoveTAsk').modal('show');
                        }, 200);
                    })
                    .catch(error => {

                    });
            },
            deleteSelectedTask() {

                var _this = this;
                _this.delete_popup = 1;
                var postData = {
                    ids: _this.selectedIds,
                };
                $('.jquery-accordion-menu').hide();
                swal({
                        title: "Are you sure?",
                        text: "You want to delete all selected task !!!",
                        type: "warning",
                        showCancelButton: true,
                        confirmButtonClass: "btn-danger btn",
                        confirmButtonText: "Yes, delete it!",
                        closeOnConfirm: true
                    },
                    function () {
                        axios.post('/api/task-list/delete-task', postData)
                            .then(response => response.data)
                            .then(response => {
                                _this.getTaskList();
                                $('.jquery-accordion-menu').hide();
                                _this.delete_popup = 0;
                                // swal("Deleted!", "Successfully delete selected task !", "success");
                                swal.close();
                            })
                            .catch(error => {
                                console.log('Api for delete task not Working !!!')
                            });
                    });


            },
            AddDontForgetTagToSelectedIds() {

                var _this = this;
                var postData = {
                    ids: _this.selectedIds,
                    tags: 'Dont Forget',
                    color: '#ff0000'
                };
                axios.post('/api/task-list/add-tag-to-multiple-task', postData)
                    .then(response => response.data)
                    .then(response => {
                        _this.getTaskList();
                        $('.jquery-accordion-menu').hide();
                    })
                    .catch(error => {
                        console.log('Api for add tag not Working !!!')
                    });
            },

            showLog() {
                var _this = this;
                axios.get('/api/task-list/get-log/' + _this.selectedData.id)
                    .then(response => response.data)
                    .then(response => {
                        _this.task_logs = response;
                        _this.ShowDetails(_this.selectedData);
                        setTimeout(function () {
                            $('#_log').click()
                        }, 300)
                    })
                    .catch(error => {
                        console.log('Api for move down task not Working !!!')
                    });
            },

            // get task list
            getTaskListWithDynamicEmptyNode() {
                var _this = this;
                var datePicker = new Date();
                datePicker.setDate(datePicker.getDate() - 1);
                _this.disabledDates = {
                    to: datePicker, // Disable all dates up to specific date
                };
                let data = {
                    id: this.projectId,
                    list_id: this.list_id,
                    nav_id: this.nav_id
                };
                axios.post('/api/task-list', data)
                    .then(response => response.data)
                    .then(response => {
                        this.treeList = response.task_list;
                        this.multiple_list = response.multiple_list;
                        setTimeout(function () {
                            $('[data-toggle="tooltip"]').tooltip();
                        }, 1000);
                        if (response.task_list.length === 0) {

                            var date = Math.round(new Date().getTime() / 1000);
                            var newEmpty = {
                                children: [],
                                clicked: 0,
                                date: "",
                                description: null,
                                id: date,
                                list_id: _this.list_id,
                                nav_id: _this.nav_id,
                                parent_id: 0,
                                sort_id: 1,
                                tags: "",
                                text: ""
                            };
                            _this.treeList = [newEmpty];
                            setTimeout(function () {
                                $("#" + date).click();
                                $("#" + date).focus();
                                $("#" + date).addClass('form-control');
                                $("#" + date).removeClass('input-hide');
                            }, 100)
                        }
                        // setTimeout(function () {
                        //     $('.delete-icon').hide();
                        // }, 500)
                    })
                    .catch(error => {

                    });
            },
            getTaskList() {
                var _this = this;
                let data = {
                    id: this.projectId,
                    list_id: this.list_id,
                    nav_id: this.nav_id
                };
                axios.post('/api/task-list', data)
                    .then(response => response.data)
                    .then(response => {
                        this.treeList = response.task_list;
                        this.multiple_list = response.multiple_list;
                        $('[data-toggle="tooltip"]').tooltip('dispose');
                        setTimeout(function () {
                            $('[data-toggle="tooltip"]').tooltip('enable');
                        }, 500);
                        if (this.treeList.length === 1 && this.treeList[0].text === '') {
                            let id = this.treeList[0].id;
                            setTimeout(function () {
                                $("#" + id).click();
                                $("#" + id).focus();
                                $("#" + id).addClass('form-control');
                                $("#" + id).removeClass('input-hide');
                            }, 300)
                        }
                    })
                    .catch(error => {

                    });
            },

            //collect data by child navbar component
            showTask(data) {
                this.list_id = data.list_id;
                this.nav_id = data.nav_id;
                this.list.name = data.title;
                this.list.id = data.list_id
                this.list.description = data.description;
                this.list.type = data.type;
                if (data.type === 'list') {
                    localStorage.selected_nav = JSON.stringify({
                        list_id: data.list_id,
                        nav_id: data.nav_id,
                        project_id: this.projectId,
                        type: 'list'
                    });
                    this.getTaskList()
                } else if (data.type === 'board') {
                    localStorage.selected_nav = JSON.stringify({
                        list_id: data.list_id,
                        nav_id: data.nav_id,
                        project_id: this.projectId,
                        type: 'board'
                    });
                } else if (data.type === 'rules') {
                    var  _this = this;
                    _this.rule = {
                        id : (data.rules_id) ? data.rules_id : null,
                        action_type : data.action_type,
                    }
                } else if (data.type === 'overview') {
                    var  _this = this;

                }
            },
            getNavbar(data) {
                var _this = this;
                setTimeout(function () {
                    _this.AllNavItems = data.AllNavItem;
                }, 500)
            },
            UpdateListModel() {
                $("#updateListBoardModel").modal('show');
            },
            UpdateListOrBoard() {
                var _this = this;
                var l = Ladda.create(document.querySelector('.ladda_update_list_board'));
                l.start();
                this.list.project_id = this.projectId;
                this.list.nav_id = this.nav_id;
                this.list.id = this.list_id;
                axios.post('/api/board-list-update', this.list)
                    .then(response => response.data)
                    .then(response => {
                        l.stop();
                        _this.AllNavItems = response.navItems.original.success;
                        $("#updateListBoardModel").modal('hide');
                    })
                    .catch(error => {
                        console.log('Add list api not working!!')
                    });
            },

            DeleteListOrBoard(data) {
                var type = data.type;
                var action = data.action;
                var _this = this;
                _this.action_T = action;
                _this.type_T = type;
                if (action === 'move') {
                    _this.list_T = [];
                    _this.nav_T = [];
                    _this.selectedListNav = 'Select list Nav';
                    _this.transferBtn = false;

                    axios.get('/api/nav-item/' + _this.projectId)
                        .then(response => response.data)
                        .then(response => {
                            _this.nav_T = response.success;
                            setTimeout(() => {
                                $('#transAndMoveTAsk').modal('show');
                            }, 200);
                        })
                        .catch(error => {

                        });


                } else {
                    swal({
                            title: "Are you sure?",
                            text: "If you delete this " + type + " then all task will delete !!!",
                            type: "warning",
                            showCancelButton: true,
                            confirmButtonClass: "btn-danger",
                            confirmButtonText: "Yes, Complete it!",
                            closeOnConfirm: false
                        },
                        function () {
                            axios.post('/api/board-list-delete', {type: type, id: _this.list_id, action: action})
                                .then(response => response.data)
                                .then(response => {
                                    swal("Complete!", "This List is deleted successfully !", "success");
                                    window.location.href = '/project-dashboard/' + _this.projectId;
                                })
                                .catch(error => {
                                    console.log('Add list api not working!!')
                                });

                        });


                }

            },
            showSubList_T() {

                let _this = this;
                _this.transferBtn = false;
                _this.list_T = [];
                _this.column_T = [];
                _this.selectedSubList = 'Select list';
                let data = {
                    'projectId': _this.projectId,
                    'listId': _this.selectedListNav,
                    'type': _this.type_T,

                };
                axios.post('/api/multiple-list', data)
                    .then(response => response.data)
                    .then(response => {
                        _this.list_T = response.success;
                        _this.type_T = response.type;
                    })
                    .catch(error => {
                    });
            },
            get_T_Bttn() {
                this.transferBtn = true;
            },

            getColumnAndConfirmButton() {
                var _this = this;
                _this.selectedColumn = "Select column";
                if (_this.type_T === 'board') {
                    let data = {
                        'list_id': _this.selectedSubList
                    };

                    axios.post('/api/board-column', data)
                        .then(response => response.data)
                        .then(response => {
                            _this.column_T = response.data;
                        })
                        .catch(error => {
                        });

                } else {
                    this.transferBtn = true;
                }

            },
            DeleteAndMoveAllTask() {
                var _this = this;
                swal({
                        title: "Are you sure?",
                        text: "You want to delete the list and move all task ?!!!",
                        type: "warning",
                        showCancelButton: true,
                        confirmButtonClass: "btn-danger",
                        confirmButtonText: "Yes, Delete  & Move Task",
                        closeOnConfirm: false
                    },
                    function () {
                        axios.post('/api/board-list-delete', {
                            type: _this.type_T,
                            id: _this.list_id,
                            action: _this.action_T,
                            target: _this.selectedSubList
                        })
                            .then(response => response.data)
                            .then(response => {
                                // swal("Complete!", "This " + _this.type_T + " is deleted and all task are moved !", "success");
                                window.location.href = '/project-dashboard/' + _this.projectId;
                            })
                            .catch(error => {
                                console.log('Add list api not working!!')
                            });

                    });
            },
            MoveTaskToListOrBoard() {
                var _this = this;
                swal({
                        title: "Are you sure?",
                        text: "You want to move selected task ?!!!",
                        type: "warning",
                        showCancelButton: true,
                        confirmButtonClass: "btn-danger",
                        confirmButtonText: "Yes Move",
                        closeOnConfirm: false
                    },
                    function () {
                        axios.post('/api/selected-task-move', {
                            ids: _this.selectedIds,
                            nav: _this.selectedListNav,
                            target: _this.selectedSubList,
                            column_id: _this.selectedColumn
                        })
                            .then(response => response.data)
                            .then(response => {
                                // swal("Complete!", "All Selected task are moved !", "success");
                                swal.close();
                                _this.getTaskList()
                            })
                            .catch(error => {
                                console.log('Add list api not working!!')
                            });

                    });
            },
            PastTaskToList() {
                var _this = this;
                console.log({
                    task_ids: _this.selectedIds,
                    nav_id: _this.selectedListNav,
                    list_id: _this.selectedSubList
                });
                swal({
                        title: "Are you sure?",
                        text: "You want to past selected task ?!!!",
                        type: "warning",
                        showCancelButton: true,
                        confirmButtonClass: "btn-danger",
                        confirmButtonText: "Yes Past",
                        closeOnConfirm: false
                    },
                    function () {
                        axios.post('/api/selected-multiple-task-past-to-another-list', {
                            task_ids: _this.selectedIds,
                            nav_id: _this.selectedListNav,
                            list_id: _this.selectedSubList
                        })
                            .then(response => response.data)
                            .then(response => {
                                // swal("Complete!", "All Selected task are moved !", "success");
                                swal.close();
                                _this.getTaskList()
                            })
                            .catch(error => {
                                console.log('Add list api not working!!')
                            });

                    });
            },
            MoveAllTask() {
                var _this = this;
                swal({
                        title: "Are you sure?",
                        text: "You want to delete the list and move all task ?!!!",
                        type: "warning",
                        showCancelButton: true,
                        confirmButtonClass: "btn-danger",
                        confirmButtonText: "Yes, Delete  & Move Task",
                        closeOnConfirm: false
                    },
                    function () {
                        axios.post('/api/board-list-move', {
                            type: _this.type_T,
                            id: _this.list_id,
                            target: _this.selectedListNav
                        })
                            .then(response => response.data)
                            .then(response => {
                                swal("Complete!", "This " + _this.type_T + " is Moved Successfully !", "success");
                                window.location.href = '/project-dashboard/' + _this.projectId;
                            })
                            .catch(error => {
                                console.log('Add list api not working!!')
                            });

                    });
            },
            DownloadTaskPDF() {
                swal("Under Process!", "Working under process", "success");
            },
            MoveListTOAnotherNav(data) {
                var type = data.type;
                var _this = this;
                _this.type_T = type;
                _this.list_T = [];
                _this.nav_T = [];
                _this.selectedListNav = 'Select list Nav';
                _this.transferBtn = false;

                axios.get('/api/nav-item/' + _this.projectId)
                    .then(response => response.data)
                    .then(response => {
                        _this.nav_T = response.success;
                        setTimeout(() => {
                            $('#transToNav').modal('show');
                        }, 300);
                    })
                    .catch(error => {

                    });
            },
            RemoveNewEmptyChildren(data) {
                var children = data.children;
                var index = 0;
                for (var i = 0; i < children.length; i++) {
                    if (children[i].text == '') {
                        index = i;
                    }
                }
                children.splice(index, 1);
            },
            saveData(e, data) {
                if (e.which === 13) {
                    $('.inp').addClass('input-hide');
                    $('.inp').removeClass('form-control');
                    this.addNode(data);
                }
            },

            SaveDataWithoutCreateNewNode(data) {
                var _this = this;
                var postData = {
                    id: data.id,
                    text: (data.text === null) ? '' : data.text,
                };
                // console.log(postData)
                if (data.text !== '') {
                    axios.post('/api/task-list/update', postData)
                        .then(response => response.data)
                        .then(response => {
                            if (response.empty) {

                            }
                        })
                        .catch(error => {
                            console.log('Api for move down task not Working !!!')
                        });
                }

            },
            DeleteEmptyTask() {
                var _this = this;
                var postData = {
                    id: _this.empty_task_delete_flag
                };


                if ($("#" + _this.empty_task_delete_flag).val() !== undefined && $("#" + _this.empty_task_delete_flag).val().length <= 0) {
                    setTimeout(function () {
                        axios.post('/api/task-list/delete-empty-task', postData)
                            .then(response => response.data)
                            .then(response => {
                                if (response.success === 1) {
                                    var id = response.id;
                                    _this.RemoveEmptyTask(id, _this.treeList);
                                }
                            })
                            .catch(error => {
                                console.log('Api for move down task not Working !!!')
                            }, 1000);
                    })

                }

            },
            RemoveEmptyTask(id, data) {
                if (data.length > 0) {
                    for (let index = 0; index < data.length; index++) {
                        if (index !== undefined && data[index].id === id) {
                            data.splice(index, 1)
                            // this.check_uncheck_child = data[index].children;
                            return true;
                        } else {
                            this.RemoveEmptyTask(id, data[index].children);
                        }
                    }
                }

            },

            dataCopy(data) {
                var _this = this;
                var targetData = data.parent.children;
                for (i = 0; i < targetData.length; i++) {
                    if (targetData[i].text == targetData.text) {
                        // _this.selectedCopy = targetData[i];
                        break;
                    }
                }
            },
            openPicker: function () {
                let _this = this;
                setTimeout(function () {
                    let target = $('.vdp-datepicker__calendar:visible');
                    let wH = window.innerHeight + 140;
                    let position = target.offset();
                    let tH = target.height();
                    let cH = wH - position.top;
                    if (cH < tH) {
                        target.css({bottom: 0 + 'px'});
                    }
                }, 200)
            },
            updateDate(date) {
                date = new Date(date);
                var _this = this;
                var month = (parseFloat(date.getMonth() + 1) > 9) ? parseFloat(date.getMonth() + 1) : '0' + parseFloat(date.getMonth() + 1);
                var formatedDate = date.getFullYear() + '-' + month + '-' + date.getDate();

                var postData = {
                    id: _this.selectedData.id,
                    date: formatedDate
                };
                axios.post('/api/task-list/update', postData)
                    .then(response => response.data)
                    .then(response => {
                        _this.getTaskList()
                    })
                    .catch(error => {
                        console.log('Api for task date update not Working !!!')
                    });
            },
            switchEvent(e) {
                $(e.target).closest('.eachItemRow').find('.switchToggle').collapse('toggle');
            },
            addAttachment(data) {
                let refData = data._id;
                $('#file' + refData).click();
            },
            updatePicture(e, data) {
                var _this = this;
                this.file = e.target.files[0];
                let formData = new FormData();
                formData.append('file', this.file);
                formData.append('id', data.id);
                formData.append('files', 'sdsds');

                axios.post('/api/task-list/update', formData, {headers: {'Content-Type': 'multipart/form-data'}})
                    .then(response => response.data)
                    .then(response => {
                        _this.getTaskList()
                    })
                    .catch(error => {
                        console.log('Api for task date update not Working !!!')
                    });
            },
            deletePhoto(img,id) {
                var _this = this;
                axios.post('/api/task-list/delete-img', {'img': img,id: id})
                    .then(response => response.data)
                    .then(response => {
                        _this.getTaskList();
                        $("#imageModal").modal('hide');
                    })
                    .catch(error => {
                        console.log('Api for task date update not Working !!!')
                    });
            },

            hasPermission(permission) {
                return helper.hasPermission(permission);
            },

            shwAssignUserDropDown(data) {
                let targets = $('#click' + data.id).find('.assign-user-');
                if (targets.length > 0) {
                    $(targets[0]).click();
                }
            },
            AddDontForgetTag(data) {

                data.tags[0] = 'Dont Forget';
                var moveItem;
                var children = data.parent.children;
                var text = data.text;
                for (var i = 0; i < children.length; i++) {
                    if (children[i].text == text) {
                        if (i > 0) {
                            moveItem = data.parent.children[i];
                            children.splice(i, 1);
                            break;
                        }
                    }
                }
                var text1 = "Don't Forget Section";
                for (var i = 0; i < this.treeList.length; i++) {
                    if (this.treeList[i].text == text1) {
                        this.treeList[i].children.push(moveItem);
                        break;
                    }
                }
            },

            keyDownAction(e, data) {
                if (e.which === 9) {
                    e.stopPropagation();
                    e.preventDefault();
                    this.makeChild(data);
                }
                if (e.which === 9 && e.shiftKey) {
                    e.stopPropagation();
                    e.preventDefault();
                    this.unMakeChild(data);

                }
                if (e.ctrlKey && e.which === 73) {
                    e.stopPropagation();
                    e.preventDefault();
                    this.addAttachment(this.selectedData);
                }
            },

            ShowDetails() {
                var _this = this;

                if (_this.selectedData != null && _this.selectedData.sort_id !== -2) {
                    $('#task_width').removeClass('task_width');
                    $('#task_width').addClass('task_widthNormal');
                    $('#details').removeClass('details');
                    $('#details').addClass('detailsShow');
                }
            },
            HideDetails() {
                $('#task_width').addClass('task_width');
                $('#task_width').removeClass('task_widthNormal');
                $('#details').addClass('details');
                $('#details').removeClass('detailsShow');
            },
            ShowTextArea(data) {
                var _this = this;
                $('.SubmitButton').show();
                var option = {
                    height: 50,
                    maxHeight: 200
                };
                _this.growInit(option);
            },
            HideCustomMenu() {
                $('.jquery-accordion-menu').hide();
            },
            expandAll() {
                th.breadthFirstSearch(this.tree1data, node => {
                    node.open = true
                })
            },
            collapseAll() {
                th.breadthFirstSearch(this.tree1data, node => {
                    node.open = false
                })
            },

            showImage(data, image,task_id) {
                this.modalImg = [image,task_id];
                $("#imageModal").modal();
            },
            RuleUpdate(){
                this.AllNavItems = 'update';
            }

        },
        directives: {
            ClickOutside
        },
        watch: {
            filterProjectForm: {
                handler(val) {
                    this.getTaskList();
                },
                deep: true
            }
        }
    }

</script>
