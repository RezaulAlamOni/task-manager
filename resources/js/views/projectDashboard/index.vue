<template>
    <div>
        <div class="row page-titles">
<!--            Navbar Component-->
            <Navbar :projectId="$route.params.projectId"
                    :AllNavItems="AllNavItems"
                    @getNavBars="getNavbar"
                    @getList="showTask">
            </Navbar>

            <div class="input-group col-sm-3 searchList">
                <input type="text" class="form-control searchTaskList" id="searchTaskList" placeholder="Search task"
                       name="search">
                <div class="input-group-btn searchClick" id="searchClick">
                    <button class="btn btn-default" type="submit"><i class="fa fa-search"></i></button>
                </div>
            </div>

        </div>
        <div>
            <div v-if="AllNavItems === null" class="col-md-8 text-center pt-5">
                <h2 style="color: #d1a894">Create Task View.</h2>
            </div>
            <div v-else-if="AllNavItems !== null && list.type === null" class="col-md-8 text-center pt-5">
                <h2 style="color: #d1a894">Select Task View.</h2>
            </div>
        </div>
<!--        //tree view component section-->
        <div class="TaskListAndDetails" v-if="list.type === 'list'">
            <div class="task_width" id="task_width" @click="HideDetails">
                <div id="tree_view_list">
                    <div class="col-11" id="col10" style="border: none">
                        <div class="offset-1" >
                            <div class="container-header pl-5 mr-5">
                                <h2>
                                    {{list.name}}
                                    <button type="submit" class="btn btn-primary pull-right" @click="UpdateListModel">
                                        EDIT {{list.name}}
                                    </button>
                                </h2>
                                <p v-if="list.description != null" class="compltit-p">{{list.description}}</p>
                            </div>
                        </div>

                        <Tree class="tree4" :data="treeList" draggable="draggable" cross-tree="cross-tree"
                              v-if="list.type === 'list'"
                              @drop="dropNode"
                              @change="ChangeNode"
                              :indent="2"
                              :space="0">
                            <div :class="{eachItemRow: true}" slot-scope="{data, _id,store}"
                                 style="font-size: 12px"
                                 @click="makeItClick($event, data)" v-on:dblclick="showLog" :id="data._id">
                                <template v-if="!data.isDragPlaceHolder" v-html="data.html">

                                    <a class="task-complete left-content li-opacity "
                                       :title="(data.children.length)? 'Complete '+data.children.length + ' task': 'Complete'"
                                       @click="addTaskToComplete(data)"
                                    >
                                        <i class="outline-check_circle_outline icon-image-preview "></i>
                                    </a>
                                    <a class="delete-icon left-content li-opacity"
                                       @click="RemoveNodeAndChildren(data)"
                                       :title="'Remove this node'">
                                        <i class="baseline-playlist_delete icon-image-preview"></i>
                                    </a>
                                    <a class="left-content1 li-opacity ">
                                        <i class="outline-arrow_upward icon-image-preview"></i>
                                    </a>
                                    <b v-if="data.children && data.children.length && data.open"
                                       @click="store.toggleOpen(data)"><i class="fa fa-fw fa-minus"></i></b>
                                    <b v-else-if="data.children && data.children.length && !data.open"
                                       @click="store.toggleOpen(data)"><i class="fa fa-fw fa-plus"></i></b>
                                    <span>
                                        <input type="text" v-model="data.text"
                                               :id="data.id"
                                               @focus="hideItem($event)"
                                               @blur="showItem($event,data)"
                                               @keypress="saveData($event,data)"
                                               @keydown="keyDownAction($event,data)"
                                               class="inp input-hide input-title" @click="makeInput($event,data)">
                                    </span>

                                    <a class="attach-icon hide-item-res">
                                        <span v-if="data.files && data.files.length !== 0">
                                            <template v-for="fl in data.files">
                                                <img class="task-img" :src="'/images/'+fl.file_name"
                                                     @click="showImage(data.files, fl.file_name)">
                                            </template>
                                        </span>
                                        <i class="fa fa-paperclip icon-image-preview dropdown-toggle-split li-opacity"
                                           @click="addAttachment(data)"></i>
                                        <input type="file" :id="'file'+data._id" ref="file" style="display: none; "
                                               @change="updatePicture($event,data)">
                                    </a>


                                    <a class="tag-icon hide-item-res" >
                                        <i v-if="data.tags.length > 0"
                                           class="dropdown-toggle-split"
                                           data-toggle="dropdown">
                                            <template v-for="(tag ,index) in data.tags">
                                                <template v-if="index < 2">
                                                    <span class="badge badge-warning" v-if="tag.text !== null"
                                                          data-toggle="tooltip" data-placement="bottom"
                                                          :title="data.tagTooltip"
                                                          v-bind:style="[{'background':tag.color},{'margin-left' : 1 +'px'},{'float' : 'left'}]">
                                                        {{(data.tags.length > 2 ) ? tag.text.substring(0,3) : tag.text.substring(0,3) }}
                                                    </span>
                                                        <span class="badge badge-warning" v-else
                                                              v-bind:style="[{'background':tag.color},{'margin-left' : 1 +'px'}]">
                                                        ::
                                                    </span>
                                                </template>
                                            </template>


                                        </i>



                                        <i v-else :id="'tag-'+data._id"
                                           class="outline-local_offer icon-image-preview li-opacity"
                                           data-toggle="dropdown">

                                        </i>
                                        <div class="dropdown-menu dropdown-menu-right" :id="'dropdown'+data._id">

                                            <diV class="collapse show switchToggle" style="">
                                                <div class="container-fluid">
                                                    <vue-tags-input
                                                        v-model="tag1"
                                                        :tags="data.tags"
                                                        :allow-edit-tags="true"
                                                        @tags-changed="newTags => (changeTAg(newTags))"
                                                        @before-deleting-tag="DeleteTag"
                                                    />
                                                    <div class="row">
                                                        <div class="col-12">
                                                            <template v-for="tag in data.tags">
                                                                <li class="badge-pill tags"
                                                                    v-if="tag.text !== 'Dont Forget'"
                                                                    v-bind:style="[{'background': tag.color },{'margin-left' : 1 +'px'}]">
                                                                    {{(tag.text !== undefined) ?tag.text.substring(0,12) : ''}}
                                                                </li>
                                                            </template>
                                                            <li class="badge-pill tags" style="background: #FB8678"
                                                                @click="addExistingTag($event,data ,'Dont Forget')">
                                                                Dont Forget
                                                            </li>
                                                        </div>
                                                    </div>
                                                    <hr>
                                                    <div class="col-xs-12" style="margin-top:10px;width: 100%;">
                                                        <button type="submit"
                                                                class="btn btn-small btn-primary pull-right"
                                                                @click="showTagManageModel">Manage Tag
                                                        </button>
                                                    </div>
                                                </div>

                                            </diV>
                                        </div>
                                    </a>

                                    <div class="hide-item-res">
                                        <a class="calender li-opacity clickHide" v-if="!data.date">
                                            <i class="outline-event icon-image-preview" title="toggle"
                                               data-toggle></i>
                                        </a>
                                        <datepicker
                                            :disabled-dates="disabledDates"
                                            input-class="dateCal"
                                            v-model="data.date"
                                            format='dd MMM'
                                            @selected="updateDate"
                                            calendar-button-icon='<i class="outline-event icon-image-preview"></i>'>
                                        </datepicker>


                                    </div>
                                    <div>
                                        <a class="user "><i
                                            class="outline-person icon-image-preview li-opacity dropdown-toggle-split"
                                            data-toggle="dropdown"></i>
                                            <div class="dropdown-menu dropdown-menu-right">

                                                <diV class="collapse show switchToggle">
                                                    <li class="assignUser">
                                                        <input type="text" class="input-group searchUser"
                                                               placeholder="Set assignee by name and email">
                                                        <label class="pl-2 ">
                                                            <small style="font-size: 12px">Or invite a new member by
                                                                email address
                                                            </small>
                                                        </label>
                                                    </li>
                                                    <li class="assignUser">
                                                        <span v-for="user in data.users">
                                                            <div class="row" @click="assignUserToTask(user,data)">
                                                                <div class="col-md-3 pt-2 pl-5">
                                                                    <p class="assignUser-photo">{{(user.name !== null) ? user.name.substring(0,12) : ''}}</p>
                                                                </div>
                                                                <div class="col-md-9">
                                                                    <h5>{{user.name}}<br>
                                                                        <small>{{user.email}}</small>
                                                                    </h5>
                                                                </div>
                                                            </div>
                                                        </span>
                                                    </li>
                                                </diV>
                                                <li class="border-top pl-2" @click="switchEvent($event)">

                                                    <span style="font-size: 13px;">Assign an external team</span>
                                                    <switches v-model="id"
                                                              style="position:absolute;right: 10px;bottom: -4px"
                                                              theme="bootstrap"
                                                              color="success">
                                                    </switches>
                                                </li>
                                            </div>
                                        </a>

                                    </div>
                                    <a class="subTask_plus li-opacity clickHide" @click="addEmptyChild(data)">
                                        <i class="baseline-playlist_add icon-image-preview"></i>
                                    </a>
                                    <a class="task_plus li-opacity clickHide" @click="addNode(data)">
                                        <i class="baseline-add icon-image-preview"></i>
                                    </a>

                                </template>
                            </div>
                        </Tree>

                    </div>
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
            <div class="offset-1 col-9" >
                <div class="container-header pl-5 mr-5">
                    <h2>
                        {{list.name}}
                        <button type="submit" class="btn btn-primary pull-right" @click="UpdateListModel">
                            EDIT {{list.name}}
                        </button>
                    </h2>
                    <p v-if="list.description != null" class="compltit-p">{{list.description}}</p>
                </div>
            </div>
            <!-- Board View Component -->
            <BoardView
                :board_id="list_id"
                :projectId="projectId"
                :nav_id="nav_id">
            </BoardView>

        </div>


        <div class="modal fade" id="imageModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
             aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Image Show</h5>
                        <span class="badge badge-warning file-delete" @click="deletePhoto(modalImg)">Delete Photo</span>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <img :src="'/images/'+modalImg" class="image-auto">
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="TagManage" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
             aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h3 class="text-center text-uppercase">Manage All Tag</h3>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div id="table" class="table-responsive">
                            <table data-v-095ab3dc="" class="table">
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
                                        <td class="pt-3-half" v-else contenteditable="true"
                                            @keyup="updateTagName($event,tag)" @keydown="newLineoff($event)">
                                            {{tag.title}}
                                        </td>
                                        <td class="pt-3-half">
                                            <input type="color" :value="tag.color" @change="updateTagColor($event,tag)"
                                                   style="cursor: pointer;background-color: #fff;border: none;">
                                        </td>
                                        <td>
                                            <a href="javascript:void(0)" @click="DeleteTagFromModal(tag)"
                                               class="compltit-blue-a">
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
                        <button type="button" class="btn btn-secondary" data-dismiss="modal" aria-label="Close">Cancel
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="updateListBoardModel" tabindex="-1" role="dialog"
             aria-labelledby="exampleModalLabel"
             aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title pl-3"> Update {{list.name}} <span
                            class="text-uppercase">[{{list.type}}]</span></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p>Add your new list here !</p>
                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label">Title</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" v-model="list.name">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label">Description</label>
                            <div class="col-sm-8">
                                <textarea name="" id="" cols="40" rows="3" v-model="list.description"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" @click="UpdateListOrBoard">Update</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal" aria-label="Close">Cancel
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
            Navbar,BoardView
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
            }
        },
        mounted() {
            let _this = this;
            this.projectId = this.$route.params.projectId;
            this.getTaskList();

            $(document).ready(function () {
                $('.searchList').hide();
                $('.SubmitButton').hide();
                $('.submitdetails').hide();
                setTimeout(function () {
                    $('.delete-icon').hide();
                }, 1000)
                setTimeout(function () {
                    $('#list' + _this.multiple_list[0].id).click();
                }, 300)
            });
        },
        created() {
            let _this = this;
            hotkeys('enter,tab,shift+tab,up,down,left,right,ctrl+c,ctrl+x,ctrl+v,ctrl+u,ctrl+d,ctrl+b,ctrl+s,ctrl+i,ctrl+shift+3', function (event, handler) {
                event.preventDefault()
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
                            $('#_details').click()
                        }, 500)
                        break;
                    case "ctrl+c":
                        _this.selectedCopy = _this.selectedData;
                        _this.selectedCut = null;
                        break;
                    case "ctrl+x":
                        _this.selectedCut = _this.selectedData;
                        _this.selectedCopy = null;
                        break;
                    case "ctrl+v":
                        _this.pastCopyAndCut(_this.selectedData);
                        break;
                    case "ctrl+d":
                        _this.RemoveNodeAndChildren(_this.selectedData);
                        break;
                    case "ctrl+u":
                        _this.shwAssignUserDropDown(_this.selectedData);
                        break;
                    case "ctrl+b":
                        _this.AddDontForgetTag(_this.selectedData);//add DON'T FORGET SECTION
                        break;
                    case "ctrl+s":
                        $('.searchList').show();
                        break;
                    case "ctrl+i":
                        _this.addAttachment(_this.selectedData);
                        break;
                    case "ctrl+shift+3":
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
                    text.style.overflow = 'auto';
                } else {
                    text.style.overflow = 'hidden';
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
                    target.style.overflow = 'hidden';
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
            },
            dragNode(node, targetTree, oldTree) {
            },
            ChangeNode(index, new2, old) {
            },
            assignUserToTask(user, data) {
                alert('dfsa');
            },

            makeItClick(e, data) {
                this.selectedData = data;
                this.tags = data.tags;
                $('.eachItemRow').removeClass('clicked');
                $(e.target).addClass('clicked');
                $(e.target).closest('.eachItemRow').addClass('clicked');
            },
            makeInput(e, data) {
                this.selectedData = data;
                $('.inp').addClass('input-hide');
                $('.inp').removeClass('form-control');
                $(e.target).removeClass('input-hide');
                $(e.target).addClass('form-control');
            },
            hideItem(e) {
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

                }, 500)

                $('.inp').addClass('input-hide');
                $('.inp').removeClass('form-control');

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
                if (text !== '') {
                    axios.post('/api/task-list/add-task', postData)
                        .then(response => response.data)
                        .then(response => {
                            // _this.getTaskList()
                            console.log(response)

                            setTimeout(function () {
                                _this.addEmptyNode(data)
                            }, 500)
                        })
                        .catch(error => {
                            console.log('Api is not Working !!!')
                        });
                } else {
                    console.log('Empty text')
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
                        console.log(response)
                        _this.newEmptyTaskID = response.success.id;
                        _this.getTaskList()
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

                if (data.text !== '') {
                    axios.post('/api/task-list/task-make-child', postData)
                        .then(response => response.data)
                        .then(response => {
                            _this.newEmptyTaskID = response.success;
                            _this.getTaskList()
                            setTimeout(function () {
                                $("#" + _this.newEmptyTaskID).click();
                            }, 500)
                        })
                        .catch(error => {
                            console.log('Api is task-make-child not Working !!!')
                        });
                } else {
                    for (var i = 0; i < children.length; i++) {
                        if (children[i].text == '') {
                            children.splice(i, 1);
                        }
                    }
                    _this.addEmptyChild(_this.reselectParentId)
                }

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
                if (data.text !== '') {
                    axios.post('/api/task-list/reverse-child', postData)
                        .then(response => response.data)
                        .then(response => {
                            _this.newEmptyTaskID = response.success;
                            _this.getTaskList()
                            setTimeout(function () {
                                $("#" + _this.newEmptyTaskID).click();
                            }, 500)
                        })
                        .catch(error => {
                            console.log('Api is task-unmake-child not Working !!!')
                        });
                } else {
                    var children = data.parent.children;
                    for (var i = 0; i < children.length; i++) {
                        if (children[i].text == '') {
                            children.splice(i, 1);
                        }
                    }
                    _this.addEmptyNode(_this.reselectParentId)
                }

            },
            pastCopyAndCut(data) {
                var _this = this;
                var postData = {
                    target_id: data.id,
                    copy_id: (this.selectedCopy === null) ? this.selectedCut.id : this.selectedCopy.id,
                    type: (this.selectedCopy === null) ? 'cut' : 'copy',
                    text: (this.selectedCopy === null) ? this.selectedCut.text : this.selectedCopy.text,
                    nav_id: _this.nav_id
                }

                axios.post('/api/task-list/copy-cut-past', postData)
                    .then(response => response.data)
                    .then(response => {
                        _this.getTaskList()
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
                myColor = '#' + (Math.random() * 0xFFFFFF << 0).toString(16)
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
                    }
                    axios.post('/api/task-list/add-tag', postData)
                        .then(response => response.data)
                        .then(response => {
                            console.log(response.success)
                            _this.getTaskList()
                            $('#dropdown' + data._id).toggle();
                            _this.selectedData = data
                            _this.tag = null
                        })
                        .catch(error => {
                            console.log('Api for move down task not Working !!!')
                        });
                }
            },
            addExistingTag(e, data, tag) {

                var _this = this;
                var color = (tag === 'Dont Forget') ? '#ff0000' : _this.generateColor();
                var postData = {
                    id: data.id,
                    tags: tag,
                    color: color
                }
                axios.post('/api/task-list/add-tag', postData)
                    .then(response => response.data)
                    .then(response => {
                        console.log(response.success)
                        _this.getTaskList()
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
                    }
                    axios.post('/api/task-list/add-tag', postData)
                        .then(response => response.data)
                        .then(response => {
                            console.log(response.success)
                            _this.getTaskList()
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
                    id: obj.tag.id,
                }
                if (obj.tag.text !== 'Dont Forget') {
                    axios.post('/api/task-list/delete-by-tag-id', postData)
                        .then(response => response.data)
                        .then(response => {
                            console.log(response.success)
                            _this.getTaskList()
                            _this.tag = null
                        })
                        .catch(error => {
                            console.log('Api for move down task not Working !!!')
                        });
                }


            },
            showTagManageModel() {
                var _this = this;
                axios.get('/api/task-list/all-tag')
                    .then(response => response.data)
                    .then(response => {
                        _this.manageTag = response.tags;
                        console.log(response)
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
                }
                axios.post('/api/task-list/update-tag', postData)
                    .then(response => response.data)
                    .then(response => {
                        _this.manageTag = response.tags;
                        _this.getTaskList()
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
                    }
                    axios.post('/api/task-list/update-tag', postData)
                        .then(response => response.data)
                        .then(response => {
                            _this.manageTag = response.tags;
                            _this.getTaskList()
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
                    title: tag.title,
                }
                axios.post('/api/task-list/delete-tag', postData)
                    .then(response => response.data)
                    .then(response => {
                        _this.manageTag = response.tags;
                        _this.getTaskList()
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
                }
                axios.post('/api/task-list/update', postData)
                    .then(response => response.data)
                    .then(response => {
                        console.log(response)
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
                }
                axios.post('/api/task-list/update', postData)
                    .then(response => response.data)
                    .then(response => {
                        console.log(response)
                        _this.getTaskList()
                        alert('Task is added to compete list !!');
                        // $('#dropdown' + data._id).toggle();
                        // _this.selectedData.tags = tag
                    })
                    .catch(error => {
                        console.log('Api for complete task not Working !!!')
                    });
            },
            moveItemUp(data) {
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
                        console.log(response)
                        _this.getTaskList()
                        _this.selectedData = data;
                        setTimeout(function () {
                            $("#" + data.id).click();
                        }, 300)
                    })
                    .catch(error => {
                        console.log('Api for move up task not Working !!!')
                    });
            },
            moveItemDown(data) {
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
                }
                axios.post('/api/task-list/move-task', postData)
                    .then(response => response.data)
                    .then(response => {
                        console.log(response)
                        _this.getTaskList()
                        setTimeout(function () {
                            $("#" + data.id).click();
                        }, 300)
                    })
                    .catch(error => {
                        console.log('Api for move down task not Working !!!')
                    });

            },
            RemoveNodeAndChildren(data) {
                var _this = this;
                if (confirm('Are You sure you want to delete this task !! ?')) {
                    var postData = {
                        id: data.id,
                        text: data.text
                    }
                    axios.post('/api/task-list/delete-task', postData)
                        .then(response => response.data)
                        .then(response => {
                            _this.getTaskList()
                        })
                        .catch(error => {
                            console.log('Api for delete task not Working !!!')
                        });
                }
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
            getTaskList() {
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
                        }, 1000)
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
                            _this.treeList = [newEmpty]
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
                var month = (parseFloat(date.getMonth() + 1) > 9) ? parseFloat(date.getMonth() + 1) : '0' + parseFloat(date.getMonth() + 1)
                var formatedDate = date.getFullYear() + '-' + month + '-' + date.getDate()

                var postData = {
                    id: _this.selectedData.id,
                    date: formatedDate
                }
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
                var _this = this
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
                        _this.getTaskList()
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
                let targets = $('#' + data._id).find('.outline-person');
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
                console.log(_this.selectedData);
                if (_this.selectedData != null) {
                    $('#task_width').removeClass('task_width');
                    $('#task_width').addClass('task_widthNormal');
                    $('#details').removeClass('details');
                    $('#details').addClass('detailsShow');
                    $('#col10').removeClass('col-10');
                    $('#col10').addClass('col-12');
                    $('#col10').removeClass('offset-2');
                }
            },
            HideDetails() {
                $('#task_width').addClass('task_width');
                $('#task_width').removeClass('task_widthNormal');
                $('#details').addClass('details');
                $('#details').removeClass('detailsShow');
                $('#col10').removeClass('col-12');
                $('#col10').addClass('col-10');
                $('#col10').addClass('offset-2');
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
            HideTextArea() {
                var _this = this;
                $('.SubmitButton').hide();
            },
            ShowListDetails(data) {
                var _this = this;
                $('.submitdetails').show();
                var option = {
                    height: 50,
                    maxHeight: 400
                };
                _this.growInit(option);
            },
            HideListDetails() {
                var _this = this;
                $('.submitdetails').hide();
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
