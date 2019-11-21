<template>
    <main>
        <div class="container">
            <!--            <div class="row">-->
            <!--                <div class="col-md-12">-->
            <!--                    <h2 style="margin: 20px;">This is the list name-->
            <!--                        <span style="float: right;">-->
            <!--                            <span data-toggle="dropdown" class="dropdown-toggle-split col-md-12 opacity">-->
            <!--                                <i class="fa fa-ellipsis-h"></i>-->
            <!--                            </span>-->
            <!--                            <div class="dropdown-menu">-->
            <!--                                <div class="collapse show switchToggle">-->
            <!--                                    <a href="#" class="dropdown-item"><i class="fa fa-edit opacity"></i> Add to list/board</a>-->
            <!--                                    <a href="#" class="dropdown-item"><i class="fa fa-edit opacity"></i> Create List</a>-->
            <!--                                    <a href="#" class="dropdown-item"><i class="fa fa-angle-double-left opacity"></i> Delete</a>-->
            <!--                                </div>-->
            <!--                            </div>-->
            <!--                        </span>-->
            <!--                    </h2>-->
            <!--                </div>-->
            <!--            </div>-->
            <!--            <div class="row">-->
            <!--                <div class="col-md-12">-->
            <!--                    <img src="/img/taskimg.png" class="img-responsive">-->
            <!--                </div>-->
            <!--            </div>-->

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
                                    @click="dataCollapse('#collapse'+list.id)" data-toggle="tooltip"
                                >
                                    {{list.list_title}}
                                </h2>
                                <span class="option-btn">
                                    <span data-toggle="dropdown" class="dropdown-toggle-split col-md-12 opacity">
                                        <i class="fa fa-ellipsis-h"></i>
                                    </span>
                                    <div class="dropdown-menu overview-dropdown dropdown-menu-right">
                                        <div class="collapse show switchToggle">
                                            <a href="#" class="dropdown-item">
                                                <img data-v-0ca4b43b="" src="/img/task-icon/edit.png" alt=""
                                                     height="17px" width="17px" class="mr-2">
                                                Add to list/board
                                            </a>
                                            <a href="#" class="dropdown-item" @click="EditList(list)">
                                                <img data-v-0ca4b43b="" src="/img/task-icon/edit.png" alt=""
                                                     height="17px" width="17px" class="mr-2">
                                                Edit
                                            </a>
                                            <a href="#" class="dropdown-item">
                                                <img data-v-0ca4b43b="" src="http://taskspark/img/task-icon/trash.png"
                                                     alt="" height="20px" width="20px" class="mr-2">
                                                Delete
                                            </a>
                                        </div>
                                    </div>
                                </span>
                            </div>
                            <div class="col-md-12">
                                <!--                                <span>Description :</span>-->
                                <p>
                                    {{list.description}}
                                </p>
                            </div>
                        </div>
                        <div :id="'collapse'+list.id" class="collapse show multi-collapse " aria-labelledby="headingOne"
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
                                                <span class="progress-bar-custom">
                                                    <img :src="baseUrl+'/img/'+data.progress+'.png'" alt="" height="28"
                                                         width="28"
                                                         v-if="(data.progress !== null)"
                                                         class="task-complete-progress left-content">

                                                    <img :src="baseUrl+'/img/'+data.progress+'.png'" alt="" height="28"
                                                         width="28"
                                                         v-if="data.progress === '100'"
                                                         :title="(data.complete_tooltip !== null) ? data.complete_tooltip : 'Complete'"
                                                         data-toggle="tooltip"
                                                         class="task-complete left-content li-opacity ">

                                                    <img :src="baseUrl+'/img/task-icon/circle-check.png'" alt=""
                                                         height="28" width="28"
                                                         v-else
                                                         :title="(data.complete_tooltip !== '') ? data.complete_tooltip : 'Complete'"
                                                         data-toggle="tooltip"
                                                         class="task-complete left-content li-opacity">
                                                </span>
                                                    <!--                                                    <b v-if="data.children && data.children.length && data.open">-->
                                                    <!--                                                        <i class="fa fa-fw fa-minus"></i></b>-->
                                                    <!--                                                    <b v-else-if="data.children && data.children.length && !data.open">-->
                                                    <!--                                                        <i class="fa fa-fw fa-plus"></i></b>-->
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
        </div>
    </main>
</template>

<script>
    import Sortable from 'sortablejs';
    import draggableHelper from 'draggable-helper';
    import {DraggableTree} from 'vue-draggable-nested-tree';
    import * as Ladda from 'ladda';

    export default {
        props: ['projectID'],
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
                All_list : null,
                list: {
                    id: null,
                    name: null,
                    description: null,
                    nav_id: null,
                    type: null
                },
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
                var _this = this;
                axios.get('/api/project-overview/' + _this.project_id)
                    .then(response => response.data)
                    .then(response => {
                        var lists = response.data;
                        var new_list = [];
                        // setTimeout(function () {
                            for (var i = 0; i < lists.length; i++) {
                                new_list = [...new_list, ...lists[i].all_list];
                            }
                            _this.All_list = new_list;
                            _this.All_list.sort((a, b) => (a.sort_id > b.sort_id) ? 1 : -1)
                            console.log(_this.All_list)
                        // },100)

                    })
                    .catch(error => {
                        console.log('Api is drag and drop not Working !!!')
                    });
            },
            dataCollapse(id) {
                $(id).slideToggle();
            },
            DragDropAndSort(e) {
                var data = [];
                var a = $('.overview-list');
                $.each(a, function(i,v){
                    data[i] = $(v).attr('data-id')
                });
                axios.post('/api/project-overview/list-sort' , {data : data})
                    .then(response => response.data)
                    .then(response => {
                    })
                    .catch(error => {
                        console.log('Api is drag , drop and sort not Working !!!')
                    });
            },
            EditList(list){
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
                    })
                    .catch(error => {
                        console.log('Add list api not working!!')
                    });
            },

        }
    };  
</script>
