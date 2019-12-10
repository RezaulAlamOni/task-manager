<template>
    <div class="container" v-if="Object.keys(selectedData).length > 0">
        <!-- {{selectedData}} -->
        <div class="row">
            <div class="col-2">
                <!--                <h4 class="compltit-blue" >#ID{{selectedData.id}}</h4>-->
                <h4 class="compltit-blue  details-header" @click="showOriginalList(selectedData)"
                    style="cursor:pointer;" data-toggle="tooltip"
                    :title="(selectedData.list_id === null) ? 'This Card Is Only show In Board' : 'Click For Go List'">
                    #ID{{ selectedData.cardId }}</h4>
            </div>
            <div class="col-2">
                <img :src="baseUrl+ '/img/task-icon/date-plus.png'" style=" height: 29px;cursor: pointer"
                     v-if="selectedData.date === '0000-00-00'"
                     class="details-title calender-for-details details-header">
                <!--                <i class="fa fa-calendar details-title calender-for-details details-header"-->
                <!--                   v-if="selectedData.date === '0000-00-00'"></i>-->

                <datepicker
                    :disabled-dates="disabledDates"
                    @selected="updateDate"
                    calendar-button-icon='<i class="outline-event icon-image-preview"></i>'
                    format='dd MMM'
                    input-class="dateCal due-details details-header"
                    v-model="selectedData.date">
                </datepicker>
            </div>
            <div class="col-1">
                <img class="img-responsive details-header"
                     :src="baseUrl+'/img/'+selectedData.progress+'.png'"
                     style="height:30px;width:30px;">

            </div>
            <div class="col-4 text-center" >
                <a :class="{'tag-icon': true, 'tag-icon-free': selectedData.tags == undefined || selectedData.tags.length == 0}">
                    <div v-if="selectedData.tags && selectedData.tags.length !== 0" style="display:inline-flex;">
                        <div style="margin-top: 10px;" v-for="(item, tagIndex) in selectedData.tags">
                            <div class="dropdown-toggle-split "
                                 data-toggle="dropdown"
                                 style="padding-right: 0px; padding-left: 1px;" v-if="tagIndex < 2">
                                    <span class="badge badge-danger "
                                          v-if='item == "Dont Forget"'>{{item.text.substring(0,12)}}</span>
                                <span :title="selectedData.tagTooltip"
                                      class="badge badge-success "
                                      data-placement="bottom"
                                      data-toggle="tooltip"
                                      v-bind:style="[{'background': item.color },{'margin-left' : 1 +'px'}]"
                                      style="line-height: 19px;font-size: 12px"
                                      v-else
                                >{{item.text.substring(0,10)}}
                                        <span v-if="item.text.length > 10">..</span>
                                    </span>
                            </div>

                            <div :id="'dropdown1'+selectedData.cardId" class="dropdown-menu dropdown-menu1"  style="width: 250px !important;">

                                <diV class="collapse show switchToggle">
                                    <div class="container-fluid">
                                        <vue-tags-input
                                            :allow-edit-tags="true"
                                            :tags="selectedData.tags"
                                            @before-deleting-tag="deleteTag => deleteCardTag(deleteTag,selectedData)"
                                            @tags-changed="newTags => (changeTag(newTags,selectedData))"
                                            v-model="tag"
                                        />
                                        <div class="row">
                                            <div class="col-12">
                                                <template v-for="(tag, tagIndx) in selectedData.existing_tags">
                                                    <li class="badge-pill tags"
                                                        @click="addExistingTag( tagIndx, selectedData.cardId, '')"
                                                        v-bind:style="[{'background': tag.color },{'margin-left' : 1 +'px'}]"
                                                        v-if="tag.text !== 'Dont Forget'">
                                                        {{(tag.title !== undefined) ?tag.title.substring(0,12) :
                                                        ''}}
                                                    </li>
                                                </template>
                                                <!-- <template> -->
                                                <!-- <li class="badge-pill tags"
                                                    v-bind:style="[{'background': item.color },{'margin-left' : 1 +'px'}]">
                                                </li> -->
                                                <!-- </template> -->
                                                <li @click="addExistingTag( 0, selectedData.cardId, 'Dont Forget')"
                                                    class="badge-pill tags" style="background: #FB8678"> Dont Forget
                                                </li>
                                            </div>
                                        </div>
                                        <!-- <hr> -->
                                        <!-- <div class="row">
                                            <div class="col-12">
                                                <template>
                                                    <li class="badge-pill tags"
                                                        v-bind:style="[{'background': item.color },{'margin-left' : 1 +'px'}]">
                                                    </li>
                                                </template>
                                                <li class="badge-pill tags" style="background: #FB8678" >Dont Forget</li>
                                            </div>
                                        </div>
                                        <hr> -->
                                        <!-- <div class="col-xs-12"
                                            style="margin-top:10px;width: 100%;">
                                            <button @click="showTagManageModel"
                                                    class="btn btn-small btn-primary pull-right"
                                                    type="submit">
                                                Manage Tag
                                            </button>
                                        </div> -->
                                    </div>

                                </diV>
                            </div>
                        </div>
                    </div>

                    <img :src="baseUrl+'/img/task-icon/tag-add.png'" data-toggle="dropdown"
                         class="dropdown-toggle-split li-opacity" style=" height: 29px;cursor: pointer; margin: 9px 5px;" v-else
                         data-original-title="Add Tag">


                    <div class="dropdown-menu dropdown-menu1 " style="width: 250px !important;">
                        <diV class="collapse show switchToggle">
                            <div class="container-fluid">
                                <vue-tags-input
                                    :allow-edit-tags="true"
                                    :tags="tag1"
                                    @before-deleting-tag="deleteTag => deleteCardTag(deleteTag,selectedData)"
                                    @tags-changed="newTags => (changeTag(newTags,selectedData))"
                                    v-model="tag"
                                />
                                <div class="row">
                                    <div class="col-12">
                                        <template v-for="(tag, tagIndx) in selectedData.existing_tags">
                                            <li class="badge-pill tags"
                                                @click="addExistingTag( tagIndx, selectedData.cardId ,'')"
                                                v-bind:style="[{'background': tag.color },{'margin-left' : 1 +'px'}]"
                                                v-if="tag.text !== 'Dont Forget'">
                                                {{(tag.title !== undefined) ?tag.title.substring(0,12) : ''}}
                                            </li>
                                        </template>
                                        <li @click="addExistingTag( 0, selectedData.cardId, 'Dont Forget')"
                                            class="badge-pill tags" style="background: #FB8678"> Dont Forget
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
            </div>
            <!-- Assign Users -->
            <div class="col-md-3" style="padding: 0px;">
                <a class="user dropdown-hide-with-remove-icon">
                    <template v-if="selectedData.assigned_user.length > 0">
                        <span class="assigned_user dropdown-toggle-split " style="float: right;padding-right: 50px"
                              data-toggle="dropdown" v-for="(assign,keyId) in selectedData.assigned_user">
                            <p :title="assign.name"
                               class="assignUser-photo-for-selected text-uppercase"
                               data-placement="bottom" data-toggle="tooltip"
                               style="top: 10px;"
                               v-if="keyId <= 1">{{(assign.name !== null) ? assign.name.substring(0,2) : ''}}
                            </p>

                        </span>
                    </template>
                    <span data-toggle="dropdown" class=" dropdown-toggle-split" style="float: right;" v-else>

                    <!-- <i class="outline-person icon-image-preview li-opacity "-->
                    <!--    data-toggle="tooltip" title="Assignee">-->
                    <!-- </i>-->
                        <img :src="baseUrl+'/img/task-icon/add-user.png'"
                             class="li-opacity"
                             style=" height: 29px;cursor: pointer; margin: 9px 11px;"
                             data-original-title="Add User">

                    </span>

                    <div class="dropdown-menu dropdown-menu-right" style="z-index: 1;right: -50px;left: inherit !important;width: 300px">
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
                                <template v-for="user in selectedData.users">
                                    <div
                                        @click="(selectedData.assigned_user_ids.includes(user.id)) ? '' : assignUserToTask(user, selectedData) "
                                        :class="(selectedData.assigned_user_ids.includes(user.id)) ? 'active-user disabled' : 'users-select'"
                                        class=" row"
                                        v-bind:disabled="(selectedData.assigned_user_ids.includes(user.id)) ? true : false">
                                        <div class="col-md-3 pt-1 pl-4">
                                            <p class="assignUser-photo">
                                                {{(user.name !== null) ? user.name.substring(0,2) : ''}}</p>
                                        </div>
                                        <div class="col-md-9 assign-user-name-email">
                                            <h5>{{user.name}}<br>
                                                <small>{{user.email}}</small>
                                            </h5>
                                        </div>
                                        <a :id="'remove-assign-user'+user.id"
                                           v-if="selectedData.assigned_user_ids.includes(user.id)"
                                           @click="removeAssignedUser(user.id, selectedData.cardId)"
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

                            <span class="assign-user-drop-down-text">Assign an external team</span>
                            <switches class="assign-user-switch-for-dropdown"
                                      color="success"
                                      theme="bootstrap"
                                      v-model="id">
                            </switches>
                        </li>
                    </div>
                </a>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <h5 class="detailsTitle" v-html="selectedData.data">
                </h5>
            </div>
        </div>
        <!-- Tabs -->
        <div class="row">
            <div class="col-12">
                <ul class="nav nav-tabs" id="myTab" role="tablist">
                    <li class="nav-item">
                        <a aria-controls="home" aria-selected="true" class="nav-link active" data-toggle="tab"
                           href="#home"
                           id="_details" role="tab">Details</a>
                    </li>
                    <li class="nav-item" @click="getFiles(selectedData.cardId)">
                       <a aria-controls="file" aria-selected="false" class="nav-link" data-toggle="tab"
                          href="#file"
                          id="_file" role="tab">Files</a>
                   </li>
                    <li class="nav-item" @click="getComments(selectedData.cardId)">
                        <a aria-controls="comment" aria-selected="false" class="nav-link" data-toggle="tab"
                           href="#comment"
                           id="_comment" role="tab">Comments</a>
                    </li>

                    <li class="nav-item">
                        <a aria-controls="child" aria-selected="false" class="nav-link" data-toggle="tab" href="#child"
                           id="_requirement" role="tab">Requirement</a>
                    </li>

                    <li class="nav-item">
                        <a @click="showLog" aria-controls="log" aria-selected="false" class="nav-link" data-toggle="tab" href="#log"
                           id="_log" role="tab">Logs</a>
                    </li>
                    <li @click="showChild(selectedData.cardId)" class="nav-item" v-if="selectedData.type !== 'task' && (selectedData.childrens.length  > 0 || selectedData.parents.length  > 0)">
                        <a aria-controls="child" aria-selected="false" class="nav-link" data-toggle="tab" href="#child"
                           id="_child" role="tab">Parents & Childs</a>
                    </li>
                    <!-- <li @click="showChild(selectedData.cardId)" class="nav-item" v-if="selectedData.parents.length  > 0">
                        <a aria-controls="child" aria-selected="false" class="nav-link" data-toggle="tab" href="#child"
                           id="_child" role="tab">Childs</a>
                    </li> -->
                </ul>
            </div>
        </div>
        <!-- Tab panes -->
        <div class="tab-content">
            <div aria-labelledby="home-tab" class="tab-pane active" id="home" role="tabpanel">
                <hr>
                <!-- Text Description -->
                <div class="row">
                    <div class="col-12">
                        <div class="textAreaExtend">
                            <div class="row">
                                <div class="col-12">
                                    <h3>Details</h3>
                                </div>
                                <div class="col-12">
                                    <ckeditor :editor="editor" v-model="selectedData.description" :config="editorConfig"></ckeditor>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <div class="submitdetails" id="submitdetails" style="margin-top: 20px">
                                        <a @click="updateDescription('#bx'+selectedData.cardId)" class="btn btn-default"
                                           href="javascript:void(0)"
                                           style="background: #7BB348;">Post</a>
                                        <!-- <a class="btn btn-default" href="javascript:void(0)"
                                           style="border: 1px solid #f1efe6" @click="HideDetails">Cancel</a> -->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- files -->
                <div class="row">
                    <div class="col-12" v-if="selectedData.files && selectedData.files.length !== 0">
                        <h3 style="margin-top: 20px;">Files</h3>
                        <div class="row">
                            <template v-for="fl in selectedData.files">
                                <div class="col-6">
                                    <img :src="'/images/'+fl.file_name" class="task-img-right-pane">
                                </div>
                            </template>
                        </div>
                        <div style="cursor: pointer; background-color: #F8F8F8; margin:10px 0;">
                            <input :id="'file'+selectedData.cardId" :ref="selectedData.cardId"
                                   type="file">
                            <a @click="addAttachment(selectedData)"><i class="fa fa-paperclip"></i></a>
                        </div>
                    </div>
                </div>
                <!-- Comments -->
            </div>

            <div aria-labelledby="log-tab" class="tab-pane" id="file" role="tabpanel">
               <span>
                   <h3 class="p-3">Files of this card </h3>
                   <div class=" comment-section-in-task-details" style="max-height: calc(100vh - 315px);">
                       <div class="row" v-if="selectedData.files !== ''" id='fileSection' style=" max-height: calc(100vh - 390px); width: 100%; overflow: auto;" >
                            <div class="col-md-4" v-for="files in selectedData.files" >
                                <!-- <p :title="comments.user.name" class="assignUser-photo-for-selected text-uppercase details-comments-pic"
                                    data-placement="bottom" data-toggle="tooltip"> {{ comments.user.name.substring(0,2) }}</p> -->
                                <div class="card-list card" >
                                    <span style="padding: 10px;" >
                                        <a target="_blank" :href="'/storage/'+selectedData.cardId+'/'+files.file_name"
                                            style="cursor: pointer;position: absolute;">
                                            <div style="float: left;" v-if="files.file_name.toLowerCase().endsWith('.png') || files.file_name.toLowerCase().endsWith('.jpg') || files.file_name.toLowerCase().endsWith('.jpeg') || files.file_name.toLowerCase().endsWith('.gif')">
                                                <img title="Click To Download" data-toggle="tooltip"
                                                        :src="'/storage/'+selectedData.cardId+'/'+files.file_name" height="50" width="50">
                                            </div>
                                            <div style="float: left;" v-else-if="files.file_name.toLowerCase().endsWith('.txt') ">
                                                <img title="Click To Download" data-toggle="tooltip"
                                                    :src="'/img/txt.png'" height="50" width="50">
                                            </div>
                                            <div style="float: left;" v-else-if="files.file_name.toLowerCase().endsWith('.pdf') ">
                                                <img title="Click To Download" data-toggle="tooltip"
                                                    :src="'/img/pdf.png'" height="50" width="50">
                                            </div>
                                            <div style="float: left;" v-else-if="files.file_name.toLowerCase().endsWith('.doc') || files.file_name.toLowerCase().endsWith('.docx') || files.file_name.toLowerCase().endsWith('.xls') || files.file_name.toLowerCase().endsWith('.xlsx')">
                                                <img title="Click To Download" data-toggle="tooltip"
                                                    :src="'/img/file.png'" height="50" width="50">
                                            </div>
                                            <div style="float: left;" v-else>
                                                <img title="Click To Download" data-toggle="tooltip"
                                                    :src="'/img/attachment.png'" height="50" width="50">
                                            </div>
                                        </a>
                                        <span :title="(files != undefined ) ? files.user.name : ''" data-toggle="tooltip" style="position: relative; float: right;" v-html="dateFormate(files.created_at)+'<br>&emsp;&emsp;'+files.user.name.split(' ')[0].substring(0,9)"></span>

                                        <span>
                                            <img @click="deleteFile(files.id)" style="position: absolute; right: 5px; bottom: 10px;" :src="'https://img.icons8.com/color/48/000000/delete-forever.png'" height="20" width="20">
                                        </span>
                                    </span>
                                </div>
                            </div>
                       </div>
                       <div class="col-12" style="margin: 10px 0px;">
                           <div class="">
                               <input :id="'files'+selectedData.cardId" :ref="selectedData.cardId" style="display: none;" @change="updateCardPicture($event,selectedData)"
                                        type="file">
                               <span> Upload File : </span><a @click="addCardAttachment(selectedData)" class="btn btn-default btn-sm"
                                   style="border: 1px solid #f1efe6">
                                       <i class="fa fa-paperclip"></i>
                               </a>
                           </div>
                       </div>
                   </div>
               </span>
           </div>

            <div aria-labelledby="comment-tab" class="tab-pane" id="comment" role="tabpanel" style="overflow: hidden;">
                <span>
                    <div class="row comment-section-in-task-details" style="max-height: calc(100vh - 300px);">
                        <div id='cmntSection' style="margin:0px 28px; max-height: calc(100vh - 370px); width: 90%; height: 1000px; overflow: auto;" >

                            <div class="comment_block">
                                <div class="new_comment">
                                    <template v-for="comments in comment" style="margin-top: 15px;" >
                                        <ul class="user_comment">
                                            <div class="user_avatar" :title="comments.user.name" data-placement="bottom" data-toggle="tooltip" >
                                                <img :src="comments.user.photo_url" v-if="comments.user.photo_url !== null && comments.user.photo_url !== ''">
                                                <p :title="comments.user.name"
                                                 data-placement="bottom" data-toggle="tooltip"
                                                class="comment-avature user_avatar"
                                                v-else>
                                                    {{ comments.user.name.substring(0,2) }}</p>
                                            </div>
                                            <div class="comment_body">
                                                <span style="padding: 10px;" v-if="comments.comment != '' && comments.comment != null">
                                                    <p>
                                                        <span class="user">{{comments.user.name}} :</span>
                                                        <span v-html="comments.comment"></span>
                                                    </p>
                                                </span>
                                                <span style="padding: 10px;" v-if="comments.attatchment != '' && comments.attatchment != null">
                                                    <span class="user">{{comments.user.name}} :</span>
                                                    <a target="_blank" :href="'/storage/'+selectedData.cardId+'/comment/'+comments.attatchment"
                                                        style="cursor: pointer;">
                                                        <div v-if="comments.attatchment.toLowerCase().endsWith('.png') || comments.attatchment.toLowerCase().endsWith('.jpg') || comments.attatchment.toLowerCase().endsWith('.gif')">
                                                            <img title="Click To Download" data-toggle="tooltip"
                                                                :src="'/storage/'+selectedData.cardId+'/comment/'+comments.attatchment" height="80" width="80">
                                                        </div>
                                                        <div v-else-if="comments.attatchment.toLowerCase().endsWith('.txt') ">
                                                            <img title="Click To Download" data-toggle="tooltip"
                                                                :src="'/img/txt.png'" height="50" width="50">
                                                        </div>
                                                        <div v-else-if="comments.attatchment.toLowerCase().endsWith('.pdf') ">
                                                            <img title="Click To Download" data-toggle="tooltip"
                                                                :src="'/img/pdf.png'" height="50" width="50">
                                                        </div>
                                                        <div v-else-if="comments.attatchment.toLowerCase().endsWith('.doc') || comments.attatchment.toLowerCase().endsWith('.docx') || comments.attatchment.toLowerCase().endsWith('.xls') || comments.attatchment.toLowerCase().endsWith('.xlsx')">
                                                            <img title="Click To Download" data-toggle="tooltip"
                                                                :src="'/img/file.png'" height="50" width="50">
                                                        </div>
                                                        <div style="float: left;" v-else>
                                                            <img title="Click To Download" data-toggle="tooltip"
                                                                :src="'/img/attachment.png'" height="50" width="50">
                                                        </div>
                                                    </a>
                                                </span>
                                            </div>

                                            <div class="comment_toolbar">
                                                <div class="comment_details">
                                                    <ul>
                                                        <li><i class="fa fa-clock-o"></i> {{comments.created_at.substring(11,16)}}</li>
                                                        <li><i class="fa fa-calendar"></i>{{ ' '+comments.created_at.substring(0,10)}}</li>
                                                        <li><i class="fa fa-pencil"></i> <span class="user">{{comments.user.name}}</span></li>
                                                        <li @click="deleteDetailComment(comments.id)"><i class="fa fa-trash"></i> <span class="user" style="color: red"> Delete</span></li>
                                                    </ul>
                                                </div>
                                                <div class="comment_tools">
                                                    <ul>
                                                        <li @click="replyToComment(comments.id)"><i class="fa fa-reply"></i></li>
                                                <!--         <li><i class="fa fa-share-alt"></i></li>-->
                                                <!--         <li><i class="fa fa-heart love"></i></li>-->
                                                    </ul>
                                                </div>

                                            </div>
                                            <li :id="'replyBox'+comments.id" class="replyCommentSection" style="display : none;" >
                                                <!-- v-click-outside="hidereplaybox(comments.id)" -->
                                                <!-- <input type='text' class="form-control" style="width: 85% !important; margin-bottom: 10px;">
                                                <a class="btn btn-default btn-sm" style="background: #7BB348;" @click="saveComment(selectedData.cardId)">Post</a> -->
                                                <div class="mb-3 input-group display-inline position-relative" style="margin-bottom: 10px; left: 78px;">
                                                    <div :id="'myUL-user-reply'+comments.id" class="myUL-user-comment" style="left: 0px; top: -20px;">
                                                        <template v-for="user in replyProjectUsers" v-if=" replyProjectUsers !== null && replyProjectUsers.length > 0">
                                                            <li @click="replySearchTaskByAssignedUser(user.id,user.name,comments)">
                                                                <a href="javascript:void(0)">
                                                                    <span class="assignUser-suggest-photo">
                                                                            {{(user.name !== null) ? user.name.substring(0,2) : ''}}
                                                                    </span>
                                                                    {{user.name}}
                                                                </a>
                                                            </li>
                                                        </template>
                                                        <template v-else>
                                                            <li>
                                                                <a href="javascript:void(0)">
                                                                    No user found!
                                                                </a>
                                                            </li>
                                                        </template>
                                                    </div>
                                                    <input  :id="'replyTextBox'+comments.id" type="text"
                                                            class="custom-input"
                                                            name="subscribe_email"
                                                            @keyup="commentReplyPress($event,selectedData,comments)"
                                                            placeholder="Reply ... ">
                                                    <div class="input-group-append">
                                                        <span @click="saveReply(comments.id, selectedData.cardId)" class="input-group-text" id="basic-addon1">Reply</span>
                                                    </div>
                                                </div>
                                            </li>
                                            <ul style="position: relative; left: 77px; width: calc(100% - 80px);"
                                                v-if="comments.comment_reply.length > 0"
                                                v-for="reply in comments.comment_reply">
                                                <div class="user_avatar" :title="reply.user.name" data-placement="bottom" data-toggle="tooltip" >
                                                    <img :src="reply.user.photo_url" v-if="reply.user.photo_url !== null && reply.user.photo_url !== ''">
                                                    <p :title="reply.user.name"
                                                    data-placement="bottom" data-toggle="tooltip"
                                                    class="comment-avature user_avatar"
                                                    v-else>
                                                        {{ reply.user.name.substring(0,2) }}</p>
                                                </div>
                                                <div class="comment_body">
                                                    <span style="padding: 10px;" v-if="reply.comment != '' && reply.comment != null">
                                                        <p>
                                                            <span class="user">{{reply.user.name}} :</span>
                                                            <span v-html="reply.comment"></span>
                                                        </p>
                                                    </span>
                                                    <span style="padding: 10px;" v-if="reply.attatchment != '' && reply.attatchment != null">
                                                        <span class="user">{{reply.user.name}} :</span>
                                                        <a target="_blank" :href="'/storage/'+selectedData.cardId+'/comment/'+reply.attatchment"
                                                            style="cursor: pointer;">
                                                            <div v-if="reply.attatchment.endsWith('.png') || reply.attatchment.endsWith('.jpg') || reply.attatchment.endsWith('.gif')">
                                                                <img title="Click To Download" data-toggle="tooltip"
                                                                    :src="'/storage/'+selectedData.cardId+'/comment/'+reply.attatchment" height="80" width="80">
                                                            </div>
                                                            <div v-if="reply.attatchment.endsWith('.txt') ">
                                                                <img title="Click To Download" data-toggle="tooltip"
                                                                    :src="'/img/txt.png'" height="50" width="50">
                                                            </div>
                                                            <div v-if="reply.attatchment.endsWith('.pdf') ">
                                                                <img title="Click To Download" data-toggle="tooltip"
                                                                    :src="'/img/pdf.png'" height="50" width="50">
                                                            </div>
                                                            <div v-if="reply.attatchment.endsWith('.doc') || reply.attatchment.endsWith('.docx') || reply.attatchment.endsWith('.xls') || reply.attatchment.endsWith('.xlsx')">
                                                                <img title="Click To Download" data-toggle="tooltip"
                                                                    :src="'/img/file.png'" height="50" width="50">
                                                            </div>
                                                        </a>
                                                    </span>
                                                </div>
                                                <div class="comment_toolbar">
                                                    <div class="comment_details" style="width: 100% !important;">
                                                        <ul>
                                                            <li><i class="fa fa-clock-o"></i> {{ reply.created_at.substring(11,16)}}</li>
                                                            <li><i class="fa fa-calendar"></i> {{ reply.created_at.substring(0,10)}}</li>
                                                            <li><i class="fa fa-pencil"></i> <span class="user"> {{ reply.user.name }}</span></li>
                                                            <li @click="deleteDetailComment(reply.id)"><i class="fa fa-trash"></i> <span class="user" style="color: red"> Delete</span></li>
                                                        </ul>
                                                    </div>
                                                    <!-- <div class="comment_tools">
                                                        <ul>
                                                            <li @click="replyToComment(comments.id)"><i class="fa fa-reply"></i></li>
                                                            <li><i class="fa fa-share-alt"></i></li>
                                                            <li><i class="fa fa-heart love"></i></li>
                                                        </ul>
                                                    </div> -->
                                                </div>
                                            </ul>

                                        </ul>
                                    </template>

                                </div>
                            </div>
                        </div>
                        <div class="col-12" style="margin: 10px 0px;">
                            <!-- <img alt="user" class="commentPic" src="/images/avatar.png" title="Avater">
                            position: fixed; bottom: 0px; width: 54%; right: 15px; background: white; width: 100%; max-width: 634px;
                             -->
                            <div  v-click-outside="HideTextArea" >
                                <ul id="myUL-user" class="myUL-user-comment">
                                    <template v-for="user in projectUsers" v-if=" projectUsers !== null && projectUsers.length > 0">
                                        <li @click="SearchTaskByAssignedUser(user.id,user.name)">
                                            <a href="javascript:void(0)">
                                                <span class="assignUser-suggest-photo">
                                                        {{(user.name !== null) ? user.name.substring(0,2) : ''}}
                                                </span>
                                                {{user.name}}
                                            </a>
                                        </li>
                                    </template>
                                    <template v-else>
                                        <li>
                                            <a href="javascript:void(0)">
                                                No user found!
                                            </a>
                                        </li>
                                    </template>
                                    <!-- <li>
                                        <a href="javascript:void(0)">
                                            <span class="assignUser-suggest-photo"> Su </span> Sudip
                                        </a>
                                    </li> -->
                                </ul>
                                <p :title="selectedData.userName" class="assignUser-photo-for-selected text-uppercase details-comments-pic"
                                data-placement="bottom" data-toggle="tooltip" style="overflow:hidden;"> {{ selectedData.userName.substring(0,2) }}</p>
                                <textarea @focus="ShowTextArea(selectedData)"
                                        :id="'comment'+selectedData.cardId"
                                        class="form-control commentInput"
                                        data-grow="auto"
                                        @keyup="commentPress($event,selectedData)"
                                        placeholder="Add comment">
                                </textarea>
                                <!-- v-on:keyup.50="userList($event,selectedData)" -->

                                <div class="SubmitButton" id="SubmitButton" style="margin-bottom: 10px; margin-top: 10px;">
                                    <a class="btn btn-default btn-sm" style="background: #7BB348;" @click="saveComment(selectedData.cardId)">Post</a>
                                    <!-- <a class="btn btn-default btn-sm" style="border: 1px solid #f1efe6"
                                    @click="HideTextArea">Cancel</a> -->
                                    <input :id="'file'+selectedData.cardId" :ref="selectedData.cardId" style="display: none;" @change="updatePicture($event,selectedData)"
                                        type="file">
                                    <a @click="addAttachment(selectedData)" class="btn btn-default btn-sm"
                                    style="border: 1px solid #f1efe6">
                                        <i class="fa fa-paperclip"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </span>
            </div>
            <div aria-labelledby="log-tab" class="tab-pane" id="log" role="tabpanel">
                <span>
                    <div class="log-data">
                        <h3 class="p-3">Log data :</h3>
                        <div class="tags-log">
                            <table class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th scope="col" style=" width: 40%;">Title</th>
                                        <th scope="col" style=" width: 20%;">Log Type</th>
                                        <th scope="col" style=" width: 20%;">Action Type</th>
                                        <th scope="col" style=" width: 20%;">Action At</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="log in task_logs">
                                        <td>{{log.title}}</td>
                                        <td>{{log.log_type}}</td>
                                        <td>{{log.action_type}}</td>
                                        <td class="text-center">{{ log.action_at | relative }} </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </span>

            </div>
            <div aria-labelledby="child-tab" class="tab-pane" id="child" role="tabpanel">
                <span>
                    <div class="tags-log">
                        <!-- <div v-if="selectedData.parents.length  > 0">
                        <label class="label"> <h5>Parents:</h5></label>
                            <li class="list-group-item" v-for="(parent, index) in selectedData.parents" :key="index">
                                <label class="checkbox_cus_mini ">
                                    <span v-for="(tab,iid) in index" :key="iid"> &emsp;</span>
                                    {{ parent.title }}
                                </label>
                            </li>
                            <li class="list-group-item">
                                <label class="checkbox_cus_mini">
                                    <span v-for="(tab,iid) in (selectedData.parents.length+1)" :key="iid"> &emsp;</span>
                                    {{ selectedData.data }}
                                </label>
                            </li>
                            <Br/>
                        </div> -->
                        <div  v-if="selectedData.type !== 'task' && selectedData.childrens.length  > 0">
                            <label class="label"> <h5>Tree View:</h5></label>
                            <div v-for="(child,index) in selectedData.childrens">
                                <li class="list-group-item detail-tree-view ">
                                    <label class="checkbox_cus_mini ">
                                        {{ child.title }}
                                    </label>
                                    <ul v-if="child.child_task.length  > 0" class="list-group list-group-flush">
                                        <div v-for="child1 in child.child_task">
                                            <li class="list-group-item detail-tree-view">
                                                <label class="checkbox_cus_mini ">
                                                    {{ child1.title }}
                                                    </label>
                                                <ul v-if="child1.child_task.length  > 0" class="list-group list-group-flush">
                                                    <div v-for="child2 in child1.child_task">
                                                        <li class="list-group-item detail-tree-view">
                                                            <label class="checkbox_cus_mini ">
                                                                {{ child2.title }}
                                                            </label>
                                                            <ul v-if="child2.child_task.length  > 0"
                                                                class="list-group list-group-flush">
                                                                <div v-for="child3 in child2.child_task">
                                                                    <li class="list-group-item detail-tree-view">
                                                                        <label class="checkbox_cus_mini ">
                                                                            {{ child3.title }}
                                                                        </label>
                                                                        <ul v-if="child1.child_task.length  > 0" class="list-group list-group-flush">
                                                                            <div v-for="child2 in child1.child_task">
                                                                                <li class="list-group-item detail-tree-view">
                                                                                    <label class="checkbox_cus_mini ">
                                                                                        {{ child2.title }}
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
                        </div>
                    </div>
                </span>
            </div>
        </div>
    </div>
</template>
<style scoped>
    .detail-tree-view {
        border-top: 1px solid gray !important;
    }

    .selected-li {
        background-color: #ddf3fd;
    }
</style>
<script>
    import switches from 'vue-switches';
    import Datepicker from 'vuejs-datepicker';
    import VueTagsInput from '@johmun/vue-tags-input';
    import ClickOutside from 'vue-click-outside';
    import CKEditor from '@ckeditor/ckeditor5-vue';
    import ClassicEditor from '@ckeditor/ckeditor5-build-classic';

    export default {
        components: {switches, Datepicker, VueTagsInput, ckeditor: CKEditor.component},
        name: "TaskDetails",
        props: ['selectedData'],
        data() {
            return {
                comment: null,
                editor: ClassicEditor,
                editorData: '<p>Content of the editor.</p>',
                editorConfig: {
                    // The configuration of the editor.
                },
                showDetails: false,
                disabledDates: {
                    id: null
                },
                id: 0,
                tag: null,
                tags: [],
                tag1: '',
                cards: [],

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
                transferBtn: false,
                project: null,
                projectUsers: null,
                replyProjectUsers: null,
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
                // selectedData : {},
                task_logs : [],
                check_uncheck_child: null,
                manageTag: null,
                trigger : false,
                userNames : '',
                commentsData : '',
            }
        },
        mounted() {
            let _this = this;
            console.log(this.selectedData);
            // CKEDITOR.replace( "description" );
            // console.log(selectedData);
            setTimeout(function () {
                $('[data-toggle="tooltip"]').tooltip();
                var hei = $("#cmntSection").height();
                $("#cmntSection").animate({ scrollTop: hei }, 2000);
                // $('#comment'+selectedData.cardId);
                //  .getElementById('comment'+_this.selectedData.cardId).addEventListener("paste", _this.handlePaste);
            }, 1000);
        },
        created() {

        },
        methods: {
            handlePaste(){
                console.log('paste');
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
            updateDate(date) {
                date = new Date(date);
                var _this = this;
                var month = (parseFloat(date.getMonth() + 1) > 9) ? parseFloat(date.getMonth() + 1) : '0' + parseFloat(date.getMonth() + 1);
                var formatedDate = date.getFullYear() + '-' + month + '-' + date.getDate();

                var postData = {
                    id: _this.selectedData.cardId,
                    date: date
                };
                axios.post('/api/card-update/' + _this.selectedData.cardId, postData)
                    .then(response => response.data)
                    .then(response => {
                        // _this.getTaskList();
                    })
                    .catch(error => {
                        console.log('Api for task date update not Working !!!')
                    });
            },
            HideListDetails() {
                var _this = this;
                // $('.submitdetails').hide();
            },
            HideDetails() {
                // this.getBoardTask();
                $('#task_width').addClass('task_width');
                $('#task_width').removeClass('task_widthNormal');
                $('#details').addClass('details');
                $('#details').removeClass('detailsShow');
            },
            updateDescription(id) {

                var _this = this;
                // console.log(this.selectedData.description);
                var postData = {
                    id: _this.selectedData.cardId,
                    description: _this.selectedData.description
                };
                axios.post('/api/card-update/' + _this.selectedData.cardId, postData)
                .then(response => response.data)
                .then(response => {
                    console.log(response)
                    _this.HideDetails();
                    // $('.submitdetails').hide();
                    // _this.getTaskList()
                    // $('#dropdown' + data._id).toggle();
                    // _this.selectedData.tags = tag
                })
                .catch(error => {
                    console.log('Api for move down task not Working !!!');
                });
            },
            HideTextArea() {
                var _this = this;
                $('#cmntSection').css({maxHeight:' calc(100vh - 370px)'});
                $('.SubmitButton').hide();
            },
            ShowTextArea(data) {
                $('#cmntSection').css({maxHeight:' calc(100vh - 420px)'});
                this.$emit('textArea', data)
                $('#comment'+data.cardId).css({height : '50px', maxHeight : '100px'});
                 var hei = $("#cmntSection").height();
                $("#cmntSection").animate({ scrollTop: hei }, 1000);
                // var _this = this;
                // $('.SubmitButton').show();
                // var option = {
                //     height: 50,
                //     maxHeight: 200
                // };
                // _this.growInit(option);
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
            addAttachment(data) {
                // console.log(data);
                let refData = data.cardId;
                // alert(refData);
                $('#file' + refData).click();
            },
            addCardAttachment(data) {
                // console.log(data);
                let refData = data.cardId;
                // alert(refData);
                $('#files' + refData).click();
            },
            updatePicture(e, data) {
                var _this = this;
                this.file = e.target.files[0];
                let formData = new FormData();
                formData.append('file', this.file);
                formData.append('id', data.cardId);
                formData.append('files', 'sdsds');

                axios.post('/api/comment-file-upload', formData, {headers: {'Content-Type': 'multipart/form-data'}})
                .then(response => response.data)
                .then(response => {
                    _this.comment.push(response.Data);
                    setTimeout(() => {
                        $('[data-toggle="tooltip"]').tooltip();
                    }, 100);
                    // _this.getTaskList()
                })
                .catch(error => {
                    console.log('Api for task date update not Working !!!')
                });
            },
            updateCardPicture(e, data) {
                var _this = this;
                this.file = e.target.files[0];
                let formData = new FormData();
                formData.append('file', this.file);
                formData.append('id', data.cardId);
                formData.append('files', 'sdsds');

                axios.post('/api/card-file-upload', formData, {headers: {'Content-Type': 'multipart/form-data'}})
                .then(response => response.data)
                .then(response => {
                    console.log(response.files);
                    console.log(_this.selectedData.files);
                    _this.selectedData.files.push(response.files);
                    console.log(_this.selectedData.files);
                    setTimeout(() => {
                        $('[data-toggle="tooltip"]').tooltip();
                        e.target.type = 'text';
                        e.target.value = '';
                        e.target.type = 'file';
                    }, 100);
                    // _this.getTaskList()
                })
                .catch(error => {
                    console.log('Api for task date update not Working !!!')
                });
            },
            deleteFile(id){
                let _this = this;

                swal({
                    title: 'Are you sure to delete this file?',
                    text: "",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: 'red',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, Delete It!'
                },
                function(){
                    var data = {
                         'id' : id
                    };
                    axios.post('/api/delete-card-file', data)
                    .then(response => response.data)
                    .then(response => {
                        _this.getFiles(_this.selectedData.cardId);
                        swal("Deleted!", "Successfully Deleted", "success");
                        setTimeout(() => {
                            swal.close();
                        }, 1000);
                    })
                    .catch(error =>{

                    });
                });

            },
            saveComment(id){
                let _this = this;
                let comment = $('#comment'+id).val();
                if (comment === '' || comment === null) {
                    swal('Warning!!','Comment is empty','warning');
                    setTimeout(() => {
                        swal.close();
                    }, 1000);
                    return false;
                }
                var commentData = {
                    'comment' : comment,
                    'task_id' : id
                };
                axios.post('/api/add-comment', commentData)
                .then(response => response.data)
                .then(response => {
                    console.log(_this.comment);
                    $('#comment'+id).val('');
                    setTimeout(() => {
                        _this.comment.push(response.Data);
                        _this.HideTextArea();
                        var hei = $("#cmntSection").height();
                        $("#cmntSection").animate({ scrollTop: hei }, 1000);
                        // console.log(_this.selectedData.comment);
                    }, 500);
                })
                .catch(error =>{

                });

            },
            showImage(data, image)
            {
                this.modalImg = image;
                $("#imageModal").modal();
            },
            showAssignedUserRemoveButton(data) {

                $('[data-toggle="tooltip"]').tooltip('hide');

                setTimeout(function () {
                    $('#remove-assign-user-modal' + data.cardId).toggleClass('remove-assign-user');
                    $('#remove-assign-user-modal' + data.cardId).toggle();
                    $('#remove-assign-user-modal' + data.cardId).removeClass('remove-assigned');
                }, 500)

            },
            commentPress(e,data)
            {
                console.log(e.which);
                let _this = this;
                // this.projectUsers = null;
                let cmHe = $('#comment'+data.cardId).height();
                $('#cmntSection').css({maxHeight: ' calc(100vh - 420px - '+cmHe+'px + 30px)'});
                // console.log(this.selectedData.comment);
                if (e.which == 32) {
                    _this.trigger = false;
                    _this.userNames = '';
                    _this.projectUsers = null;
                }

                if (_this.trigger == true && e.which !== 16 && e.which !== 50) {
                    _this.userNames += e.key;
                    // console.log(e.key, _this.userNames);
                    // axios.post('/api/task-list/search-result', {
                    //     'user_id': id,
                    //     p_id: _this.projectId,
                    //     list_id: nav_type.list_id,
                    //     type: (_this.search_type === 'all') ? 'overview' : nav_type.type,
                    // })
                    // .then(response => response.data)
                    // .then(response => {
                    //     _this.searchData.tasks = response.search_tasks;
                    //     // console.log(_this.searchData.tasks)
                    //     $('#myUL-user').removeClass('myUL-user');
                    //     $('#myUL').removeClass('myUL');
                    //     $('#myUL').addClass('myUL-show');

                    // })
                    // .catch(error => {
                    //     console.log('Api is drag and drop not Working !!!')
                    // });
                }
                
                if (e.shiftKey && e.which == 50) {
                    _this.trigger = true;
                    _this.commentsData = $('.commentInput').val();
                    axios.get('/api/task-list/all-suggest-user')
                    .then(response => response.data)
                    .then(response => {
                        _this.projectUsers = response.search_user;
                    })
                    .catch(error => {
                        console.log('All suggest user api not working')
                    })
                }
            },
            commentReplyPress(e,data,comments)
            {
                // console.log(e.which);
                let _this = this;
                // this.projectUsers = null;
                // let cmHe = $('#replyTextBox'+comments.id).height();
                // $('#cmntSection').css({maxHeight: ' calc(100vh - 420px - '+cmHe+'px + 30px)'});
                // console.log(this.selectedData.comment);
                if (e.which == 32) {
                    _this.trigger = false;
                    _this.userNames = '';
                    _this.replyProjectUsers = null;
                }

                if (_this.trigger == true && e.which !== 16 && e.which !== 50) {
                    _this.userNames += e.key;
                    console.log(_this.userNames);
                    // console.log(e.key, _this.userNames);
                    // axios.post('/api/task-list/search-result', {
                    //     'user_id': id,
                    //     p_id: _this.projectId,
                    //     list_id: nav_type.list_id,
                    //     type: (_this.search_type === 'all') ? 'overview' : nav_type.type,
                    // })
                    // .then(response => response.data)
                    // .then(response => {
                    //     _this.searchData.tasks = response.search_tasks;
                    //     // console.log(_this.searchData.tasks)
                    //     $('#myUL-user').removeClass('myUL-user');
                    //     $('#myUL').removeClass('myUL');
                    //     $('#myUL').addClass('myUL-show');

                    // })
                    // .catch(error => {
                    //     console.log('Api is drag and drop not Working !!!')
                    // });
                }
                
                if (e.shiftKey && e.which == 50) {
                    _this.trigger = true;
                    _this.commentsData = $('#replyTextBox'+comments.id).val();
                    axios.get('/api/task-list/all-suggest-user')
                    .then(response => response.data)
                    .then(response => {
                        _this.replyProjectUsers = response.search_user;
                    })
                    .catch(error => {
                        console.log('All suggest user api not working')
                    })
                }
            },
            userList(e,data)
            {   
                let _this = this;
                var value = e.target.value;
                // console.log(value.endsWith('@'));
                let cmHe = $('#comment'+data.cardId).height();
                $('#cmntSection').css({maxHeight: ' calc(100vh - 420px - '+cmHe+'px + 30px)'});
                // console.log(this.selectedData);
            },
            replySearchTaskByAssignedUser(id, name, comment) {
                let _this = this;
               $('#replyTextBox'+comment.id).val(_this.commentsData+''+name+' ');
                //    $('.SubmitButton').show();
                   $('#replyTextBox'+comment.id).focus();
               _this.replyProjectUsers = null;
            },
            SearchTaskByAssignedUser(id, name) {
                let _this = this;
               $('.commentInput').val(_this.commentsData+''+name+' ');
                //    $('.SubmitButton').show();
                //    $('.commentInput').focus();
               _this.projectUsers = null;
            },
            // removeAssignedUser(user, index, key) {

            //     // console.log(user.id, user.task_id);
            //     var _this = this;
            //     var postData = {
            //         user_id: user.cardId,
            //         task_id: user.task_id
            //     };
            //     axios.post('/api/task-list/assign-user-remove', postData)
            //         .then(response => response.data)
            //         .then(response => {
            //             // console.log(response);
            //             if (response === 'success') {
            //                 // _this.cards[index].task[key].assigned_user.splice(0,1);
            //                 setTimeout(function () {
            //                     // _this.getData();
            //                     _this.selectedData.assigned_user = [];
            //                     _this.selectedData.assigned_user_ids = [];
            //                 }, 100);
            //             }
            //         })
            //         .catch(error => {
            //             console.log('Api assign-user-remove is not Working !!!')
            //         });
            // },
            removeAssignedUser(user_id, task_id)
            {

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
                            setTimeout(function () {
                                // _this.getData();
                                _this.selectedData.assigned_user = [];
                                _this.selectedData.assigned_user_ids = [];
                            }, 100);
                        }
                    })
                    .catch(error => {
                        console.log('Api assign-user-remove is not Working !!!')
                    });
            },
            assignUserToTask(user, data)
            {
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
                                // _this.cards[index].task[key].assigned_user.push(response.data);
                                _this.selectedData.assigned_user.push(response.data);
                                _this.selectedData.assigned_user_ids.push(response.data.id);
                                // _this.getData();
                            }, 100);
                        }
                    })
                    .catch(error => {
                        console.log('Api is not Working !!!')
                    });
            },
            switchEvent(e)
            {
                $(e.target).closest('.eachItemRow').find('.switchToggle').collapse('toggle');
            },
            deleteCardTag(obj, card)
            {
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
                            _this.selectedData.tags.splice(obj.index, 1);
                            setTimeout(function () {
                                // _this.getData();
                            }, 100);
                            _this.tags = [];
                        })
                        .catch(error => {
                            console.log('Api for delete tag not Working !!!')
                        });
                }

            },
            changeTag(tags, card, columnIndex, cardIndex)
            {
                // console.log(card);
                var _this = this;
                var old = this.selectedData.tags.length;
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
                                // _this.getBoardTask();
                                console.log(response);
                                _this.selectedData.tags.push({
                                    assign_id: response.data.assign_id,
                                    board_id: response.data.board_id,
                                    classes: "",
                                    color: response.data.color,
                                    id: response.data.id,
                                    style: 'style="background: "' + response.data.color,
                                    text: response.data.title,
                                });
                                $('.dropdown-menu').removeClass('show');
                                // _this.getData();
                            }, 100);
                        })
                        .catch(error => {
                            console.log("2nd error =>" + error)
                        });
                }
            },
            addExistingTag(index, cardId, dntfrgt = '')
            {
                let _this = this;
                console.log(this.selectedData.existing_tags[index]);
                if (dntfrgt !== '') {
                    var postData = {
                        id: cardId,
                        tags: "Dont Forget",
                        color: "#FF0000",
                        type: 'task',
                    };
                } else {
                    var postData = {
                        id: cardId,
                        tags: this.selectedData.existing_tags[index].title,
                        color: this.selectedData.existing_tags[index].color,
                        type: 'task',
                    };
                }
                // if(card.types == "task"){
                //     postData.id = card.id;
                // }
                axios.post('/api/task-list/add-tag', postData)
                    .then(response => response.data)
                    .then(response => {
                        _this.selectedData.tags.push({
                            assign_id: response.data.assign_id,
                            board_id: response.data.board_id,
                            classes: "",
                            color: _this.selectedData.existing_tags[index].color,
                            id: _this.selectedData.existing_tags[index].id,
                            style: '',
                            text: _this.selectedData.existing_tags[index].title,


                            id: _this.selectedData.existing_tags[index]
                        });
                        _this.selectedData.existing_tags.splice(index, 1);
                        // $('#dropdown' + cardId).toggle();
                        setTimeout(function () {
                            // _this.getBoardTask();
                        }, 100);
                    })
                    .catch(error => {
                        console.log("1st error =>" + error)
                    });
            },
            showOriginalList(task) {
                if (task.type !== 'task') {
                    $('#list' + task.list_id).click();
                    setTimeout(function () {
                        $('#click' + task.cardId).click();
                    }, 1000);
                    $('[data-toggle="tooltip"]').tooltip('dispose');
                    setTimeout(function () {
                        $('[data-toggle="tooltip"]').tooltip('enable');
                    }, 500);
                } else {
                    $('.board' + task.multiple_board_id).click();
                    setTimeout(function () {
                        $('#card_' + task.cardId).click();
                    }, 1000);
                    $('[data-toggle="tooltip"]').tooltip('dispose');
                    setTimeout(function () {
                        $('[data-toggle="tooltip"]').tooltip('enable');
                    }, 500);
                }

            },
            showTagManageModel()
            {
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
            generateColor()
            {
                var myColor = '#000000';
                myColor = '#' + (Math.random() * 0xFFFFFF << 0).toString(16);
                return myColor;
            },
            dateFormate(date)
            {
                const monthNames = ["Jan", "Feb", "Mar", "Apr", "May", "Jun",
                    "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"
                ];
                var formatedate = new Date(date);

                var hours = formatedate.getHours();
                var minutes = formatedate.getMinutes();
                var ampm = hours >= 12 ? 'pm' : 'am';
                hours = hours % 12;
                hours = hours ? hours : 12; // the hour '0' should be '12'
                minutes = minutes < 10 ? '0'+minutes : minutes;
                var strTime = hours + ':' + minutes + ' ' + ampm;

                var moment =  '&emsp;&emsp;'+formatedate.getDate()+' '+monthNames[formatedate.getMonth()]+' '+formatedate.getFullYear()+'<br>&emsp;&emsp;'+strTime;
                return moment;
            },
            showChild(cardId)
            {
                let _this = this;
                let data = {
                    'task_id' : cardId
                };
                axios.post('/api/show-child-parent',data)
                .then(response => response.data)
                .then(response => {
                    _this.selectedData.childrens = response.childs;//.child_task;
                    _this.selectedData.parents = response.childs;
                    console.log(response.childs.length);
                })
                .catch(error => {

                })
            },
            getFiles(cardId)
            {
                let _this = this;
                let data = {
                    'task_id' : cardId
                };
                _this.selectedData.files = [];
                axios.post('/api/get-card-file',data)
                .then(response => response.data)
                .then(response => {
                    _this.selectedData.files = response.files ;
                    console.log(_this.selectedData);
                })
                .catch(error => {

                })
            },
            getComments(cardId)
            {
                let _this = this;
                let data = {
                    'task_id' : cardId
                };
                _this.comment = [];
                axios.post('/api/get-card-comment',data)
                .then(response => response.data)
                .then(response => {
                    _this.comment = response.comment.comment;
                    _this.selectedData.comment = _this.comment;
                    console.log(_this.selectedData);
                })
                .catch(error => {

                })
            },
            deleteDetailComment(id)
            {
                let _this = this;
                swal({
                    title: 'Are you sure to delete this comment?',
                    text: "",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: 'red',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, Delete It!'
                },
                function(){
                    var data = {
                         'id' : id
                    };
                    axios.post('/api/delete-card-comment', data)
                    .then(response => response.data)
                    .then(response => {
                        _this.getComments(_this.selectedData.cardId);
                        swal("Deleted!", "Successfully Deleted", "success");
                        setTimeout(() => {
                            swal.close();
                        }, 1000);
                    })
                    .catch(error =>{

                    });
                });
            },
            replyToComment(id)
            {
                this.replyProjectUsers = null;
                $('.replyCommentSection').hide();
                $('#replyBox'+id).show();
                $('#replyTextBox'+id).focus();
            },
            hidereplaybox(id)
            {
                let _this = this;
                if(_this.replyProjectUsers === null){
                    $('#replyBox'+id).hide();
                    $('#replyTextBox'+id).val('');
                }
            },
            saveReply(id, task_id)
            {
                let _this = this;
                let reply = $('#replyTextBox'+id).val();
                let data = {
                    'parent_id' : id,
                    'task_id'   : task_id,
                    'comment'   : reply
                }
                // console.log(data);
                axios.post('/api/save-comment-reply', data)
                .then(response => data)
                .then(response => {
                    _this.getComments(task_id);
                    $('#replyBox'+id).hide();
                })
                .catch(error => {

                })
            },
            showLog() {
                var _this = this;
                _this.task_logs = [];
                axios.get('/api/task-list/get-log/' + _this.selectedData.cardId)
                    .then(response => response.data)
                    .then(response => {
                        _this.task_logs = response;
                    })
                    .catch(error => {
                        console.log('Api for move down task not Working !!!')
                    });
            },
        },

        directives: {
            ClickOutside
        },
    }
</script>

