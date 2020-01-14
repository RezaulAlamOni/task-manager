<template>
    <div>
        <div id="board_view_list" @click="HideDetails">
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
                                            <img :src="baseUrl+'/img/'+column.progress+'.png'" height="40" width="40"/>
                                        </div>
                                        <div class="col-md-6 " style="margin: 0px; padding : 0px;word-break: break-all">
                                            <span class="col_name"
                                                  style="word-break: break-all; max-height: 71px; display: block; height: 42px; overflow: hidden;"
                                                  :title="column.name" data-placement="bottom" data-toggle="tooltip">
                                                {{ (column.name && column.name.length > 38)? column.name.substring(0,38)+ " .." : column.name }}
                                            </span>
                                        </div>
                                        <div style="margin: 0px; padding : 0px;">
                                            <span class="total-task">{{column.children.length}}</span>
                                        </div>
                                        <div class="col-md-1"
                                             style="position: absolute; display: inline; right: 10px; top: 9px">
                                            <span class="pull-right" style="display: inline-flex;">

                                                <span class="dropdown-toggle-split opacity"
                                                      data-toggle="dropdown" style="padding: 0px;">
                                                   <i class="fal fa-ellipsis-h" style="font-size:22px"></i>
                                                </span>
                                                <div class="dropdown-menu card-option-dropdown">
                                                    <diV class="collapse show switchToggle">
                                                        <a @click="addExistingTask(index,column.boardId)"
                                                           class="dropdown-item"
                                                           href="javascript:void(0)"
                                                           v-if="column.moveToCol == false || column.ruleType === 'asnUser'">
                                                            <i class="fal fa-layer-plus mr-2"></i>
                                                            Add existing tasks
                                                        </a>
                                                        <a @click="addCard(index,column.boardId)"
                                                           class="dropdown-item"
                                                           href="javascript:void(0)"
                                                           v-if="column.moveToCol == false || column.ruleType === 'asnUser'">
                                                            <i class="fal fa-plus-square mr-2"></i>
                                                            Create new tasks
                                                        </a>
                                                        <div class="dropdown-divider"
                                                             v-if="column.moveToCol == false || column.ruleType === 'asnUser'"></div>
                                                        <a @click="updateColumSow(index)" class="dropdown-item"
                                                           href="#">
                                                            <i class="fal fa-edit mr-2"></i>
                                                            Edit column
                                                        </a>
                                                        <a @click="hideColumn(index, column.boardId)"
                                                           class="dropdown-item" href="#">
                                                            <i class="far fa-eye-slash mr-2"></i>
                                                            Hide column
                                                        </a>
                                                        <div class="dropdown-divider"></div>
                                                        <a @click="transferColumnToOtherBoard(index, column.boardId)"
                                                           class="dropdown-item" href="#">
                                                            <i class="fad fa-exchange-alt mr-2"></i>
                                                            Transfer Column to another board
                                                        </a>
                                                        <a @click="showLinkModel(index, column.boardId)"
                                                           class="dropdown-item" href="#"
                                                           v-if="column.moveToCol == false || column.ruleType === 'asnUser'">
                                                            <i class="fas fa-link mr-2"></i>
                                                            Link to List
                                                        </a>
                                                        <li class="dropdown-submenu"
                                                            v-if="column.linkToList.length > 0">
                                                            <a class="dropdown-item" href="#">
                                                                <i class="fas fa-unlink mr-2"></i>
                                                                Unlink Lists
                                                            </a>
                                                            <ul class="dropdown-menu">
                                                                <li v-for="unlinks in column.linkToList"
                                                                    class="dropdown-item"
                                                                    @click="unlinklistToCol(index, column.boardId, unlinks.id)">
                                                                    <a href="#"><img
                                                                        :src="baseUrl+'/img/task-icon/unlink.png'"
                                                                        height="18" width="18">
                                                                         {{(unlinks.link_to_list_column !== null && unlinks.link_to_list_column !== undefined) ? unlinks.link_to_list_column.list_title : ''}}
                                                                    </a>
                                                                </li>
                                                            </ul>
                                                        </li>
                                                        <!-- <a v-if="column.linkToList.length > 0" @click="unlinklistToCol(index, column.boardId)" class="dropdown-item"
                                                            href="#"><img :src="baseUrl+'/img/task-icon/unlink.png'" height="18" width="18" > Unlink Lists
                                                                 </a> -->
                                                            <div class="dropdown-divider"></div>
                                                            <a @click="deleteColumn(index,column.boardId)"
                                                               class="dropdown-item"
                                                               href="#">
                                                                <i data-v-0ca4b43b="" class="fal fa-trash-alt mr-2"
                                                                   aria-hidden="true"></i>
                                                                Delete column</a>
                                                        </diV>
                                                    </div>
                                                </span>
                                            </span>
                                        </div>
                                        <div v-if="column.moveToCol === true || column.ruleType === 'asnUser'"
                                             :id="column.boardId+'rulesCard'"
                                             style="float: left; position: absolute; top: 55px; padding: 0px 9px;">
                                            <div class="card-list card">
                                                <span v-if="column.ruleStatus === 0"
                                                      v-html="'<strong>Rule is paused</strong>'"
                                                      class="text-warning"></span>
                                                <span v-if="column.ruleType === 'mvCard'"
                                                      v-html="'This column has rule <strong>'+column.ruleName+'</strong> and moves cards to <strong>'+column.boardName+'</strong> Board in Column <strong>'+column.moveToColName+'</strong>'"></span>
                                                <span v-if="column.ruleType === 'asnUser'"
                                                      v-html="'This column has rule <strong>'+column.ruleName+',</strong> assign to <strong>'+ column.usersName +'</strong>'"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <Container
                                    :style="{'padding-top': ruleAlertHeight(column.boardId+'rulesCard', 'coll'+column.boardId)}"
                                    :drop-placeholder="dropPlaceholderOptions"
                                    :get-child-payload="getCardPayload(column.id)"
                                    :id="'coll'+column.boardId"

                                    @drop="(e) => onCardDrop(column.id, column.boardId, index, e)"
                                    drag-class="card-ghost"
                                    drop-class="card-ghost-drop"
                                    group-name="col"
                                >
                                    <Draggable :key="card.id" v-for="(card , key) in column.children">
                                        <div
                                            :class="[card.props.className,(card.priority_label !== null) ? 'pc-'+card.priority_label : '']"
                                            :style="card.props.style"
                                            class="card-list"
                                            @contextmenu="makeItClick($event, card,column.children, index, key, column.boardId)"
                                            @click="makeItClick($event, card, column.children, index, key, column.boardId)"
                                            :id="'card_'+card.cardId"
                                            v-on:dblclick="showLog(card)"
                                            v-if="card.data !== 'Dont Forget Section'">
                                            <div :id="'titleUserMention'+card.cardId" class="dropdowns-card-user"
                                                 style="z-index: 1;">
                                                <diV class="collapse show switchToggle">
                                                    <ul id="myUL-user" class="myUL-user-card"
                                                        style="background: #f3f3f3; border-radius: 5px; border: 1px solid #d4d4d4; ">
                                                        <template v-for="user in projectUsers"
                                                                  v-if="projectUsers !== null && projectUsers.length > 0">
                                                            <li @click="SearchTaskByAssignedUsers(user.id, user.name, card, user)">
                                                                <a href="javascript:void(0)">
                                                                        <span class="assignUser-suggest-photo">
                                                                            {{(user.name !== null) ? user.name.substring(0,2) : ''}}
                                                                        </span>
                                                                    {{user.name}}
                                                                </a>
                                                            </li>
                                                        </template>
                                                        <template v-for="(user, tagIndx) in card.existing_tags"
                                                                  v-if="card.existing_tags !== null && card.existing_tags.length > 0 && projectUsers === null ">
                                                            <li @click="tagMention(card, user, index , tagIndx, key)"
                                                                class="users-select row">
                                                                <!--addExistingTag(index , tagIndx, key, card.cardId, '') -->
                                                                <div class="col-md-9 add-tag-to-selected">
                                                                    <span
                                                                        class="badge badge-default tag-color-custom-contextmenu"
                                                                        :style="{'background' : user.color}">.</span>
                                                                    <h5>{{user.title}}</h5>
                                                                </div>
                                                            </li>
                                                        </template>
                                                        <!-- <template v-else>
                                                            <li>
                                                                <a href="javascript:void(0)">
                                                                    No user found!
                                                                </a>
                                                            </li>
                                                        </template> -->
                                                    </ul>
                                                </diV>
                                            </div>
                                            <!-- @click="selectCard(card)" -->
                                            <span
                                                :class="[(card.priority_label !== null) ? 'pch-'+card.priority_label : 'ch-option']"
                                            > <!-- v-if="card.textareaShow === true" -->
                                                <span class="dropdown-toggle-split opacity"
                                                      data-toggle="dropdown">
                                                    <i class="fal fa-ellipsis-h"
                                                       :class="[(card.priority_label !== null) ? 'ch-option-icon' : '']"
                                                       style="color: #272757; font-size:22px;"></i>
                                                </span>
                                                <div class="dropdown-menu card-option-dropdown">
                                                    <diV class="collapse show switchToggle">

                                                        <a @click="showTransferModel(index, key, card.cardId, column.boardId)"
                                                           class="dropdown-item" href="#"
                                                           title="Transfer To Other Board Or Column">
                                                            <i class="fad fa-sort-alt mr-2"></i> Transfer task to another board
                                                        </a>

                                                        <div class="dropdown-divider"
                                                             v-if="card.types === 'task'"></div>
                                                        <a @click="deleteTask(index, key, card.cardId)"
                                                           class="dropdown-item" title="Remove Task"
                                                           href="#" v-if="card.types === 'task'">
                                                            <i class="fal fa-minus-octagon mr-2"></i>
                                                            Remove task from <strong> This Board </strong>
                                                        </a>
                                                        <div class="dropdown-divider"></div>
                                                        <a @click="RemoveNodeAndChildren()" class="dropdown-item"
                                                           href="#">
                                                            <i class="fad fa-trash-alt mr-2"></i>
                                                            Delete the task
                                                        </a>
                                                    </diV>
                                                </div>
                                            </span>

                                            <div :id="'title'+card.cardId" contenteditable="true"
                                                 class="card-title-blur card-title-show"
                                                 @click="makeInput($event,card.cardId)"
                                                 @blur="showItem($event,card,index,key)"
                                                 @keypress="preventEnter($event)"
                                                 @keyup="cardTitlePress($event,card,index,key)"
                                                 v-html="card.data">
                                            </div>
                                            <div>
                                                <a class="calender clickHide" v-if="card.date === '0000-00-00'">
                                                    <i class="fal fa-calendar-plus icon-image-preview" data-toggle
                                                       title="toggle"></i>
                                                </a>
                                                <flatPickr
                                                    :class="{
                                                            dateCal:true,
                                                            'flatpickr-input': true,
                                                            'flatpickr-input1': card.date != '0000-00-00' ? false : true,
                                                                active: true,
                                                                dateCal1: card.date != '0000-00-00' ? true : false
                                                            }"
                                                    style="width: 40px;"
                                                    :config="date_config"
                                                    @on-change="updateDate(card)"
                                                    name="date"
                                                    v-model="card.date">
                                                </flatPickr>
                                            </div>
                                            <div class="card-delete-icon">
                                                <a @click="deleteCard(index, key, card.cardId)"
                                                   v-if="card.types == 'card'">
                                                    <!-- <i class="baseline-playlist_delete icon-image-preview"></i> -->
                                                    <i class="fal fa-trash-alt icon-image-preview"></i>
                                                </a>
                                                <a @click="deleteTask(index, key, card.cardId)"
                                                   v-if="card.types == 'task'">
                                                    <!-- <i class="baseline-playlist_delete icon-image-preview"></i> -->
                                                    <i class="fal fa-trash-alt icon-image-preview"></i>
                                                </a>
                                            </div>
                                            <a class="user-assign-card ">
                                                <template v-if="card.assigned_user.length > 0">
                                                    <span class="assigned_user-card "
                                                          data-toggle="dropdown"
                                                          v-for="(assign,keyId) in card.assigned_user">
                                                        <p :title="assign.name" style="margin-right: 1px;"
                                                           class="assignUser-photo-for-card text-uppercase"
                                                           data-placement="bottom" data-toggle="tooltip"
                                                           v-if="keyId <= 1">{{(assign.name !== null) ? assign.name.substring(0,2) : ''}}
                                                        </p>
                                                    </span>
                                                </template>
                                                <span data-toggle="dropdown" class=" dropdown-toggle-split" v-else>
                                                    <i class="fal fa-user-plus icon-image-preview "
                                                       data-toggle="tooltip"
                                                       title="Assignee"></i>
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
                                                                            {{(user.name !== null) ?
                                                                            user.name.substring(0,2) : ''}}</p>
                                                                    </div>
                                                                    <div class="col-md-9 assign-user-name-email">
                                                                        <h5>{{user.name}}<br>
                                                                            <small>{{user.email}}</small>
                                                                        </h5>
                                                                    </div>
                                                                    <a :id="'remove-assign-user'+user.id"
                                                                       v-if="card.assigned_user_ids.includes(user.id)"
                                                                       @click="removeAssignedUser(user, card)"
                                                                       data-toggle="tooltip"
                                                                       title="Remove user from assigned !"
                                                                       class="remove-assign-user badge badge-danger"
                                                                       href="javascript:void(0)">
                                                                        <i class="fal fa-user-times"></i>
                                                                    </a>
                                                                    <a :id="'remove-assign-user'+user.id" v-else
                                                                       data-toggle="tooltip"
                                                                       title="Assign user to task!"
                                                                       class="remove-assign-user badge badge-success"
                                                                       href="javascript:void(0)">
                                                                        <i class="fal fa-user-times"></i>
                                                                    </a>
                                                                </div>

                                                            </template>
                                                        </li>
                                                    </diV>
                                                </div>
                                            </a>
                                            <a class="tag-icon">
                                                <div v-if="card.tags && card.tags.length !== 0">
                                                    <div style="float: left;" v-for="(item, tagIndex) in card.tags">
                                                        <div class="dropdown-toggle-split "
                                                             data-toggle="dropdown"
                                                             style="padding-right: 0px; padding-left: 1px;"
                                                             v-if="tagIndex < 2">
                                                            <span class="badge badge-danger "
                                                                  v-if='item == "Dont Forget"'>
                                                                {{item.text.substring(0,12)}}
                                                            </span>
                                                            <span :title="card.tagTooltip"
                                                                  class="badge badge-success "
                                                                  data-placement="bottom"
                                                                  data-toggle="tooltip"
                                                                  v-bind:style="[{'background': item.color },{'margin-left' : 1 +'px'}]"
                                                                  style="height: 18px;line-height: 14px;"
                                                                  v-else>
                                                                {{item.text.substring(0,5)}}
                                                                <span v-if="item.text.length > 5">.</span>
                                                            </span>
                                                        </div>

                                                        <div :id="'dropdown1'+card.cardId"
                                                             class="dropdown-menu dropdown-menu1">

                                                            <diV class="collapse show switchToggle" style="">
                                                                <div class="container-fluid">
                                                                    <vue-tags-input
                                                                        :allow-edit-tags="true"
                                                                        :tags="card.tags"
                                                                        @before-deleting-tag="deleteTag => deleteCardTag(deleteTag,card,index,key)"
                                                                        @tags-changed="newTags => (changeTag(newTags,card,index,key))"
                                                                        v-model="tag"/>
                                                                    <div class="row">
                                                                        <div class="col-12">
                                                                            <template
                                                                                v-for="(tag, tagIndx) in card.existing_tags">
                                                                                <li class="badge-pill tags"
                                                                                    @click="addExistingTag(index , tagIndx, key, card, '')"
                                                                                    v-bind:style="[{'background': tag.color },{'margin-left' : 1 +'px'}]"
                                                                                    v-if="tag.text !== 'Dont Forget'">
                                                                                    {{(tag.title !== undefined) ?
                                                                                    tag.title.substring(0,12) : ''}}
                                                                                </li>
                                                                            </template>
                                                                            <li @click="addExistingTag(index , 0, key, card, 'Dont Forget')"
                                                                                class="badge-pill tags"
                                                                                style="background: #FB8678;height: 17px;line-height: 14px;">
                                                                                Dont Forget
                                                                            </li>
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
                                                <i class="fal fa-tags icon-image-preview" data-toggle="dropdown"
                                                   title="Add Tag" v-else></i>
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
                                                                    <template
                                                                        v-for="(tag, tagIndx) in card.existing_tags">
                                                                        <li class="badge-pill tags"
                                                                            @click="addExistingTag(index , tagIndx, key, card ,'')"
                                                                            v-bind:style="[{'background': tag.color },{'margin-left' : 1 +'px'}]"
                                                                            v-if="tag.text !== 'Dont Forget'">
                                                                            {{(tag.title !== undefined)
                                                                            ?tag.title.substring(0,12) : ''}}
                                                                        </li>
                                                                    </template>
                                                                    <li @click="addExistingTag(index , 0, key, card, 'Dont Forget')"
                                                                        class="badge-pill tags"
                                                                        style="background: #FB8678"> Dont Forget
                                                                    </li>
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
                                            <a class="priority-icon-card" data-toggle="tooltip" title="Priority">
                                                <span class="priority-icon dropdown-toggle-split"
                                                      v-if="card.priority_label !== null"
                                                      style="top: 12px;"
                                                      data-toggle="dropdown">
                                                     <span title="" data-placement="bottom" data-toggle="tooltip"
                                                           v-if="card.priority_label === 'high'"
                                                           class="badge badge-warning text-capitalize "
                                                           style="background: #e25858;height: 18px; line-height: 13px;color:#ffffff "
                                                           data-original-title="">
                                                        {{card.priority_label}}
                                                    </span>
                                                    <span title="" data-placement="bottom" data-toggle="tooltip"
                                                          v-if="card.priority_label === 'low'"
                                                          class="badge badge-warning text-capitalize "
                                                          style="background: #5987d1;height: 18px; line-height: 13px; "
                                                          data-original-title="">
                                                        {{card.priority_label}}
                                                    </span>
                                                    <span title="" data-placement="bottom" data-toggle="tooltip"
                                                          v-if="card.priority_label === 'medium'"
                                                          class="badge badge-warning text-capitalize "
                                                          style="background: #e58c62;height: 18px; line-height: 13px;"
                                                          data-original-title="">
                                                        {{card.priority_label}}
                                                     </span>
                                                </span>
                                                <i class="fal fa-exclamation-triangle icon-image-preview" v-else
                                                   style="margin-bottom: -2px"
                                                   data-toggle="dropdown" title="Add Priority"></i>

                                                <div class="dropdown-menu dropdown-menu-right"
                                                     style="z-index: 1;width: 185px;">
                                                    <div class="collapse show switchToggle">
                                                        <ul>
                                                            <li class="assignUser">
                                                                <label class="pl-2 label-text">
                                                                <span class="assign-user-drop-down-text">
                                                                    Select Task Priority
                                                                </span>
                                                                </label>
                                                            </li>
                                                            <li class="assignUser">

                                                                <div class="users-select row">
                                                                    <div class="col-md-9 add-tag-to-selected"
                                                                         @click="Add_Priority('3',card)">
                                                                    <span
                                                                        class="badge badge-default tag-color-custom-contextmenu"
                                                                        style="background: #e25858;">.</span>
                                                                        <h5 class="text-capitalize"> high</h5>
                                                                    </div>
                                                                    <div @click="RemovePriority(card)"
                                                                         style=" width: 20%; text-align: right;padding-top: 4px;font-size: 16px"
                                                                         v-if="card.priority_label === 'high'">
                                                                    <span>
                                                                        <i class="far fa-minus-octagon"></i>
                                                                    </span>
                                                                    </div>
                                                                </div>
                                                                <div class="users-select row">
                                                                    <div class="col-md-9 add-tag-to-selected"
                                                                         @click="Add_Priority('2',card)">
                                                                    <span
                                                                        class="badge badge-default tag-color-custom-contextmenu"
                                                                        style="background: #e58c62;">.</span>
                                                                        <h5 class="text-capitalize">medium</h5>
                                                                    </div>
                                                                    <div @click="RemovePriority(card)"
                                                                         style=" width: 20%; text-align: right;padding-top: 4px;font-size: 16px"
                                                                         v-if="card.priority_label === 'medium'">
                                                                        <i class="far fa-minus-octagon"></i>
                                                                    </div>
                                                                </div>
                                                                <div class="users-select row">
                                                                    <div class="col-md-9 add-tag-to-selected"
                                                                         @click="Add_Priority('1',card)">
                                                                    <span
                                                                        class="badge badge-default tag-color-custom-contextmenu"
                                                                        style="background: #5987d1;">.</span>
                                                                        <h5>Low</h5>
                                                                    </div>
                                                                    <div @click="RemovePriority(card)"
                                                                         style=" width: 20%; text-align: right;padding-top: 4px;font-size: 16px"
                                                                         v-if="card.priority_label === 'low'">
                                                                        <i class="far fa-minus-octagon"></i>
                                                                    </div>
                                                                </div>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </a>
                                            <div :class="[(card.priority_label !== null) ? 'pch-total-child' : 'total-child']">
                                                <span @click="hideChilds(card.cardId)" v-if="card.child > 0 ">
                                                    <i v-if="card.open === 0" class="fal fa-layer-minus"
                                                    style="font-size: 14px;"></i>
                                                    <i v-if="card.open === 1" class="fal fa-layer-plus"
                                                    style="font-size: 14px;"></i>
                                                    <i v-if="card.open === null" class="fal fa-layer-minus"
                                                    style="font-size: 14px;"></i>
                                                    <strong>
                                                        {{ card.child }}
                                                    </strong>
                                                </span>
                                                <span v-if="card.comment.length > 0" title="Comments">
                                                    <i class="fal fa-comments"></i>
                                                </span>
                                                <span v-if="card.description !== ''" title="Description">
                                                    <!-- <i class="fal fa-clipboard-prescription"></i> -->
                                                    <i class="fal fa-align-justify"></i>
                                                </span>
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
                    <div class="jquery-accordion-menu" id="jquery-accordion-menu" v-click-outside="HideCustomMenu">
                        <ul>
                            <!-- <li>
                                <a href="javascript:void(0)" data-toggle="dropdown" class="dropdown-toggle-split ">
                                    <img src="/img/task-icon/copy-1.png" class="contex-menu-icon"> Copy </a>
                                <span class="contex-menu-sortcut">
                                    <span class="badge-pill badge-default">Ctrl</span>+
                                    <span class="badge-pill badge-default">C</span>
                                </span>
                            </li> -->
                            <!-- <li>
                                <a href="javascript:void(0)" data-toggle="dropdown" class="dropdown-toggle-split ">
                                    <img src="/img/task-icon/cut.png" class="contex-menu-icon"> Cut </a>
                                <span class="contex-menu-sortcut">
                                    <span class="badge-pill badge-default">Ctrl</span>+
                                    <span class="badge-pill badge-default">X</span>
                                </span>
                            </li> -->
                            <!-- <li>
                                <a href="javascript:void(0)" data-toggle="dropdown" class="dropdown-toggle-split disabled" style="color: gray;">
                                    <img src="/img/task-icon/past.png" class="contex-menu-icon"> Paste </a>
                                <span class="contex-menu-sortcut">
                                    <span class="badge-pill badge-default">Ctrl</span>+
                                    <span class="badge-pill badge-default">v</span>
                                </span>
                            </li> -->
                            <li>
                                <a href="javascript:void(0)"
                                   data-toggle="dropdown" class="dropdown-toggle-split ">
                                    <i class="fal fa-user-plus " aria-hidden="true"></i>
                                    Assign User to Selected
                                </a>
                                <span class="contex-menu-sortcut">
                                    <span class="badge-pill badge-default">Ctrl</span>+<span
                                    class="badge-pill badge-default">U</span>
                                </span>
                                <div class="dropdown-menu dropdown-menu-right">
                                    <div class="collapse show switchToggle">
                                        <ul>
                                            <li class="assignUser">
                                                <label class="pl-2 label-text">
                                                    <span class="assign-user-drop-down-text">
                                                        Select User For Assign To Selected Task.
                                                    </span>
                                                </label>
                                            </li>
                                            <li class="assignUser">
                                                <!-- {{ scene.children[0].children[0].users }} -->
                                                <!-- v-for="user in scene.children[0].children[0].users" -->
                                                <template v-for="user in allUsers">
                                                    <div @click="ActionToSelectedTask(user.id,'user')"
                                                         class="users-select row">
                                                        <div class="col-md-3 pt-1 pl-4">
                                                            <p class="assignUser-photo">
                                                                {{(user.name !== null) ? user.name.substring(0,2) : ''}}
                                                            </p>
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
                                    </div>
                                </div>
                            </li>
                            <li>

                                <a @click="deleteSelectedTask" href="javascript:void(0)">
                                    <i class="fad fa-acorn"></i>
                                    Delete Selected
                                    <span class="badge-pill badge-default contex-menu-sortcut">Delete</span>
                                </a>
                            </li>
                            <li>
                                <a href="javascript:void(0)" data-toggle="dropdown" class="dropdown-toggle-split ">
                                    <i class="fal fa-tags contex-menu-icon" aria-hidden="true"></i>
                                    Add Tags
                                </a>
                                <span class="contex-menu-sortcut">
                                    <span class="badge-pill badge-default">Shift</span>+
                                    <span class="badge-pill badge-default">#</span>
                                </span>
                                <div class="dropdown-menu dropdown-menu-right" style="width: 240px;">
                                    <div class="collapse show switchToggle">
                                        <ul>
                                            <li class="assignUser">
                                                <label class="pl-2 label-text">
                                                    <span class="assign-user-drop-down-text">
                                                        Select Tag
                                                    </span>
                                                </label>
                                            </li>
                                            <li class="assignUser">
                                                <template v-for="user in allTags">
                                                    <div @click="ActionToSelectedTask(user.id,'tag')"
                                                         class="users-select row">
                                                        <div class="col-md-9 add-tag-to-selected">
                                                            <span
                                                                class="badge badge-default tag-color-custom-contextmenu"
                                                                :style="{'background' : user.color}">.</span>
                                                            <h5>{{user.title}}</h5>
                                                        </div>
                                                    </div>
                                                </template>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </li>
                            <li>
                                <a @click="showTransferModel(this.currentColumn , '' , '', this.currentColumnIndex)">
                                    <i class="far fa-compress"></i>
                                    Move to another column or Board
                                </a>
                                <span class="contex-menu-sortcut">
                                    <span class="badge-pill badge-default">Ctrl</span>+
                                    <span class="badge-pill badge-default">M</span>
                                </span>
                            </li>
                            <li>
                                <a href="javascript:void(0)" class="dropdown-toggle-split " data-toggle="dropdown">
                                    <i class="fal fa-exclamation-triangle contex-menu-icon"></i>
                                    Add Priority
                                </a>
                                <!-- <span class="contex-menu-sortcut">-->
                                <!--    <span class="badge-pill badge-default">Shift</span>+<span-->
                                <!--           class="badge-pill badge-default">#</span>-->
                                <!-- </span>-->
                                <div class="dropdown-menu dropdown-menu-right">
                                    <div class="collapse show switchToggle">
                                        <ul>
                                            <li class="assignUser">
                                                <label class="pl-2 label-text">
                                                        <span class="assign-user-drop-down-text">
                                                            Select Task Priority
                                                        </span>
                                                </label>
                                            </li>
                                            <li class="assignUser">
                                                <div class="users-select row"
                                                     @click="Add_Priority('3',null)">
                                                    <div class="col-md-9 add-tag-to-selected">
                                                            <span
                                                                class="badge badge-default tag-color-custom-contextmenu"
                                                                style="background: #e25858;">.</span>
                                                        <h5 class="text-capitalize"> high</h5>
                                                    </div>
                                                </div>
                                                <div class="users-select row"
                                                     @click="Add_Priority('2',null)">
                                                    <div class="col-md-9 add-tag-to-selected">
                                                            <span
                                                                class="badge badge-default tag-color-custom-contextmenu"
                                                                style="background: #e58c62;">.</span>
                                                        <h5 class="text-capitalize">medium</h5>
                                                    </div>
                                                </div>
                                                <div class="users-select row"
                                                     @click="Add_Priority('1',null)">
                                                    <div class="col-md-9 add-tag-to-selected">
                                                            <span
                                                                class="badge badge-default tag-color-custom-contextmenu"
                                                                style="background: #5987d1;">.</span>
                                                        <h5>Low</h5>
                                                    </div>
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </li>
                            <li v-if="selectedCards.types == 'task'">
                                <a @click="deleteAllTasks(currentColumnIndex, currentColumnKey, selectedCards.cardId)"
                                   href="javascript:void(0)">
                                    <i class="fad fa-eraser"></i>
                                    Remove Tasks From This Column
                                    <!-- <span class="badge-pill badge-default contex-menu-sortcut">Delete</span> -->
                                </a>
                            </li>
                            <!-- <li>
                                <a href="javascript:void(0)"><img src="/img/task-icon/move.png" class="contex-menu-icon"> Move Selected
                                </a> <span class="contex-menu-sortcut"><span class="badge-pill badge-default">Ctrl</span>+<span class="badge-pill badge-default">M</span></span>
                            </li> -->

                            <li style="position: relative" @click="openPicker()">
                                <datepicker
                                    :disabled-dates="disabledDates"
                                    v-model="date_for_selected"
                                    @selected="ActionToSelectedTask('','date')"
                                    calendar-button-icon='<i class="outline-event icon-image-preview"></i>'
                                    format='dd MMM'
                                    input-class="dateCal-selected">
                                </datepicker>
                                <a class="calender li-opacity clickHide">
                                    <i class="fal fa-calendar-plus contex-menu-icon"></i>
                                    Set Due Date
                                </a>
                            </li>
                            <!-- <li>
                                <a href="javascript:void(0)"><img src="/img/task-icon/move-1.png" class="contex-menu-icon"> Move To Dont Forget Section </a>
                            </li> -->
                        </ul>
                    </div>
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
                                            <a @click="DeleteTagFromModal(tag)"
                                               class="compltit-blue-a badge badge-danger"
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

        <div aria-hidden="true" aria-labelledby="exampleModalLabel" class="modal fade" id="transferCard" role="dialog" tabindex="-1">
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
                                            v-if="navs.type === 'board'"> {{navs.title}}
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
                                    <option :key="index" v-bind:value="navList.id"
                                            v-for="(navList, index) in boardColumn">
                                        {{navList.title}}
                                    </option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button v-if="transferBtn" aria-label="Close" @click="transferCardToOtherBoard"
                                class="btn btn-success" data-dismiss="modal" type="button">Transfer
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
                        <button v-if="transferBtn" aria-label="Close" @click="transferColumnToOtherBoardSave"
                                class="btn btn-success" data-dismiss="modal" type="button">Transfer
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
                        <h4 class="text-center ">Link List To Column </h4>
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
                        <button v-if="linkBtn" aria-label="Close" @click="listLinkToCol" class="btn btn-success"
                                data-dismiss="modal" type="button">Link
                        </button>
                        <button aria-label="Close" class="btn btn-secondary" data-dismiss="modal" type="button">Cancel
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div aria-hidden="true" aria-labelledby="exampleModalLabel" class="modal fade" id="loader" role="dialog"
             tabindex="-1">
            <div class="modal-dialog " role="document">
                <div>
                </div>
            </div>
        </div>
        <div aria-hidden="true" aria-labelledby="exampleModalLabel" class="modal fade" id="user_list" role="dialog"
             tabindex="-1">
            <div class="modal-dialog " role="document">
                <div class="modal-content">
                    <div class="modal-header" style="border-radius: 13px;">
                        <h5 class="modal-title">User List</h5>
                        <button aria-label="Close" class="close" data-dismiss="modal" type="button">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body list-model">
                        <div class="form-group row">
                            <div class="col-sm-9">
                                <ul class="list-group list-group-flush">
                                    <li class="list-group-item">
                                        <label class="checkbox_cus_mini">
                                            <input @click="checkedAllUser(allUsers)" type="checkbox"
                                                   class="checkedAllUser"> All
                                            <span class="checkmark"></span>
                                        </label>
                                    </li>
                                    <li class="list-group-item" v-for="user in allUsers">
                                        <label class="checkbox_cus_mini">
                                            <input v-model="userIdList" :value="user.id"
                                                   @click="addUserToFilter(user.id)" type="checkbox" name="side_dav"> {{
                                            user.name }}
                                            <span class="checkmark"></span>
                                        </label>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <!-- {{ selectedExistedTask }} -->
                        <button class="btn btn-primary" @click="userFilter()" type="button">Filter User</button>
                        <!-- <button @click="clearInputFeild" class="btn btn-secondary" type="button">Cancel</button> -->
                    </div>
                </div>
            </div>
        </div>
        <div aria-hidden="true" aria-labelledby="exampleModalLabel" class="modal fade" id="priority_list_modal"
             role="dialog"
             tabindex="-1">
            <div class="modal-dialog " role="document">
                <div class="modal-content">
                    <div class="modal-header" style="border-radius: 13px;">
                        <h5 class="modal-title">Priority List </h5>
                        <button aria-label="Close" class="close" data-dismiss="modal" type="button">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body list-model">
                        <div class="form-group row">
                            <div class="col-sm-9">
                                <ul class="list-group list-group-flush">
                                    <!--                                    <li class="list-group-item" >-->
                                    <!--                                        <label class="checkbox_cus_mini">-->
                                    <!--                                            <input type="checkbox" class="checkedUser"> All-->
                                    <!--                                            <span class="checkmark"></span>-->
                                    <!--                                        </label>-->
                                    <!--                                    </li>-->
                                    <li class="list-group-item">
                                        <label class="checkbox_cus_mini">
                                            <input @click="addFilterToFilter('3')" type="checkbox" class="checkedUser"> Heigh
                                            <span class="checkmark"></span>
                                        </label>
                                    </li>
                                    <li class="list-group-item">
                                        <label class="checkbox_cus_mini">
                                            <input @click="addFilterToFilter('2')" type="checkbox" class="checkedUser"> Medium
                                            <span class="checkmark"></span>
                                        </label>
                                    </li>
                                    <li class="list-group-item">
                                        <label class="checkbox_cus_mini">
                                            <input @click="addFilterToFilter('1')" type="checkbox" class="checkedUser"> Low
                                            <span class="checkmark"></span>
                                        </label>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <!-- {{ selectedExistedTask }} -->
                        <button class="btn btn-primary" @click="priorityHide()" type="button">Hide</button>
                        <button class="btn btn-primary" @click="priorityShow()" type="button">Show</button>
                        <!-- <button @click="clearInputFeild" class="btn btn-secondary" type="button">Cancel</button> -->
                    </div>
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
                                    <input type="checkbox" @change="selectAll()" class="checkedAll" name="side_dav"> All
                                    <span class="checkmark"></span>
                                </label>
                                <!-- <input type="checkbox" @change="selectAll()" class="checkedAll"> All <br> -->
                            </div>
                            <div v-for="tree in tree4data">
                                <li class="list-group-item"
                                    :class="(tree.board_parent_id !== null && tree.children.length <= 0) ? 'list-group-item-hide' : ''">
                                    <label
                                        :class="{'checkbox_cus_mini' : true, '_pen_disable' : true,'disabledTask': tree.board_parent_id !== null}"
                                        v-if="tree.text !== '' && tree.text !== 'Dont Forget Section' && tree.board_parent_id !== null && tree.children.length > 0"
                                        v-html="tree.text">
                                        <!-- <input :id="tree.id" :value="tree.id" type="checkbox"
                                                v-if="tree.text !== '' && tree.board_parent_id !== null"
                                                checked disable> -->

                                        <!-- <span class="checkmark"></span>-->
                                    </label>
                                    <label class="checkbox_cus_mini"
                                           v-if="tree.text !== '' && tree.text !== 'Dont Forget Section' && tree.board_parent_id == null">
                                        <input :id="tree.id" :value="tree.id" type="checkbox"
                                               v-model="selectedExistedTask"
                                               :class="{'selectAll': true}" @change="selectChild(tree.id)"
                                               v-if="tree.text !== '' && tree.board_parent_id === null">
                                        <span v-html="tree.text"></span>
                                        <span class="checkmark"></span>
                                    </label>
                                    <ul class="list-group list-group-flush" v-if="tree.children">
                                        <div v-for="child in tree.children"
                                             :class="(child.board_parent_id !== null && child.children.length <= 0) ? 'list-group-item-hide' : ''">
                                            <li class="list-group-item">
                                                <label
                                                    :class="{'checkbox_cus_mini' : true, '_pen_disable' : true,'disabledTask': child.board_parent_id !== null}"
                                                    v-if="child.text !== '' && child.text !== 'Dont Forget Section' && child.board_parent_id !== null && child.children.length > 0">
                                                    <input :id="child.id" class="tree-child" :value="child.id"
                                                           type="checkbox"
                                                           checked disable> <span v-html="child.text"></span>
                                                    <!--<span class="checkmark"></span>-->
                                                </label>
                                                <label class="checkbox_cus_mini"
                                                       v-if="child.text !== '' && child.text !== 'Dont Forget Section' && child.board_parent_id == null">
                                                    <input :id="child.id" class="tree-child selectAll" :value="child.id"
                                                           type="checkbox" v-model="selectedExistedTask"
                                                           @change="selectChild(child.id)"> <span
                                                    v-html="child.text"></span>
                                                    <span class="checkmark"></span>
                                                </label>

                                                <ul class="list-group list-group-flush" v-if="child.children">
                                                    <div v-for="child1 in child.children"
                                                         :class="(child1.board_parent_id !== null && child1.children.length <= 0) ? 'list-group-item-hide' : ''">
                                                        <li class="list-group-item">
                                                            <label
                                                                :class="{'checkbox_cus_mini' : true, '_pen_disable' : true,'disabledTask': child1.board_parent_id !== null}"
                                                                v-if="child1.text !== '' && child1.text !== 'Dont Forget Section' && child1.board_parent_id !== null  && child1.children.length > 0">
                                                                <input :id="child1.id" :value="child1.id"
                                                                       class="tree-child"
                                                                       type="checkbox"
                                                                       checked disable> <span
                                                                v-html="child1.text"></span>
                                                                <!-- <span class="checkmark"></span>-->
                                                            </label>

                                                            <label class="checkbox_cus_mini"
                                                                   v-if="child1.text !== '' && child1.text !== 'Dont Forget Section' && child1.board_parent_id == null">
                                                                <input :id="child1.id" :value="child1.id"
                                                                       class="tree-child selectAll"
                                                                       type="checkbox" v-model="selectedExistedTask"
                                                                       @change="selectChild(child1.id)"><span
                                                                v-html="child1.text"></span>
                                                                <span class="checkmark"></span>
                                                            </label>
                                                            <ul class="list-group list-group-flush"
                                                                v-if="child1.children">
                                                                <div v-for="child2 in child1.children"
                                                                     :class="(child2.board_parent_id !== null && child2.children.length <= 0) ? 'list-group-item-hide' : ''">
                                                                    <li class="list-group-item">
                                                                        <label
                                                                            :class="{'checkbox_cus_mini' : true, '_pen_disable' : true,'disabledTask': child2.board_parent_id !== null}"
                                                                            v-if="child2.text !== '' && child2.text !== 'Dont Forget Section' && child2.board_parent_id !== null  && child2.children.length > 0">
                                                                            <input :id="child2.id" :value="child2.id"
                                                                                   class="tree-child"
                                                                                   type="checkbox"
                                                                                   checked disable> <span
                                                                            v-html="child2.text"></span>
                                                                            <!-- <input type="checkbox" @change="selectAll()" class="checkedAll" name="side_dav" > All -->
                                                                            <!-- <span class="checkmark"></span>-->
                                                                        </label>

                                                                        <label class="checkbox_cus_mini"
                                                                               v-if="child2.text !== '' && child2.text !== 'Dont Forget Section' && child2.board_parent_id == null">
                                                                            <input :id="child2.id" :value="child2.id"
                                                                                   class="tree-child selectAll"
                                                                                   type="checkbox"
                                                                                   v-model="selectedExistedTask"
                                                                                   @change="selectChild(child2.id)">
                                                                            <span v-html="child2.text"></span>
                                                                            <!-- <input type="checkbox" @change="selectAll()" class="checkedAll" name="side_dav" > All -->
                                                                            <span class="checkmark"></span>
                                                                        </label>

                                                                        <ul class="list-group list-group-flush"
                                                                            v-if="child2.children">
                                                                            <div v-for="child3 in child2.children"
                                                                                 :class="(child3.board_parent_id !== null && child3.children.length <= 0) ? 'list-group-item-hide' : ''">
                                                                                <li class="list-group-item">
                                                                                    <label
                                                                                        :class="{'checkbox_cus_mini' : true, '_pen_disable' : true,'disabledTask': child2.board_parent_id !== null}"
                                                                                        v-if="child3.text !== '' && child3.text !== 'Dont Forget Section' && child3.board_parent_id !== null && child3.children.length > 0">
                                                                                        <input :id="child3.id"
                                                                                               :value="child3.id"
                                                                                               class="tree-child"
                                                                                               type="checkbox"
                                                                                               checked disable>
                                                                                        <span
                                                                                            v-html="child3.text"></span>
                                                                                        <!-- <input type="checkbox" @change="selectAll()" class="checkedAll" name="side_dav" > All -->
                                                                                        <!-- <span class="checkmark"></span>-->
                                                                                    </label>

                                                                                    <label class="checkbox_cus_mini"
                                                                                           v-if="child3.text !== '' && child3.text !== 'Dont Forget Section' && child3.board_parent_id == null">
                                                                                        <input :id="child3.id"
                                                                                               :value="child3.id"
                                                                                               class="tree-child selectAll"
                                                                                               type="checkbox"
                                                                                               v-model="selectedExistedTask"
                                                                                               @change="selectChild(child3.id)">
                                                                                        <span
                                                                                            v-html="child3.text"></span>
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
                        <button @click="AddExistingTasks" class="btn btn-primary" type="button">Add Tasks</button>&nbsp;
                        <button @click="clearInputFeild" class="btn btn-secondary" type="button">Cancel</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="detailsShowFull" id="details">
            <TaskDetails v-if="Object.keys(selectedData).length > 0"
                         :selectedData="selectedData"
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
        props: ['nav_id', 'board_id', 'projectId', 'filter_type', 'auth_user'],
        components: {Container, Draggable, flatPickr, switches, VueTagsInput, Datepicker, TaskDetails},
        data() {
            return {
                baseUrl: window.location.origin,
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
                    altFormat: 'd M Y',
                    dateFormat: 'd M Y',
                },
                date_for_selected: null,
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
                transferBtn: false,
                linkBtn: false,
                project: null,
                tree4data: [],
                currentColumn: null,
                currentColumnIndex: null,
                currentColumnKey: null,
                cards: [],
                selectedCards: [],
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
                    nav_id: null,
                    type: 'board'
                },
                navItem: {
                    title: null,
                    type: null,
                    sort_id: null,
                    project_id: null,
                },
                selectedData: {},
                selectedIds: [],
                task_logs: null,
                check_uncheck_child: null,
                manageTag: null,
                allUsers: null,
                allTags: null,
                disabledDates: {
                    id: null,
                },
                shift_first: null,
                triggers: null,
                tagTriggers: null,
                userNames: '',
                projectUsers: null,
                commentsData: null,
                filter_types: null,
                selectedPriorites: [],
                userIdList: [],
                Socket : null,
                authUser : null
            }
        },
        mounted() {
            var _this = this;
            _this.authUser = _this.auth_user;
            _this.$toastr.defaultTimeout = 1000;
            $('#header-item').text('Project  / Task Board');
            $(document)
                .one('focus.autoExpand', 'textarea.autoExpand', function () {
                    var savedValue = this.value;
                    this.value = '';
                    this.baseScrollHeight = this.scrollHeight;
                    this.value = savedValue;
                })
                .on('input.autoExpand', 'textarea.autoExpand', function () {
                    var minRows = this.getAttribute('data-min-rows') | 0, rows;
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
            $('#loder-hide').fadeIn();
            _this.getBoardTask();
            $(document).ready(function () {
                $('.searchList').hide();
            });
            this.getAuthUser();
            this.connectSocket();

        },
        created() {
            let _this = this;
            hotkeys('enter,tab,shift+tab,up,down,left,right,ctrl+c,ctrl+x,ctrl+v,ctrl+t,delete,ctrl+b,ctrl+s,ctrl+i,shift+3', function (event, handler) {
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
                        _this.showLog(_this.selectedData);
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
                        var nav_type = JSON.parse(localStorage.selected_nav);
                        console.log(nav_type)
                        if (nav_type.type === 'board' && _this.selectedIds.length > 0) {
                            _this.deleteSelectedTask();
                        }
                        break;
                    case "ctrl+t":
                        _this.showTransferModel(_this.currentColumn, '', '', _this.currentColumnIndex);
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
                        // $('#dropdown1' + _this.selectedData.cardId).click();
                        // console.log(_this.selectedData.cardId);
                        break;
                }
            });
        },
        methods: {
            connectSocket: function () {
                let app = this;
                if (app.Socket == null) {
                    // app.Socket = io.connect('http://localhost:4100/');
                    app.Socket = io.connect('https://spark.compltit.net:4100/');

                    app.Socket.on('CardMoved', function (res) {
                        // if (res.project_id == app.projectId && res.user_id != app.authUser.id){
                        if (res.project_id == app.projectId){
                            app.getBoardTask();
                            // swal('Card moved','You assign on a task!', 'success');
                        }
                    })
                    app.Socket.on('taskUpdateSocket', function (res) {
                        console.log( res.user_id + " " +app.authUser.id)
                        if (res.board_id == app.board_id && res.project_id == app.projectId && res.user_id != app.authUser.id ) { // && res.user_id != app.authUser.id
                            // if (res.list_id == app.list.id && res.project_id == app.projectId) {
                            // swal('Updated', 'Task Update!', 'success');
                            app.getBoardTask();
                        }
                    })
                    app.Socket.on('cardUpdatedSocket', function(res) {
                        console.log('app.list ', res.list_id, app.board_id);
                        console.log('resp', res);
                        if (res.board_id == app.board_id && res.project_id == app.projectId && res.user_id != app.authUser.id ) {
                            // if (res.list_id == app.list.id && res.project_id == app.projectId) {
                            // swal('Updated', 'Task Update!', 'success');
                        }
                        app.getBoardTask();
                    })
                }
            },
            getAuthUser(){
                // auth-user
                var _this = this;
                axios.get('/api/auth-user')
                    .then(response => response.data)
                    .then(response => {
                        _this.authUser = response.user;
                    })
                    .catch(error => {

                    });
            },
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
                this.HideDetails();
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
                        ruleType: this.cards[i].type,
                        ruleStatus: this.cards[i].status,
                        usersName: this.cards[i].users,
                        moveToCol: this.cards[i].moveToCol,
                        ruleName: this.cards[i].ruleName,
                        boardName: this.cards[i].boardName,
                        moveToColName: this.cards[i].colName,
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
                            type: 'card',
                            child: this.cards[i].task[j].child,
                            childrens: this.cards[i].task[j].children,
                            parents: this.cards[i].task[j].parents,
                            comment: this.cards[i].task[j].comment,
                            files: '',
                            userName: this.cards[i].task[j].userName,
                            data: this.cards[i].task[j].name,
                            open: this.cards[i].task[j].cardOpen,
                            textareaShow: this.cards[i].task[j].textareaShow,
                            description: this.cards[i].task[j].description,
                            list_id: this.cards[i].task[j].list_id,
                            board_id: this.cards[i].task[j].multiple_board_id,
                            date: this.cards[i].task[j].date,
                            parent_id: this.cards[i].task[j].parent_id,
                            progress: this.cards[i].task[j].progress,
                            tags: this.cards[i].task[j].tags,
                            tagTooltip: this.cards[i].task[j].tagTooltip,
                            delete: this.cards[i].task[j].name,
                            priority_label: this.cards[i].task[j].priority_label
                        }))
                    })),
                };
                $('[data-toggle="tooltip"]').tooltip('dispose');
                $('#loder-hide').fadeOut()
                setTimeout(function () {
                    $('[data-toggle="tooltip"]').tooltip();
                }, 1000)
            },
            selectChild(id) {
                var _this = this;
                _this.findChild(id, _this.tree4data)
                var is_checked = _this.selectedExistedTask.indexOf(id);
                if (is_checked > -1) {
                    _this.CheckWithChild(id, _this.check_uncheck_child);
                } else {
                    _this.UncheckWithChild(id, _this.check_uncheck_child);
                }
            },
            findChild(id, data) {
                if (data.length > 0) {
                    for (let index = 0; index < data.length; index++) {
                        if (index !== undefined && data[index].id === id) {
                            if (data[index].board_parent_id === null) {
                                this.check_uncheck_child = data[index].children;
                            }
                            return true;
                        } else {
                            this.findChild(id, data[index].children);
                        }
                    }
                }

            },
            CheckWithChild(id, child) {
                // console.log(id);
                var _this = this;
                if (id !== 0 && _this.selectedExistedTask.indexOf(id) === -1) {
                    _this.selectedExistedTask.push(id);
                }
                if (child.length > 0) {
                    for (let index = 0; index < child.length; index++) {
                        if (child[index].board_parent_id === null) {
                            _this.CheckWithChild(child[index].id, child[index].children);
                        } else {
                            _this.CheckWithChild(0, child[index].children);
                        }
                    }
                }
            },
            UncheckWithChild(id, child) {
                var _this = this;
                var key = _this.selectedExistedTask.indexOf(id);
                if (key !== -1) {
                    _this.selectedExistedTask.splice(key, 1);
                }

                if (child.length > 0) {
                    for (let index = 0; index < child.length; index++) {
                        _this.UncheckWithChild(child[index].id, child[index].children);
                    }
                }

            },
            hideChilds(cardId) {
                let _this = this;
                let data = {
                    'parent_id': cardId
                };
                axios.post('/api/hideChildes', data)
                    .then(response => response.data)
                    .then(response => {
                        // console.log(response);
                        _this.$toastr.s("Successfully hide child ! ");
                        _this.getBoardTask();
                        _this.boardSocketCall();
                    })
                    .catch(error => {

                    })
            },
            selectAll() {
                if ($('.checkedAll').prop('checked') === false) {
                    $('.selectAll').prop('checked', false);
                    this.selectedExistedTask = [];
                } else {
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
                    if (key !== -1 && parentKey === -1) {
                        this.selectedExistedTask.splice(key, 1);
                    } else {
                        if (key === -1 && child[index].board_parent_id === null) {
                            this.selectedExistedTask.push(child[index].id);
                        }
                    }
                    if (child[index].children.length > 0) {
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
                axios.post('/api/column-sort', data)
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
                    this.boardSocketCall();
            },
            onCardDrop(columnId, boardId, index, dropResult) {
                // index = index+1;
                // console.log('drops => ',dropResult);
                let _this = this;
                if (dropResult.removedIndex !== null || dropResult.addedIndex !== null) {
                    // console.log(this.scene.children[index]);
                    // console.log(this.scene, dropResult);
                    const scene = Object.assign({}, this.scene);
                    const column = scene.children.filter(p => p.id === columnId)[0];
                    const columnIndex = scene.children.indexOf(column);
                    const newColumn = Object.assign({}, column);
                    // console.log('col',column);
                    newColumn.children = applyDrag(newColumn.children, dropResult);
                    scene.children.splice(columnIndex, 1, newColumn);
                    // console.log(this.scene.children[index]);
                    this.scene = scene
                    let data = this.scene.children[index];
                    // console.log("sort",data);
                    axios.post('/api/card-sort', data)
                        .then(response => response.data)
                        .then(response => {
                            _this.getBoardTask();
                            setTimeout(() => {
                            }, 500);
                            // console.log('sorted');
                        })
                        .catch(error => {
                            // console.log('sorting failed');
                        });

                    if (dropResult.removedIndex === null && dropResult.addedIndex !== null) {
                        // console.log("Drag started",columnId, boardId, index, dropResult);
                        let data = {
                            'id': dropResult.payload.cardId,
                            'board_parent_id': boardId
                        }
                        $('#loader').modal('show');
                        axios.post('/api/change-board-parent', data)
                            .then(response => response.data)
                            .then(response => {
                                setTimeout(() => {
                                    // _this.getBoardTask();
                                    $('#loader').modal('hide');

                                }, 500);
                                let mailData = {
                                    subject : "A Card is moved to another column",
                                    body    : "A Card that you are assigned on is moved to another column",
                                    email   : "email_taskUpdated",
                                    generalBody : "A Card is moved to another column",
                                    task_id : dropResult.payload.cardId
                                };
                                _this.sendMail(mailData);
                                // console.log('shifted');
                            })
                            .catch(error => {
                                setTimeout(() => {
                                    $('#loader').modal('hide');
                                }, 500);
                                // console.log('shifting failed');
                            });
                    }
                    _this.boardSocketCall();
                }
            },
            onDragStart(columnId, boardId, index, dropResult) {
                if (dropResult.addedIndex != null) {
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
                            _this.$toastr.s("Board successfully created ");
                            setTimeout(() => {
                                swal.close();
                            }, 1000);
                        }
                        _this.boardSocketCall();
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
                                _this.$toastr.s("Column successfully updated ");
                            }
                        })
                        .catch(error => {
                        });
                    setTimeout(function () {
                        _this.getBoardTask();
                        _this.boardSocketCall();
                        // _this.getData();
                        // _this.editField = {};
                        // $("#EditModal").modal('show');
                    }, 300);
                }
            },
            addExistingTask(index, id) {
                this.tree4data = [];
                this.subNav = [];
                this.selectedNav = 'Select Nav';
                this.selectedSubNav = 'Select Nav List';
                this.selectedExistedTask = [];
                this.currentColumn = id;
                this.currentColumnIndex = index;
                let _this = this;
                axios.get('/api/nav-item/' + this.projectId)
                    .then(response => response.data)
                    .then(response => {
                        _this.nav = response.success;
                        _this.$toastr.s("Existing task successfully added ");
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
                $('.jquery-accordion-menu').hide();
                this.board = [];
                this.subBoard = [];
                this.boardColumn = [];
                this.selectedBoard = 'Select Board';
                this.selectedSubBoard = 'Select Board List';
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
            showLinkModel(index, boardId) {
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
            linkToCol() {
                let _this = this;
                let data = {
                    'projectId': this.projectId,
                    'columnId': this.currentColumn,
                    'multiple_list': this.selectedSubNav
                }
                axios.post('/api/is-linked', data)
                    .then(response => response.data)
                    .then(response => {
                        if (response.success === false) {
                            this.linkBtn = true;
                        } else {
                            //swal('Warning!!', 'That list is already linked. Need to unlink first', 'warning');
                            _this.$toastr.w("This list is already linked. Need to unlink first");
                            setTimeout(() => {
                                swal.close();
                            }, 1000);
                        }
                        // _this.getBoardTask();
                        // console.log('added');
                    })
                    .catch(error => {
                        console.log('not added');
                    });
            },
            listLinkToCol() {
                let _this = this;
                // alert(this.currentColumn);
                // alert(this.selectedSubNav);
                let data = {
                    'projectId': this.projectId,
                    'columnId': this.currentColumn,
                    'multiple_list': this.selectedSubNav
                }
                axios.post('/api/link-list-to-column', data)
                    .then(response => response.data)
                    .then(response => {

                        _this.getBoardTask();
                        _this.boardSocketCall();
                        // console.log('added');
                        _this.$toastr.s("List successfully linked!");
                    })
                    .catch(error => {
                        console.log('not added');
                    });
                // swal('Warning!!','Work in progress','warning');
            },
            unlinklistToCol(index, boardId, linkListId) {
                let _this = this;
                // alert(this.currentColumn);
                // alert(this.selectedSubNav);
                swal({
                        title: 'Are you sure to unlink?',
                        text: "",
                        type: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: 'red',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes, unlink it!'
                    },
                    function () {
                        let data = {
                            'linkListId': linkListId,
                            'projectId': _this.projectId,
                            'columnId': boardId,
                            'multiple_list': _this.selectedSubNav
                        }
                        axios.post('/api/unlink-list-to-column', data)
                            .then(response => response.data)
                            .then(response => {
                                _this.getBoardTask();
                                _this.boardSocketCall();
                                // swal("Unlinked!", "Successfully Unlinked", "success");

                                _this.$toastr.s("Successfully Unlinked");
                                setTimeout(() => {
                                    swal.close();
                                }, 1000);
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
                this.selectedSubBoard = 'Select Board List';
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
            getColumn() {
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
                console.log(this.selectedIds);
                // return 0;
                let data = {
                    'cardId': this.selectedIds,
                    'board_parent_id': this.selectedBoardColumn,
                };
                axios.post('/api/Transfer-to-board', data)
                .then(response => response.data)
                .then(response => {
                    if (response.success) {
                        _this.getBoardTask();
                        _this.boardSocketCall();
                        $('#transferCard').modal('hide');
                        _this.$toastr.s("Card Successfully Transferred");
                    } else {
                        $('#transferCard').modal('hide');
                    }
                    // console.log(selectedBoard,selectedSubBoard,selectedBoardColumn);
                    // _this.boardColumn = response.data;
                })
                .catch(error => {

                });
            },
            transferColumnToOtherBoard(index, id) {

                this.board = [];
                this.subBoard = [];
                this.boardColumn = [];
                this.selectedBoard = 'Select Board';
                this.selectedSubBoard = 'Select Board List';
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
                        _this.$toastr.s("Column Successfully Transferred");
                        setTimeout(() => {
                            $('#transferColumn').modal('show');
                        }, 500);
                    })
                    .catch(error => {

                    });
                this.updateIndex = index;
            },
            transferColumnToOtherBoardSave() {
                var _this = this;

                let data = {
                    'columnId': this.currentColumn,
                    'multiple_board_id': this.selectedSubBoard,
                };
                axios.post('/api/Transfer-column-to-board', data)
                    .then(response => response.data)
                    .then(response => {

                        if (response.success) {
                            _this.getBoardTask();
                            _this.boardSocketCall();
                            $('#transferColumn').modal('hide');
                            _this.$toastr.s("Column Successfully Transferred");
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
                        title: "Are you sure",
                        text: "You want to delete this task?",
                        type: "warning",
                        showCancelButton: true,
                        confirmButtonClass: "btn-danger btn",
                        confirmButtonColor: "red",
                        confirmButtonText: "Yes, delete it!",
                        closeOnConfirm: false
                    },
                    function () {
                        axios.post('/api/task-list/delete-task', postData)
                            .then(response => response.data)
                            .then(response => {
                                _this.getBoardTask();
                                _this.boardSocketCall();
                                // swal("Deleted!", "Successfully deleted task", "success");
                                _this.$toastr.w("Task Successfully Deleted !");
                                setTimeout(() => {
                                    swal.close();
                                }, 1000);
                            })
                            .catch(error => {
                                console.log('Api for delete task not Working !!!')
                            });

                    });

            },

            getBoardTask() {
                var _this = this;
                // $('#loder-hide').fadeIn();
                // this.scene = {};

                var datePicker = new Date();
                datePicker.setDate(datePicker.getDate() - 1);
                _this.disabledDates = {
                    to: datePicker, // Disable all dates up to specific date
                };
                var tz = Intl.DateTimeFormat().resolvedOptions().timeZone;
                let data = {
                    projectId: this.projectId,
                    board_id: this.board_id,
                    nav_id: this.nav_id,
                    tz :tz
                };
                axios.post('/api/board-task', data)
                    .then(response => response.data)
                    .then(response => {
                        _this.allCardIds = response.allCardIds;
                        _this.cards = response.success;
                        _this.allUsers = response.allUsers;
                        _this.allTags = response.allTags;
                        _this.getData();
                    })
                    .catch(error => {
                    });
            },
            getBoardTaskFilter(type) {
                // console.log(type);
                var _this = this;
                var tz = Intl.DateTimeFormat().resolvedOptions().timeZone;
                let data = {
                    projectId: this.projectId,
                    board_id: this.board_id,
                    nav_id: this.nav_id,
                    type: type,
                    users: [],
                    filter : this.selectedPriorites,
                    tz :tz
                };
                if (this.userIdList.length > 0) {
                    data.users = this.userIdList;
                }
                axios.post('/api/board-task-filter', data)
                    .then(response => response.data)
                    .then(response => {
                        _this.allCardIds = response.allCardIds;
                        _this.cards = response.success;
                        _this.allUsers = response.allUsers;
                        _this.allTags = response.allTags;
                        _this.getData();
                    })
                    .catch(error => {
                    });
                this.filter_types = null;
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
                    swal('Warning!', 'No Task To Add', 'warning');
                    setTimeout(() => {
                        swal.close();
                    }, 1000);
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
                        _this.boardSocketCall();
                    })
                    .catch(error => {
                    });

                this.updateIndex = null;
                this.selectedExistedTask = [];
                $("#addExistingTask").modal('hide');
            },
            makeInput(e, id) {
                let _this = this;
                $('#title' + id).removeClass('card-title-blur');
                $('#title' + id).addClass('card-title-focus');
                $('.dropdowns-card-user').hide();

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
                let _this = this;
                axios.post('/api/card-add', {'id': id})
                    .then(response => response.data)
                    .then(response => {
                        if (response.success == true) {
                            let data = response.data;
                            _this.cards[index].task.unshift({
                                id: data.id,
                                name: data.title,
                                date: data.date,
                                tags: [],
                                assigned_user: [],
                                users: [],
                                clicked: 0,
                                textareaShow: true
                            });
                            let keys = _this.cards[index].task.length - 1;
                            _this.getBoardTask();
                            setTimeout(function () {
                                $('#title' + data.id).click();
                                $('#title' + data.id).focus();
                            }, 1000)
                        }
                        _this.boardSocketCall();
                    })
                    .catch(error => {
                    });
            },
            deleteCard(index, cardIndex, id) {

                let _this = this;
                swal({
                    title: 'Are you sure to delete the card?',
                    text: "You won't be able to revert this!",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: 'Red',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }, function () {
                    if (_this.cards[index].task[cardIndex].id == id) {
                        axios.get('/api/card-delete/' + id)
                            .then(response => response.data)
                            .then(response => {
                                if (response.success == true) {
                                    let keys = _this.cards[index].task.length - 1;
                                    _this.getBoardTask();
                                    _this.getData();
                                    _this.boardSocketCall();
                                    setTimeout(function () {
                                        $('#id' + index + keys).click();
                                        $('#id' + index + keys).focus();
                                    }, 100)
                                    swal("Deleted!", "The card has been deleted.", "success");
                                    setTimeout(() => {
                                        swal.close();
                                    }, 1000);
                                }
                            })
                            .catch(error => {
                                console.log('error => ' + error);
                            });
                    }
                });


            },
            deleteTask(index, cardIndex, id) {
                let _this = this;
                swal({
                    title: 'Are you sure',
                    text: "You want to remove this task?",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: 'Red',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, remove it'
                }, function () {
                    if (_this.cards[index].task[cardIndex].id == id) {
                        axios.get('/api/board-task-delete/' + id)
                            .then(response => response.data)
                            .then(response => {
                                // if(response.success){
                                _this.cards[index].task.splice(cardIndex, 1);
                                // delete _this.cards[index].task[cardIndex];
                                // _this.cards[index].task.length = _this.cards[index].task.length-1;
                                _this.getData();
                                _this.boardSocketCall();
                                swal("Removed!", "The task has been removed.", "success");
                                setTimeout(() => {
                                    swal.close();
                                }, 1000);
                                // }
                            })
                            .catch(error => {

                            });
                    } else {
                        // alert("couden't delete");
                    }
                });
            },
            deleteAllTasks(index, cardIndex, id) {
                let _this = this;
                id = {
                    'id' : this.selectedIds
                };
                swal({
                    title: 'Are you sure',
                    text: "You want to remove this task?",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: 'Red',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, remove it'
                }, function () {
                    axios.post('/api/board-task-delete/', id)
                        .then(response => response.data)
                        .then(response => {
                            // if(response.success){
                            _this.cards[index].task.splice(cardIndex, 1);
                            // delete _this.cards[index].task[cardIndex];
                            // _this.cards[index].task.length = _this.cards[index].task.length-1;
                            _this.getBoardTask();
                            _this.boardSocketCall();
                            _this.selectedIds = [];
                            $('.card-list').removeClass('selected-card');
                            swal("Removed!", "The task has been removed.", "success");
                            setTimeout(() => {
                                swal.close();
                            }, 1000);
                            // }
                        })
                        .catch(error => {

                        });
                });
            },
            addTag(e, index, key) {
                if (e.which === 13) {
                    this.cards[index].task[key].tags.splice(0, 1, this.tag);
                    this.tag = null;
                }
            },
            addExistingTag(index, tagIndx, key, card, dntfrgt = '') {
                let _this = this;
                if (dntfrgt !== '') {
                    var postData = {
                        id: card.cardId,
                        tags: "Dont Forget",
                        color: "#FF0000",
                        type: 'task',
                    };
                } else {
                    var postData = {
                        id: card.cardId,
                        tags: this.cards[index].task[key].existing_tags[tagIndx].title,
                        color: this.cards[index].task[key].existing_tags[tagIndx].color,
                        type: 'task',
                    };
                }
                axios.post('/api/task-list/add-tag', postData)
                    .then(response => response.data)
                    .then(response => {
                        // _this.cards[index].task[key].tags.push(data);
                        // _this.cards[index].task[key].existing_tags.splice(tagIndx, 1);
                        // $('#dropdown' + cardId).toggle();
                        setTimeout(function () {
                            _this.getBoardTask();
                            _this.listSocketCall(card.list_id);
                        }, 100);
                    })
                    .catch(error => {
                        console.log("1st error =>" + error)
                    });
            },
            deleteColumn(index, id) {
                let _this = this;
                swal({
                        title: "Are you sure?",
                        text: "Your will not be able to recover this",
                        type: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "red",
                        confirmButtonClass: "btn-danger",
                        confirmButtonText: "Yes, delete it!",
                        closeOnConfirm: false
                    },
                    function () {
                        axios.get('/api/board-delete/' + id)
                            .then(response => response.data)
                            .then(response => {
                                if (response.success) {
                                    _this.cards.splice(index, 1);
                                    _this.getData();
                                    _this.boardSocketCall();
                                    swal("Deleted!", "Your imaginary file has been deleted.", "success");
                                    setTimeout(() => {
                                        swal.close();
                                    }, 1000);
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
                        _this.boardSocketCall();
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
                        _this.boardSocketCall();
                    })
                    .catch(error => {
                    });
            },
            preventEnter(e) {
                if (e.which == 13) {
                    e.preventDefault();
                    // alert('Enter pressed');
                }
            },
            cardTitlePress(e, card, index, key) {
                $('.dropdowns-card-user').hide();
                // console.log(e.which);
                let _this = this;
                let title = $('#title' + card.cardId).text();
                // this.projectUsers = null;
                // let cmHe = $('#replyTextBox'+comments.id).height();
                // $('#cmntSection').css({maxHeight: ' calc(100vh - 420px - '+cmHe+'px + 30px)'});
                // console.log(this.selectedData.comment);
                if (e.which === 32 || e.which === 13) {
                    // || e.which === 8
                    _this.triggers = false;
                    _this.userNames = '';
                    _this.projectUsers = null;
                    $('.dropdowns-card-user').hide();
                }
                if (_this.triggers == true && e.which !== 16 && e.which !== 50) {
                    var lastIndex = title.lastIndexOf(" ");

                    let str = title.substring(lastIndex);
                    if (str.includes('@')) {
                        let notKeys = ["Backspace", "ScrollLock", "null", "NumLock", "Tab", "ArrowLeft", "ArrowDown", "ArrowRight", "ArrowUp"];
                        // console.log(e.key);
                        if (notKeys.includes(e.key) === false) {
                            _this.userNames += e.key;
                        }
                        if (e.key === "Backspace") {
                            _this.userNames = _this.userNames.slice(0, -1);
                        }
                        axios.post('/api/task-list/search-result', {'user_name': _this.userNames})
                        .then(response => response.data)
                        .then(response => {
                            _this.projectUsers = response.search_user;
                            $('.dropdowns-card-user').hide();
                            if (_this.projectUsers.length > 0) {
                                $('#titleUserMention' + card.cardId).show();
                            }
                        })
                        .catch(error => {
                            console.log('search user is not Working !!!')
                        });
                    }
                }

                if (e.shiftKey && e.which == 50) {
                    _this.allTags = null;
                    _this.triggers = true;
                    _this.commentsData = $('#title' + card.cardId).text();
                    axios.get('/api/task-list/all-suggest-user')
                        .then(response => response.data)
                        .then(response => {
                            // _this.projectUsers = response.search_user;
                            $('.dropdowns-card-user').hide();
                            // $('#titleUserMention' + card.cardId).show();
                        })
                        .catch(error => {
                            console.log('All suggest user api not working')
                        })
                }
                if (e.shiftKey && e.which == 51) {
                    _this.projectUsers = null;
                    _this.tagTriggers = true;
                    _this.commentsData = $('#title' + card.cardId).text();
                    axios.get('/api/task-list/all-tag-for-manage')
                        .then(response => response.data)
                        .then(response => {
                            _this.allTags = response.tags;
                            $('.dropdowns-card-user').hide();
                            $('#titleUserMention' + card.cardId).show();
                            console.log(_this.allTags);
                        })
                        .catch(error => {
                            console.log('All suggest user api not working')
                        })
                }
            },
            SearchTaskByAssignedUsers(id, name, card, user) {
                let _this = this;
                $('#title' + card.cardId).focus();
                _this.assignUserToTask(user, 0, 0, card);
                $('#title' + card.cardId).html(_this.commentsData + '' + name + ' ');
                _this.projectUsers = null;
                $('.dropdowns-card-user').hide();
            },
            tagMention(card, tag, index, tagIndx, key) {
                let _this = this;
                $('#title' + card.cardId).focus();
                $('#title' + card.cardId).html(_this.commentsData + '' + tag.title + ' ');
                _this.addExistingTag(index, tagIndx, key, card, '');
                _this.allTags = null;
                $('.dropdowns-card-user').hide();
            },
            showItem(e, data, index, child_key) {

                $('#title' + data.cardId).addClass('card-title-blur');
                $('#title' + data.cardId).removeClass('card-title-focus');
                // let attData = $(e.target).attr('data-text');
                // let attDataNew = e.target.value;
                // $('.dropdowns-card-user').hide();
                setTimeout(() => {
                    $('.dropdowns-card-user').hide();
                }, 300);
                let attDataNew = $('#title' + data.cardId).text();
                data.data = attDataNew;
                this.saveData(data, index, child_key);
            },
            showHideTextarea(id) {
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
                // console.log("data = ",data);
                if (data.data === "") {
                    _this.getBoardTask();
                    swal('Blank!', 'Title is required!', 'warning');
                    setTimeout(() => {
                        swal.close();
                    }, 1000);
                } else {
                    let title = {
                        'title': data.data
                    };
                    axios.post('/api/card-update/' + data.cardId, title)
                        .then(response => response.data)
                        .then(response => {
                            _this.cards[index].task[child_key].name = data.data;
                            _this.getData();
                            _this.listSocketCall(data.list_id);
                            let mailData = {
                                subject : "Card updated",
                                body    : "Card is updated that you are assigned on",
                                email   : "email_taskUpdated",
                                generalBody : "A Card title is updates",
                                task_id :  data.cardId
                            };
                            _this.sendMail(mailData);
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
                var _this = this;
                var tz = Intl.DateTimeFormat().resolvedOptions().timeZone;
                setTimeout(function () {
                    let data = {
                        'date': card.date+' 23:59:59',
                        'tz': tz,
                    };
                    axios.post('/api/card-update/' + card.cardId, data)
                        .then(response => response.data)
                        .then(response => {
                            // _this.getBoardTask();
                            let mailData = {
                                subject : "Card date updated",
                                body    : "A card date is updated that you are assigned on",
                                email   : "email_taskUpdated",
                                generalBody : "A Card date is updated",
                                task_id :  card.cardId
                            };
                            _this.sendMail(mailData);
                            _this.listSocketCall(card.list_id);
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
                                _this.listSocketCall(card.list_id)
                                $('.dropdown-menu').removeClass('show');
                                // _this.getData();
                            }, 100);
                        })
                        .catch(error => {
                            console.log("2nd error =>" + error)
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
                                _this.listSocketCall(card.list_id);
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
            showLog(card) {
                var _this = this;
                $('#title' + card.cardId).blur();

                setTimeout(function () {
                    _this.ShowDetails();
                    $('#_file').click();
                    $('#_details').click();
                }, 300)

            },
            selectCard(card, child) {
                this.selectedData = card;
                this.selectedCard = card.cardId;
                this.task_logs = null;
                this.HideDetails();
                $('.card-list').css("background-color", "#ffffff");
                $('#card_' + this.selectedCard).css("background-color", "#ddf3fd");
                console.log(this.selectedData);
            },
            ShowDetails() {
                var _this = this;
                if (_this.selectedData != null && _this.selectedData.sort_id !== -2) {
                    $('#task_width').removeClass('task_width');
                    $('#task_width').addClass('task_widthNormal');
                    // $('#details').removeClass('details');
                    // $('#details').addClass('detailsShow');
                    $('#details').addClass('details-show');
                }
            },
            Add_Priority(priority, card) {
                var _this = this;
                var data = {
                    ids: (card == null) ? _this.selectedIds : [card.cardId],
                    priority: priority
                }
                axios.post('/api/task-list/add-priority', data)
                    .then(response => response.data)
                    .then(response => {
                        _this.getBoardTask();
                        (card == null) ? _this.boardSocketCall() : _this.listSocketCall(card.list_id);
                        _this.selectedIds = [];
                        $('.card-list').removeClass('selected-card');
                        $('.jquery-accordion-menu').hide();
                    })
                    .catch(error => {
                        console.log('Api for task add priority not Working !!!')
                    });
            },
            RemovePriority(card) {
                var _this = this;
                var data = {
                    ids: (card == null) ? _this.selectedIds : [card.cardId],
                    priority: null
                }
                axios.post('/api/task-list/add-priority', data)
                    .then(response => response.data)
                    .then(response => {
                        _this.getBoardTask();
                        (card == null) ? _this.boardSocketCall() : _this.listSocketCall(card.list_id);
                        _this.selectedIds = [];
                        $('.card-list').removeClass('selected-card');
                        $('.jquery-accordion-menu').hide();
                    })
                    .catch(error => {
                        console.log('Api for task add priority not Working !!!')
                    });
            },
            HideDetails() {
                // this.getBoardTask();
                $('#task_width').addClass('task_width');
                $('#task_width').removeClass('task_widthNormal');
                // $('#details').addClass('details');
                // $('#details').removeClass('detailsShow');
                $('#details').removeClass('details-show');
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
            HideCustomMenu() {
                $('.jquery-accordion-menu').hide();
            },
            makeItClick(e, card, child, index, key, boardId) {
                this.currentColumn = boardId;
                this.currentColumnIndex = index;
                this.currentColumnKey = key;
                this.selectedCards = card;
                var _this = this;
                if (e.ctrlKey && e.which === 1) {
                    if (_this.selectedIds.includes(card.cardId)) {
                        var indexs = _this.selectedIds.indexOf(card.cardId);
                        if (indexs > -1) {
                            $('#card_' + _this.selectedIds[indexs]).removeClass('selected-card');
                            _this.selectedIds.splice(indexs, 1);
                        }
                    } else {
                        _this.selectedIds.push(card.cardId);
                    }
                    // console.log(_this.selectedIds);
                    for (let index = 0; index < _this.selectedIds.length; index++) {
                        $('#card_' + _this.selectedIds[index]).addClass('selected-card');
                    }
                } else if (e.shiftKey && e.which === 1) {
                    var first = _this.shift_first;
                    var last = card.cardId;
                    var flag = 0;
                    var flag1 = 0;
                    var index_last = _this.allCardIds.indexOf(last);
                    var index_first = _this.allCardIds.indexOf(first);
                    if (index_first > index_last) {
                        first = card.cardId;
                        index_first = _this.allCardIds.indexOf(first);
                        last = _this.shift_first;
                    }
                    _this.selectedIds = [];
                    $('.card-list').removeClass('selected-card');
                    for (var i = index_first; i <= _this.allCardIds.length; i++) {
                        if (_this.allCardIds[i] === first) {
                            flag = 1;
                            flag1 = 1;
                        }
                        if (flag === 1) {
                            _this.selectedIds.push(_this.allCardIds[i]);
                            // $('#click' + _this.allCardIds[i]).addClass('clicked');
                            $('#card_' + _this.allCardIds[i]).addClass('selected-card');
                        }
                        if (flag1 === 1 && _this.allCardIds[i] === last) {
                            flag = 0;
                            flag1 = 0;
                            break;
                        }
                    }
                } else if (e.which === 1) {
                    _this.selectedIds = [];
                    _this.selectedIds.push(card.cardId);
                    _this.shift_first = card.cardId;

                    this.selectedData = card;
                    this.selectedCard = card.cardId;
                    this.task_logs = null;
                    this.HideDetails();
                    $('.card-list').removeClass('selected-card');
                    $('#card_' + this.selectedCard).addClass('selected-card');


                } else if (e.which === 3) {
                    e.preventDefault();
                    e.stopPropagation();
                    if (_this.context_menu_flag !== 1) {
                        $('#rmenu').addClass('menu-show');
                        let target = $(e.target);
                        let w = target.closest('#board_view_list').width();
                        // console.log(w);
                        let h = target.closest('#board_view_list').height();
                        let p = target.closest('#board_view_list').offset();
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

                        let ttarget = target.closest('#board_view_list').find('.jquery-accordion-menu');

                        if (_this.selectedIds.length > 0) {
                            var index = _this.selectedIds.indexOf(card.cardId);
                            if (index > -1) {
                                ttarget.css({
                                    top: top,
                                    left: left,
                                }).fadeIn();
                            } else {
                                $('.eachItemRow').removeClass('clicked');

                                $('.jquery-accordion-menu').hide();
                                _this.selectedIds = [];
                                $('.card-list').removeClass('selected-card');
                            }
                        }
                    }
                } else if (e.ctrlKey && e.which === 65) {
                    // e.target.setSelectionRange(0, card.text.length);
                }
            },
            removeAssignedUser(user, card) {
                // console.log(user.id, user.task_id);
                var _this = this;
                var postData = {
                    user_id: user.id,
                    task_id: card.cardId
                };
                // console.log(postData)
                axios.post('/api/task-list/assign-user-remove', postData)
                    .then(response => response.data)
                    .then(response => {
                        if (response === 'success') {
                            // _this.cards[index].task[key].assigned_user.splice(0,1);
                            // _this.cards[index].task[key].assigned_user_ids.splice(0,1);  dasfsdaf
                            console.log(response);
                            _this.getBoardTask();
                            _this.listSocketCall(card.list_id);
                            let mailData = {
                                subject : "Removed from a card",
                                body    : "You are removed from a card that you are assigned on.",
                                email   : "email_whenRemovedFromTask",
                                generalBody : user.name+" is removed from a card ("+card.data+") ",
                                user_id :  user.id
                            };
                            _this.sendMail(mailData);
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
                            // _this.cards[index].task[key].assigned_user.push(response.data);
                            //  console.log(_this.cards);
                            setTimeout(function () {
                                _this.getBoardTask();
                                _this.listSocketCall(data.list_id);
                                let mailData = {
                                subject : "Added to a card",
                                body    : "You are assigned on a card",
                                email   : "email_whenAddedToTask",
                                generalBody : "A Card ( "+data.data+" ) is assigned to "+user.name,
                                user_id :  user.id
                            };
                            _this.sendMail(mailData);
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
                        confirmButtonColor: 'red',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes, delete it!'
                    },
                    function () {
                        var postData = {
                            id: tag.id,
                            title: tag.title,
                        };
                        axios.post('/api/task-list/delete-tag', postData)
                            .then(response => response.data)
                            .then(response => {
                                // _this.manageTag = response.tags;
                                // _this.showTagManageModel();
                                _this.getBoardTask();
                                swal("Deleted!", "The tag has been deleted.", "success");
                                setTimeout(() => {
                                    swal.close();
                                }, 1000);
                                // _this.getData();
                                // _this.tag = null
                            })
                            .catch(error => {
                                console.log('Api for delete tag not Working !!!');
                            });

                    });
            },
            ruleAlertHeight: function (obj, trigger) {
                setTimeout(function () {
                    let target = $('#' + obj);
                    let h = 0;
                    if (target.length > 0) {
                        h = $('#' + obj).height();
                    }
                    $('#' + trigger).css({top: h + 'px'});
                }, 400)
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
                            _this.getBoardTask();
                            $('.jquery-accordion-menu').hide();
                            _this.selectedIds = [];
                            $('.card-list').removeClass('selected-card');
                        })
                        .catch(error => {
                            console.log('Api for delete task not Working !!!')
                        });
                }, 500)
            },
            openPicker: function () {
                let _this = this;
                setTimeout(function () {
                    let target = $('.vdp-datepicker__calendar:visible');
                    let wH = window.innerHeight + 140;
                    let position = target.offset();
                    let tH = target.height();
                    let cH = wH - position.top;
                    if (cH < tH) {
                        target.css({bottom: 0 + 'px'});
                    }
                }, 200)
            },
            deleteSelectedTask() {
                var _this = this;
                _this.delete_popup = 1;
                var postData = {
                    ids: _this.selectedIds,
                };
                $('.jquery-accordion-menu').hide();
                swal({
                        title: "Are you sure",
                        text: "You want to delete all selected tasks?",
                        type: "warning",
                        showCancelButton: true,
                        confirmButtonClass: "btn-danger btn",
                        confirmButtonColor: 'Red',
                        confirmButtonText: "Yes, delete them",
                        closeOnConfirm: true
                    },
                    function () {
                        axios.post('/api/task-list/delete-task', postData)
                            .then(response => response.data)
                            .then(response => {
                                _this.getBoardTask();
                                _this.boardSocketCall();
                                $('.jquery-accordion-menu').hide();
                                _this.delete_popup = 0;
                                swal("Deleted!", "Successfully deleted selected tasks", "success");
                                setTimeout(() => {
                                    swal.close();
                                }, 1000);
                            })
                            .catch(error => {
                                console.log('Api for delete task not Working !!!')
                            });
                    });
            },
            addUserToFilter(userId) {
                if (this.userIdList.includes(userId)) {
                    var indexs = this.userIdList.indexOf(userId);
                    if (indexs > -1) {
                        this.userIdList.splice(indexs, 1);
                    }
                } else {
                    this.userIdList.push(userId);
                }
            },
            userFilter() {
                if (this.userIdList.length < 1) {
                    swal('Warning!!', "No user is selected ", "warning");
                } else {
                    this.getBoardTaskFilter(this.filter_types);
                    $('#user_list').modal('hide');
                }

            },
            checkedAllUser(allUsers) {
                if ($('.checkedAllUser').prop('checked') === false) {
                    this.userIdList = [];
                } else {
                    for (let i = 0; allUsers.length > i; i++) {
                        this.userIdList.push(allUsers[i].id);
                    }
                }
            },
            priorityHide() {
                // console.log(this.selectedPriorites);
                if (this.selectedPriorites.length <= 0) {
                    swal('Warning!!','Nothing selected','warning');
                    return false;
                }
                this.getBoardTaskFilter('p_hide');
                $('#priority_list_modal').modal('hide');
            },
            priorityShow() {
                // console.log(this.selectedPriorites);
                if (this.selectedPriorites.length <= 0) {
                    swal('Warning!!','Nothing selected','warning');
                    return false;
                }
                this.getBoardTaskFilter('p_show');
                $('#priority_list_modal').modal('hide');
            },
            addFilterToFilter(type) {
                if (this.selectedPriorites.includes(type)) {
                    var indexs = this.selectedPriorites.indexOf(type);
                    if (indexs > -1) {
                        this.selectedPriorites.splice(indexs, 1);
                    }
                } else {
                    this.selectedPriorites.push(type);
                }
                // console.log(this.selectedPriorites)
            },
            boardSocketCall(list_id = null){
                let _this = this;
                _this.Socket.emit('cardUpdated',{
                    // user_id : _this.auth_user.id,
                    // project_id : _this.projectId
                    project_id: _this.projectId,
                    board_id : _this.board_id,
                    list_id : list_id,
                    user_id : _this.authUser.id,
                });
            },
            listSocketCall(list_id = null){
                let _this = this;
                _this.Socket.emit('taskUpdate',{
                    // user_id : _this.auth_user.id,
                    // project_id : _this.projectId
                    project_id: _this.projectId,
                    board_id : _this.board_id,
                    list_id : list_id,
                    user_id : _this.authUser.id,
                });
            },
            sendMail(data){
                axios.post('/api/send-mail/',data)
                .then(response => response.data)
                .then(response => {
                    console.log(response);
                })
                .catch(error => {

                });
            }
        },
        directives: {
            ClickOutside
        },
        watch: {
            projectId: function (val) {
                this.projectId = val;
                $('#loder-hide').fadeIn();
                this.getBoardTask()
            },
            board_id: function (val) {
                this.board_id = val;
                $('#loder-hide').fadeIn();
                this.getBoardTask()
            },
            nav_id: function (val) {
                this.nav_id = val;
                $('#loder-hide').fadeIn();
                this.getBoardTask()
            },
            filter_type: function (val) {
                this.filter_types = val;
                console.log(val)
                if (val === 'my') {
                    this.userIdList = [];
                    this.getBoardTaskFilter(val);
                } else if (val === 'users_task') {
                    $('#user_list').modal('show');
                } else if (val === 'all') {
                    this.getBoardTask();
                } else if (val === 'not_assign') {
                    this.getBoardTaskFilter(val);
                } else if (val === 'priority') {
                    this.getBoardTaskFilter(val);
                } else if (val === 'asc' || val === 'desc') {
                    this.getBoardTaskFilter(val);
                } else if (val === 'date') {
                    this.getBoardTaskFilter(val);
                } else if (val === 'priority_based') {
                    $('#priority_list_modal').modal('show');
                }
                // this.filter_type = null;
            },
        }
    }
</script>
