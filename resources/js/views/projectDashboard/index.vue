<template>
    <div>
        <div class="page-titles">
            <!-- Navbar Component-->
            <Navbar :AllNavItems="AllNavItems"
                    :projectId="$route.params.projectId"
                    @getList="showTask"
                    @showSearchInputField="showSearchInputField"
                    @getNavBars="getNavbar">
            </Navbar>

            <div class="input-group col-sm-4 searchList">

                <input class="form-control searchTaskList"
                       type="text" id="myInput"
                       placeholder="Search for names.."
                       title="Type in a name"
                       @keyup="searchDataFormTask($event)"
                >

                <ul class="myUL" id="myUL">
                    <template v-for="task in searchData.tasks" v-if="searchData.tasks.length > 0">
                        <li>
                            <a @mouseover="selectTaskFromTaskTreeList(task)"
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
                    <template v-for="user in searchData.users" v-if="searchData.users.length > 0">
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

        <div class="col-md-8 text-center pt-5" v-if="AllNavItems === null">
            <h2 style="color: #d1a894">Create Task View.</h2>
        </div>
        <div class="col-md-8 text-center pt-5" v-else-if="AllNavItems !== null && list.type === null">
            <h2 style="color: #d1a894">Select Task View.</h2>
        </div>

        <div class="container">
            <div class="col-12">
                <h2 class="p-t-20" v-if="list.type !== null">
                    {{list.name}}
                    <button @click="UpdateListModel" class="btn btn-primary pull-right" type="submit">
                        EDIT {{list.name}}
                    </button>
                </h2>
                <p class="compltit-p" v-if="list.description != null">{{list.description}}</p>
            </div>
        </div>
        <!--        //tree view component section-->
        <div class="TaskListAndDetails container-fluid" v-if="list.type === 'list'" id="TaskListAndDetails">
            <div class="task_width" id="task_width">
                <div class="col-11" id="tree_view_list">

                    <Tree :data="treeList" :indent="2" :space="0" @change="ChangeNode"
                          @drop="dropNode"
                          class="tree4"
                          @drag="dragNode"
                          @moving="dragNode"
                          cross-tree="cross-tree"
                          draggable="draggable"
                          v-if="list.type === 'list'">
                        <div :class="{eachItemRow: true}" :id="'click'+data.id"
                             @click="makeItClick($event, data)"
                             @contextmenu="makeItClick($event, data)"
                             slot-scope="{data, _id,store}" style="font-size: 12px" v-on:dblclick="showLog">
                            <template v-html="data.html" v-if="!data.isDragPlaceHolder">

                                <a :title="(data.children.length)? 'Complete '+data.children.length + ' task': 'Complete'"
                                   @click="addTaskToComplete(data)" data-toggle="tooltip"
                                   class="task-complete left-content li-opacity "
                                >
                                    <i class="outline-check_circle_outline icon-image-preview "></i>
                                </a>
                                <a :title="'Remove this task'"
                                   @click="RemoveNodeAndChildren(data)"
                                   class="delete-icon left-content li-opacity"
                                   data-toggle="tooltip"
                                   href="javascript:void(0)">
                                    <i class="baseline-playlist_delete icon-image-preview"></i>
                                </a>
                                <a class="left-content1 li-opacity ">
                                    <i class="outline-arrow_upward icon-image-preview"></i>
                                </a>
                                <b @click="HideShowChild(store , data)"
                                   v-if="data.children && data.children.length && data.open"><i
                                    class="fa fa-fw fa-minus"></i></b>
                                <b @click="HideShowChild(store , data)"
                                   v-else-if="data.children && data.children.length && !data.open"><i
                                    class="fa fa-fw fa-plus"></i></b>
                                <span>
                                        <input :id="data.id" @blur="showItem($event,data)"
                                               @click="makeInput($event,data)"
                                               @focus="hideItem($event,data)"
                                               @keydown="keyDownAction($event,data)"
                                               @keypress="saveData($event,data)"

                                               class="inp input-hide input-title"
                                               type="text" v-model="data.text">
                                    </span>

                                <a class="attach-icon hide-item-res">
                                        <span v-if="data.files && data.files.length !== 0">
                                            <template v-for="(fl,file_id ) in data.files">
                                                <img :src="'/images/'+fl.file_name" v-if="file_id < 2"
                                                     @click="showImage(data.files, fl.file_name)"
                                                     class="task-img">
                                            </template>
                                        </span>
                                    <i @click="addAttachment(data)"
                                       class="fa fa-paperclip icon-image-preview dropdown-toggle-split li-opacity"
                                       title="File"
                                       data-toggle="tooltip"></i>

                                    <input :id="'file'+data._id" @change="updatePicture($event,data)" ref="file"
                                           style="display: none; "
                                           type="file">
                                </a>


                                <a class="tag-icon hide-item-res">
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
                                        <i class="outline-local_offer icon-image-preview li-opacity"
                                           data-toggle="tooltip" title="Tag">
                                        </i>
                                    </span>

                                    <div :id="'dropdown'+data._id" class="dropdown-menu dropdown-menu-right">

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
                                                                {{(tag.title !== undefined) ?tag.title.substring(0,12) :
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

                                <div class="hide-item-res">
                                    <a class="calender li-opacity clickHide" v-if="data.date === '0000-00-00'"
                                       title="Due Date" data-toggle="tooltip">
                                        <i class="outline-event icon-image-preview" data-toggle
                                           title="toggle"></i>
                                    </a>
                                    <datepicker
                                        :disabled-dates="disabledDates"
                                        @selected="updateDate"
                                        calendar-button-icon='<i class="outline-event icon-image-preview"></i>'
                                        format='dd MMM'
                                        input-class="dateCal"
                                        title="Due Date" data-toggle="tooltip"
                                        v-model="data.date">
                                    </datepicker>


                                </div>
                                <div>
                                    <a class="user dropdown-hide-with-remove-icon">
                                        <template v-if="data.assigned_user.length > 0">
                                            <span class="assigned_user dropdown-toggle-split "
                                                  data-toggle="dropdown" v-for="(assign,keyId) in data.assigned_user">
                                                <p :title="assign.name"
                                                   @click="showAssignedUserRemoveButton(data)"
                                                   class="assignUser-photo-for-selected text-uppercase"
                                                   data-placement="bottom" data-toggle="tooltip"
                                                   v-if="keyId <= 1">{{(assign.name !== null) ? assign.name.substring(0,2) : ''}}
                                                    <a :id="'remove-assign-user'+data.id"
                                                       @click="removeAssignedUser(assign)"
                                                       class="remove-assigned" href="javascript:void(0)">
                                                        <i class="fa fa-times remove-assign-user-icon"></i>
                                                    </a>
                                                </p>

                                            </span>
                                        </template>
                                        <span  data-toggle="dropdown" class=" dropdown-toggle-split" v-else>
                                            <i class="outline-person icon-image-preview li-opacity "
                                               data-toggle="tooltip" title="Assignee">
                                            </i>
                                        </span>

                                        <div class="dropdown-menu dropdown-menu-right">
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
                                                    <template v-for="user in data.users">
                                                        <div @click="assignUserToTask(user,data)"
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
                                <a @click="addChild(data)" class="subTask_plus li-opacity clickHide " data-toggle="tooltip" title="Add Child">
                                    <i class="baseline-playlist_add icon-image-preview"></i>
                                </a>
                                <a @click="addNode(data)" class="task_plus li-opacity clickHide"  data-toggle="tooltip" title="Add Task Bellow">
                                    <i class="baseline-add icon-image-preview"></i>
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
                                    <i class="glyphicon-cog icon-image-preview contex-menu-icon"></i>
                                    Copy </a>
                                <a href="javascript:void(0)" class="dropdown-toggle-split disabled" v-else
                                   style="color: gray"
                                   data-toggle="dropdown">
                                    <i class="glyphicon-cog icon-image-preview contex-menu-icon"></i>
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
                                    <i class="glyphicon-cog icon-image-preview contex-menu-icon"></i>
                                    Cut </a>
                                <a href="javascript:void(0)" class="dropdown-toggle-split disabled"
                                   v-else style="color: gray"
                                   data-toggle="dropdown">
                                    <i class="glyphicon-cog icon-image-preview contex-menu-icon"></i>
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
                                    <i class="glyphicon-cog icon-image-preview contex-menu-icon"></i>
                                    Paste </a>
                                <a href="javascript:void(0)" class="dropdown-toggle-split disabled" style="color: gray"
                                   data-toggle="dropdown"
                                   v-else>
                                    <i class="glyphicon-cog icon-image-preview contex-menu-icon"></i>
                                    Paste </a>
                                <span class="contex-menu-sortcut">
                                    <span class="badge-pill badge-default">Ctrl</span>+<span
                                    class="badge-pill badge-default">v</span>
                                </span>
                            </li>
                            <li><a @click="deleteSelectedTask" href="javascript:void(0)">
                                <i class="baseline-playlist_delete icon-image-preview contex-menu-icon" data-toggle
                                   title="toggle"></i>
                                Delete Selected <span class="badge-pill badge-default contex-menu-sortcut">Delete</span></a>
                            </li>
                            <li>
                                <a href="javascript:void(0)" class="dropdown-toggle-split " data-toggle="dropdown">
                                    <i class="outline-person icon-image-preview contex-menu-icon"></i>
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
                                    <i class="outline-local_offer icon-image-preview contex-menu-icon"></i>
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
                            <li style="position: relative">

                                <datepicker
                                    :disabled-dates="disabledDates"
                                    v-model="date_for_selected"
                                    @selected="ActionToSelectedTask('','date')"
                                    calendar-button-icon='<i class="outline-event icon-image-preview"></i>'
                                    format='dd MMM'
                                    input-class="dateCal-selected">

                                </datepicker>

                                <a class="calender li-opacity clickHide">
                                    <i class="outline-event icon-image-preview contex-menu-icon" data-toggle
                                       title="toggle"></i> Set Due Date
                                </a>


                            </li>
                            <li><a @click="AddDontForgetTagToSelectedIds" href="javascript:void(0)">
                                <i class="baseline-playlist_delete icon-image-preview contex-menu-icon" data-toggle
                                   title="toggle"></i>
                                Move To Dont Forget Section </a>
                            </li>

                        </ul>
                    </div>
                </div>
                <div style="height: 50px;">

                </div>
            </div>
            <div class="details" id="details">
                <TaskDetails
                    :selectedData="selectedData"
                    :task_logs="task_logs"
                    @textArea="ShowTextArea">
                </TaskDetails>
            </div>
        </div>

        <!--        //board component section-->
        <div class="boardView" v-if="list.type === 'board'">
            <!-- Board View Component -->
            <BoardView
                :board_id="list_id"
                :nav_id="nav_id"
                :projectId="projectId">
            </BoardView>

        </div>


        <div aria-hidden="true" aria-labelledby="exampleModalLabel" class="modal fade" id="imageModal" role="dialog"
             tabindex="-1">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Image Show</h5>
                        <span @click="deletePhoto(modalImg)" class="badge badge-warning file-delete">Delete Photo</span>
                        <button aria-label="Close" class="close" data-dismiss="modal" type="button">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <img :src="'/images/'+modalImg" class="image-auto">
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
                                    <tr>
                                        <td class="pt-3-half" v-if="tag.title === 'Dont Forget'">{{tag.title}}</td>
                                        <td @keydown="newLineoff($event)" @keyup="updateTagName($event,tag)"
                                            class="pt-3-half"
                                            contenteditable="true" v-else>
                                            {{tag.title}}
                                        </td>
                                        <td class="pt-3-half">
                                            <input :value="tag.color" @change="updateTagColor($event,tag)"
                                                   style="cursor: pointer;background-color: #fff;border: none;"
                                                   type="color">
                                        </td>
                                        <td>
                                            <a @click="DeleteTagFromModal(tag)" class="compltit-blue-a"
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
                                <textarea cols="40" id="" name="" rows="3" v-model="list.description"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button @click="UpdateListOrBoard" class="btn btn-primary" type="button">Update</button>
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

    export default {
        components: {
            Tree: DraggableTree,
            thy: draggableHelper,
            switches,
            Datepicker,
            VueTagsInput,
            TaskDetails,
            Navbar, BoardView
        },
        data() {
            return {
                disabledDates: {
                    id: null
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
                date_for_selected: null,
                dNode: null,
                dNodeInterval: null,
                dNodeHeght: 0,
            }
        },
        mounted() {
            let _this = this;
            this.projectId = this.$route.params.projectId;
            // this.getTaskList();

            $(document).ready(function () {
                $('.searchList').hide();
                $('.SubmitButton').hide();
                $('.submitdetails').hide();
                setTimeout(function () {
                    $('.delete-icon').hide();
                }, 1000);
                setTimeout(function () {
                    $('#list' + _this.multiple_list[0].id).click();
                }, 300)
            });
        },
        created() {
            let _this = this;
            hotkeys('enter,tab,shift+tab,up,down,left,right,ctrl+c,ctrl+x,ctrl+v,ctrl+u,delete,ctrl+b,ctrl+s,ctrl+i,shift+3', function (event, handler) {
                event.preventDefault();
                switch (handler.key) {
                    case "enter" :
                        _this.addNode(_this.selectedData);
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
                        _this.showLog();
                        _this.task_logs = null;
                        _this.ShowDetails(_this.selectedData);
                        setTimeout(function () {
                            $('#_details').click();
                        }, 500);
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
                    var top = $('#' + node._id)[0].getBoundingClientRect().top + target.scrollTop -241;
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
                var top = $('#click' + task.id)[0].getBoundingClientRect().top + target.scrollTop - 241;
                target.scrollTo(0, top);
            },

            SearchResultClick(task) {
                var target = document.getElementById('TaskListAndDetails');
                var top = $('#click' + task.id)[0].getBoundingClientRect().top + target.scrollTop - 241;
                target.scrollTo(0, top);
                $('#myUL').addClass('myUL');
                $('#myUL').removeClass('myUL-show');
            },
            HideShowChild(store, data){
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
                        if (response === 'success') {
                            _this.getTaskList()
                        }
                    })
                    .catch(error => {
                        console.log('Api is not Working !!!')
                    });
            },
            showAssignedUserRemoveButton(data) {

                $('[data-toggle="tooltip"]').tooltip('hide');

                setTimeout(function () {
                    $('#remove-assign-user' + data.id).toggleClass('remove-assign-user');
                    $('#remove-assign-user' + data.id).removeClass('remove-assigned');
                }, 500)

            },
            removeAssignedUser(user) {

                // console.log(user.id, user.task_id);
                var _this = this;
                var postData = {
                    user_id: user.id,
                    task_id: user.task_id
                };
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

            makeItClick(e, data) {
                var _this = this;
                if (e.ctrlKey && e.which === 1) {
                    var index = _this.selectedIds.indexOf(data.id);
                    if (index > -1) {
                        _this.selectedIds.splice(index, 1);
                        $('#click' + data.id).removeClass('clicked');
                        if (data.text !== 'Dont Forget Section') {
                            // data.draggable = true;
                        }

                    } else {
                        _this.selectedIds.push(data.id);
                        $('#click' + data.id).addClass('clicked');
                    }
                    $('.jquery-accordion-menu').hide();
                    if (_this.selectedIds.length > 1) {
                        this.selectedData = {};
                    }

                } else if (e.which === 1) {
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

                } else if (e.which === 3) {
                    e.preventDefault();
                    e.stopPropagation();
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

            },
            makeInput(e, data) {
                this.selectedData = data;
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
                // data.draggable = false;
                $(e.target).closest('.eachItemRow').find('.task-complete').hide();
                $(e.target).closest('.eachItemRow').find('.tag-icon').hide();
                $(e.target).closest('.eachItemRow').find('.attach-icon').hide();
                $(e.target).closest('.eachItemRow').find('.subTask_plus').hide();
                $(e.target).closest('.eachItemRow').find('.task_plus').hide();
                $(e.target).closest('.eachItemRow').find('.calender').hide();
                $(e.target).closest('.eachItemRow').find('.user').hide();
                $(e.target).closest('.eachItemRow').find('.dateCal').hide();
                $(e.target).closest('.eachItemRow').find('.delete-icon').show();

            },
            showItem(e, data) {
                this.SaveDataWithoutCreateNewNode(data);
                setTimeout(function () {
                    $(e.target).closest('.eachItemRow').find('.delete-icon').hide();
                    $(e.target).closest('.eachItemRow').find('.task-complete').show();
                    $(e.target).closest('.eachItemRow').find('.tag-icon').show();
                    $(e.target).closest('.eachItemRow').find('.attach-icon').show();
                    $(e.target).closest('.eachItemRow').find('.subTask_plus').show();
                    $(e.target).closest('.eachItemRow').find('.task_plus').show();
                    $(e.target).closest('.eachItemRow').find('.calender').show();
                    $(e.target).closest('.eachItemRow').find('.user').show();
                    $(e.target).closest('.eachItemRow').find('.dateCal').show();

                }, 500);

                $('.inp').addClass('input-hide');
                $('.inp').removeClass('form-control');

            },

            copyTask() {
                var _this = this;
                if (_this.selectedIds.length > 1) {
                    alert('Copy and past now working on single task !')
                } else {
                    if (_this.selectedData.text !== 'Dont Forget Section') {
                        _this.selectedCopy = _this.selectedData;
                        _this.selectedCut = null;
                        $('.jquery-accordion-menu').hide();
                    } else {
                        alert("you can't copy this task !")
                    }

                }

            },
            cutTask() {
                var _this = this;
                if (_this.selectedIds.length > 1) {
                    alert('Cut and past now working on single task !')
                } else {
                    if (_this.selectedData.text !== 'Dont Forget Section') {
                        _this.selectedCut = _this.selectedData;
                        _this.selectedCopy = null;
                        $('.jquery-accordion-menu').hide();
                    } else {
                        alert("you can't cut this task !")
                    }

                }

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
                        }, 500)
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
                        }, 500)
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
                        }, 500)
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
                        }, 500)
                    })
                    .catch(error => {
                        console.log('Api is task-unmake-child not Working !!!')
                    });
            },
            pastCopyAndCut() {
                var _this = this;
                var data = _this.selectedData;
                if (_this.selectedIds.length > 1) {
                    return false;
                }
                var postData = {
                    target_id: data.id,
                    copy_id: (this.selectedCopy === null) ? this.selectedCut.id : this.selectedCopy.id,
                    type: (this.selectedCopy === null) ? 'cut' : 'copy',
                    text: (this.selectedCopy === null) ? this.selectedCut.text : this.selectedCopy.text,
                    nav_id: _this.nav_id
                };

                axios.post('/api/task-list/copy-cut-past', postData)
                    .then(response => response.data)
                    .then(response => {
                        _this.getTaskList();
                        setTimeout(function () {
                            $("#" + response.success).click();
                        }, 500)
                    })
                    .catch(error => {
                        console.log('Api is copy and cut not Working !!!')
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
                    id : tag.id,
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
                axios.post('/api/task-list/update', postData)
                    .then(response => response.data)
                    .then(response => {
                        _this.getTaskList();
                        alert('Task is added to compete list !!');
                        // $('#dropdown' + data._id).toggle();
                        // _this.selectedData.tags = tag
                    })
                    .catch(error => {
                        console.log('Api for complete task not Working !!!')
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
                axios.post('/api/task-list/delete-task', postData)
                    .then(response => response.data)
                    .then(response => {
                        _this.getTaskList()
                    })
                    .catch(error => {
                        console.log('Api for delete task not Working !!!')
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
            deleteSelectedTask() {

                var _this = this;
                var postData = {
                    ids: _this.selectedIds,
                };
                axios.post('/api/task-list/delete-task', postData)
                    .then(response => response.data)
                    .then(response => {
                        _this.getTaskList();
                        $('.jquery-accordion-menu').hide();
                    })
                    .catch(error => {
                        console.log('Api for delete task not Working !!!')
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
                        setTimeout(function () {
                            $('.delete-icon').hide();
                        }, 500)
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
                        setTimeout(function () {
                            $('[data-toggle="tooltip"]').tooltip();
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
                        setTimeout(function () {
                            $('.delete-icon').hide();
                            $('.dropdown-hide-with-remove-icon').on('hidden.bs.dropdown', function () {
                                $('.remove-assign-user').addClass('remove-assigned');
                                $('.remove-assigned').removeClass('remove-assign-user');
                            })
                        }, 500)
                    })
                    .catch(error => {

                    });
            },

            //collect data by child navbar component
            showTask(data) {
                this.list_id = data.list_id;
                this.nav_id = data.nav_id;
                this.list.name = data.title;
                this.list.description = data.description;
                this.list.type = data.type;
                if (data.type === 'list') {
                    this.getTaskList()
                }

            },
            getNavbar(data) {
                this.AllNavItems = data.AllNavItems;
            },

            UpdateListModel() {
                $("#updateListBoardModel").modal('show');
            },
            UpdateListOrBoard() {
                var _this = this;
                this.list.project_id = this.projectId;
                this.list.nav_id = this.nav_id;
                this.list.id = this.list_id;
                axios.post('/api/board-list-update', this.list)
                    .then(response => response.data)
                    .then(response => {
                        _this.AllNavItems = response.navItems.original.success;
                        $("#updateListBoardModel").modal('hide');
                    })
                    .catch(error => {
                        console.log('Add list api not working!!')
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
                    text: data.text,
                };
                axios.post('/api/task-list/update', postData)
                    .then(response => response.data)
                    .then(response => {
                        // _this.getTaskList();
                    })
                    .catch(error => {
                        console.log('Api for move down task not Working !!!')
                    });
            },

            dataCopy(data) {
                var _this = this;
                var targetData = data.parent.children;
                for (i = 0; i < targetData.length; i++) {
                    if (targetData[i].text == targetData.text) {
                        _this.selectedCopy = targetData[i];
                        break;
                    }
                }
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
            deletePhoto(img) {
                var _this = this;
                axios.post('/api/task-list/delete-img', {'img': img})
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
                let targets = $('#click' + data.id).find('.outline-person');
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

            showImage(data, image) {
                this.modalImg = image;
                $("#imageModal").modal();
            },

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
