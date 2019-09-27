<template>
    <div class="card pt-0 pr-0">

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
                                                    <a class="dropdown-item" href="#" @click="hideColumn(index)"><i
                                                            class="fa fa-angle-double-left opacity" ></i> Hide column</a>
                                                    <div class="dropdown-divider"></div>
                                                    <a class="dropdown-item" href="#" @click="deleteColumnCards(index)">
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
                                            <a class="tag-icon">
                                                <div v-if="card.tags && card.tags.length !== 0">
                                                    <div v-for="item in card.tags">
                                                        <div class="dropdown-toggle-split " data-toggle="dropdown">
                                                            <span class="badge badge-danger "  v-if='item == "Dont Forget"'>{{item.substring(0,12)}}</span>
                                                            <span class="badge badge-success " v-else>{{item.substring(0,10)}}..</span>
                                                        </div>


                                                        <div class="dropdown-menu dropdown-menu1 dropdown-menu-left" :id="'dropdown'+data._id">

                                                            <diV class="collapse show switchToggle" style="">
                                                                <div class="container-fluid">
                                                                    <vue-tags-input
                                                                        :tags="data.tags"
                                                                        :allow-edit-tags="true"
                                                                    />
                                                                        <div class="row">
                                                                            <div class="col-12">
                                                                                <template>
                                                                                    <li class="badge-pill tags"
                                                                                        v-bind:style="[{'background': red },{'margin-left' : 1 +'px'}]">
                                                                                        sdfasd
                                                                                    </li>
                                                                                </template>
                                                                                <li class="badge-pill tags" style="background: #FB8678" > Dont Forget </li>
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
                                                                :allow-edit-tags="true"
                                                            />
                                                                <div class="row">
                                                                    <div class="col-12">
                                                                        <template>
                                                                            <li class="badge-pill tags">
                                                                                sdfasd
                                                                            </li>
                                                                        </template>
                                                                        <li class="badge-pill tags" style="background: #FB8678" > Dont Forget </li>
                                                                    </div>
                                                                </div>
                                                                <hr>
                                                                <div class="col-xs-12" style="margin-top:10px;width: 100%;">
                                                                    <button type="submit" class="btn btn-small btn-primary pull-right">Manage Tag</button>
                                                                </div>
                                                        </div>

                                                    </diV>
                                                </div>

                                            </a>
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
                        <p>You need to set a  andprogress color for the new column.</p>
                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label">Name</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" v-model="addField.name">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label">Percent Complete</label>
                            <div class="col-sm-8">
                                <select class="form-control" v-model="addField.progress">
                                    <option>0%</option>
                                    <option>10%</option>
                                    <option>20%</option>
                                    <option>30%</option>
                                    <option>40%</option>
                                    <option>50%</option>
                                    <option>60%</option>
                                    <option>70%</option>
                                    <option>80%</option>
                                    <option>90%</option>
                                    <option>100%</option>
                                </select>
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
                                <input type="text" class="form-control" v-model="editField.name">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label">Percent Complete</label>
                            <div class="col-sm-8">
                                <select class="form-control" v-model="editField.progress">
                                    <option>0%</option>
                                    <option>10%</option>
                                    <option>20%</option>
                                    <option>30%</option>
                                    <option>40%</option>
                                    <option>50%</option>
                                    <option>60%</option>
                                    <option>70%</option>
                                    <option>80%</option>
                                    <option>90%</option>
                                    <option>100%</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label">Color</label>
                            <div class="col-sm-10">
                                <input type="color" class="form-control" v-model="editField.color">
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

        <div class="modal fade" id="addListModel" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
             aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title pl-3"> Add List</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p>Add your new list here !</p>
                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label">List Title</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" v-model="list.name">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label">List Description</label>
                            <div class="col-sm-8">
                                <textarea name="" id="" cols="40" rows="3" v-model="list.description"></textarea>
                            </div>
                        </div>
                        <!--                        <p v-if="addField.error" class="text-danger"></p>-->
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" @click="AddNewList">Add</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal" aria-label="Close">Cancel
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="addBoardModel" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
             aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title pl-3"> Add List</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p>Add your new list here !</p>
                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label">Board Title</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" v-model="list.name">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label">Board Description</label>
                            <div class="col-sm-8">
                                <textarea name="" cols="40" rows="3" v-model="list.description"></textarea>
                            </div>
                        </div>
                        <!--                        <p v-if="addField.error" class="text-danger"></p>-->
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" @click="AddNewBoard">Add</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal" aria-label="Close">Cancel
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="shortcutModel" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
             aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title pl-3"> Shortcuts</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <ul class="list-group list-group-horizontal multi-list-group" style="margin-left: 0px !important;">
                            <li class="list-group-item">
                                <span class="badge-pill badge-default">ENTER</span>
                            </li>
                            <li class="list-group-item">Save and Create New Task</li>
                        </ul>
                        <ul class="list-group list-group-horizontal multi-list-group" style="margin-left: 0px !important;">
                            <li class="list-group-item">
                                <span class="badge-pill badge-default">TAB</span>
                            </li>
                            <li class="list-group-item">Make Sub Task</li>
                        </ul>
                        <ul class="list-group list-group-horizontal multi-list-group" style="margin-left: 0px !important;">
                            <li class="list-group-item">
                                <span class="badge-pill badge-default">SHIFT</span>
                                +
                                <span class="badge-pill badge-default">TAB</span>
                            </li>
                            <li class="list-group-item">Make  Parent Task</li>
                        </ul>
                        <ul class="list-group list-group-horizontal multi-list-group" style="margin-left: 0px !important;">
                            <li class="list-group-item">
                                <span class="badge-pill badge-default">CTRL</span>
                                +
                                <span class="badge-pill badge-default">C</span>
                            </li>
                            <li class="list-group-item">Copy Task</li>
                        </ul>
                        <ul class="list-group list-group-horizontal multi-list-group" style="margin-left: 0px !important;">
                            <li class="list-group-item">
                                <span class="badge-pill badge-default">CTRL</span>
                                +
                                <span class="badge-pill badge-default">V</span>
                            </li>
                            <li class="list-group-item">Paste Task</li>
                        </ul>
                        <ul class="list-group list-group-horizontal multi-list-group" style="margin-left: 0px !important;">
                            <li class="list-group-item">
                                <span class="badge-pill badge-default">CTRL</span>
                                +
                                <span class="badge-pill badge-default">X</span>
                            </li>
                            <li class="list-group-item">Cut Task</li>
                        </ul>
                        <ul class="list-group list-group-horizontal multi-list-group" style="margin-left: 0px !important;">
                            <li class="list-group-item">
                                <span class="badge-pill badge-default">CTRL</span>
                                +
                                <span class="badge-pill badge-default">S</span>
                            </li>
                            <li class="list-group-item">Search</li>
                        </ul>
                        <ul class="list-group list-group-horizontal multi-list-group" style="margin-left: 0px !important;">
                            <li class="list-group-item">
                                <span class="badge-pill badge-default">CTRL</span>
                                +
                                <span class="badge-pill badge-default">i</span>
                            </li>
                            <li class="list-group-item">Upload Image/File</li>
                        </ul>
                        <ul class="list-group list-group-horizontal multi-list-group" style="margin-left: 0px !important;">
                            <li class="list-group-item">
                                <span class="badge-pill badge-default">CTRL</span>
                                +
                                <span class="badge-pill badge-default">U</span>
                            </li>
                            <li class="list-group-item">Assign User</li>
                        </ul>
                        <ul class="list-group list-group-horizontal multi-list-group" style="margin-left: 0px !important;">
                            <li class="list-group-item">
                                <span class="badge-pill badge-default">Shift</span>
                                +
                                <span class="badge-pill badge-default">#</span>
                            </li>
                            <li class="list-group-item">Add Tag</li>
                        </ul>
                        <ul class="list-group list-group-horizontal multi-list-group" style="margin-left: 0px !important;">
                            <li class="list-group-item">
                                <span class="badge-pill badge-default">Shift</span>
                                +
                                <span class="badge-pill badge-default">B</span>
                            </li>
                            <li class="list-group-item">Add Don't Forget Tag</li>
                        </ul>
                        <ul class="list-group list-group-horizontal multi-list-group" style="margin-left: 0px !important;">
                            <li class="list-group-item">
                                <span class="badge-pill badge-default">CTRL</span>
                                +
                                <span class="badge-pill badge-default">D</span>
                            </li>
                            <li class="list-group-item">Delete Task</li>
                        </ul>
                        <ul class="list-group list-group-horizontal multi-list-group" style="margin-left: 0px !important;">
                            <li class="list-group-item">
                                <span class="badge-pill badge-default"><i class="fa fa-caret-up"></i></span>
                            </li>
                            <li class="list-group-item">
                                <span class="badge-pill badge-default"><i class="fa fa-caret-down"></i></span>
                            </li>
                            <li class="list-group-item">Move Task Up &amp; Down</li>
                        </ul>

                        <ul class="list-group list-group-horizontal multi-list-group" style="margin-left: 0px !important;">
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
                        <button type="button" class="btn btn-secondary" data-dismiss="modal" aria-label="Close">
                            Close
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="addNavItem" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
             aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title pl-3"> Add Nav Item</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group row">
                            <div class="col-sm-4">
                                <label class="control-label float-right m-t-ng-8 txt_media1">Nav Title</label>
                            </div>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" v-model="navItem.title">
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-4">
                                <label class="control-label float-right m-t-ng-8 txt_media1">Sort Number</label>
                            </div>
                            <div class="col-sm-8">
                                <input type="number" class="form-control" min="0" v-model="navItem.sort_id">
                            </div>
                        </div>

                        <div class="row form-group">
                            <div class="col-sm-4">
                                <label class="control-label float-right m-t-ng-8 txt_media1">Select Type</label>
                            </div>
                            <div class="col-sm-8">
                                <div class="iradio">
                                    <label>
                                        <input type="radio" name="optionsRadios" id="optionsRadios1" value="list">
                                        &nbsp; List view
                                    </label>
                                </div>
                                <div class="iradio">
                                    <label>
                                        <input type="radio" name="optionsRadios" id="optionsRadios2" value="board">
                                        &nbsp; Board View
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" @click="AddNavItem">Add</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal" aria-label="Close">Cancel
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="updateNavItem" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
             aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title pl-3"> Add Nav Item</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group row">
                            <div class="col-sm-4">
                                <label class="control-label float-right m-t-ng-8 txt_media1">Nav Title</label>
                            </div>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" v-model="navItem.title">
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-4">
                                <label class="control-label float-right m-t-ng-8 txt_media1">Sort Number</label>
                            </div>
                            <div class="col-sm-8">
                                <input type="number" class="form-control" min="0" v-model="navItem.sort_id">
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" @click="updateNavItem">Update</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal" aria-label="Close">Cancel
                        </button>
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
                project: null,
                tree4data: [
                    {
                        id: 1, parent: 0,
                        text: "Don't Forget Section",
                        clicked: 0,
                        date: '',
                        tags: ["Dont Forget"],
                        children: [
                            {
                                id: 2,
                                parent: 1,
                                text: 'node 1-1',
                                html: 'Atik',
                                tags: ["Dont Forget"],
                                files: [{file: '/images/logo.png'}],
                                clicked: 0
                            },
                            {
                                id: 3,
                                parent: 1,
                                text: 'node 1-2',
                                clicked: 0,
                                tags: ["Dont Forget"],
                                children: [
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
                        id: 6,
                        parent: 0,
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
            this.projectId = this.$route.params.projectId;
            $(document).ready(function () {
                $(function () {
                    $('[data-toggle="popover"]').popover()
                });
                $("#popoverData").popover({trigger: "hover"});
            });
            this.getBoardTask();
            // this.getData();
            // this.projectId = this.$route.params.projectId;
            // this.AllNavItem();
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
                            delete: this.cards[i].task[j].name
                        }))
                    })),
                }

            },
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
                        console.log(response.success)
                        _this.AllNavItem()
                        $("#addNavItem").modal('hide');

                    })
                    .catch(error => {
                        console.log('Api for move down task not Working !!!')
                    });
            },
            AllNavItem() {
                var _this = this;
                axios.get('/api/nav-item/' + _this.projectId)
                    .then(response => response.data)
                    .then(response => {
                        console.log(response)
                        _this.AllNavItems = response.success;

                    })
                    .catch(error => {
                        console.log('Api for move down task not Working !!!')
                    });
            },

            updateNavbarModel(data) {
                this.nav_id = data.id;
                this.navItem.title = data.title;
                this.navItem.type = data.type;
                this.navItem.sort_id = data.sort_id;
                this.navItem.nav_id = data.id;
                this.navItem.project_id = data.project_id;

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
                        console.log(response.success)
                        _this.AllNavItem()
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
                this.nav_id = id;
                $("#addBoardModel").modal('show');
            },
            setListId(id, title, nav_id, type) {
                this.board_id = id;
                this.nav_id = nav_id;
                this.getBoardTask()
                this.nav_id = nav_id;
            },
            AddNewList() {
                this.list.project_id = this.projectId;
                this.list.nav_id = this.nav_id;
                axios.post('/api/list-add', this.list)
                    .then(response => response.data)
                    .then(response => {
                        this.multiple_list = response.multiple_list;
                        console.log(response)
                        this.AllNavItem()
                        setTimeout(function () {
                            $('#list' + response.id.id).click();
                        }, 300)
                        $("#addListModel").modal('hide');
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
                        this.multiple_list = response.multiple_board;
                        console.log(response)
                        this.AllNavItem()
                        $("#addBoardModel").modal('hide');
                    })
                    .catch(error => {
                        console.log('Add list api not working!!')
                    });
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
                console.log(this.nev_id, this.board_id);
                if (!this.addField.name) {
                    this.addField.error = 'Name is required!';
                } else if(!this.nav_id || !this.board_id){
                     this.addField.error = 'select board';
                } else {
                    $("#addModal").modal('hide');
                    let data = {
                        title: this.addField.name,
                        color: this.addField.color,
                        project_id: this.projectId,
                        progress: this.progress,
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
                setTimeout(function () {
                    $("#EditModal").modal('show');
                },100);
            },
            updateColumn() {
                console.log(this.cards[this.updateIndex])
                if (!this.editField.name || this.editField.name === '') {
                    this.editField.error = 'Name is required!';
                } else {
                    let data = this.cards[this.updateIndex];
                    $("#EditModal").modal('hide');
                    axios.post('/api/board-modify',data)
                    .then(response => response.data)
                    .then(response => {
                        if(response.success === true){
                            this.cards[this.updateIndex].column = this.editField.name;
                        }
                    })
                    .catch(error => {

                    });
                    this.getData();
                    this.editField = {};
                }
            },
            addExistingTask(index){
                this.updateIndex = index;
                $("#addExistingTask").modal('show');
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

                console.log(index,id)
                let _this = this;
                axios.post('/api/card-add',{'id': id})
                .then(response => response.data)
                .then(response => {
                    if(response.success == true){
                        let data = response.data;
                        console.log(response.data);
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
            }
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
                console.log(this.board_id)
            },
            nav_id : function (val) {
                this.nav_id = val;
                this.getBoardTask()
            },
        }
    }
</script>
