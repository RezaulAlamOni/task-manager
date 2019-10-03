<template>
    <div>

        <div id="board_view_list">
            <div class="col-12" id="col10" style="border: none">
                <div class="card-scene">
                    <Container
                            orientation="horizontal"
                            @drop="onColumnDrop($event)"
                            drag-handle-selector=".column-drag-handle"
                            @drag-start="dragStart"
                            :drop-placeholder="upperDropPlaceholderOptions">
                        <Draggable v-for="(column , index ) in scene.children" :key="column.id">
                            <div class="hidden-column" v-if="column.hidden">
                                <div class="card-column-header">
                                    <span class="column-drag-handle" @click="showColumn(index, column.boardId)">&#8667;</span>
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
                                                    <a class="dropdown-item" href="javascript:void(0)" @click="addExistingTask(index)">Add existing tasks</a>
                                                    <a class="dropdown-item" href="javascript:void(0)"
                                                       @click="addCard(index,column.boardId)">Create new tasks</a>
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
                                                    <a class="dropdown-item" href="#" @click="hideColumn(index, column.boardId)"><i
                                                            class="fa fa-angle-double-left opacity" ></i> Hide column</a>
                                                    <div class="dropdown-divider"></div>
                                                    <a class="dropdown-item" href="#" @click="deleteColumnCards(index, column.boardId)">
                                                        <i class="fa fa-trash opacity"></i> Peekaboo all tasks in this column</a>
                                                    <div class="dropdown-divider"></div>
                                                    <a class="dropdown-item" href="#" @click="deleteColumn(index,column.boardId)"><i
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
                                                    @blur="showItem($event,card,index,key)"
                                                    data-grow="auto"
                                                    :id="'id'+index+key"
                                                    class="inp input-hide text-area" @click="makeInput($event)">
                                            </textarea>
                                            <div>
                                                <a class="calender li-opacity clickHide" v-if="!card.date">
                                                    <i class="outline-event icon-image-preview" title="toggle"
                                                       data-toggle></i>
                                                </a>
                                                <flatPickr
                                                        v-model="card.date"
                                                        :config="date_config"
                                                        @on-change="updateDate(card)"
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
                                            <div style="position: absolute; right: 160px; bottom: 8px; opacity: 0.25">
                                                <a @click="deleteCard(index, key, card.cardId)">
                                                     <i class="baseline-playlist_delete icon-image-preview"></i>
                                                </a>
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
                                                                        <img src=""
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
                                                                        <img src=""
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
                                                                      style="position:absolute; right: 10px;bottom: -4px"
                                                                      theme="bootstrap"
                                                                      color="success">
                                                            </switches>
                                                        </li>
                                                    </div>
                                                </a>
                                            </div>

                                            <div>
                                                <a class="tag-icon">
                                                    <div v-if="card.tags && card.tags.length !== 0">
                                                        <div v-for="(item, index) in card.tags" style="float: left;">
                                                            <div class="dropdown-toggle-split " style="padding-right: 0px; padding-left: 1px;" data-toggle="dropdown" v-if="index < 2" >
                                                                <span class="badge badge-danger "  v-if='item == "Dont Forget"'>{{item.text.substring(0,12)}}</span>
                                                                <span class="badge badge-success "
                                                                        data-toggle="tooltip" 
                                                                        data-placement="bottom" 
                                                                        :title="card.tagTooltip" 
                                                                        v-else
                                                                        v-bind:style="[{'background': item.color },{'margin-left' : 1 +'px'}]"
                                                                >{{item.text.substring(0,10)}}..</span>
                                                            </div>

                                                            <div class="dropdown-menu dropdown-menu1 dropdown-menu-left" :id="'dropdown'+card.id">

                                                                <diV class="collapse show switchToggle" style="">
                                                                    <div class="container-fluid">
                                                                        <vue-tags-input
                                                                            v-model="tag"
                                                                            :tags="card.tags"
                                                                            :allow-edit-tags="true"
                                                                            @tags-changed="newTags => (changeTag(newTags,card,index,key))"
                                                                            @before-deleting-tag="deleteTag => deleteCardTag(deleteTag,card,index,key)"
                                                                        />
                                                                            <div class="row">
                                                                                <div class="col-12">
                                                                                    <template>
                                                                                        <!-- <li class="badge-pill tags"
                                                                                            v-bind:style="[{'background': item.color },{'margin-left' : 1 +'px'}]">
                                                                                        </li> -->
                                                                                    </template>
                                                                                    <!-- <li class="badge-pill tags" style="background: #FB8678" > Dont Forget </li> -->
                                                                                </div>
                                                                            </div>
                                                                            <hr>
                                                                            <div class="col-xs-12" style="margin-top:10px;width: 100%;">
                                                                                <button type="submit" class="btn btn-small btn-primary pull-right">Manage Tag</button>
                                                                            </div>
                                                                    </div>

                                                                </diV>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <i v-else class="outline-local_offer icon-image-preview dropdown-toggle-split li-opacity" data-toggle="dropdown"></i>
                                                    <div class="dropdown-menu dropdown-menu1 dropdown-menu-left">

                                                        <diV class="collapse show switchToggle" style="">
                                                            <div class="container-fluid">
                                                                <vue-tags-input
                                                                    v-model="tag"
                                                                    :tags="tag1"
                                                                    :allow-edit-tags="true"
                                                                    @tags-changed="newTags => (changeTag(newTags,card,index,key))"
                                                                    @before-deleting-tag="deleteTag => deleteCardTag(deleteTag,card,index,key)"
                                                                />
                                                                    <!-- <div class="row"> -->
                                                                        <!-- <div class="col-12"> -->
                                                                            <!-- <template> -->
                                                                                <!-- <li class="badge-pill tags"> -->
                                                                                <!-- </li> -->
                                                                            <!-- </template> -->
                                                                            <!-- <li class="badge-pill tags" style="background: #FB8678" > Dont Forget </li> -->
                                                                        <!-- </div> -->
                                                                    <!-- </div> -->
                                                                    <hr>
                                                                    <div class="col-xs-12" style="margin-top:10px;width: 100%;">
                                                                        <button type="submit" class="btn btn-small btn-primary pull-right">Manage Tag</button>
                                                                    </div>
                                                            </div>

                                                        </diV>
                                                    </div>

                                                </a>
                                            </div>
                                        </div>
                                    </Draggable>
                                </Container>
                            </div>
                        </Draggable>
                        <div>
                            <p class="add-column" @click="addColumn" v-if="board_id"><i class="fa fa-plus"></i> add column</p>
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
                        <p>You need to set a name and progress color for the new column.</p>
                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label">Name</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" v-model="addField.name">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label">Percent Complete </label>
                            <div class="col-sm-8">
                                <select class="form-control" v-model="addField.progress">
                                    <option value="0" style="background-image:url('/images/0.png');">0% Complete </option>
                                    <option value="12.5" style="background-image:url('/images/125.png');">12.5% Complete </option>
                                    <option value="25" style="background-image:url('/images/25.png');">25% Complete </option>
                                    <option value="37.5" style="background-image:url('/images/375.png');">37.5% Complete </option>
                                    <option value="50" style="background-image:url('/images/50.png');">50% Complete </option>
                                    <option value="62.5" style="background-image:url('/images/625.png');">62.5% Complete </option>
                                    <option value="75" style="background-image:url('/images/75.png');">75% Complete </option>
                                    <option value="87.5" style="background-image:url('/images/875.png');">87.5% Complete </option>
                                    <option value="100" style="background-image:url('/images/100.png');">100% Complete </option>
                                </select>
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
                                <input type="text" class="form-control" v-model="editField.name">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label">Percent Complete</label>
                            <div class="col-sm-8">
                                <select class="form-control" v-model="editField.progress">
                                    <option value="0" style="background-image:url('/images/0.png');">0% Complete </option>
                                    <option value="12.5" style="background-image:url('/images/125.png');">12.5% Complete </option>
                                    <option value="25" style="background-image:url('/images/25.png');">25% Complete </option>
                                    <option value="37.5" style="background-image:url('/images/375.png');">37.5% Complete </option>
                                    <option value="50" style="background-image:url('/images/50.png');">50% Complete </option>
                                    <option value="62.5" style="background-image:url('/images/625.png');">62.5% Complete </option>
                                    <option value="75" style="background-image:url('/images/75.png');">75% Complete </option>
                                    <option value="87.5" style="background-image:url('/images/875.png');">87.5% Complete </option>
                                    <option value="100" style="background-image:url('/images/100.png');">100% Complete </option>
                                </select>
                            </div>
                        </div>
                        <p v-if="editField.error" class="text-danger">{{editField.error}}</p>
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
                        <h5 class="modal-title">Add Existing Task</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body list-model">
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Select Nav :</label>
                            <div class="col-sm-9">
                                <select class="form-control" v-model="selectedNav" @change="showSubNav()">
                                    <option disabled>Select Nav</option>
                                    <option v-for="(navs, index) in nav" :key="index" v-bind:value="navs.id" v-if="navs.type === 'list'">{{navs.title}}</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row" v-if="subNav.length > 0">
                            <label class="col-sm-4 col-form-label">Select Nav List :</label>
                            <div class="col-sm-8">
                                <select class="form-control" v-model="selectedSubNav" @change="getAllTask()">
                                    <option disabled>Select Nav List</option>
                                    <option v-for="(navList, index) in subNav" :key="index" v-bind:value="navList.id">{{navList.list_title}}</option>
                                </select>
                            </div>
                        </div>
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
<script>
    import flatPickr from 'vue-flatpickr-component';
    import 'flatpickr/dist/flatpickr.css';
    import switches from 'vue-switches';
    import ClickOutside from 'vue-click-outside';
    import Datepicker from 'vuejs-datepicker';
    import {Container, Draggable} from 'vue-smooth-dnd';
    import {applyDrag, generateItems} from '../../../assets/plugins/utils/helpers';
    import VueTagsInput from '@johmun/vue-tags-input';

    export default {
        props : ['nav_id','board_id','projectId'],
        components: {Container, Draggable, flatPickr, switches, VueTagsInput, Datepicker},
        data() {
            return {
                id: 0,
                tags: [],
                addField: {
                    name: null,
                    color: null,
                    error: null,
                    progress : null
                },
                editField: {
                    name: null,
                    color: null,
                    boardId: null,
                    error: null,
                    progress : null
                },
                date_config: {
                    enableTime: false,
                    wrap: true,
                    disableMobile: true,
                    altFormat: 'Y-m-d',
                    dateFormat: 'd M',
                },
                nav: [],
                subNav: [],
                selectedNav: 'Select Nav',
                selectedSubNav: 'Select Nav List',
                project: null,
                tree4data: [],
                cards: [],
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
                tag: '',
                tag1: [],
                selectedExistedTask : [],
                multiple_list : null,
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
            }
        },
        mounted() {
            $('#header-item').text('Project  / Task Board')
            $(document).ready(function () {
                $(function () {
                    $('[data-toggle="popover"]').popover()
                });
                $("#popoverData").popover({trigger: "hover"});
            });
            this.getBoardTask();
            // this.getData();
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

            getData() {
                this.scene = {
                    type: 'container',
                    props: {
                        orientation: 'horizontal'
                    },
                    children: generateItems(this.cards.length, i => ({
                        id: `column${i}`,
                        boardId: this.cards[i].id,
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
                            cardId: this.cards[i].task[j].id,
                            props: {
                                className: 'card',
                                style: {backgroundColor: 'white'}
                            },
                            data: this.cards[i].task[j].name,
                            date: this.cards[i].task[j].date,
                            tags: this.cards[i].task[j].tags,
                            tagTooltip: this.cards[i].task[j].tagTooltip,
                            delete: this.cards[i].task[j].name
                        }))
                    })),
                }
                setTimeout(function () {
                    $('[data-toggle="tooltip"]').tooltip();
                }, 1000)
                // console.log(scene);

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
                // console.log(this.nav_id);
                if (!this.addField.name) {
                    this.addField.error = 'Name is required!';
                } else if(!this.nav_id || !this.board_id){
                     this.addField.error = 'select board';
                } else {
                    $("#addModal").modal('hide');
                    let data = {
                        title: this.addField.name,
                        color: this.addField.color,
                        progress: this.addField.progress,   
                        project_id: this.projectId,
                        nav_id: this.nav_id,
                        multiple_board_id: this.board_id,
                        task: [{name: '', date: '', tags: [], clicked: 0}]
                    };
                    this.saveBoard(data);

                    this.getData();
                    this.addField = {};
                }
            },
            saveBoard(data){
                let _this = this;
                axios.post('/api/board-save',data)
                .then(response => response.data)
                .then(response => {
                    if(response.success == true){
                        _this.cards.push({
                            id: response.data.id,
                            column: response.data.title,
                            hidden: response.data.hidden,
                            task: []
                        });
                        _this.getData();
                    }
                })
                .catch(error => {});
            },
            updateColumSow(index) {
                this.updateIndex = index;
                this.editField.name = this.cards[index].column;
                this.editField.boardId = this.cards[index].id;
                this.editField.progress = this.cards[index].progress;
                this.editField.color = this.cards[index].color;
                this.editField.error = '';
                // console.log(this.editField)
                setTimeout(function () {
                    $("#EditModal").modal('show');
                },100);
            },
            updateColumn() {
                let _this = this;
                if (!this.editField.name || this.editField.name === '') {
                    this.editField.error = 'Name is required!';
                } else {
                    let data = this.editField;//this.cards[this.updateIndex];
                    $("#EditModal").modal('hide');
                    axios.post('/api/board-modify',data)
                    .then(response => response.data)
                    .then(response => {
                        // console.log(_this.editField)
                        if(response.success === true){    
                            _this.cards[_this.updateIndex].column = _this.editField.name;
                        }
                    })
                    .catch(error => {

                    });
                    setTimeout(function () {
                        // console.log(_this.cards[_this.updateIndex].column);
                        _this.getData();
                        // _this.editField = {};
                        // $("#EditModal").modal('show');
                    },300);
                }
            },
            addExistingTask(index){
                let _this = this;
                axios.get('/api/nav-item/'+this.projectId)
                .then(response => response.data)
                .then(response => {
                    _this.nav = response.success;
                    // console.log(response.success);
                })
                .catch(error => {

                });
                this.updateIndex = index;
                // this.getAllTask();
                $("#addExistingTask").modal('show');
            },
            showSubNav(){
                let _this = this;
                let data = {
                    'projectId' : this.projectId,
                    'navId' : this.selectedNav
                };
                axios.post('/api/nev-list',data)
                .then(response => response.data)
                .then(response => {
                    _this.subNav = response.success;
                })
                .catch(error => {

                });
                // alert(this.selectedNav);
            },
            getBoardTask(){

                var _this = this;
                var datePicker = new Date();
                datePicker.setDate(datePicker.getDate() - 1);
                _this.disabledDates = {
                    to: datePicker, // Disable all dates up to specific date
                };
                let data = {
                    projectId: this.projectId,
                    board_id: this.board_id,
                    nav_id: this.nav_id
                };
                axios.post('/api/board-task', data)
                    .then(response => response.data)
                    .then(response => {
                        // console.log(response.success);
                        _this.cards = response.success;
                        _this.getData();
                    })
                    .catch(error => {

                    });

            },

            clearInputFeild() {
                $("#EditModal").modal('hide');
                $("#addModal").modal('hide');
                $("#addExistingTask").modal('hide');
                this.addField = {};
            },
            AddExistingTasks(){
                let total = this.selectedExistedTask.length;
                if(total <= 0){
                    alert('No task to add');
                    return false;
                }
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
            addCard(index,id) {

                // console.log(index,id)
                let _this = this;
                axios.post('/api/card-add',{'id': id})
                .then(response => response.data)
                .then(response => {
                    if(response.success == true){
                        let data = response.data;
                        // console.log(response.data);
                        _this.cards[index].task.push({id: data.id, name: data.title, date: data.date, tags: [], clicked: 0});
                        let keys = _this.cards[index].task.length-1;
                        _this.getData();
                        setTimeout(function () {
                            $('#id'+index+keys).click();
                            $('#id'+index+keys).focus();
                        },100)
                    }
                })
                .catch(error => {

                });
            },
            deleteCard(index,cardIndex,id){
                let _this = this;
                if(confirm('Are you sure you want to delete this card?') && this.cards[index].task[cardIndex].id == id){
                    axios.delete('/api/card-delete/'+id)
                    .then(response => response.data)
                    .then(response => {
                        if(response.success){
                            delete _this.cards[index].task[cardIndex];
                            _this.cards[index].task.length = _this.cards[index].task.length-1;
                            _this.getData();
                        }
                    })
                    .catch(error => {

                    });
                }
            },
            saveData(data,index,child_key) {
                let _this = this;
                if (!data.data ) {
                    // alert('Title is required!');
                } else {
                    let title =  {
                        'title' : data.data
                    }
                    axios.post('/api/card-update/'+data.cardId,title)
                    .then(response => response.data)
                    .then(response => {
                        _this.cards[index].task[child_key].name = data.data;
                        _this.getData();

                    })
                    .catch(error => {

                    });
                }
            },
            addTag(e,index,key){
                if (e.which === 13) {
                    this.cards[index].task[key].tags.splice(0,1,this.tag);
                    this.tag = null;
                }
            },
            addExistingTag(index,key,tag){
                this.cards[index].task[key].tags.splice(0,1,tag);
            },
            deleteColumn(index,id) {
                let _this = this;
                if (confirm('Are you sure you want to delete this board?')) {
                    axios.delete('/api/board-delete/'+id)
                    .then(response => response.data)
                    .then(response => {
                        if(response.success){
                            _this.cards.splice(index, 1)
                            _this.getData();
                        }
                    })
                    .catch(error => {

                    });
                }
            },
            deleteColumnCards(index, id) {
                let _this = this;
                if (confirm('Are you sure tou want to delete all cards from this board?')) {
                    axios.delete("/api/board-deleteAllCards/"+id)
                    .then(response => response.data)
                    .then(response => {
                        if(response.success){
                            _this.cards[index].task = [];
                            _this.getData();
                        }
                    })
                    .catch(error => {

                    })
                }
            },
            hideItem(index) {

            },
            hideColumn(index, id) {
                let _this = this;
                let ishide = {
                    "hide":1
                }
                axios.post('/api/board-hide/'+id, ishide)
                .then(response => response.data)
                .then(response => {
                    _this.cards[index].hidden = 1;
                    _this.getData();
                })
                .catch(error => {

                });
            },
            showColumn(index, id){
                let _this = this;
                let ishide = {
                    "hide":0
                }
                axios.post('/api/board-hide/'+id, ishide)
                .then(response => response.data)
                .then(response => {
                    _this.cards[index].hidden = 0;
                    _this.getData();
                })
                .catch(error => {

                });
            },
            showItem(e,data,index,child_key) {
                $('.inp').addClass('input-hide');
                $('.inp').removeClass('form-control');
                this.saveData(data,index,child_key)
            },
            saveCardData(e,data){
                 if (e.which === 13) {
                    $('.inp').addClass('input-hide');
                    $('.inp').removeClass('form-control');
                }
            },
            updateDate(card){
                setTimeout(function () {
                    let data = {
                        'date' : card.date
                    };
                    axios.post('/api/card-update/'+card.cardId, data)
                    .then(response => response.data)
                    .then(response => {

                    })
                    .catch(error => {

                    });
                }, 300)
            },
            generateColor() {
                var myColor = '#000000';
                myColor = '#' + (Math.random() * 0xFFFFFF << 0).toString(16)
                return myColor;
            },
            changeTag(tags, card, columnIndex, cardIndex) {
                var _this = this;
                // console.log(tags)
                var _this = this;
                var old = this.tags.length;
                var newl = tags.length;

                if (newl > old) {
                    this.tags = tags;

                    var color = (this.tags[newl - 1].text === 'Dont Forget') ? '#ff0000' : _this.generateColor();
                    var postData = {
                        id: card.cardId,
                        tags: _this.tags[newl - 1].text,
                        color: color,
                        type: 'board',
                    }
                    axios.post('/api/task-list/add-tag', postData)
                    .then(response => response.data)
                    .then(response => {
                        let cardTags = {
                            'board_id' : response.data.board_id,
                            'classes' : '',
                            'color' : response.data.color,
                            'id' : response.data.id,
                            'style' : "background-color: "+response.data.color,
                            'text' : response.data.title,
                        }
                        _this.cards[columnIndex].task[cardIndex].tags.push(cardTags);
                        // console.log(response)
                    })
                    .catch(error => {
                        console.log("error")
                    });
                    setTimeout(function () {
                        _this.getData();
                    },100);
                }
            },
            deleteCardTag(obj, card, columnIndex, cardIndex) {
                var _this = this;
                var postData = {
                    id: obj.tag.id,
                }
                // console.log(obj);
                if (obj.tag.text !== 'Dont Forget') {
                    axios.post('/api/task-list/delete-by-tag-id', postData)
                        .then(response => response.data)
                        .then(response => {
                            // console.log(response.success)
                            _this.cards[columnIndex].task[cardIndex].tags.splice(obj.index,1);
                            _this.getData()
                            _this.tags = []
                        })
                        .catch(error => {
                            console.log('Api for delete tag not Working !!!')
                        });
                }

            },
            getAllTask(){
                let data = {
                    id: this.projectId,
                    nav_id: this.selectedNav,
                    list_id: this.selectedSubNav,
                };
                axios.post('/api/all-task-list', data)
                    .then(response => response.data)
                    .then(response => {
                        console.log(response.task_list);
                        this.tree4data = response.task_list;
                    })
                    .catch(error => {

                    });
            },
        },
        directives: {
            ClickOutside
        },
        watch: {
            projectId : function (val) {
                this.projectId = val;
                this.getBoardTask()
            },
            board_id : function (val) {
                this.board_id = val;
                this.getBoardTask()
                // console.log(this.board_id)
            },
            nav_id : function (val) {
                this.nav_id = val;
                this.getBoardTask()
            },
        }
    }
</script>
