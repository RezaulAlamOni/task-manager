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
                    <div class="card overview-list">
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
                                            <a href="#" class="dropdown-item">
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
                        <div :id="'collapse'+list.id" class="collapse show multi-collapse" aria-labelledby="headingOne"
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
                                                    <img :src="baseUrl+'/img/'+data.progress+'.png'" alt="" height="28" width="28"
                                                         v-if="(data.progress !== null)"
                                                         class="task-complete-progress left-content">

                                                    <img :src="baseUrl+'/img/'+data.progress+'.png'" alt="" height="28" width="28"
                                                         v-if="data.progress === '100'"
                                                         :title="(data.complete_tooltip !== null) ? data.complete_tooltip : 'Complete'"
                                                         data-toggle="tooltip"
                                                         class="task-complete left-content li-opacity ">

                                                    <img :src="baseUrl+'/img/task-icon/circle-check.png'" alt="" height="28" width="28"
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


        </div>
    </main>
</template>

<script>
    import Sortable from 'sortablejs';
    import draggableHelper from 'draggable-helper';
    import {DraggableTree} from 'vue-draggable-nested-tree';

    export default {
        props: ['projectID'],
        components: {
            Tree: DraggableTree,
            thy: draggableHelper,
            Sortable
        },
        data() {
            return {
                baseUrl: window.location.origin,
                project_id: this.projectID,
                All_list: null,
            }
        },
        mounted() {
            var _this = this;
            _this.getAllList();
            setTimeout(function () {
                var listWithHandle = document.getElementById('listWithHandle');
                Sortable.create(listWithHandle, {
                    handle: '.sort-trigger',
                    animation: 150
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
                        for (var i = 0; i < lists.length; i++) {
                            new_list = [...new_list, ...lists[i].all_list];
                        }
                        _this.All_list = new_list;
                        console.log(new_list)
                    })
                    .catch(error => {
                        console.log('Api is drag and drop not Working !!!')
                    });
            },
            dataCollapse(id) {
                $(id).slideToggle();
            }
        }
    };
</script>
