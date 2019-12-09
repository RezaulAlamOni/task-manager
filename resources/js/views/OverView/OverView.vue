<template>
    <main>
        <div class="container">
            <div class="loder" id="loder-hide">
                <div class="foo foo1">
                    <div class="circle"></div>
                </div>
                <div class="foo foo2">
                    <div class="circle"></div>
                </div>
            </div>
            <ul class="nav nav-pills" id="myTab" role="tablist" style="background: #e4e4e4;padding: 4px 0px 0 0px">
                <li class="nav-item">
                    <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab"
                       aria-controls="home" aria-selected="true">All List</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab"
                       aria-controls="profile"
                       @click="GetAllFiles" aria-selected="false">Files</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="contact-tab" data-toggle="tab" href="#contact" role="tab"
                       aria-controls="contact"
                       @click="GetAllComments" aria-selected="false">Comments</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="contact-logs" data-toggle="tab" href="#logs" role="tab"
                       aria-controls="contact"
                       @click="GetAllLogs" aria-selected="false">All Logs</a>
                </li>
            </ul>
            <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                    <div class="accordion" id="listWithHandle">
                        <template v-for="list in All_list">
                            <div class="card overview-list" :data-id="list.id">
                                <div class="card-header overview-list-header" id="headingOne" data-toggle="collapse"
                                     data-target="" aria-expanded="false"
                                     aria-controls="collapseOne">
                                    <div class="col-md-12">
                                        <img src="/img/task-icon/move.png" alt="" height="20px" width="20px"
                                             class="mr-2 sort-trigger" data-toggle="tooltip"
                                             title="Change sort-order lists">
                                        <h2 style="margin: 10px 15px;cursor: pointer" data-placement="bottom"
                                            @click="dataCollapse('#collapse'+list.id,list.id)" data-toggle="tooltip">
                                            {{list.list_title}}
                                        </h2>
                                        <span class="option-btn">
                                    <span data-toggle="dropdown" class="dropdown-toggle-split col-md-12 opacity">
                                        <i class="fa fa-ellipsis-h"></i>
                                    </span>
                                    <div class="dropdown-menu overview-dropdown dropdown-menu-right">
                                        <div class="collapse show switchToggle">

                                            <a href="#" class="dropdown-item" @click="EditList(list)">
                                                <img data-v-0ca4b43b="" src="/img/task-icon/edit.png" alt=""
                                                     height="17px" width="17px" class="mr-2">
                                                Edit List
                                            </a>
                                            <a href="#" class="dropdown-item" @click="DeleteList(list)">
                                                <img data-v-0ca4b43b="" src="/img/task-icon/trash.png"
                                                     alt="" height="20px" width="20px" class="mr-2">
                                                Delete List
                                            </a>
                                            <a :href="'/list-pdf-create/'+list.id" class="dropdown-item">
                                                <img data-v-0ca4b43b="" src="/img/task-icon/pdf.png"
                                                     alt="" height="20px" width="20px" class="mr-2">
                                                Download PDF
                                            </a>
                                            <a href="#" class="dropdown-item" v-if="list.is_delete === 1"
                                               @click="RestoreList(list.id)">
                                                <img data-v-0ca4b43b="" src="/img/task-icon/restore.png" alt=""
                                                     height="17px" width="17px" class="mr-2">
                                                Restore this list
                                            </a>
                                        </div>
                                    </div>
                                </span>
                                    </div>
                                    <div class="col-md-12">
                                        <p>
                                            {{list.description}}
                                        </p>
                                    </div>
                                </div>
                                <div :id="'collapse'+list.id" class="collapse show multi-collapse "
                                     :class="(list.open === 0) ? 'hide-overview-list-task' : 'show-overview-list-task'"
                                     aria-labelledby="headingOne"
                                     data-parent="#listWithHandle">
                                    <div class="card-body p-0">
                                        <div class="TaskListAndDetails">
                                            <div class="col-11" id="tree_view_list">
                                                <Tree :data="list.tasks" :indent="2"
                                                      :space="0" class="tree4"
                                                      draggable="false"
                                                      cross-tree="cross-tree">
                                                    <div :class="{eachItemRow: true}"
                                                         slot-scope="{data, _id,store,vm}"
                                                         style="font-size: 12px">
                                                        <template v-html="data.html">
                                                <span class="">
                                                    <img :src="baseUrl+'/img/'+data.progress+'.png'" alt="" height="28"
                                                         width="28"
                                                         v-if="(data.progress !== null)"
                                                         :title="(data.complete_tooltip !== '') ? data.complete_tooltip : 'Complete'"
                                                         data-toggle="tooltip"
                                                         class="task-complete-progress left-content">

                                                    <img :src="baseUrl+'/img/'+data.progress+'.png'" alt="" height="28"
                                                         width="28"
                                                         v-if="data.progress === '100'"
                                                         :title="(data.complete_tooltip !== null) ? data.complete_tooltip : 'Complete'"
                                                         data-toggle="tooltip"
                                                         class="task-complete left-content li-opacity ">

                                                </span>
                                                            <span>
                                                        <span class="inp input-hide input-title"
                                                              style="width: 95%;">
                                                            {{data.text}}
                                                        </span>
                                                    </span>
                                                        </template>
                                                    </div>
                                                </Tree>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>
                <div class="tab-pane fade row" id="profile" role="tabpanel" aria-labelledby="profile-tab"
                     style="margin: 0px 15px;">
                    <div class="row" v-if="All_files != null && All_files.length > 0">
                        <template v-for="file in All_files.data" v-if="All_files.data.length > 0">
                            <div class="">
                                <div class="card" style="width: 13rem;margin: 10px 15px 0 0;">
                                    <a :href="baseUrl+'/storage/'+file.tasks_id+'/'+file.file_name"
                                       style="height: 120px;" target="_blank">
                                        <img :src="baseUrl+'/storage/'+file.tasks_id+'/'+file.file_name"
                                             v-if="file.file_name.toLowerCase().endsWith('.png') || file.file_name.toLowerCase().endsWith('.jpg') || file.file_name.toLowerCase().endsWith('.jpeg')  "
                                             class="card-img-top" alt="..." height="120">
                                        <img :src="baseUrl+'/img/txt.png'"
                                             v-else-if="file.file_name.toLowerCase().endsWith('.txt')"
                                             class="card-img-top" alt="..." height="120">
                                        <img :src="baseUrl+'/img/pdf.png'"
                                             v-else-if="file.file_name.toLowerCase().endsWith('.pdf')"
                                             class="card-img-top" alt="..." height="120">
                                        <img :src="baseUrl+'/img/file.png'"
                                             v-else-if="file.file_name.toLowerCase().endsWith('.docx')"
                                             class="card-img-top" alt="..." height="120">
                                        <img :src="baseUrl+'/img/txt.png'"
                                             v-else class="card-img-top" alt="..." height="120">
                                    </a>
                                    <div class="card-body" style="padding: 5px 0px 6px 10px;">
                                        <h3 data-toggle="tooltip" :title="file.title" style="cursor: pointer"
                                            @click="ShowList(file.list_id)">
                                            <!--                                            #TID{{file.tasks_id}}-->
                                        </h3>
                                        <div class="comment_details">
                                            <ul style="margin: 0px !important;">
                                                <li data-toggle="tooltip" :title="file.user.name">
                                                    <i class="fa fa-pencil" style="color: #5299e0;"></i>
                                                    <span class="user">{{file.user.name}}</span>
                                                </li>
                                                <li><i class="fa fa-calendar" style="color: #5299e0;"></i> {{file.created_at.substring(0,10)}}
                                                </li>
                                                <li><i class="fa fa-clock-o" style="color: #5299e0;"></i>
<!--                                                    {{file.created_at.substring(11,16)}}-->
                                                    {{file.created_at | relative}}
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>
                    <div v-else style="text-align: center; margin-top: 25px; color: #637588;">
                        <h3>No files added yet!</h3>
                    </div>

                    <template v-if="All_files.last_page > 1">
                        <nav aria-label="Page navigation example" style="padding-top: 39px;">
                            <ul class="pagination justify-content-end">
                                <li class="page-item " :class="(All_files.prev_page_url === null) ? 'disabled' : ''">
                                    <a class="page-link" href="javascript:void(0)"
                                       @click="FilePagination(All_files.current_page-1)" tabindex="-1">Previous</a>
                                </li>
                                <template v-for="i in All_files.last_page">
                                    <li class="page-item" :class="(All_files.current_page === i) ? 'active' : ''">
                                        <a class="page-link" @click="FilePagination(i)"
                                           href="javascript:void(0)">{{i}}</a>
                                    </li>
                                </template>

                                <li class="page-item"
                                    :class="(All_files.last_page === All_files.current_page) ? 'disabled' : ''">
                                    <a class="page-link" @click="FilePagination(All_files.current_page+1)"
                                       href="javascript:void(0)">Next</a>
                                </li>
                            </ul>
                        </nav>
                    </template>
                </div>
                <div class="tab-pane fade" id="contact" role="tabpanel" aria-labelledby="contact-tab">

                    <!-- comments container -->
                    <div class="comment_block">
                        <!--                        <h2>All Comments Here</h2>-->
                        <div class="new_comment" v-if="All_comments != null && All_comments.length > 0">
                            <template v-for="comment in All_comments">
                                <ul class="user_comment">
                                    <div class="user_avatar">
                                        <img :src="comment.user.photo_url"
                                             v-if="comment.user.photo_url !== null">
                                        <p class="comment-avature user_avatar" v-else>
                                            {{comment.user.name.substring(0,2)}}</p>
                                    </div>
                                    <div class="comment_body">
                                        <p>
                                            <span class="user">{{comment.user.name}} :</span>
                                            <span v-if="comment.attatchment === null">{{comment.comment}}</span>
                                            <template v-else>
                                                <a :href="baseUrl+'/storage/'+comment.task_id+'/comment/'+comment.attatchment"
                                                   data-toggle="tooltip" title="Click For View Attachment"
                                                   target="_blank">
                                                    <img
                                                        v-if="comment.attatchment.toLowerCase().endsWith('.png') || comment.attatchment.toLowerCase().endsWith('.jpg') || comment.attatchment.toLowerCase().endsWith('.jpeg')"
                                                        :src="baseUrl+'/storage/'+comment.task_id+'/comment/'+comment.attatchment"
                                                        height="100" alt="">
                                                    <span v-else>{{comment.attatchment}}</span>
                                                </a>
                                            </template>


                                        </p>
                                        <p>
                                            <span style="cursor : pointer;color: #6495ED"
                                                  @click="ShowList(comment.list_id)">
<!--                                                #TID-{{comment.task_id}}-->
                                            </span><!--<br>-->
                                            Task : {{comment.title}}
                                        </p>
                                    </div>

                                    <div class="comment_toolbar">
                                        <div class="comment_details">
                                            <ul>
                                                <li><i class="fa fa-clock-o"></i>
<!--                                                    {{comment.created_at.substring(11,16)}}-->
                                                    {{comment.created_at | relative}}
                                                </li>
<!--                                                <li><i class="fa fa-calendar"></i>-->
<!--&lt;!&ndash;                                                    {{comment.created_at.substring(0,10)}}&ndash;&gt;-->
<!--                                                    -->
<!--                                                </li>-->
                                                <li><i class="fa fa-pencil"></i> <span class="user">{{comment.user.name}}</span>
                                                </li>
                                            </ul>
                                        </div>
                                        <!--                                    <div class="comment_tools">-->
                                        <!--                                        <ul>-->
                                        <!--                                            <li><i class="fa fa-share-alt"></i></li>-->
                                        <!--                                            <li><i class="fa fa-reply"></i></li>-->
                                        <!--                                            <li><i class="fa fa-heart love"></i></li>-->
                                        <!--                                        </ul>-->
                                        <!--                                    </div>-->

                                    </div>
                                </ul>
                            </template>
                        </div>
                        <div v-else style="text-align: center; margin-top: 25px; color: #637588;">
                            <h3>No comments yet!</h3>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="logs" role="tabpanel" aria-labelledby="contact-logs">

                    <!-- comments container -->
                    <div class="comment_block">
                        <div>
                            <div class="form-inline" style="position:relative;">
                                <div class="form-group pl-1">
                                    <label for="perPage">Select Per Page Item : &nbsp;</label>
                                    <select class="form-control" id="perPage" name="perPage" v-model="per_page"
                                            @change="setPerPageItem">
                                        <option :value="All_logs.total">All</option>
                                        <template v-for="i in Math.floor(All_logs.total/100)">
                                            <option v-if="i == 1" value="50">50</option>
                                            <option v-if="i == 2" value="100">100</option>
                                            <option v-if="i == 3" value="300">300</option>
                                            <option v-if="i == 4" value="500">500</option>
                                            <option v-if="i == 5" value="1000">1000</option>
                                        </template>

                                    </select>
                                </div>
                                <div>&emsp; Total : {{All_logs.total}}</div>
                                <div>
                                    <template v-if="All_logs.last_page > 1">
                                        <nav aria-label="Page navigation example"
                                             style="position: absolute;right: 0;top: 0;">
                                            <ul class="pagination justify-content-end"
                                                style="padding: 0px;margin: 0px;">
                                                <li class="page-item "
                                                    :class="(All_logs.prev_page_url === null) ? 'disabled' : ''">
                                                    <a class="page-link" href="javascript:void(0)"
                                                       @click="LogPagination(All_logs.current_page-1)" tabindex="-1">Previous</a>
                                                </li>
                                                <template v-for="i in pages">
                                                    <li class="page-item"
                                                        :class="(All_logs.current_page === i) ? 'active' : ''">
                                                        <a class="page-link" @click="LogPagination(i)"
                                                           href="javascript:void(0)">{{i}}</a>
                                                    </li>
                                                </template>

                                                <li class="page-item"
                                                    :class="(All_logs.last_page === All_logs.current_page) ? 'disabled' : ''">
                                                    <a class="page-link" @click="LogPagination(All_logs.current_page+1)"
                                                       href="javascript:void(0)">Next</a>
                                                </li>
                                            </ul>
                                        </nav>
                                    </template>
                                </div>
                            </div>

                        </div>
                        <div class="new_comment">
                            <table class="table table-striped table-bordered">
                                <thead>
                                <tr>
                                    <th scope="col" style="width: 4%;">#</th>
                                    <th scope="col" style="width: 26%;">Title</th>
                                    <th scope="col" style="width: 17%;">Log Type</th>
                                    <th scope="col" style="width: 17%;">Action Type</th>
                                    <th scope="col" style="width: 17%;">Action By</th>
                                    <th scope="col" style="width: 11%;text-align: center">Action At</th>
                                    <th scope="col" style="width: 8%;text-align: center">Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                <template v-for="(log , key) in All_logs.data">
                                    <tr>
                                        <td>{{log.id}}</td>
                                        <td>{{log.title}}</td>
                                        <td>{{log.log_type}}</td>
                                        <td class="text-capitalize">{{log.action_type}}</td>
                                        <td>{{log.user.name}}</td>
                                        <td class="text-center">{{log.action_at | relative}}</td>

                                        <td class="text-center" style="cursor : pointer">
                                            <a href="javascript:void(0)" v-if="log.action_type === 'softdelete'"
                                            data-toggle="tooltip" title="Undo Delete" @click="RestoreDeletedItem(log.id)">
                                                <img src="/img/task-icon/restore.png" alt="" height="20px" width="20px" class="mr-2">
                                            </a>
                                            <a href="javascript:void(0)" v-else>
<!--                                                <img src="/img/task-icon/trash.png" alt="" height="20px" width="20px" class="mr-2">-->
                                            </a>
                                        </td>
                                    </tr>
                                </template>

                                </tbody>
                            </table>

                        </div>
                        <div>
                            <template v-if="All_logs.last_page > 1">
                                <nav aria-label="Page navigation example">
                                    <ul class="pagination justify-content-end">
                                        <li class="page-item "
                                            :class="(All_logs.prev_page_url === null) ? 'disabled' : ''">
                                            <a class="page-link" href="javascript:void(0)"
                                               @click="LogPagination(All_logs.current_page-1)"
                                               tabindex="-1">Previous</a>
                                        </li>
                                        <template v-for="i in pages">
                                            <li class="page-item"
                                                :class="(All_logs.current_page === i) ? 'active' : ''">
                                                <a class="page-link" @click="LogPagination(i)"
                                                   href="javascript:void(0)">{{i}}</a>
                                            </li>
                                        </template>

                                        <li class="page-item"
                                            :class="(All_logs.last_page === All_logs.current_page) ? 'disabled' : ''">
                                            <a class="page-link" @click="LogPagination(All_logs.current_page+1)"
                                               href="javascript:void(0)">Next</a>
                                        </li>
                                    </ul>
                                </nav>
                            </template>
                        </div>
                    </div>
                </div>
            </div>

            <div aria-hidden="true" aria-labelledby="exampleModalLabel" class="modal fade" id="updateListBoardModelO"
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
                            <button @click="UpdateListOrBoard"
                                    class="btn btn-primary ladda-button ladda_update_list_board"
                                    data-style="expand-right">
                                Update
                            </button>
                            <button aria-label="Close" class="btn btn-secondary" data-dismiss="modal" type="button">
                                Cancel
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</template>

<script>
    import Sortable from 'sortablejs';
    import draggableHelper from 'draggable-helper';
    import {DraggableTree} from 'vue-draggable-nested-tree';
    import * as Ladda from 'ladda';

    export default {
        props: ['projectID', 'update_overview'],
        components: {
            Tree: DraggableTree,
            thy: draggableHelper,
            Sortable,
            Ladda
        },
        data() {
            return {
                baseUrl: window.location.origin,
                project_id: this.projectID,
                All_list: null,
                All_files: {
                    data: null,
                    last_page: null,
                    current_page: null,
                    first_page_url: null,
                    last_page_url: null,
                    next_page_url: null,
                    per_page: null,
                    prev_page_url: null,
                    total: null,
                    from: null,
                    to: null,
                },
                All_logs: {
                    data: null,
                    last_page: null,
                    current_page: null,
                    first_page_url: null,
                    last_page_url: null,
                    next_page_url: null,
                    per_page: null,
                    prev_page_url: null,
                    total: null,
                    from: null,
                    to: null,
                },
                All_comments: null,
                list: {
                    id: null,
                    name: null,
                    description: null,
                    nav_id: null,
                    type: null
                },
                per_page: 100,
                pages: [],
            }
        },
        mounted() {
            var _this = this;
            _this.getAllList();
            setTimeout(function () {
                var listWithHandle = document.getElementById('listWithHandle');
                Sortable.create(listWithHandle, {
                    handle: '.sort-trigger',
                    animation: 150,
                    onChange: function (e) {
                        // console.log(e.newIndex);
                    },
                    onEnd: function (evt) {
                        _this.DragDropAndSort(evt);
                    }
                });
                $('[data-toggle="tooltip"]').tooltip();
            }, 500)


        },
        created() {
            // var data = [];
            // var data2 = [];
            // var test = [...data, ...data2]
        },
        methods: {
            getAllList() {
                $('#loder-hide').fadeIn();
                var _this = this;
                axios.get('/api/project-overview/' + _this.project_id)
                    .then(response => response.data)
                    .then(response => {
                        var lists = response.data;
                        var new_list = [];

                        for (var i = 0; i < lists.length; i++) {
                            new_list = [...new_list, ...lists[i].all_list];
                        }
                        _this.All_list = new_list;
                        _this.All_list.sort((a, b) => (a.sort_id > b.sort_id) ? 1 : -1)

                        $('#loder-hide').fadeOut();
                        setTimeout(function () {
                            $('[data-toggle="tooltip"]').tooltip();
                        }, 500)
                    })
                    .catch(error => {
                        console.log('Api is drag and drop not Working !!!')
                    });
            },
            dataCollapse(id, list_id) {
                axios.post('/api/project-overview/list-open-close', {list_id: list_id})
                    .then(response => response.data)
                    .then(response => {
                        $(id).slideToggle();
                    })
                    .catch(error => {
                        console.log('Api is drag , drop and sort not Working !!!')
                    });
            },
            DragDropAndSort(e) {
                var data = [];
                var a = $('.overview-list');
                $.each(a, function (i, v) {
                    data[i] = $(v).attr('data-id')
                });
                axios.post('/api/project-overview/list-sort', {data: data})
                    .then(response => response.data)
                    .then(response => {
                    })
                    .catch(error => {
                        console.log('Api is drag , drop and sort not Working !!!')
                    });
            },
            EditList(list) {
                // this.$emit('EditListFromOverview', { list : list})
                this.list.id = list.id;
                this.list.name = list.list_title;
                this.list.description = list.description;
                this.list.nav_id = list.nav_id;
                this.list.type = 'list';
                $("#updateListBoardModelO").modal('show');
            },
            UpdateListOrBoard() {
                var _this = this;
                var l = Ladda.create(document.querySelector('.ladda_update_list_board'));
                l.start();
                axios.post('/api/board-list-update', _this.list)
                    .then(response => response.data)
                    .then(response => {
                        l.stop();
                        _this.getAllList();
                        $("#updateListBoardModelO").modal('hide');
                        this.$emit('updateLatestNav')
                    })
                    .catch(error => {
                        console.log('Add list api not working!!')
                    });
            },
            DeleteList(list) {
                var _this = this;
                swal({
                        title: "Are you sure?",
                        text: "If you delete this this then all task will delete !!!",
                        type: "warning",
                        showCancelButton: true,
                        confirmButtonClass: "btn-danger",
                        confirmButtonColor: "#e66983",
                        confirmButtonText: "Yes, Delete it!",
                        closeOnConfirm: false,
                        showLoaderOnConfirm: true
                    },
                    function () {
                        axios.post('/api/board-list-delete', {type: 'list', id: list.id, action: 'delete', overview: 0})
                            .then(response => response.data)
                            .then(response => {
                                swal("Complete!", "This List is deleted successfully !", "success");
                                _this.getAllList()
                                _this.$emit('updateLatestNav')
                                swal.close();
                            })
                            .catch(error => {
                                console.log('Add list api not working!!')
                            });
                    });
            },
            RestoreList(list_id) {
                var _this = this;
                swal({
                        title: "Are you sure?",
                        text: "If you want to restore this list !!!",
                        type: "warning",
                        showCancelButton: true,
                        confirmButtonClass: "btn-danger",
                        confirmButtonColor: "#e6c28e",
                        confirmButtonText: "Yes, Restore it!",
                        closeOnConfirm: false,
                        showLoaderOnConfirm: true
                    },
                    function () {
                        axios.post('/api/board-list-delete', {type: 'list', id: list_id, action: 'delete', overview: 2})
                            .then(response => response.data)
                            .then(response => {
                                swal("Complete!", "This List is restore successfully !", "success");
                                _this.getAllList()
                                _this.$emit('updateLatestNav')
                                swal.close();
                            })
                            .catch(error => {
                                console.log('Add list api not working!!')
                            });
                    });
            },
            GetAllFiles() {
                var _this = this;
                axios.get('/api/overview-all-files/' + _this.project_id)
                    .then(response => response.data)
                    .then(response => {
                        _this.All_files = response.files;
                        console.log(response.files);
                        setTimeout(function () {
                            $('[data-toggle="tooltip"]').tooltip();
                        }, 500)
                    })
                    .catch(error => {
                        console.log('Add files api not working!!')
                    });
            },
            FilePagination(page) {
                var _this = this;
                $('#loder-hide').fadeIn();
                _this.All_files.data = null;
                axios.get('/api/overview-all-files/' + _this.project_id + '?page=' + page)
                    .then(response => response.data)
                    .then(response => {
                        _this.All_files = response.files;
                        $('#loder-hide').fadeOut();
                        setTimeout(function () {
                            $('[data-toggle="tooltip"]').tooltip();
                        }, 500)
                    })
                    .catch(error => {
                        console.log('Add files api not working!!')
                    });

            },

            ShowList(id) {
                $('#list' + id).click();
            },
            GetAllComments() {
                var _this = this;
                axios.get('/api/overview-all-comments/' + _this.project_id)
                    .then(response => response.data)
                    .then(response => {
                        _this.All_comments = response.comments;
                        setTimeout(function () {
                            $('[data-toggle="tooltip"]').tooltip();
                        }, 500)
                    })
                    .catch(error => {
                        console.log('Add files api not working!!')
                    });
            },
            CommentPagination(page) {
                var _this = this;
                _this.All_files.data = null;
                axios.get('/api/overview-all-comments/' + _this.project_id + '?page=' + page)
                    .then(response => response.data)
                    .then(response => {
                        _this.All_files = response.files;
                        console.log(response.files);
                        setTimeout(function () {
                            $('[data-toggle="tooltip"]').tooltip();
                        }, 500)
                    })
                    .catch(error => {
                        console.log('Add files api not working!!')
                    });

            },
            GetAllLogs() {
                var _this = this;
                axios.get('/api/overview-all-logs/' + _this.project_id)
                    .then(response => response.data)
                    .then(response => {
                        _this.All_logs = response.logs;
                        _this.pages = [];
                        if (_this.All_logs.last_page > 5 && _this.All_logs.current_page > 2) {
                            _this.pages.push(_this.All_logs.current_page - 2)
                            _this.pages.push(_this.All_logs.current_page - 1)
                            _this.pages.push(_this.All_logs.current_page)
                            _this.pages.push(_this.All_logs.current_page + 1)
                            _this.pages.push(_this.All_logs.current_page + 2)
                        } else {
                            _this.pages = [1, 2, 3, 4, 5]
                        }
                        console.log(_this.pages)
                        setTimeout(function () {
                            $('[data-toggle="tooltip"]').tooltip();
                        }, 500)
                    })
                    .catch(error => {
                        console.log('Add files api not working!!')
                    });
            },
            LogPagination(page) {
                var _this = this;
                $('#loder-hide').fadeIn();
                // _this.All_logs.data = null;
                axios.get('/api/overview-all-logs/' + _this.project_id + '?page=' + page + '&per_page=' + _this.per_page)
                    .then(response => response.data)
                    .then(response => {
                        _this.All_logs = response.logs;
                        _this.pages = [];
                        if (_this.All_logs.last_page > 5 && _this.All_logs.current_page > 2 && _this.All_logs.last_page > _this.All_logs.current_page + 1) {
                            _this.pages.push(_this.All_logs.current_page - 2)
                            _this.pages.push(_this.All_logs.current_page - 1)
                            _this.pages.push(_this.All_logs.current_page)
                            _this.pages.push(_this.All_logs.current_page + 1)
                            _this.pages.push(_this.All_logs.current_page + 2)
                        }else if (_this.All_logs.last_page > 5 && _this.All_logs.current_page <= 2) {
                            _this.pages = [1, 2, 3, 4, 5]
                        }else if (_this.All_logs.last_page > 5){
                            _this.pages = [_this.All_logs.last_page - 4, _this.All_logs.last_page - 3, _this.All_logs.last_page - 2, _this.All_logs.last_page - 1, _this.All_logs.last_page]
                        }else {
                            _this.pages=[]
                            for (var i=1; i<=_this.All_logs.last_page;i++){
                                _this.pages.push(i);
                            }
                        }
                        $('#loder-hide').fadeOut();
                    })
                    .catch(error => {
                        console.log('Add files api not working!!')
                    });

            },
            setPerPageItem() {
                $('#loder-hide').fadeIn();
                this.LogPagination(1);
            },
            RestoreDeletedItem(id){
                var _this = this;
                swal({
                        title: "Are you sure?",
                        text: "If you want to Undo this delete action !!!",
                        type: "warning",
                        showCancelButton: true,
                        confirmButtonClass: "btn-danger",
                        confirmButtonColor: "#e6c28e",
                        confirmButtonText: "Yes, Restore it!",
                        closeOnConfirm: false,
                        showLoaderOnConfirm: true
                    },
                    function () {
                        axios.post('/api/overview-log/undo-action', {type: 'delete', id : id})
                            .then(response => response.data)
                            .then(response => {
                                // swal("Success!", "Undo successfully Done !", "success");
                                if (response.status){
                                    _this.GetAllLogs();
                                }

                                swal.close();
                            })
                            .catch(error => {
                                console.log('undo failed')
                            });
                    });


            }

        },
        watch: {
            update_overview: function (val) {
                this.getAllList();
                // this.update_overview = '';
            }
        }
    };
</script>
