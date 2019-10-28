<template>

    <div class="align-self-center">
        <nav class="navbar-expand-md navbar-spark">
            <div class="container-fluid">
                <div class="collapse navbar-collapse show">
                        <span v-for="nav in AllNavItems">
                            <ul class="navbar-nav ml-4 float-sm-left">
                                <li class="nav-item dropdown">
                                    <a aria-expanded="false" aria-haspopup="true"
                                       class="d-block d-md-flex text-center nav-link dropdown-toggle"
                                       data-toggle="dropdown" href="#">
                                        <span class="d-none d-md-block">{{nav.title}}</span>
                                    </a>
                                    <div aria-labelledby="dropdownMenuButton"
                                         class="dropdown-menu dropdown-menu-left">

                                        <h6 class="dropdown-header" v-if="nav.type === 'list'"> Lists</h6>
                                        <h6 class="dropdown-header" v-else-if="nav.type === 'board'"> Board</h6>

                                        <span v-for="nav_list in nav.lists">
                                            <span :id="'list'+nav_list.id" :class="'board'+nav_list.id"
                                                  @click="setListId(nav_list,nav.id,nav_list.description,nav.type)"
                                                  class="dropdown-item">
                                                <a href="javascript:void(0)" v-if="nav.type === 'list'">{{nav_list.list_title}} </a>
                                                 <a href="javascript:void(0)" v-else>{{nav_list.board_title}}</a>
                                             </span>

                                        </span>
                                        <div class="dropdown-divider"></div>

                                        <a @click="addListModel(nav.id)" class="dropdown-item"
                                           href="Javascript:void(0)"
                                           v-if="nav.type === 'list'">
                                            <i class="fa fa-fw text-left fa-btn fa-plus-circle"></i>
                                           Create  <b>{{nav.title}}</b> List
                                        </a>

                                        <a @click="addBoardModel(nav.id)" class="dropdown-item"
                                           href="Javascript:void(0)"
                                           v-else-if="nav.type === 'board'">
                                            <i class="fa fa-fw text-left fa-btn fa-plus-circle"></i>
                                            Create <b>{{nav.title}}</b> Board
                                        </a>
                                    </div>
                                </li>
                            </ul>
                        </span>
                    <ul class="navbar-nav ml-4 nav-bar-right">
                        <li class="nav-item search-nav-icon">
                            <button class="btn btn-default" type="submit" @click="showSearchInputField"><i
                                class="fa fa-search"></i></button>
                        </li>
                        <li class="nav-item" style="margin-right:20px;">
                            <a @click="shortcutModel" class="d-block d-md-flex text-center nav-link"
                               href="Javascript:void(0)">
                                <span class="d-none d-md-block">Shortcuts</span>
                            </a>
                        </li>

                        <li class="nav-item dropdown">
                            <a aria-expanded="false" aria-haspopup="true" class="d-block d-md-flex text-center nav-link"
                               data-toggle="dropdown" href="#">
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
                            <a aria-expanded="false" aria-haspopup="true" class="d-block d-md-flex text-center nav-link"
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
                                <textarea class="form-control" cols="40" id="" name="" rows="3" v-model="list.description"></textarea>
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
                                <textarea class="form-control" cols="40" name="" rows="3" v-model="list.description"></textarea>
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
                        <h5 class="modal-title pl-3 text-uppercase"> Update <span v-if="update_navItem.type === 'list'">List</span> <span v-else>Board</span> Nav Item</h5>
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
                                        <a href="javascript:void(0)"> <i class="fa fa-trash"></i> Delete with all <span v-if="update_navItem.type === 'list'">List</span> <span v-else>Board</span></a>
                                    </span>
                                    <span class="dropdown-item custom-dropdown-item">
                                        <a href="javascript:void(0)"> <i class="fa fa-arrows"></i> Delete & move <span v-if="update_navItem.type === 'list'">List</span> <span v-else>Board</span> </a>
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
    </div>


</template>

<script>
    export default {
        name: "Navbar",
        props: ['projectId', 'AllNavItems'],
        data() {
            return {
                list: {
                    name: null,
                    description: null,
                    nav_id: null
                },
                navItem: {
                    title: null,
                    type: null,
                    sort_id: null,
                    project_id: null,
                },
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
                            swal("Created", "Nav Create Successful", "success")
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
                        _this.$emit('getNavBars', {AllNavItems: _this.AllNavItems})

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
                axios.post('/api/nav-item/update', _this.navItem)
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
                            swal("Created", "Board Create Successful", "success")
                        }
                    })
                    .catch(error => {
                        console.log('Add list api not working!!')
                    });
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
                            swal("Created", "Board Create Successful", "success")
                            setTimeout(function () {
                                $('.board' + response.id.id).click();
                            }, 300);
                        }
                    })
                    .catch(error => {
                        console.log('Add list api not working!!')
                    });
            },
        }
    }
</script>

<style scoped>
</style>
