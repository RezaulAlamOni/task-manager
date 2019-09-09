<template>
    <div class="card pt-0 pr-0">
        <div class="row page-titles">
            <div class="col-md-12 col-12 align-self-center">
                <ul class="nav" style="border-bottom: 1px solid #cedcc4">
                    <li class="nav-item">
                        <div class="btn-group">
                            <button type="button" class="btn dropdown-toggle deactiveIteam" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                List
                            </button>
                            <div class="dropdown-menu">
                                <span v-for="list in multiple_list">
                                     <router-link class="nav-link drop-item"
                                                  :to="{ name: 'project-dashboard', params: { projectId: projectId }}">{{list.list_title}}<i
                                         class="i-btn x20 task-complete icon-circle-o"></i></router-link>
                                </span>
                                <a class="dropdown-item" href="#"><i class="fa fa-plus"></i> Add List</a>
                            </div>
                        </div>
                    </li>
                    <li class="nav-item">
                        <div class="btn-group">
                            <button type="button" class="btn dropdown-toggle activeTask" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span>Board</span>
                            </button>
                            <div class="dropdown-menu">
                                <a class="dropdown-item" href="#">Action</a>
                                <router-link class="nav-link" :to="{ name: 'project-board', params: { projectId: projectId }}">
                                    Board <i
                                    class="tree-toggle i-btn x30"></i>
                                </router-link>
                                <a class="dropdown-item" href="#"><i class="fa fa-plus"></i> Add Board</a>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>

            <div class="input-group col-sm-3 searchList">
                <input type="text" class="form-control searchTaskList" id="searchTaskList" placeholder="Search task"
                       name="search">
                <div class="input-group-btn searchClick" id="searchClick">
                    <button class="btn btn-default" type="submit"><i class="fa fa-search"></i></button>
                </div>
            </div>

        </div>

        <div id="board_view_list">
            <div class="col-12" id="col10" style="border: none">
                <div class="card-scene">
                    <p><a href="#" @click="addColumn"><i class="fa fa-plus"></i> Add Column</a></p>
                    <Container
                            orientation="horizontal"
                            @drop="onColumnDrop($event)"
                            drag-handle-selector=".column-drag-handle"
                            @drag-start="dragStart"
                            :drop-placeholder="upperDropPlaceholderOptions"
                    >
                        <Draggable v-for="(column , index ) in scene.children" :key="column.id">
                            <div class="hidden-column" v-if="column.hidden">
                                <div class="card-column-header">
                                    <span class="column-drag-handle" @click="showColumn(index)">&#8667;</span>
                                </div>
                            </div>
                            <div :class="column.props.className" v-else>
                                <div class="card-column-header">
                                    <span class="column-drag-handle">&#x2630;</span>
                                    <span class="col_name">{{ column.name }}</span> <span class="total-task">{{column.children.length}}</span>
                                    <span class="pull-right">
                                        <span>
                                            <span class="dropdown-toggle-split col-md-12 opacity" data-toggle="dropdown">
                                                <i class="fa fa-plus"></i>
                                            </span>
                                            <div class="dropdown-menu">
                                                <diV class="collapse show switchToggle">
                                                    <a class="dropdown-item" style="cursor:pointer;" href="#" @click="addExistingTask(index)">Add existing tasks</a>
                                                    <a class="dropdown-item" style="cursor:pointer;"
                                                       @click="addCard(index)">Create new tasks</a>
                                                </diV>
                                            </div>
                                        </span>

                                        <span>
                                            <span class="dropdown-toggle-split col-md-12 opacity" data-toggle="dropdown">
                                                <i class="fa fa-ellipsis-h"></i>
                                            </span>
                                            <div class="dropdown-menu">
                                                <diV class="collapse show switchToggle">
                                                    <a class="dropdown-item" href="#" @click="updateColumSow(index)"><i
                                                            class="fa fa-edit opacity"></i> Edit column</a>
                                                    <a class="dropdown-item" href="#" @click="hideColumn(index)"><i
                                                            class="fa fa-angle-double-left opacity" ></i> Hide column</a>
                                                    <div class="dropdown-divider"></div>
                                                    <a class="dropdown-item" href="#" @click="deleteColumnCards(index)">
                                                        <i class="fa fa-trash opacity"></i> Peekaboo all tasks in this column</a>
                                                    <div class="dropdown-divider"></div>
                                                    <a class="dropdown-item" href="#" @click="deleteColumn(index)"><i
                                                            class="fa fa-trash opacity"></i> Delete column</a>
                                                </diV>
                                            </div>
                                        </span>
                                    </span>
                                </div>
                                <Container
                                        group-name="col"
                                        @drop="(e) => onCardDrop(column.id, e)"
                                        @drag-start="(e) => log('drag start', e)"
                                        @drag-end="(e) => log('drag end', e)"
                                        :get-child-payload="getCardPayload(column.id)"
                                        drag-class="card-ghost"
                                        drop-class="card-ghost-drop"
                                        :drop-placeholder="dropPlaceholderOptions"
                                >
                                    <Draggable v-for="(card , key) in column.children" :key="card.id">
                                        <div class="card-list" :class="card.props.className" :style="card.props.style">
                                            <textarea
                                                      type="text" v-model="card.data"
                                                      @focus="hideItem($event)"
                                                      @blur="showItem($event,card.data,index,key)"
                                                      data-grow="auto"
                                                      :id="'id'+index+key"
                                                      class="inp input-hide text-area" @click="makeInput($event)">
                                            </textarea>
                                            <!--                                            <p>{{ card.data }}</p>-->
                                            <div>
                                                <a class="calender li-opacity clickHide" v-if="!card.date">
                                                    <i class="outline-event icon-image-preview" title="toggle"
                                                       data-toggle></i>
                                                </a>
                                                <flatPickr
                                                        v-model="card.date"
                                                        :config="date_config"
                                                        :class="{
                                                                    dateCal:true,
                                                                    'flatpickr-input': true,
                                                                    'flatpickr-input1': card.date != '' ? false : true,
                                                                     active: true,
                                                                     dateCal1: card.date != '' ? true : false
                                                                 }"
                                                        name="date">
                                                </flatPickr>
                                            </div>
                                            <div class="user">
                                                <a><i class="outline-person icon-image-preview li-opacity dropdown-toggle-split"
                                                      data-toggle="dropdown"></i>
                                                    <div class="dropdown-menu dropdown-menu-left">
                                                        <diV class="collapse show switchToggle">
                                                            <li class="assignUser">
                                                                <input type="text" class="input-group searchUser"
                                                                       placeholder="Set assignee by name and email">
                                                                <label class="pl-2 ">
                                                                    <small style="font-size: 12px">Or invite a new
                                                                        member by
                                                                        email address
                                                                    </small>
                                                                </label>
                                                            </li>
                                                            <li class="assignUser">
                                                                <div class="row">
                                                                    <div class="col-md-3 pt-2 pl-5">
                                                                        <img src="data:image/jpeg;base64,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"
                                                                             class="img-circle" style="width: 30px"
                                                                             alt="not found">
                                                                    </div>
                                                                    <div class="col-md-9">
                                                                        <h5>Haassa Jklcsad <br>
                                                                            <small>dasda@gad.con</small>
                                                                        </h5>
                                                                    </div>

                                                                </div>
                                                                <div class="row">
                                                                    <div class="col-md-3 pt-2 pl-5">
                                                                        <img src="data:image/jpeg;base64,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"
                                                                             class="img-circle" style="width: 30px"
                                                                             alt="not found">
                                                                    </div>
                                                                    <div class="col-md-9">
                                                                        <h5>jakibul Nahid<br>
                                                                            <small>asdsafa@opo.con</small>
                                                                        </h5>
                                                                    </div>
                                                                </div>
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
                                            <a class="tag-icon ">
                                                <div v-if="card.tags && card.tags.length !== 0">
                                                    <div v-for="item in card.tags">
                                                        <div class="dropdown-toggle-split " data-toggle="dropdown">
                                                            <span class="badge badge-danger "  v-if='item == "Dont Forget"'>{{item.substring(0,12)}}</span>
                                                            <span class="badge badge-success " v-else>{{item.substring(0,10)}}..</span>
                                                        </div>

                                                        <div class="dropdown-menu dropdown-menu1 dropdown-menu-left" :id="'dropdown'+index+key">
                                                            <diV class="collapse show switchToggle" style="">
                                                                <li class="assignUser">
                                                                    <input
                                                                            type="text"
                                                                            class="input-group searchUser"
                                                                            v-model="tag"
                                                                            @keypress="addTag($event,index,key)"

                                                                    >
                                                                    <label class="pl-2 pt-3">
                                                                        <span class="badge badge-success" @click="addExistingTag(index,key,'Tags')">Tags</span>
                                                                        <span class="badge badge-danger" @click="addExistingTag(index,key,'Dont Forget')">Dont Forget</span>
                                                                    </label>
                                                                </li>
                                                            </diV>
                                                        </div>
                                                    </div>
                                                </div>

                                                <i v-else
                                                   class="outline-local_offer icon-image-preview dropdown-toggle-split li-opacity"
                                                   data-toggle="dropdown"></i>
                                                <div class="dropdown-menu dropdown-menu1 dropdown-menu-left" >
                                                    <diV class="collapse show switchToggle" style="">
                                                        <li class="assignUser">
                                                            <input
                                                                    type="text"
                                                                    class="input-group searchUser"
                                                                    v-model="tag"
                                                                    @keypress="addTag($event,index,key)"

                                                            >
                                                            <label class="pl-2 pt-3">
                                                                <span class="badge badge-success">Tags</span>
                                                                <span class="badge badge-danger">Dont Forget</span>
                                                            </label>
                                                        </li>
                                                    </diV>
                                                </div>

                                            </a>
                                        </div>
                                    </Draggable>
                                </Container>
                            </div>
                        </Draggable>
                        <div>
                            <p class="add-column" @click="addColumn"><i class="fa fa-plus"></i> add column</p>
                        </div>
                    </Container>
                </div>
            </div>
        </div>

        <div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
             aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Add column</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p>You need to set a progress and color for the new column.</p>
                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label">Name</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" v-model="addField.name">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label">Color</label>
                            <div class="col-sm-10">
                                <input type="color" class="form-control" v-model="addField.color">
                            </div>
                        </div>
                        <p v-if="addField.error" class="text-danger">{{addField.error}}</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" @click="setColumn()">Add</button>
                        <button type="button" class="btn btn-secondary" @click="clearInputFeild()">Cancel</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="EditModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
             aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Update column</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p>You need to set a progress and color for the new column.</p>
                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label">Name</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" v-model="addField.name">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label">Color</label>
                            <div class="col-sm-10">
                                <input type="color" class="form-control" v-model="addField.color">
                            </div>
                        </div>
                        <p v-if="addField.error" class="text-danger">{{addField.error}}</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" @click="updateColumn">Update</button>
                        <button type="button" class="btn btn-secondary" @click="clearInputFeild">Cancel</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="addExistingTask" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
             aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document" >
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Add Exixting Task</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body list-model">
                        <ul class="list-group list-group-flush">
                            <div  v-for="tree in tree4data">
                                <li class="list-group-item">
                                    <input type="checkbox" :value="tree.text" v-model="selectedExistedTask"  :id="tree.id" > {{tree.text}}
                                    <ul class="list-group list-group-flush" v-if="tree.children">
                                        <div  v-for="child in tree.children">
                                            <li class="list-group-item">
                                                <input type="checkbox" class="tree-child" :value="child.text" v-model="selectedExistedTask"  :id="child.id" > {{child.text}}
                                                <ul class="list-group list-group-flush" v-if="child.children">
                                                    <div  v-for="child1 in child.children">
                                                        <li class="list-group-item">
                                                            <input type="checkbox" class="tree-child" :value="child1.text" v-model="selectedExistedTask"  :id="child1.id" > {{child1.text}}
                                                            <ul class="list-group list-group-flush" v-if="child1.children">
                                                                <div  v-for="child2 in child1.children">
                                                                    <li class="list-group-item">
                                                                        <input type="checkbox" class="tree-child" :value="child2.text" v-model="selectedExistedTask"  :id="child2.id" > {{child2.text}}
                                                                        <ul class="list-group list-group-flush" v-if="child2.children">
                                                                            <div  v-for="child3 in child2.children">
                                                                                <li class="list-group-item">
                                                                                    <input type="checkbox" class="tree-child" :value="child3.text" v-model="selectedExistedTask"  :id="child3.id" > {{child3.text}}
                                                                                </li>
                                                                            </div>
                                                                        </ul>
                                                                    </li>
                                                                </div>
                                                            </ul>
                                                        </li>
                                                    </div>
                                                </ul>
                                            </li>
                                        </div>
                                    </ul>
                                </li>
                            </div>
                        </ul>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" @click="AddExistingTasks">Add Tasks</button>
                        <button type="button" class="btn btn-secondary" @click="clearInputFeild">Cancel</button>
                    </div>
                </div>
            </div>
        </div>

    </div>

</template>
<style>
    .card{
        padding-top: 0 !important;
        margin-top: 0 !important;
    }
</style>
<script>
    import flatPickr from 'vue-flatpickr-component';
    import 'flatpickr/dist/flatpickr.css';
    import switches from 'vue-switches';
    import hotkeys from 'hotkeys-js';
    import ClickOutside from 'vue-click-outside';

    import {Container, Draggable} from 'vue-smooth-dnd';
    // import {applyDrag, generateItems} from '../../../plugins/utils/helpers';
    import {applyDrag, generateItems} from '../../../assets/plugins/utils/helpers';

    export default {
        components: {Container, Draggable, flatPickr, switches},
        data() {
            return {
                id: 0,
                addField: {
                    name: null,
                    color: null,
                    error: null
                },
                date_config: {
                    enableTime: false,
                    wrap: true,
                    disableMobile: true,
                    dateFormat: 'd M',
                },
                project: null,
                tree4data: [
                    {
                        id: 1,
                        parent: 0,
                        text: "Don't Forget Section",
                        clicked: 0,
                        date: '',
                        tags: ["Dont Forget"],
                        children: [
                            {
                                id: 2, parent: 1,
                                text: 'node 1-1',
                                html: 'Atik',
                                tags: ["Dont Forget"],
                                files: [{file: '/images/logo.png'}],
                                clicked: 0
                            },
                            {
                                id: 3, parent: 1,
                                text: 'node 1-2', clicked: 0, tags: ["Dont Forget"], children: [
                                    {
                                        id: 4,
                                        parent: 3,
                                        text: 'node 1-2-1',
                                        date: '10 Aug',
                                        tags: ["Dont Forget"],
                                        clicked: 0
                                    },
                                    {
                                        id: 5,
                                        parent: 3,
                                        text: 'node 1-2-2',
                                        date: '25 Aug',
                                        tags: ["Dont Forget"],
                                        clicked: 0
                                    },
                                ]
                            },
                        ]
                    },
                    {
                        id: 6, parent: 0,
                        text: 'node 2',
                        html: 'Test 1',
                        date: '05 Aug',
                        clicked: 0,
                        tags: ['Important'],
                        assigned_user: {name: 0, picture: 0}
                    },
                    {
                        id: 7, parent: 0,
                        text: 'node 3',
                        html: 'oni',
                        clicked: 0,
                        tags: ['Important'],
                        assigned_user: {name: 0, picture: 0}
                    },
                    {id: 8, parent: 3, text: 'node 4', draggable: true, html: 'Test', date: '6 Aug', clicked: 0},
                    {id: 9, parent: 3, text: 'node 5', date: '15 Aug', html: '251  41', clicked: 0, tags: ['Tags']},
                    {id: 10, parent: 3, text: 'node 6', droppable: false, date: '19 Aug', clicked: 0},
                    {
                        id: 11, parent: 10,
                        text: 'node 7', clicked: 0, date: '', children: [
                            {id: 12, parent: 11, text: 'node 7-1', html: 'Atik', clicked: 0},
                            {
                                id: 13, parent: 11,
                                text: 'node 7-2', clicked: 0, children: [
                                    {id: 14, parent: 13, text: 'node 7-2-1', date: '10 Aug', clicked: 0},
                                    {id: 15, parent: 13, text: 'node 7-2-2', date: '25 Aug', clicked: 0},
                                ]
                            },
                            {
                                id: 16, parent: 10,
                                text: 'node 7-3', children: [
                                    {id: 17, parent: 16, text: 'node 7-3-1', clicked: 0},
                                    {id: 18, parent: 16, text: 'node 7-3-2 undroppable', droppable: false, clicked: 0},
                                ], clicked: 0
                            },
                            {id: 19, parent: 10, text: 'node 7-4', clicked: 0},
                            {id: 20, parent: 10, text: 'node 7-5', clicked: 0},
                            {id: 21, parent: 10, text: 'node 7-6', clicked: 0},
                        ]
                    },
                ],
                cards: [
                    {
                        column: 'To Do',
                        task: [
                            {name: 'node 1-1-1 sdf a srsdfgs df gsdf', date: '10 Aug', tags: ["Nothing"], clicked: 0},
                            {name: 'node 1-1-2', date: '25 Aug', tags: ["Dont Forget"], clicked: 0},
                        ],
                        hidden : 0
                    },
                    {
                        column: 'In Progress',
                        task: [
                            {name: 'node 1-2-1', date: '10 Aug', tags: ["Do First"], clicked: 0},
                            {name: 'node 1-2-2', date: '25 Aug', tags: ["Dont Forget"], clicked: 0},
                            {name: 'node 1-2-3', date: '', tags: ["important"], clicked: 0},
                            {name: 'node 1-2-4', date: '25 Aug', tags: [], clicked: 0},
                        ],
                        hidden : 1
                    },
                    {
                        column: 'Complete',
                        task: [
                            {name: 'node 1-3-1', date: '10 Aug', tags: ["new"], clicked: 0},
                            {name: 'node 1-3-2', date: '25 Aug', tags: ["Dst"], clicked: 0},
                            {name: 'node 1-3-3', date: '25 Aug', tags: ["Dont Forget"], clicked: 0},
                        ],
                        hidden : 0
                    }
                ],
                scene: {},
                upperDropPlaceholderOptions: {
                    className: 'cards-drop-preview',
                    animationDuration: '150',
                    showOnTop: true
                },
                dropPlaceholderOptions: {
                    className: 'drop-preview',
                    animationDuration: '150',
                    showOnTop: true
                },
                updateIndex: null,
                tag: null,
                selectedExistedTask : [],
                projectId : null,
                multiple_list : null
            }
        },
        mounted() {
            $('#header-item').text('Project  / Task Board')
            this.projectId = this.$route.params.projectId;
            $(document).ready(function () {
                $(function () {
                    $('[data-toggle="popover"]').popover()
                })
                $("#popoverData").popover({trigger: "hover"});
            });
            this.getData();
            this.projectId = this.$route.params.projectId;
            this.getProjects();
            $(document).ready(function () {
                $('.searchList').hide();
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

            getProjects() {
                axios.get('/api/project/' + this.projectId)
                    .then(response => response.data)
                    .then(response => {
                        this.project = response.project;
                        this.multiple_list = response.multiple_list;
                        $('#header-item').text(this.project.name + ' / Task Board')
                        console.log(this.multiple_list)
                    })
                    .catch(error => {
                    });
            },
            getData() {
                this.scene = {
                    type: 'container',
                    props: {
                        orientation: 'horizontal'
                    },
                    children: generateItems(this.cards.length, i => ({
                        id: `column${i}`,
                        type: 'container',
                        name: this.cards[i].column,
                        props: {
                            orientation: 'vertical',
                            className: 'card-container'
                        },
                        hidden : this.cards[i].hidden,
                        children: generateItems(this.cards[i].task.length, j => ({
                            type: 'draggable',
                            id: `${i}${j}`,
                            props: {
                                className: 'card',
                                style: {backgroundColor: 'white'}
                            },
                            data: this.cards[i].task[j].name,
                            date: this.cards[i].task[j].date,
                            tags: this.cards[i].task[j].tags
                        }))
                    })),
                }

                console.log(this.scene);
            },

            onColumnDrop(dropResult) {
                const scene = Object.assign({}, this.scene)
                scene.children = applyDrag(scene.children, dropResult)
                this.scene = scene
            },
            onCardDrop(columnId, dropResult) {
                if (dropResult.removedIndex !== null || dropResult.addedIndex !== null) {
                    const scene = Object.assign({}, this.scene)
                    const column = scene.children.filter(p => p.id === columnId)[0]
                    const columnIndex = scene.children.indexOf(column)
                    const newColumn = Object.assign({}, column)
                    newColumn.children = applyDrag(newColumn.children, dropResult)
                    scene.children.splice(columnIndex, 1, newColumn)
                    this.scene = scene
                }
            },
            getCardPayload(columnId) {
                return index => {
                    return this.scene.children.filter(p => p.id === columnId)[0].children[index]
                }
            },
            dragStart() {
                console.log('drag started')
            },
            log(...params) {
                console.log(...params)
            },
            addColumn() {
                $("#addModal").modal('show');
            },
            setColumn() {
                if (!this.addField.name) {
                    this.addField.error = 'Name is required!';
                } else {
                    $("#addModal").modal('hide');
                    this.cards.push({
                        column: this.addField.name,
                        task: [{name: '', date: '', tags: [], clicked: 0}]
                    });

                    this.getData();
                    this.addField = {};
                    console.log(this.cards)
                }
            },
            updateColumSow(index) {
                this.updateIndex = index;
                this.addField.name = this.cards[this.updateIndex].column;
                $("#EditModal").modal('show');
            },
            updateColumn() {
                if (!this.addField.name) {
                    this.addField.error = 'Name is required!';
                } else {
                    $("#EditModal").modal('hide');
                    this.cards[this.updateIndex].column = this.addField.name;
                    this.getData();
                    this.addField = {};
                }
            },
            addExistingTask(index){
                this.updateIndex = index;
                $("#addExistingTask").modal('show');
            },

            clearInputFeild() {
                $("#EditModal").modal('hide');
                $("#addModal").modal('hide');
                $("#addExistingTask").modal('hide');
                this.addField = {};
            },
            AddExistingTasks(){
                let total = this.selectedExistedTask.length;

                for (var i =0; i<total; i++) {
                    this.cards[this.updateIndex].task.push({name: this.selectedExistedTask[i], date: '', tags: [], clicked: 0})
                }
                this.getData()
                this.updateIndex = null;
                this.selectedExistedTask = [];
                $("#addExistingTask").modal('hide');
            },
            makeInput(e) {
                let _this = this;

                $('.inp').addClass('input-hide');
                $('.inp').removeClass('form-control');
                $(e.target).removeClass('input-hide');
                $(e.target).addClass('form-control');

                var option = {
                    height: 50,
                    maxHeight: 200
                };
                _this.growInit(option);
            },
            addCard(index) {
                this.cards[index].task.push({name: '', date: '', tags: [], clicked: 0});
                let key = this.cards[index].task.length-1;
                this.getData();
                setTimeout(function () {
                    $('#id'+index+key).click();
                    $('#id'+index+key).focus();
                },100)
            },
            saveData(data,index,child_key) {
                if (!data) {
                    // alert('Title is required!');
                } else {
                    this.cards[index].task[child_key].name = data;
                    this.getData();
                }
            },
            addTag(e,index,key){
                if (e.which === 13) {
                    // this.cards[index].task[key].tags.push(this.tag);
                    this.cards[index].task[key].tags.splice(0,1,this.tag);
                    this.tag = null;
                    // $('#dropdown'+index+key).toggle();
                }
            },
            addExistingTag(index,key,tag){
                this.cards[index].task[key].tags.splice(0,1,tag);
                // $('#dropdown'+index+key).toggle();
            },
            deleteColumn(index) {
                let _this = this;
                if (confirm('Are you sure tou want to delete this board?')) {
                    _this.cards.splice(index, 1)
                    _this.getData();
                }
            },
            deleteColumnCards(index) {
                let _this = this;
                if (confirm('Are you sure tou want to delete all cards from this board?')) {
                    _this.cards[index].task = [];
                    _this.getData();
                }
            },
            hideItem(index) {

            },
            hideColumn(index) {
                this.cards[index].hidden = 1;
                this.getData();
            },
            showColumn(index){
                this.cards[index].hidden = 0;
                this.getData();
            },
            showItem(e,data,index,child_key) {
                $('.inp').addClass('input-hide');
                $('.inp').removeClass('form-control');
                this.saveData(data,index,child_key)
            },
        },
        directives: {
            ClickOutside
        },
        watch: {
            filterProjectForm: {
                handler(val) {
                    this.getProjects();
                },
                deep: true
            }
        }
    }


</script>
