<template >
    <div>
        <div id="board_view_list"  @click="HideDetails">
            <div class="col-12" id="col10" style="border: none">
                <div class="card-scene">
                    <Container
                        :drop-placeholder="upperDropPlaceholderOptions"
                        @drag-start="dragStart"
                        @drag-end="dragEnd"
                        @drop="onColumnDrop($event)"
                        drag-handle-selector=".column-drag-handle"
                        orientation="horizontal">
                        <Draggable :key="column.id" v-for="(column , index ) in scene.children">
                            <div class="hidden-column" v-if="column.hidden">
                                <div class="card-column-header">
                                    <span @click="showColumn(index, column.boardId)"
                                          class="column-drag-handle">&#8667;</span>

                                </div>
                            </div>
                            <div :class="column.props.className" v-else>
                                <div class="card-column-header">
                                    <div class="row" style="position: relative;">
                                        <div class="col-md-3" style="margin: 0px;  padding : 0px;">
                                            <span class="column-drag-handle">&nbsp; &#x2630;</span>
                                            <img :src="baseUrl+'/img/'+column.progress+'.png'" height="40" width="40" />
                                        </div>
                                        <div class="col-md-6 " style="margin: 0px; padding : 0px;word-break: break-all" >
                                            <span class="col_name"
                                                  style="word-break: break-all; max-height: 71px; display: block; height: 42px; overflow: hidden;"
                                                  :title="column.name" data-placement="bottom" data-toggle="tooltip">
                                                {{ (column.name && column.name.length > 38)? column.name.substring(0,38)+ " .." : column.name }}
                                            </span>
                                        </div>
                                        <div style="margin: 0px; padding : 0px;">
                                            <span class="total-task">{{column.children.length}}</span>
                                        </div>
                                        <div class="col-md-1" style="position: absolute; display: inline; right: 0px; top: 9px">
                                            <span class="pull-right" style="display: inline-flex;">
                                                <!-- <span>
                                                    <span class="dropdown-toggle-split  opacity"
                                                        data-toggle="dropdown">
                                                        <img :src="baseUrl+'/img/task-icon/plus.png'" height="18" width="18" >
                                                        <!-- <i class="fa fa-plus"></i> -->
                                                    <!--</span>
                                                    <div class="dropdown-menu">
                                                        <diV class="collapse show switchToggle">
                                                            <a @click="addExistingTask(index,column.boardId)" class="dropdown-item"
                                                            href="javascript:void(0)">
                                                                <img :src="baseUrl+'/img/task-icon/plus-o.png'" height="18" width="18" > Add existing tasks</a>
                                                            <a @click="addCard(index,column.boardId)" class="dropdown-item"
                                                            href="javascript:void(0)"><img :src="baseUrl+'/img/task-icon/create.png'" height="18" width="18" > Create new tasks</a>
                                                        </diV>
                                                    </div>
                                                </span> -->

                                                <span>
                                                    <span class="dropdown-toggle-split opacity"
                                                        data-toggle="dropdown" style="padding: 0px;">
                                                        <img :src="baseUrl+'/img/task-icon/ellipsis.png'" height="18" width="18" >
                                                        <!-- <i class="fa fa-ellipsis-h"></i> -->
                                                    </span>
                                                    <div class="dropdown-menu">
                                                        <diV class="collapse show switchToggle">
                                                            <a @click="addExistingTask(index,column.boardId)" class="dropdown-item"
                                                            href="javascript:void(0)">
                                                                <img :src="baseUrl+'/img/task-icon/plus-o.png'" height="18" width="18" > Add existing tasks</a>
                                                            <a @click="addCard(index,column.boardId)" class="dropdown-item"
                                                            href="javascript:void(0)"><img :src="baseUrl+'/img/task-icon/create.png'" height="18" width="18" > Create new tasks</a>
                                                            <div class="dropdown-divider"></div>
                                                            <a @click="updateColumSow(index)" class="dropdown-item" href="#">
                                                                <img :src="baseUrl+'/img/task-icon/edit.png'" height="18" width="18" >  Edit column</a>
                                                            <a @click="hideColumn(index, column.boardId)" class="dropdown-item"
                                                            href="#"><img :src="baseUrl+'/img/task-icon/hide.png'" height="18" width="18" > Hide column</a>
                                                            <div class="dropdown-divider"></div>
                                                            <!-- <a @click="deleteColumnCards(index, column.boardId)" class="dropdown-item"
                                                            href="#">
                                                                <i class="fa fa-trash opacity"></i> Peekaboo all tasks in this column</a> -->
                                                            <a @click="transferColumnToOtherBoard(index, column.boardId)" class="dropdown-item"
                                                                href="#">
                                                                <img :src="baseUrl+'/img/task-icon/transfer.png'" height="18" width="18" > Transfer Column to another board</a>
                                                            <a @click="showLinkModel(index, column.boardId)" class="dropdown-item"
                                                                href="#"><img :src="baseUrl+'/img/task-icon/link.png'" height="18" width="18" > Link to List </a>
                                                                <!-- v-if="column.linkToList.length <= 0"  -->
                                                            <li class="dropdown-submenu" v-if="column.linkToList.length > 0">
                                                                <a class="dropdown-item" href="#"><img :src="baseUrl+'/img/task-icon/unlink.png'" height="18" width="18" > Unlink Lists</a>
                                                                <ul class="dropdown-menu">
                                                                    <li v-for="unlinks in column.linkToList" class="dropdown-item"  @click="unlinklistToCol(index, column.boardId, unlinks.id)">
                                                                        <a href="#"><img :src="baseUrl+'/img/task-icon/unlink.png'" height="18" width="18" >
                                                                            {{unlinks.link_to_list_column.list_title}}
                                                                        </a>
                                                                    </li>
                                                                </ul>
                                                            </li>


                                                            <!-- <a v-if="column.linkToList.length > 0" @click="unlinklistToCol(index, column.boardId)" class="dropdown-item"
                                                                href="#"><img :src="baseUrl+'/img/task-icon/unlink.png'" height="18" width="18" > Unlink Lists

                                                                 </a> -->

                                                            <div class="dropdown-divider"></div>
                                                            <a @click="deleteColumn(index,column.boardId)" class="dropdown-item"
                                                            href="#">
                                                                <img :src="baseUrl+'/img/task-icon/trash.png'" height="18" width="18" > Delete column</a>
                                                        </diV>
                                                    </div>
                                                </span>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <Container
                                    :drop-placeholder="dropPlaceholderOptions"
                                    :get-child-payload="getCardPayload(column.id)"

                                    @drop="(e) => onCardDrop(column.id, column.boardId, index, e)"
                                    drag-class="card-ghost"
                                    drop-class="card-ghost-drop"
                                    group-name="col"
                                >
                                <!-- @drag-start="(e) => onDragStart(column.id,column.boardId, index,  e)"
                                    @drag-end="(e) => log('', e)" -->
                                    <Draggable :key="card.id" v-for="(card , key) in column.children" >
                                        <div :class="card.props.className"
                                                :style="card.props.style"
                                                class="card-list"
                                                @click="selectCard(card,column.children)"
                                                :id="'card_'+card.cardId"
                                                v-on:dblclick="showLog">
                                                <!-- @click="selectCard(card)" -->
                                            <span style="position: absolute; right: 10px; top: 8px; " > <!-- v-if="card.textareaShow === true" -->
                                                <span class="dropdown-toggle-split opacity"
                                                    data-toggle="dropdown" >
                                                    <i class="fa fa-ellipsis-h" ></i>
                                                </span>
                                                <div class="dropdown-menu">
                                                    <diV class="collapse show switchToggle">
                                                        <!-- <a @click="updateColumSow()" class="dropdown-item" href="#"><i
                                                            class="fa fa-edit opacity"></i> Edit column</a> -->

                                                        <a @click="showTransferModel(index, key, card.cardId, column.boardId)" class="dropdown-item"
                                                        href="#"><i
                                                            class="fa fa-share-square-o opacity"></i> Transfer task to another board</a>

                                                        <div class="dropdown-divider" v-if="card.types === 'task'"></div>
                                                        <a @click="deleteTask(index, key, card.cardId)" class="dropdown-item"
                                                        href="#" v-if="card.types === 'task'">
                                                            <i class="fa fa-minus-circle opacity"></i> Remove task from <strong>This Board</strong> </a>
                                                        <div class="dropdown-divider"></div>
                                                        <a @click="RemoveNodeAndChildren()" class="dropdown-item"
                                                        href="#"><i class="fa fa-trash opacity"></i> Delete the task</a>
                                                    </diV>
                                                </div>
                                            </span>
                                            <!-- {{card.textareaShow}} -->
                                            <!-- <froala
                                                :id="'id'+index+key"
                                                :tag="'textarea'"
                                                :data-text="card.data"
                                                @blur="showItem($event,card,index,key);card.textareaShow = false"
                                                @click="makeInput($event)"
                                                @focus="hideItem($event)"
                                                class="inp input-hide text-area autoExpand"
                                                data-grow="auto" style="padding: 10px !important;"
                                                v-model="card.data"
                                                v-if="card.textareaShow === true"></froala> -->
<!--                                            <textarea-->
<!--                                                :data-text="card.data"-->
<!--                                                :id="'id'+index+key"-->
<!--                                                @blur="showItem($event,card,index,key);card.textareaShow = false"-->
<!--                                                @click="makeInput($event)"-->
<!--                                                @focus="hideItem($event)"-->
<!--                                                class="inp input-hide text-area autoExpand"-->
<!--                                                data-grow="auto" style="padding: 10px !important;"-->
<!--                                                v-if="card.textareaShow === true" >{{ card.data }}-->
<!--                                            </textarea>-->

                                            <div :id="'title'+card.cardId" contenteditable="true" style="padding: 10px;" class="card-title-blur"
                                                 @click="makeInput($event,card.cardId)"
                                                 @blur="showItem($event,card,index,key)"
                                                 v-html="card.data">
                                            </div>

                                            <!--  -->
                                            <!-- <div v-if="card.textareaShow === false" @click="showHideTextarea(index, key, card)">{{ card.data }}</div> -->
<!--                                            <div v-if="card.textareaShow === false" @click="card.textareaShow = true;showHideTextarea('#id'+index+key)">{{ card.data }}</div>-->
                                            <br>
                                            <div class="">
                                                <div>
                                                    <a class="calender clickHide" v-if="card.date === '0000-00-00'">
                                                        <!-- <i class="outline-event icon-image-preview" data-toggle
                                                        title="toggle"></i> -->
                                                        <img :src="baseUrl+'/img/task-icon/date-plus.png'" data-toggle title="toggle" class="icon-image-preview">
                                                    </a>
                                                    <flatPickr
                                                        :class="{
                                                                dateCal:true,
                                                                'flatpickr-input': true,
                                                                'flatpickr-input1': card.date != '0000-00-00' ? false : true,
                                                                    active: true,
                                                                    dateCal1: card.date != '0000-00-00' ? true : false
                                                                }"
                                                        :config="date_config"
                                                        @on-change="updateDate(card)"
                                                        name="date"
                                                        v-model="card.date">
                                                    </flatPickr>
                                                </div>
                                                <div style="position: absolute; right: 160px; bottom: 12px; opacity: 1">
                                                    <a @click="deleteCard(index, key, card.cardId)"
                                                    v-if="card.types == 'card'">
                                                        <!-- <i class="baseline-playlist_delete icon-image-preview"></i> -->
                                                        <img src="https://img.icons8.com/color/48/000000/delete-forever.png" class="icon-image-preview">
                                                    </a>
                                                    <a @click="deleteTask(index, key, card.cardId)"
                                                    v-if="card.types == 'task'">
                                                        <!-- <i class="baseline-playlist_delete icon-image-preview"></i> -->
                                                        <img src="https://img.icons8.com/color/48/000000/delete-forever.png" class="icon-image-preview">
                                                    </a>
                                                </div>

                                                <div style="float: left;">
                                                    <a class="user dropdown-hide-with-remove-icon">
                                                        <template v-if="card.assigned_user.length > 0">
                                                            <span class="assigned_user dropdown-toggle-split "
                                                                data-toggle="dropdown" v-for="(assign,keyId) in card.assigned_user">
                                                                <p :title="assign.name"
                                                                class="assignUser-photo-for-selected text-uppercase"
                                                                data-placement="bottom" data-toggle="tooltip"
                                                                style="top: 10px;"
                                                                v-if="keyId <= 1">{{(assign.name !== null) ? assign.name.substring(0,2) : ''}}
                                                                </p>

                                                            </span>
                                                        </template>
                                                        <span data-toggle="dropdown" class=" dropdown-toggle-split" v-else>
                                                            <!-- <i class="outline-person icon-image-preview li-opacity "
                                                            data-toggle="tooltip" title="Assignee">
                                                            </i> -->
                                                            <img :src="baseUrl+'/img/task-icon/add-user.png'"  class="icon-image-preview " data-toggle="tooltip" title="Assignee">
                                                        </span>

                                                        <div class="dropdown-menu dropdown-menu-right" style="z-index: 1;">
                                                            <diV class="collapse show switchToggle">
                                                                <li class="assignUser">
                                                                    <input class="input-group searchUser"
                                                                        placeholder="Assign by name and email"
                                                                        style="width: 90%; padding: 12px 20px; margin: 10px; display: inline-block; border: 1px solid #ccc;
                                                                                    border-radius: 4px; box-sizing: border-box; "
                                                                        type="text">
                                                                    <label class="pl-2 label-text">
                                                                        <span class="assign-user-drop-down-text">
                                                                            Or invite a new member by email address
                                                                        </span>
                                                                    </label>
                                                                </li>
                                                                <li class="assignUser">
                                                                    <template v-for="user in card.users">
                                                                        <div
                                                                            @click="(card.assigned_user_ids.includes(user.id)) ? '' : assignUserToTask(user, index, key, card) "
                                                                            :class="(card.assigned_user_ids.includes(user.id)) ? 'active-user disabled' : 'users-select'"
                                                                            class="row"
                                                                            v-bind:disabled="(card.assigned_user_ids.includes(user.id)) ? true : false">
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
                                                                            <a :id="'remove-assign-user'+user.id"
                                                                            v-if="card.assigned_user_ids.includes(user.id)"
                                                                            @click="removeAssignedUser(user.id, card.cardId)"
                                                                            data-toggle="tooltip" title="Remove user from assigned !"
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

                                                <div>
                                                    <a :class="{'tag-icon': true, 'tag-icon-free': card.tags == undefined || card.tags.length == 0}">
                                                        <div v-if="card.tags && card.tags.length !== 0">
                                                            <div style="float: left;" v-for="(item, tagIndex) in card.tags">
                                                                <div class="dropdown-toggle-split "
                                                                    data-toggle="dropdown"
                                                                    style="padding-right: 0px; padding-left: 1px;" v-if="tagIndex < 2">
                                                                    <span class="badge badge-danger "
                                                                        v-if='item == "Dont Forget"'>{{item.text.substring(0,12)}}</span>
                                                                    <span :title="card.tagTooltip"
                                                                        class="badge badge-success "
                                                                        data-placement="bottom"
                                                                        data-toggle="tooltip"
                                                                        v-bind:style="[{'background': item.color },{'margin-left' : 1 +'px'}]"
                                                                        v-else
                                                                    >{{item.text.substring(0,10)}}
                                                                        <span v-if="item.text.length > 10">..</span>
                                                                    </span>
                                                                </div>

                                                                <div :id="'dropdown1'+card.cardId" class="dropdown-menu dropdown-menu1">

                                                                    <diV class="collapse show switchToggle" style="">
                                                                        <div class="container-fluid">
                                                                            <vue-tags-input
                                                                                :allow-edit-tags="true"
                                                                                :tags="card.tags"
                                                                                @before-deleting-tag="deleteTag => deleteCardTag(deleteTag,card,index,key)"
                                                                                @tags-changed="newTags => (changeTag(newTags,card,index,key))"
                                                                                v-model="tag"
                                                                            />
                                                                            <div class="row">
                                                                                <div class="col-12">
                                                                                    <template v-for="(tag, tagIndx) in card.existing_tags">
                                                                                        <li class="badge-pill tags" @click="addExistingTag(index , tagIndx, key, card.cardId, '')"
                                                                                            v-bind:style="[{'background': tag.color },{'margin-left' : 1 +'px'}]"
                                                                                            v-if="tag.text !== 'Dont Forget'">
                                                                                            {{(tag.title !== undefined) ?tag.title.substring(0,12) : ''}}
                                                                                        </li>
                                                                                    </template>
                                                                                    <li @click="addExistingTag(index , 0, key, card.cardId, 'Dont Forget')" class="badge-pill tags" style="background: #FB8678" > Dont Forget </li>
                                                                                </div>
                                                                            </div>
                                                                            <hr>
                                                                            <div class="col-xs-12"
                                                                                style="margin-top:10px;width: 100%;">
                                                                                <button @click="showTagManageModel"
                                                                                        class="btn btn-small btn-primary pull-right"
                                                                                        type="submit">
                                                                                    Manage Tag
                                                                                </button>
                                                                            </div>
                                                                        </div>

                                                                    </diV>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <img :src="baseUrl+'/img/task-icon/tag-add.png'" class="icon-image-preview"  data-toggle="dropdown" title="Add Tag" v-else>
                                                        <!-- <i class="outline-local_offer icon-image-preview dropdown-toggle-split li-opacity"
                                                            data-toggle="dropdown"
                                                            ></i> -->

                                                        <div class="dropdown-menu dropdown-menu1 ">

                                                            <diV class="collapse show switchToggle" style="">
                                                                <div class="container-fluid">
                                                                    <vue-tags-input
                                                                        :allow-edit-tags="true"
                                                                        :tags="tag1"
                                                                        @before-deleting-tag="deleteTag => deleteCardTag(deleteTag,card,index,key)"
                                                                        @tags-changed="newTags => (changeTag(newTags,card,index,key))"
                                                                        v-model="tag"
                                                                    />
                                                                    <div class="row">
                                                                        <div class="col-12">
                                                                            <template v-for="(tag, tagIndx) in card.existing_tags">
                                                                                <li class="badge-pill tags" @click="addExistingTag(index , tagIndx, key, card.cardId ,'')"
                                                                                    v-bind:style="[{'background': tag.color },{'margin-left' : 1 +'px'}]"
                                                                                    v-if="tag.text !== 'Dont Forget'">
                                                                                    {{(tag.title !== undefined) ?tag.title.substring(0,12) : ''}}
                                                                                </li>
                                                                            </template>
                                                                            <li @click="addExistingTag(index , 0, key, card.cardId, 'Dont Forget')"
                                                                                class="badge-pill tags" style="background: #FB8678" > Dont Forget </li>
                                                                        </div>
                                                                    </div>
                                                                    <hr>
                                                                    <div class="col-xs-12"
                                                                        style="margin-top:10px;width: 100%;">
                                                                        <button @click="showTagManageModel"
                                                                                class="btn btn-small btn-primary pull-right"
                                                                                type="submit">
                                                                            Manage Tag
                                                                        </button>
                                                                    </div>
                                                                </div>

                                                            </diV>
                                                        </div>

                                                    </a>
                                                </div>
                                                <div class="total-child" style="bottom: 15px; " v-if="card.child > 0"> <strong>
                                                    <!-- {{ JSON.stringify(card) }} -->
                                                    <!-- {{  }} -->
                                                        <!-- console.log(str.match(new RegExp('cardId','gi')).length);     -->
                                                    <!-- #30 -->
                                                    {{ card.child }}
                                                </strong> </div>
                                            </div>
                                        </div>
                                    </Draggable>
                                </Container>
                            </div>
                        </Draggable>
                        <div>
                            <p @click="addColumn" class="add-column" v-if="board_id">
                                <i class="fa fa-plus"></i>
                                add column
                            </p>
                        </div>
                    </Container>
                </div>
            </div>
        </div>

        <div aria-hidden="true" aria-labelledby="exampleModalLabel" class="modal fade" id="addModal" role="dialog"
             tabindex="-1">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header" style="border-radius: 13px;">
                        <h5 class="modal-title">Add column</h5>
                        <button aria-label="Close" class="close" data-dismiss="modal" type="button">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p>You need to set a name and progress color for the new column.</p>
                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label">Name</label>
                            <div class="col-sm-10">
                                <input class="form-control" type="text" v-model="addField.name">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label">Percent Complete </label>
                            <div class="col-sm-8">
                                <select class="form-control" v-model="addField.progress">
                                    <option style="background-image:url('/images/0.png');" value="0">0% Complete
                                    </option>
                                    <option style="background-image:url('/images/125.png');" value="125">12.5%
                                        Complete
                                    </option>
                                    <option style="background-image:url('/images/25.png');" value="25">25% Complete
                                    </option>
                                    <option style="background-image:url('/images/375.png');" value="375">37.5%
                                        Complete
                                    </option>
                                    <option style="background-image:url('/images/50.png');" value="50">50% Complete
                                    </option>
                                    <option style="background-image:url('/images/625.png');" value="625">62.5%
                                        Complete
                                    </option>
                                    <option style="background-image:url('/images/75.png');" value="75">75% Complete
                                    </option>
                                    <option style="background-image:url('/images/875.png');" value="875">87.5%
                                        Complete
                                    </option>
                                    <option style="background-image:url('/images/100.png');" value="100">100% Complete
                                    </option>
                                </select>
                            </div>
                        </div>
                        <p class="text-danger" v-if="addField.error">{{addField.error}}</p>
                    </div>
                    <div class="modal-footer">
                        <button @click="setColumn()" class="btn btn-primary" type="button">Add</button>
                        <button @click="clearInputFeild()" class="btn btn-secondary" type="button">Cancel</button>
                    </div>
                </div>
            </div>
        </div>
        <div aria-hidden="true" aria-labelledby="exampleModalLabel" class="modal fade" id="EditModal" role="dialog"
             tabindex="-1">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header" style="border-radius: 13px;">
                        <h5 class="modal-title">Update column</h5>
                        <button aria-label="Close" class="close" data-dismiss="modal" type="button">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p>You need to set a progress and color for the new column.</p>
                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label">Name</label>
                            <div class="col-sm-10">
                                <input class="form-control" type="text" v-model="editField.name">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label">Percent Complete</label>
                            <div class="col-sm-8">
                                <select class="form-control" v-model="editField.progress">
                                    <option style="background-image:url('/images/0.png');" value="0">0% Complete
                                    </option>
                                    <option style="background-image:url('/images/125.png');" value="125">12.5%
                                        Complete
                                    </option>
                                    <option style="background-image:url('/images/25.png');" value="25">25% Complete
                                    </option>
                                    <option style="background-image:url('/images/375.png');" value="375">37.5%
                                        Complete
                                    </option>
                                    <option style="background-image:url('/images/50.png');" value="50">50% Complete
                                    </option>
                                    <option style="background-image:url('/images/625.png');" value="625">62.5%
                                        Complete
                                    </option>
                                    <option style="background-image:url('/images/75.png');" value="75">75% Complete
                                    </option>
                                    <option style="background-image:url('/images/875.png');" value="875">87.5%
                                        Complete
                                    </option>
                                    <option style="background-image:url('/images/100.png');" value="100">100% Complete
                                    </option>
                                </select>
                            </div>
                        </div>
                        <p class="text-danger" v-if="editField.error">{{editField.error}}</p>
                    </div>
                    <div class="modal-footer">
                        <button @click="updateColumn" class="btn btn-primary" type="button">Update</button>
                        <button @click="clearInputFeild" class="btn btn-secondary" type="button">Cancel</button>
                    </div>
                </div>
            </div>
        </div>
        <div aria-hidden="true" aria-labelledby="exampleModalLabel" class="modal fade" id="TagManage" role="dialog"
             tabindex="-1">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header" style="border-radius: 13px;">
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
                                            <a @click="DeleteTagFromModal(tag)" class="compltit-blue-a badge badge-danger"
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


        <div aria-hidden="true" aria-labelledby="exampleModalLabel" class="modal fade" id="transferCard" role="dialog"
             tabindex="-1">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header" style="border-radius: 13px;">
                        <h4 class="text-center ">Transfer Task To Another Board</h4>
                        <button aria-label="Close" class="close" data-dismiss="modal" type="button">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label">Select Board :</label>
                            <div class="col-sm-8">
                                <!-- {{ selectedBoard }} -->
                                <select @change="showSubBoard()" class="form-control" v-model="selectedBoard">
                                    <option disabled>Select Board</option>
                                    <option :key="index" v-bind:value="navs.id" v-for="(navs, index) in board"
                                            v-if="navs.type === 'board'">{{navs.title}}
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row" v-if="subBoard.length > 0">
                            <label class="col-sm-4 col-form-label">Select Board List :</label>
                            <div class="col-sm-8">
                                <select @change="getColumn()" class="form-control" v-model="selectedSubBoard">
                                    <option disabled>Select Board List</option>
                                    <option :key="index" v-bind:value="navList.id" v-for="(navList, index) in subBoard">
                                        {{navList.board_title}}
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row" v-if="boardColumn.length > 0">
                            <label class="col-sm-4 col-form-label">Select Board Column :</label>
                            <div class="col-sm-8">
                                <select @change="getBttn()" class="form-control" v-model="selectedBoardColumn">
                                    <option disabled>Select Board Column</option>
                                    <option :key="index" v-bind:value="navList.id" v-for="(navList, index) in boardColumn">
                                        {{navList.title}}
                                    </option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button v-if="transferBtn" aria-label="Close" @click="transferCardToOtherBoard" class="btn btn-success" data-dismiss="modal" type="button">Transfer
                        </button>
                        <button aria-label="Close" class="btn btn-secondary" data-dismiss="modal" type="button">Cancel
                        </button>
                    </div>
                </div>
            </div>
        </div>


        <div aria-hidden="true" aria-labelledby="exampleModalLabel" class="modal fade" id="transferColumn" role="dialog"
             tabindex="-1">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header" style="border-radius: 13px;">
                        <h4 class="text-center ">Transfer Column To Another Board </h4>
                        <button aria-label="Close" class="close" data-dismiss="modal" type="button">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label">Select Board :</label>
                            <div class="col-sm-8">
                                <!-- {{ selectedBoard }} -->
                                <select @change="showSubBoard()" class="form-control" v-model="selectedBoard">
                                    <option disabled>Select Board</option>
                                    <option :key="index" v-bind:value="navs.id" v-for="(navs, index) in board"
                                            v-if="navs.type === 'board'">{{navs.title}}
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row" v-if="subBoard.length > 0">
                            <label class="col-sm-4 col-form-label">Select Board List :</label>
                            <div class="col-sm-8">
                                <select @change="getBttn()" class="form-control" v-model="selectedSubBoard">
                                    <option disabled>Select Board List</option>
                                    <option :key="index" v-bind:value="navList.id" v-for="(navList, index) in subBoard">
                                        {{navList.board_title}}
                                    </option>
                                </select>
                            </div>
                        </div>
                        <!-- <div class="form-group row" v-if="boardColumn.length > 0">
                            <label class="col-sm-4 col-form-label">Select Board Column :</label>
                            <div class="col-sm-8">
                                <select @change="getBttn()" class="form-control" v-model="selectedBoardColumn">
                                    <option disabled>Select Board Column</option>
                                    <option :key="index" v-bind:value="navList.id" v-for="(navList, index) in boardColumn">
                                        {{navList.title}}
                                    </option>
                                </select>
                            </div>
                        </div> -->
                    </div>
                    <div class="modal-footer">
                        <button v-if="transferBtn" aria-label="Close" @click="transferColumnToOtherBoardSave" class="btn btn-success" data-dismiss="modal" type="button">Transfer
                        </button>
                        <button aria-label="Close" class="btn btn-secondary" data-dismiss="modal" type="button">Cancel
                        </button>
                    </div>
                </div>
            </div>
        </div>


        <div aria-hidden="true" aria-labelledby="exampleModalLabel" class="modal fade" id="listLinkColumn" role="dialog"
             tabindex="-1">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header" style="border-radius: 13px;">
                        <h4 class="text-center ">Link List To Column  </h4>
                        <button aria-label="Close" class="close" data-dismiss="modal" type="button">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Select Nav :</label>
                            <div class="col-sm-9">
                                <select @change="showSubNav()" class="form-control" v-model="selectedNav">
                                    <option disabled>Select Nav</option>
                                    <option :key="index" v-bind:value="navs.id" v-for="(navs, index) in nav"
                                            v-if="navs.type === 'list'">{{navs.title}}
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row" v-if="subNav.length > 0">
                            <label class="col-sm-4 col-form-label">Select Nav List :</label>
                            <div class="col-sm-8">
                                <select @change="linkToCol()" class="form-control" v-model="selectedSubNav">
                                    <option disabled>Select Nav List</option>
                                    <option :key="index" v-bind:value="navList.id" v-for="(navList, index) in subNav">
                                        {{navList.list_title}}
                                    </option>
                                </select>
                            </div>
                        </div>
                        <!-- <div class="form-group row" v-if="boardColumn.length > 0">
                            <label class="col-sm-4 col-form-label">Select Board Column :</label>
                            <div class="col-sm-8">
                                <select @change="getBttn()" class="form-control" v-model="selectedBoardColumn">
                                    <option disabled>Select Board Column</option>
                                    <option :key="index" v-bind:value="navList.id" v-for="(navList, index) in boardColumn">
                                        {{navList.title}}
                                    </option>
                                </select>
                            </div>
                        </div> -->
                    </div>
                    <div class="modal-footer">
                        <button v-if="linkBtn" aria-label="Close" @click="listLinkToCol" class="btn btn-success" data-dismiss="modal" type="button">Link
                        </button>
                        <button aria-label="Close" class="btn btn-secondary" data-dismiss="modal" type="button">Cancel
                        </button>
                    </div>
                </div>
            </div>
        </div>


        <div aria-hidden="true" aria-labelledby="exampleModalLabel" class="modal fade" id="loader" role="dialog"
             tabindex="-1">
            <div class="modal-dialog" role="document">
                <div>
                </div>
            </div>
        </div>


        <div aria-hidden="true" aria-labelledby="exampleModalCenterTitle" class="modal fade" id="addExistingTask"
             role="dialog"
             tabindex="-1">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header" style="border-radius: 13px;">
                        <h5 class="modal-title">Add Existing Task</h5>
                        <button aria-label="Close" class="close" data-dismiss="modal" type="button">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body list-model">
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Select Nav :</label>
                            <div class="col-sm-9">
                                <select @change="showSubNav()" class="form-control" v-model="selectedNav">
                                    <option disabled>Select Nav</option>
                                    <option :key="index" v-bind:value="navs.id" v-for="(navs, index) in nav"
                                            v-if="navs.type === 'list'">{{navs.title}}
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row" v-if="subNav.length > 0">
                            <label class="col-sm-4 col-form-label">Select Nav List :</label>
                            <div class="col-sm-8">
                                <select @change="getAllTask()" class="form-control" v-model="selectedSubNav">
                                    <option disabled>Select Nav List</option>
                                    <option :key="index" v-bind:value="navList.id" v-for="(navList, index) in subNav">
                                        {{navList.list_title}}
                                    </option>
                                </select>
                            </div>
                        </div>
                         <ul class="list-group list-group-flush">
                            <div v-if="tree4data.length > 0">
                                <label class="checkbox_cus_mini">
                                    <input type="checkbox" @change="selectAll()" class="checkedAll" name="side_dav" > All
                                    <span class="checkmark"></span>
                                </label>
                                <!-- <input type="checkbox" @change="selectAll()" class="checkedAll"> All <br> -->
                            </div>
                            <div v-for="tree in tree4data">
                                <li class="list-group-item" :class="(tree.board_parent_id !== null && tree.children.length <= 0) ? 'list-group-item-hide' : ''">
                                    <label :class="{'checkbox_cus_mini' : true, '_pen_disable' : true,'disabledTask': tree.board_parent_id !== null}"
                                            v-if="tree.text !== '' && tree.board_parent_id !== null && tree.children.length > 0" >
                                        <input :id="tree.id" :value="tree.id" type="checkbox"
                                                v-if="tree.text !== '' && tree.board_parent_id !== null"
                                                checked disable>
                                        {{tree.text}} / {{tree.children.length}}
<!--                                        <span class="checkmark"></span>-->
                                    </label>
                                    <label class="checkbox_cus_mini"  v-if="tree.text !== '' && tree.board_parent_id == null" >
                                        <input :id="tree.id" :value="tree.id" type="checkbox" v-model="selectedExistedTask"
                                                :class="{'selectAll': true}" @change="selectChild(tree.id)" v-if="tree.text !== '' && tree.board_parent_id === null">{{tree.text}}
                                        <span class="checkmark"></span>
                                    </label>
                                    <ul class="list-group list-group-flush" v-if="tree.children">
                                        <div v-for="child in tree.children">
                                            <li class="list-group-item" :class="(child.board_parent_id !== null && child.children.length <= 0) ? 'list-group-item-hide' : ''">
                                                <label :class="{'checkbox_cus_mini' : true, '_pen_disable' : true,'disabledTask': child.board_parent_id !== null}"
                                                       v-if="child.text !== '' && child.board_parent_id !== null && child.children.length > 0" >
                                                    <input :id="child.id" class="tree-child" :value="child.id"
                                                       type="checkbox"
                                                       checked disable> {{child.text}} / {{child.children.length}}
<!--                                                    <span class="checkmark"></span>-->
                                                </label>
                                                <label class="checkbox_cus_mini" v-if="child.text !== '' && child.board_parent_id == null">
                                                    <input :id="child.id" class="tree-child selectAll" :value="child.id"
                                                       type="checkbox" v-model="selectedExistedTask" @change="selectChild(child.id)" > {{child.text}}
                                                    <span class="checkmark"></span>
                                                </label>

                                                <ul class="list-group list-group-flush" v-if="child.children">
                                                    <div v-for="child1 in child.children">
                                                        <li class="list-group-item" :class="(child1.board_parent_id !== null && child1.children.length <= 0) ? 'list-group-item-hide' : ''">
                                                            <label :class="{'checkbox_cus_mini' : true, '_pen_disable' : true,'disabledTask': child1.board_parent_id !== null}"
                                                            v-if="child1.text !== '' && child1.board_parent_id !== null  && child1.children.length > 0">
                                                                <input :id="child1.id" :value="child1.id" class="tree-child"
                                                                    type="checkbox"
                                                                    checked disable> {{child1.text}}
<!--                                                                <span class="checkmark"></span>-->
                                                            </label>

                                                            <label class="checkbox_cus_mini" v-if="child1.text !== '' && child1.board_parent_id == null">
                                                                <input :id="child1.id" :value="child1.id" class="tree-child selectAll"
                                                                    type="checkbox" v-model="selectedExistedTask" @change="selectChild(child1.id)">{{child1.text}}
                                                                <span class="checkmark"></span>
                                                            </label>
                                                            <ul class="list-group list-group-flush" v-if="child1.children">
                                                                <div v-for="child2 in child1.children">
                                                                    <li class="list-group-item" :class="(child2.board_parent_id !== null && child2.children.length <= 0) ? 'list-group-item-hide' : ''">
                                                                        <label :class="{'checkbox_cus_mini' : true, '_pen_disable' : true,'disabledTask': child2.board_parent_id !== null}"
                                                                                v-if="child2.text !== '' && child2.board_parent_id !== null  && child2.children.length > 0">
                                                                            <input :id="child2.id" :value="child2.id"
                                                                               class="tree-child"
                                                                               type="checkbox"
                                                                               checked disable> {{child2.text}}
                                                                            <!-- <input type="checkbox" @change="selectAll()" class="checkedAll" name="side_dav" > All -->
<!--                                                                            <span class="checkmark"></span>-->
                                                                        </label>

                                                                        <label class="checkbox_cus_mini" v-if="child2.text !== '' && child2.board_parent_id == null">
                                                                            <input :id="child2.id" :value="child2.id"
                                                                               class="tree-child selectAll"
                                                                               type="checkbox"
                                                                               v-model="selectedExistedTask"
                                                                               @change="selectChild(child2.id)"> {{child2.text}}
                                                                            <!-- <input type="checkbox" @change="selectAll()" class="checkedAll" name="side_dav" > All -->
                                                                            <span class="checkmark"></span>
                                                                        </label>

                                                                        <ul class="list-group list-group-flush" v-if="child2.children">
                                                                            <div v-for="child3 in child2.children">
                                                                                <li class="list-group-item" :class="(child3.board_parent_id !== null && child3.children.length <= 0) ? 'list-group-item-hide' : ''">
                                                                                    <label :class="{'checkbox_cus_mini' : true, '_pen_disable' : true,'disabledTask': child2.board_parent_id !== null}"
                                                                                            v-if="child3.text !== '' && child3.board_parent_id !== null && child3.children.length > 0">
                                                                                        <input :id="child3.id"
                                                                                                :value="child3.id"
                                                                                                class="tree-child"
                                                                                                type="checkbox"
                                                                                                 checked disable>
                                                                                            {{child3.text}}
                                                                                        <!-- <input type="checkbox" @change="selectAll()" class="checkedAll" name="side_dav" > All -->
<!--                                                                                        <span class="checkmark"></span>-->
                                                                                    </label>

                                                                                    <label class="checkbox_cus_mini" v-if="child2.text !== '' && child2.board_parent_id == null">
                                                                                        <input :id="child3.id"
                                                                                                :value="child3.id"
                                                                                                class="tree-child selectAll"
                                                                                                type="checkbox"
                                                                                                v-model="selectedExistedTask"
                                                                                                @change="selectChild(child3.id)">
                                                                                            {{child3.text}}
                                                                                        <!-- <input type="checkbox" @change="selectAll()" class="checkedAll" name="side_dav" > All -->
                                                                                        <span class="checkmark"></span>
                                                                                    </label>
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
                        <!-- {{ selectedExistedTask }} -->
                        <button @click="AddExistingTasks" class="btn btn-primary" type="button">Add Tasks</button>
                        <button @click="clearInputFeild" class="btn btn-secondary" type="button">Cancel</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="details" id="details" >
            <TaskDetails v-if="Object.keys(selectedData).length > 0"
                :selectedData="selectedData"
                :task_logs="task_logs"
                @textArea="ShowTextArea">
            </TaskDetails>
        </div>

    </div>

</template>
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
    import TaskDetails from "./boardCardDetails";
    // import VueFroala from 'vue-froala-wysiwyg';
    // import Folder from './recurLi.vue';

    export default {
        props: ['nav_id', 'board_id', 'projectId'],
        components: {Container, Draggable, flatPickr, switches, VueTagsInput, Datepicker, TaskDetails},
        data() {
            return {
                baseUrl : window.location.origin,
                id: 0,
                tags: [],
                addField: {
                    name: null,
                    color: null,
                    error: null,
                    progress: null
                },
                editField: {
                    name: null,
                    color: null,
                    boardId: null,
                    error: null,
                    progress: null
                },
                date_config: {
                    enableTime: false,
                    wrap: true,
                    disableMobile: true,
                    altFormat: 'Y-m-d',
                    dateFormat: 'd M',
                },
                nav: [],
                board: [],
                subNav: [],
                subBoard: [],
                boardColumn: [],
                selectedBoard: 'Select Board',
                selectedNav: 'Select Nav',
                selectedSubNav: 'Select Nav List',
                selectedSubBoard: 'Select Board List',
                selectedBoardColumn: 'Select Board Column',
                transferBtn : false,
                linkBtn : false,
                project: null,
                tree4data: [],
                currentColumn: null,
                currentColumnIndex: null,
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
                selectedExistedTask: [],
                multiple_list: null,
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
                selectedData : {},
                task_logs : null,
                check_uncheck_child : null,
                manageTag: null,
            }
        },
        mounted() {
            var _this = this;
            $('#header-item').text('Project  / Task Board');

            $(document)
            .one('focus.autoExpand', 'textarea.autoExpand', function(){
                var savedValue = this.value;
                this.value = '';
                this.baseScrollHeight = this.scrollHeight;
                this.value = savedValue;
            })
            .on('input.autoExpand', 'textarea.autoExpand', function(){
                var minRows = this.getAttribute('data-min-rows')|0, rows;
                this.rows = minRows;
                rows = Math.ceil((this.scrollHeight - this.baseScrollHeight) / 16);
                this.rows = minRows + rows;
            });



            $(document).ready(function () {
                $(function () {
                    $('[data-toggle="popover"]').popover()
                });
                $("#popoverData").popover({trigger: "hover"});
            });
            _this.getBoardTask();
            $(document).ready(function () {
                $('.searchList').hide();
            });

        },
        created() {
            let _this = this;
             hotkeys('enter,tab,shift+tab,up,down,left,right,ctrl+c,ctrl+x,ctrl+v,ctrl+u,delete,ctrl+b,ctrl+s,ctrl+i,shift+3', function (event, handler) {
                event.preventDefault();
                switch (handler.key) {
                    case "enter" :
                        // _this.addNode(_this.selectedData);
                        break;
                    case "tab" :
                        // _this.makeChild(_this.selectedData);
                        break;
                    case "shift+tab":
                        // _this.unMakeChild(_this.selectedData);
                        break;
                    case "up" :
                        // if (Object.keys(_this.selectedData).length > 0) {
                            // _this.moveItemUp(_this.selectedData);
                        // }
                        _this.selectedData = {};
                        break;
                    case "down" :
                        // if (Object.keys(_this.selectedData).length > 0) {
                            // _this.moveItemDown(_this.selectedData);
                        // }
                        // _this.selectedData = {};
                        break;
                    case "left" :
                        _this.HideDetails();
                        break;
                    case "right" :
                        _this.showLog();
                        // _this.task_logs = null;
                        // _this.ShowDetails(_this.selectedData);

                        break;
                    case "ctrl+c":
                        // _this.selectedCopy = _this.selectedData;
                        // _this.selectedCut = null;
                        break;
                    case "ctrl+x":
                        // _this.selectedCut = _this.selectedData;
                        // _this.selectedCopy = null;
                        break;
                    case "ctrl+v":
                        // _this.pastCopyAndCut(_this.selectedData);
                        break;
                    case "delete":
                        // _this.RemoveNodeAndChildren(_this.selectedData);
                        break;
                    case "ctrl+u":
                        // _this.shwAssignUserDropDown(_this.selectedData);
                        break;
                    case "ctrl+b":
                        // _this.AddDontForgetTagToSelectedIds();//add DON'T FORGET SECTION
                        break;
                    case "ctrl+s":
                        // _this.showSearchInputField();
                        break;
                    case "ctrl+i":
                        // _this.addAttachment(_this.selectedData);
                        break;
                    case "shift+3":
                        // $('#tag-' + _this.selectedData._id).click();
                        // console.log(_this.selectedData);
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
                        progress: this.cards[i].progress,
                        linkToList: this.cards[i].linkToList,
                        props: {
                            orientation: 'vertical',
                            className: 'card-container'
                        },
                        hidden: this.cards[i].hidden,
                        children: generateItems(this.cards[i].task.length, j => ({
                            type: 'draggable',
                            id: `${i}${j}`,
                            cardId: this.cards[i].task[j].id,
                            types: this.cards[i].task[j].type,
                            assigned_user: this.cards[i].task[j].assigned_user,
                            assigned_user_ids: this.cards[i].task[j].assigned_user_ids,
                            users: this.cards[i].task[j].users,
                            existing_tags: this.cards[i].task[j].existing_tags,
                            props: {
                                className: 'card',
                                style: {backgroundColor: 'white'}
                            },
                            child: this.cards[i].task[j].child,
                            childrens: this.cards[i].task[j].children,
                            data: this.cards[i].task[j].name,
                            textareaShow: this.cards[i].task[j].textareaShow,
                            description: this.cards[i].task[j].description,
                            list_id: this.cards[i].task[j].list_id,
                            board_id: this.cards[i].task[j].multiple_board_id,
                            date: this.cards[i].task[j].date,
                            parent_id: this.cards[i].task[j].parent_id,
                            progress: this.cards[i].task[j].progress,
                            tags: this.cards[i].task[j].tags,
                            tagTooltip: this.cards[i].task[j].tagTooltip,
                            delete: this.cards[i].task[j].name
                        }))
                    })),
                };
                $('[data-toggle="tooltip"]').tooltip('dispose');
                setTimeout(function () {
                    $('[data-toggle="tooltip"]').tooltip();
                }, 1000)
            },

            selectChild(id){
                var _this = this;
                _this.findChild(id,_this.tree4data)
                var is_checked = _this.selectedExistedTask.indexOf(id);
                if (is_checked > -1){
                   _this.CheckWithChild(id,_this.check_uncheck_child);
                }else{
                   _this.UncheckWithChild(id,_this.check_uncheck_child);
                }
            },
            findChild(id,data){
                if (data.length > 0){
                    for (let index = 0; index < data.length; index++) {
                        if(index !== undefined && data[index].id === id){
                            if(data[index].board_parent_id === null){
                                this.check_uncheck_child = data[index].children;
                            }
                            return true;
                        }else {
                            this.findChild(id,data[index].children);
                        }
                    }
                }

            },
            CheckWithChild(id , child){
                // console.log(id);
                var _this = this;
                if (id !== 0 && _this.selectedExistedTask.indexOf(id) === -1){
                    _this.selectedExistedTask.push(id);
                }
                if (child.length > 0){
                    for (let index = 0; index < child.length; index++) {
                        if(child[index].board_parent_id === null){
                            _this.CheckWithChild(child[index].id,child[index].children);
                        } else {
                            _this.CheckWithChild(0,child[index].children);
                        }
                    }
                }
            },
            UncheckWithChild(id,child){
                var _this = this;
                var key = _this.selectedExistedTask.indexOf(id);
                if (key !== -1){
                    _this.selectedExistedTask.splice(key,1);
                }

                if (child.length > 0){
                    for (let index = 0; index < child.length; index++) {
                        _this.UncheckWithChild(child[index].id,child[index].children);
                    }
                }

            },

            selectAll(){
                if($('.checkedAll').prop('checked') === false){
                    $('.selectAll').prop('checked', false);
                    this.selectedExistedTask = [];
                }else{
                    $('.selectAll').prop('checked', true);

                    // console.log(this.tree4data);
                    for (let index = 0; index < this.tree4data.length; index++) {
                        if (this.tree4data[index].board_parent_id === null) {
                            // console.log(this.tree4data[index].id);
                            this.selectedExistedTask.push(this.tree4data[index].id);
                        }
                            this.recursive(this.tree4data[index].children, this.tree4data[index].id);
                    }
                    // console.log(this.selectedExistedTask);
                }

            },
            recursive(child, parent_id) {
                for (let index = 0; index < child.length; index++) {
                    let key = this.selectedExistedTask.indexOf(child[index].id);
                    let parentKey = this.selectedExistedTask.indexOf(parent_id);
                    if(key !== -1 && parentKey === -1){
                        this.selectedExistedTask.splice(key,1);
                    } else {
                        if(key === -1 && child[index].board_parent_id === null){
                            this.selectedExistedTask.push(child[index].id);
                        }
                    }
                    if(child[index].children.length > 0) {
                        this.recursive(child[index].children, child[index].id);
                    }
                }
            },

            onColumnDrop(dropResult) {
                const scene = Object.assign({}, this.scene);
                scene.children = applyDrag(scene.children, dropResult);
                this.scene = scene
                let data = scene;
                $('#loader').modal('show');
                axios.post('/api/column-sort',data)
                .then(response => response.data)
                .then(response => {
                    setTimeout(() => {
                        $('#loader').modal('hide');
                    }, 500);
                })
                .catch(error => {
                    setTimeout(() => {
                                $('#loader').modal('hide');
                            }, 500);
                    console.log('sorting failed');
                });
            },
            onCardDrop(columnId, boardId, index, dropResult) {
                console.log('drops => ',dropResult);
                let _this = this;
                if (dropResult.removedIndex !== null || dropResult.addedIndex !== null) {

                    const scene = Object.assign({}, this.scene);
                    const column = scene.children.filter(p => p.id === columnId)[0];
                    const columnIndex = scene.children.indexOf(column);
                    const newColumn = Object.assign({}, column);
                    newColumn.children = applyDrag(newColumn.children, dropResult);
                    scene.children.splice(columnIndex, 1, newColumn);
                    this.scene = scene
                    // console.log(this.scene.children[index]);
                    let data = this.scene.children[index];
                    // console.log("sort",data);
                    axios.post('/api/card-sort',data)
                    .then(response => response.data)
                    .then(response => {
                        // _this.getBoardTask();
                         setTimeout(() => {
                        }, 500);
                        console.log('sorted');
                    })
                    .catch(error => {
                        console.log('sorting failed');
                    });

                    if( dropResult.removedIndex === null && dropResult.addedIndex !== null ) {
                        // console.log("Drag started",columnId, boardId, index, dropResult);
                        let data = {
                            'id' : dropResult.payload.cardId,
                            'board_parent_id' : boardId
                        }
                        $('#loader').modal('show');
                        axios.post('/api/change-board-parent', data)
                        .then(response => response.data)
                        .then(response => {
                            setTimeout(() => {
                                _this.getBoardTask();
                                $('#loader').modal('hide');

                            }, 500);
                            console.log('shifted');
                        })
                        .catch(error => {
                            setTimeout(() => {
                                $('#loader').modal('hide');
                            }, 500);
                            console.log('shifting failed');
                        });
                    }

                }
            },
            onDragStart(columnId, boardId, index, dropResult) {
                if(dropResult.addedIndex != null){
                    //console.log("Drag started",columnId, boardId, index, dropResult);
                }
            },
            getCardPayload(columnId) {
                return index => {
                    return this.scene.children.filter(p => p.id === columnId)[0].children[index]
                }
            },
            dragStart(dragResult) {
                //console.log(dragResult);
            },
            dragEnd(dragResult) {
                console.log(dragResult);
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
                } else if (!this.nav_id || !this.board_id) {
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
            saveBoard(data) {
                let _this = this;
                axios.post('/api/board-save', data)
                    .then(response => response.data)
                    .then(response => {
                        // console.log(response);
                        if (response.success == true) {
                            _this.editField.progress = response.data.progress;
                            _this.cards.push({
                                id: response.data.id,
                                column: response.data.title,
                                progress: response.data.progress,
                                hidden: response.data.hidden,
                                task: []
                            });
                            _this.getBoardTask();
                            swal("Successful", "Board successfuly created", "success")
                        }
                    })
                    .catch(error => {
                    });
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
                }, 100);
            },
            updateColumn() {
                let _this = this;
                if (!this.editField.name || this.editField.name === '') {
                    this.editField.error = 'Name is required!';
                } else {
                    let data = this.editField;//this.cards[this.updateIndex];
                    $("#EditModal").modal('hide');
                    axios.post('/api/board-modify', data)
                        .then(response => response.data)
                        .then(response => {
                            if (response.success === true) {
                                _this.cards[_this.updateIndex].column = _this.editField.name;
                               _this.cards[_this.updateIndex].progress = _this.editField.progress;
                            }
                        })
                        .catch(error => {
                        });
                    setTimeout(function () {
                        _this.getBoardTask();
                        // _this.getData();
                        // _this.editField = {};
                        // $("#EditModal").modal('show');
                    }, 300);
                }
            },
            addExistingTask(index, id) {
                this.tree4data = [];
                this.subNav = [];
                this.selectedNav= 'Select Nav';
                this.selectedSubNav= 'Select Nav List';
                this.selectedExistedTask = [];
                this.currentColumn = id;
                this.currentColumnIndex = index;
                let _this = this;
                axios.get('/api/nav-item/' + this.projectId)
                    .then(response => response.data)
                    .then(response => {
                        _this.nav = response.success;
                    })
                    .catch(error => {
                    });
                this.updateIndex = index;
                // this.getAllTask();
                $("#addExistingTask").modal('show');
            },
            showSubNav() {
                this.tree4data = [];
                let _this = this;
                let data = {
                    'projectId': this.projectId,
                    'navId': this.selectedNav
                };
                axios.post('/api/nav-list', data)
                    .then(response => response.data)
                    .then(response => {
                        _this.subNav = response.success;
                    })
                    .catch(error => {
                    });
            },
            showTransferModel(index, key, cardId, id) {
                this.board = [];
                this.subBoard = [];
                this.boardColumn = [];
                this.selectedBoard= 'Select Board';
                this.selectedSubBoard= 'Select Board List';
                this.selectedExistedTask = [];
                this.transferBtn = false;
                this.currentColumn = id;
                this.currentColumnIndex = index;
                let _this = this;
                axios.get('/api/nav-item/' + this.projectId)
                .then(response => response.data)
                .then(response => {
                    // console.log(response.success);
                    _this.board = response.success;
                    // console.log(_this.board);
                    setTimeout(() => {
                        $('#transferCard').modal('show');
                    }, 500);
                })
                .catch(error => {

                });
                this.updateIndex = index;
                // this.getAllTask();
            },
            showLinkModel(index, boardId){
                this.nav = [];
                this.subNav = [];
                this.selectedNav = 'Select Nav';
                this.selectedSubNav = 'Select Nav List';
                this.selectedExistedTask = [];
                this.currentColumn = boardId;
                this.currentColumnIndex = index;
                let _this = this;
                this.linkBtn = false;
                axios.get('/api/nav-item/' + this.projectId)
                .then(response => response.data)
                .then(response => {
                    // console.log(response.success);
                    _this.nav = response.success;
                    // console.log(_this.nav);
                    setTimeout(() => {
                        $('#listLinkColumn').modal('show');
                    }, 500);
                })
                .catch(error => {

                });
                this.updateIndex = index;

            },
            linkToCol(){
                let _this = this;
                let data = {
                    'projectId' : this.projectId,
                    'columnId' : this.currentColumn,
                    'multiple_list' : this.selectedSubNav
                }
                axios.post('/api/is-linked', data)
                .then(response => response.data)
                .then(response => {
                    if (response.success === false) {
                        this.linkBtn = true;
                    } else {
                        swal('Warning!!','That list is already linked. Need to unlink first','warning');
                    }
                    // _this.getBoardTask();
                    // console.log('added');
                })
                .catch(error => {
                    console.log('not added');
                });
            },
            listLinkToCol(){
                let _this = this;
                // alert(this.currentColumn);
                // alert(this.selectedSubNav);
                let data = {
                    'projectId' : this.projectId,
                    'columnId' : this.currentColumn,
                    'multiple_list' : this.selectedSubNav
                }
                axios.post('/api/link-list-to-column', data)
                .then(response => response.data)
                .then(response => {

                    _this.getBoardTask();
                    console.log('added');
                })
                .catch(error => {
                    console.log('not added');
                });
                // swal('Warning!!','Work in progress','warning');
            },
            unlinklistToCol(index, boardId, linkListId){
                let _this = this;
                // alert(this.currentColumn);
                // alert(this.selectedSubNav);
                swal({
                    title: 'Are you sure to unlink?',
                    text: "",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, unlink it!'
                },
                function(){
                    let data = {
                        'linkListId' : linkListId,
                        'projectId' : _this.projectId,
                        'columnId' : boardId,
                        'multiple_list' : _this.selectedSubNav
                    }
                    axios.post('/api/unlink-list-to-column', data)
                    .then(response => response.data)
                    .then(response => {
                        _this.getBoardTask();
                        swal("Unlinked!", "Successfully Unlinked", "success");
                    })
                    .catch(error => {
                        console.log('not added');
                    });
                });
            },
            showSubBoard() {
                let _this = this;
                this.subBoard = [];
                this.boardColumn = [];
                this.transferBtn = false;
                this.selectedSubBoard= 'Select Board List';
                let data = {
                    'projectId': this.projectId,
                    'boardId': this.selectedBoard
                };
                axios.post('/api/board-list', data)
                    .then(response => response.data)
                    .then(response => {
                        _this.subBoard = response.success;
                    })
                    .catch(error => {
                    });
            },
            getColumn(){
                let _this = this;
                this.selectedBoardColumn = 'Select Board Column';
                this.transferBtn = false;
                let data = {
                    id: this.projectId,
                    nav_id: this.selectedBoard,
                    list_id: this.selectedSubBoard,
                };

                axios.post('/api/board-column', data)
                .then(response => response.data)
                .then(response => {
                    // console.log(selectedBoard,selectedSubBoard,selectedBoardColumn);
                    _this.boardColumn = response.data;
                })
                .catch(error => {

                });

            },
            getBttn() {
                this.transferBtn = true;
            },
            transferCardToOtherBoard() {
                var _this = this;
                let data = {
                    'cardId' : this.selectedData.cardId,
                    'board_parent_id' : this.selectedBoardColumn,
                };
                axios.post('/api/Transfer-to-board', data)
                .then(response => response.data)
                .then(response => {

                    if (response.success) {
                        _this.getBoardTask();
                        $('#transferCard').modal('hide');
                    } else {
                        $('#transferCard').modal('hide');
                    }
                    // console.log(selectedBoard,selectedSubBoard,selectedBoardColumn);
                    // _this.boardColumn = response.data;
                })
                .catch(error => {

                });
            },
            transferColumnToOtherBoard(index, id){

                this.board = [];
                this.subBoard = [];
                this.boardColumn = [];
                this.selectedBoard= 'Select Board';
                this.selectedSubBoard= 'Select Board List';
                this.selectedExistedTask = [];
                this.transferBtn = false;
                this.currentColumn = id;
                this.currentColumnIndex = index;
                let _this = this;
                axios.get('/api/nav-item/' + this.projectId)
                .then(response => response.data)
                .then(response => {
                    // console.log(response.success);
                    _this.board = response.success;
                    console.log(_this.board);
                    setTimeout(() => {
                        $('#transferColumn').modal('show');
                    }, 500);
                })
                .catch(error => {

                });
                this.updateIndex = index;
            },
            transferColumnToOtherBoardSave(){
                var _this = this;

                let data = {
                    'columnId' : this.currentColumn,
                    'multiple_board_id' : this.selectedSubBoard,
                };
                axios.post('/api/Transfer-column-to-board', data)
                .then(response => response.data)
                .then(response => {

                    if (response.success) {
                        _this.getBoardTask();
                        $('#transferColumn').modal('hide');
                    } else {
                        $('#transferColumn').modal('hide');
                    }
                    // console.log(selectedBoard,selectedSubBoard,selectedBoardColumn);
                    // _this.boardColumn = response.data;
                })
                .catch(error => {

                });
            },
            RemoveNodeAndChildren() {
                var _this = this;
                var postData = {
                    id: this.selectedData.cardId,
                    text: this.selectedData.data
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
                            _this.getBoardTask()
                            swal("Deleted!", "Successfully delete task !", "success");
                        })
                        .catch(error => {
                            console.log('Api for delete task not Working !!!')
                        });

                });

            },
            getBoardTask() {
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
            AddExistingTasks() {
                let _this = this;
                let total = this.selectedExistedTask.length;
                if (total <= 0) {
                    swal('Warning!','No Task To Add','warning');
                    return false;
                }
                let data = {
                    'multiple_board_id': this.selectedSubBoard,
                    'tasks': this.selectedExistedTask,
                    'id': this.currentColumn
                };
                axios.post('/api/add-existing-tasks', data)
                    .then(response => response.data)
                    .then(response => {
                        console.log(response.data);
                        for (var i = 0; i < response.data.length; i++) {
                            _this.cards[_this.currentColumnIndex].task.push({
                                id: response.data[i].id,
                                name: response.data[i].title,
                                date: response.data[i].date,
                                tags: response.data[i].tag,
                                types: 'task',
                                clicked: 0
                            });
                            // _this.cards[_this.updateIndex].task.push({name: _this.selectedExistedTask[i], date: '', tags: [], clicked: 0})
                        }
                        // _this.getData()
                        _this.getBoardTask();
                    })
                    .catch(error => {
                    });

                this.updateIndex = null;
                this.selectedExistedTask = [];
                $("#addExistingTask").modal('hide');
            },
            makeInput(e,id) {
                let _this = this;
                $('#title'+id).removeClass('card-title-blur');
                $('#title'+id).addClass('card-title-focus');


                // $('.inp').addClass('input-hide');
                // $('.inp').removeClass('form-control');
                // $(e.target).removeClass('input-hide');
                // $(e.target).addClass('form-control');
                //
                // var option = {
                //     height: 50,
                //     maxHeight: 200
                // };
                // _this.growInit(option);
            },
            addCard(index, id) {

                // console.log(index,id)
                let _this = this;
                axios.post('/api/card-add',{'id': id})
                .then(response => response.data)
                .then(response => {
                    if(response.success == true){
                        let data = response.data;
                        _this.cards[index].task.push({id: data.id, name: data.title, date: data.date, tags: [], assigned_user:[], users:[], clicked: 0, textareaShow : true});
                        let keys = _this.cards[index].task.length-1;
                        _this.getBoardTask();
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
                swal({
                    title: 'Are you sure to delete the card?',
                    text: "You won't be able to revert this!",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                },function(){
                    if(_this.cards[index].task[cardIndex].id == id){
                        axios.get('/api/card-delete/'+id)
                        .then(response => response.data)
                        .then(response => {
                            if (response.success == true) {
                                // let data = response.data;
                                // _this.cards[index].task.push({
                                //     id: data.id,
                                //     name: data.title,
                                //     date: data.date,
                                //     tags: [],
                                //     clicked: 0
                                // });
                                let keys = _this.cards[index].task.length - 1;
                                _this.getBoardTask();
                                _this.getData();
                                setTimeout(function () {
                                    $('#id' + index + keys).click();
                                    $('#id' + index + keys).focus();
                                }, 100)
                                swal("Deleted!", "The card has been deleted.", "success");
                            }
                        })
                        .catch(error => {
                            console.log('error => '+error);
                        });
                    }
                });


            },
            // deleteCard(index, cardIndex, id) {
            //     let _this = this;
            //     if (confirm('Are you sure you want to delete this card?') && this.cards[index].task[cardIndex].id == id) {
            //         axios.get('/api/card-delete/' + id)
            //             .then(response => response.data)
            //             .then(response => {
            //                 _this.cards[index].task.splice(cardIndex, 1);
            //                 _this.getData();
            //                 if (response.success) {
            //                 }
            //             })
            //             .catch(error => {
            //             });
            //     }
            // },
            deleteTask(index, cardIndex, id) {
                let _this = this;
                swal({
                    title: 'Are you sure to delete the card?',
                    text: "You won't be able to revert this!",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                },function(){
                    if ( _this.cards[index].task[cardIndex].id == id ) {
                        axios.get('/api/board-task-delete/' + id)
                            .then(response => response.data)
                            .then(response => {
                                // if(response.success){
                                _this.cards[index].task.splice(cardIndex, 1);
                                // delete _this.cards[index].task[cardIndex];
                                // _this.cards[index].task.length = _this.cards[index].task.length-1;
                                _this.getData();
                                swal("Deleted!", "The card has been deleted.", "success");
                                // }
                            })
                            .catch(error => {
                            });
                    } else {
                        // alert("couden't delete");
                    }
                });
            },
            addTag(e, index, key) {
                if (e.which === 13) {
                    this.cards[index].task[key].tags.splice(0, 1, this.tag);
                    this.tag = null;
                }
            },
            addExistingTag(index, tagIndx, key, cardId, dntfrgt = '') {
                let _this = this;
                // let data = {
                //     'board_id' : this.cards[index].task[key].existing_tags[tagIndx].board_id,
                //     'classes' : '',
                //     'color' : this.cards[index].task[key].existing_tags[tagIndx].color,
                //     'id' : this.cards[index].task[key].existing_tags[tagIndx].id,
                //     'style' : "background-color: "+this.cards[index].task[key].existing_tags[tagIndx].color,
                //     'text' : this.cards[index].task[key].existing_tags[tagIndx].title,
                // };

                // this.getData();
                if(dntfrgt !== ''){
                    var postData = {
                        id: cardId,
                        tags: "Dont Forget",
                        color: "#FF0000",
                        type: 'task',
                    };
                }else{
                    var postData = {
                        id: cardId,
                        tags: this.cards[index].task[key].existing_tags[tagIndx].title,
                        color: this.cards[index].task[key].existing_tags[tagIndx].color,
                        type: 'task',
                    };
                }
                // if(card.types == "task"){
                //     postData.id = card.id;
                // }
                axios.post('/api/task-list/add-tag', postData)
                .then(response => response.data)
                .then(response => {
                    // _this.cards[index].task[key].tags.push(data);
                    _this.cards[index].task[key].existing_tags.splice(tagIndx, 1);
                    // $('#dropdown' + cardId).toggle();
                    setTimeout(function () {
                        _this.getBoardTask();
                    }, 100);
                })
                .catch(error => {
                    console.log("1st error =>"+error)
                });
            },
            deleteColumn(index, id) {
                let _this = this;
                swal({
                    title: "Are you sure?",
                    text: "Your will not be able to recover this",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonClass: "btn-danger",
                    confirmButtonText: "Yes, delete it!",
                    closeOnConfirm: false
                },
                function(){
                    axios.get('/api/board-delete/' + id)
                        .then(response => response.data)
                        .then(response => {
                            if (response.success) {
                                _this.cards.splice(index, 1);
                                _this.getData();
                                swal("Deleted!", "Your imaginary file has been deleted.", "success");
                            }
                        })
                        .catch(error => {
                        });
                });
            },
            // peekabo option commented. don't remove the code yet
            // deleteColumnCards(index, id) {
            //     let _this = this;

            //     swal({
            //         title: "Are you sure?",
            //         text: "Your will not be able to recover this",
            //         type: "warning",
            //         showCancelButton: true,
            //         confirmButtonClass: "btn-danger",
            //         confirmButtonText: "Yes, delete it!",
            //         closeOnConfirm: false
            //     },
            //     function(){
            //         axios.get("/api/board-deleteAllCards/" + id)
            //         .then(response => response.data)
            //         .then(response => {
            //             if (response.success) {
            //                 _this.cards[index].task = [];
            //                 _this.getData();
            //                 swal("Deleted!", "Your imaginary file has been deleted.", "success");
            //             }
            //         })
            //         .catch(error => {

            //         })
            //     });

            // },
            hideItem(index) {

            },
            hideColumn(index, id) {
                let _this = this;
                let ishide = {
                    "hide": 1
                };
                axios.post('/api/board-hide/' + id, ishide)
                    .then(response => response.data)
                    .then(response => {
                        _this.cards[index].hidden = 1;
                        _this.getData();
                    })
                    .catch(error => {
                    });
            },
            showColumn(index, id) {
                let _this = this;
                let ishide = {
                    "hide": 0
                };
                axios.post('/api/board-hide/' + id, ishide)
                    .then(response => response.data)
                    .then(response => {
                        _this.cards[index].hidden = 0;
                        _this.getData();
                    })
                    .catch(error => {
                    });
            },
            showItem(e, data, index, child_key) {

                $('#title'+data.cardId).addClass('card-title-blur');
                $('#title'+data.cardId).removeClass('card-title-focus');
                // let attData = $(e.target).attr('data-text');
                // let attDataNew = e.target.value;

                let attDataNew = $('#title'+data.cardId).html();
                // if( $.trim(attData) === $.trim(attDataNew)){
                //     // console.log($.trim(attData) , $.trim(attDataNew), $.trim(attData) === $.trim(attDataNew));
                //     this.getData();
                //     $('.inp').addClass('input-hide');
                //     $('.inp').removeClass('form-control');
                //     return false;
                // }
                data.data = attDataNew;
                // $('.inp').addClass('input-hide');
                // $('.inp').removeClass('form-control');
                this.saveData(data, index, child_key);
            },
            showHideTextarea(id){
                let _this = this;

                $('.inp').addClass('input-hide');
                $('.inp').removeClass('form-control');
                $(id).removeClass('input-hide');
                $(id).addClass('form-control');
                setTimeout(() => {
                    $(id).click();
                    $(id).focus();
                }, 100);
                var option = {
                    height: 50,
                    maxHeight: 200
                };
                _this.growInit(option);
            },
            saveData(data, index, child_key) {
                let _this = this;
                // console.log("data = "+data.data);
                if (data.data === "") {
                    _this.getBoardTask();
                    swal('Blank!','Title is required!','warning');
                } else {
                    let title = {
                        'title': data.data
                    };
                    axios.post('/api/card-update/' + data.cardId, title)
                    .then(response => response.data)
                    .then(response => {
                        _this.cards[index].task[child_key].name = data.data;
                        _this.getData();
                    })
                    .catch(error => {
                    });
                }
            },
            saveCardData(e, data) {
                if (e.which === 13) {
                    $('.inp').addClass('input-hide');
                    $('.inp').removeClass('form-control');
                }
            },
            updateDate(card) {
                setTimeout(function () {
                    let data = {
                        'date': card.date
                    };
                    axios.post('/api/card-update/' + card.cardId, data)
                        .then(response => response.data)
                        .then(response => {
                        })
                        .catch(error => {
                        });
                }, 300)
            },
            generateColor() {
                var myColor = '#000000';
                myColor = '#' + (Math.random() * 0xFFFFFF << 0).toString(16);
                return myColor;
            },
            changeTag(tags, card, columnIndex, cardIndex) {
                // console.log(card);
                var _this = this;
                var old = this.cards[columnIndex].task[cardIndex].tags.length;
                var newl = tags.length;
                let cardTags = null;
                if (newl > old) {
                    this.tags = tags;

                    var color = (this.tags[newl - 1].text === 'Dont Forget') ? '#ff0000' : _this.generateColor();
                    var postData = {
                        id: card.cardId,
                        tags: _this.tags[newl - 1].text,
                        color: color,
                        type: 'task',
                    };
                    axios.post('/api/task-list/add-tag', postData)
                    .then(response => response.data)
                    .then(response => {

                        setTimeout(function () {
                            _this.getBoardTask();
                           $('.dropdown-menu').removeClass('show');
                            // _this.getData();
                        }, 100);
                    })
                    .catch(error => {
                        console.log("2nd error =>"+error)
                    });
                }
            },
            deleteCardTag(obj, card, columnIndex, cardIndex) {
                var _this = this;
                var postData = {
                    assign_id: obj.tag.assign_id,
                    // id: obj.tag.id,
                };
                // console.log(obj);
                if (obj.tag.text !== 'Dont Forget') {
                    axios.post('/api/task-list/delete-tag', postData)
                        .then(response => response.data)
                        .then(response => {
                            _this.cards[columnIndex].task[cardIndex].tags.splice(obj.index, 1);
                            setTimeout(function () {
                                _this.getBoardTask();
                            }, 100);
                            _this.tags = [];
                        })
                        .catch(error => {
                            console.log('Api for delete tag not Working !!!')
                        });
                }

            },
            getAllTask() {
                this.tree4data = [];
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
            showLog() {
                var _this = this;
                axios.get('/api/task-list/get-log/' + _this.selectedData.cardId)
                    .then(response => response.data)
                    .then(response => {
                        // console.log(response);
                        _this.task_logs = response;
                        _this.ShowDetails(_this.selectedData);
                        setTimeout(function () {
                            $('#_details').click();
                            // $('#_log').click()
                        }, 300)
                    })
                    .catch(error => {
                        console.log('Api for move down task not Working !!!')
                    });
            },
            selectCard(card,child){
                this.selectedData = card;
                this.selectedCard = card.cardId;
                this.task_logs = null;
                this.HideDetails();
                $('.card-list').css("background-color", "#ffffff");
                $('#card_'+this.selectedCard).css("background-color","#ddf3fd");
                // console.log(this.selectedData);
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
                // this.getBoardTask();
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
            showAssignedUserRemoveButton(data) {

                $('[data-toggle="tooltip"]').tooltip('hide');

                setTimeout(function () {
                    $('#remove-assign-user' + data.cardId).toggleClass('remove-assign-user');
                    $('#remove-assign-user' + data.cardId).removeClass('remove-assigned');
                }, 500)

            },
            // removeAssignedUser(user_id, task_id) {

            //     // console.log(user.id, user.task_id);
            //     var _this = this;
            //     var postData = {
            //         user_id: user_id,
            //         task_id: task_id
            //     };
            //     axios.post('/api/task-list/assign-user-remove', postData)
            //         .then(response => response.data)
            //         .then(response => {
            //             // console.log(response);
            //             if (response === 'success') {
            //                 _this.cards[index].task[key].assigned_user.splice(0,1);
            //                 _this.cards[index].task[key].assigned_user_i.splice(0,1);
            //                 setTimeout(function () {
            //                     _this.getData();
            //                 }, 100);
            //             }
            //         })
            //         .catch(error => {
            //             console.log('Api assign-user-remove is not Working !!!')
            //         });
            // },
            removeAssignedUser(user_id, task_id) {

                // console.log(user.id, user.task_id);
                var _this = this;
                var postData = {
                    user_id: user_id,
                    task_id: task_id
                };
                        // console.log(postData)
                axios.post('/api/task-list/assign-user-remove', postData)
                    .then(response => response.data)
                    .then(response => {
                        if (response === 'success') {
                            // _this.cards[index].task[key].assigned_user.splice(0,1);
                            // _this.cards[index].task[key].assigned_user_ids.splice(0,1);
                            console.log(response);
                            _this.getBoardTask();
                        }
                    })
                    .catch(error => {
                        console.log('Api assign-user-remove is not Working !!!')
                    });
            },
            assignUserToTask(user, index, key, data) {
                var _this = this;
                var postData = {
                    task_id: data.cardId,
                    user_id: user.id
                };
                axios.post('/api/task-list/assign-user', postData)
                    .then(response => response.data)
                    .then(response => {
                        if (response.success === 'success') {
                            _this.cards[index].task[key].assigned_user.push(response.data);
                             console.log(_this.cards);
                            setTimeout(function () {
                                _this.getData();
                            }, 100);
                        }
                    })
                    .catch(error => {
                        console.log('Api is not Working !!!')
                    });
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
                        // _this.showTagManageModel();
                        _this.getBoardTask();
                        // _this.getData();
                        // $('#dropdown' + data._id).toggle();
                        // _this.selectedData = data
                        // _this.tag = null
                    })
                    .catch(error => {
                        console.log('Api for update color of tag not Working !!!')
                    });

            },
            DeleteTagFromModal(tag) {
                var _this = this;
                swal({
                    title: 'Are you sure to delete the tag?',
                    text: "You won't be able to revert this!",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                },
                function(){
                    var postData = {
                        id : tag.id,
                        title: tag.title,
                    };
                    axios.post('/api/task-list/delete-tag', postData)
                    .then(response => response.data)
                    .then(response => {
                        // _this.manageTag = response.tags;
                        // _this.showTagManageModel();
                        _this.getBoardTask();
                        swal("Deleted!", "The tag has been deleted.", "success");

                        // _this.getData();
                        // _this.tag = null
                    })
                    .catch(error => {
                        console.log('Api for delete tag not Working !!!');
                    });

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
                            _this.getBoardTask();
                            // _this.tag = null
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
            switchEvent(e) {
                $(e.target).closest('.eachItemRow').find('.switchToggle').collapse('toggle');
            },
        },
        directives: {
            ClickOutside
        },
        watch: {
            projectId: function (val) {
                this.projectId = val;
                this.getBoardTask()
            },
            board_id: function (val) {
                this.board_id = val;
                this.getBoardTask()
            },
            nav_id: function (val) {
                this.nav_id = val;
                this.getBoardTask()
            },
        }
    }
</script>
