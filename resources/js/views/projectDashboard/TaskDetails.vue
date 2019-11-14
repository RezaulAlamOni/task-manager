<template>
    <div class="container" v-if="Object.keys(selectedData).length > 0">
        <!-- Title -->
        <div class="row">
            <div class="col-2">
                <h4 class="compltit-blue details-header">#ID</h4>
            </div>
            <div class="col-2">
                <i class="fa fa-calendar details-title calender-for-details details-header"
                   v-if="selectedData.date === '0000-00-00'"></i>

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
                <img class="img-responsive details-header" src="/img/12.jpg" style="height:30px;width:30px;">
            </div>
            <div class="col-4">
                <!-- Tags -->
                <ul>

                    <i v-if="selectedData.tags.length > 0">
                        <template v-for="tag in selectedData.tags">
                            <li class="badge-pill tags details-header-tag"
                                v-bind:style="[{'background':tag.color},{'margin-left' : 1 +'px'},{'float' : 'left'}]"
                                v-if="tag.text !== null">
                                {{(selectedData.tags.length > 2 ) ? tag.text.substring(0,2) : tag.text.substring(0,3) }}
                            </li>
                        </template>

                    </i>

                    <i :id="'tag-'+selectedData._id" class="outline-local_offer icon-image-preview li-opacity details-header-tag"
                       data-toggle="dropdown"
                       v-else>
                    </i>
                </ul>
            </div>
            <!-- Assign Users -->
            <div class="col-3">
                <a class="user">
                    <template v-if="selectedData.assigned_user.length > 0">
                        <span class="assigned_user dropdown-toggle-split "
                              data-toggle="dropdown" v-for="(assign,keyId) in selectedData.assigned_user">
                            <p :title="assign.name"
                               class="assignUser-photo-for-selected text-uppercase details-header"
                               data-placement="bottom" data-toggle="tooltip"
                               v-if="keyId <= 1">{{(assign.name !== null) ? assign.name.substring(0,2) : ''}}
                                <a :id="'remove-assign-user'+selectedData.id"
                                   class="remove-assigned" href="javascript:void(0)">
                                    <i class="fa fa-times remove-assign-user-icon"></i>
                                </a>
                            </p>

                        </span>
                    </template>
                </a>

            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <h5 class="">
                    {{selectedData.text}}
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
                    <li class="nav-item">
                        <a aria-controls="profile" aria-selected="false" class="nav-link" data-toggle="tab"
                           href="#profile"
                           id="_log" role="tab">Logs</a>
                    </li>
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
                        <div class="textAreaExtend" v-click-outside="HideListDetails">
                            <div class="row">
                                <div class="col-12">
                                    <textarea @focus="ShowListDetails(selectedData)" class="form-control detailsInput"
                                              data-grow="auto"
                                              placeholder="Task Description" v-model="selectedData.description">
                                    </textarea>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <div class="submitdetails" id="submitdetails">
                                        <a @click="updateDescription" class="btn btn-default"
                                           href="javascript:void(0)"
                                           style="background: #7BB348;">Post</a>
                                        <a class="btn btn-default" href="javascript:void(0)"
                                           style="border: 1px solid #f1efe6" @click="HideListDetails">Cancel</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- files -->
                <div class="row">
                    <div class="col-12" >
                        <h3 style="margin-top: 20px;">Files</h3>
                        <div class="details-image">
                            <div class="row">
                                <template v-for="fl in selectedData.files" v-if="selectedData.files && selectedData.files.length !== 0">
                                    <div class="col-md-6">
                                        <img :src="'/images/'+fl.file_name" class="task-img-right-pane">
                                    </div>
                                </template>
                            </div>
                        </div>

                        <div style="cursor: pointer; background-color: #F8F8F8; margin:10px 0;">
                            <input :id="'file'+selectedData._id" :ref="selectedData._id" style="display: none;"
                                   type="file">
                            <a @click="addAttachment(selectedData)"><i class="fa fa-paperclip"></i></a>
                        </div>
                    </div>
                </div>
                <!-- Comments -->
                <div class="row comment-section-in-task-details">
                    <div class="col-12">
                        <div class="textAreaExtend" v-click-outside="HideTextArea">
                            <p class="assignUser-photo-for-selected text-uppercase details-comments-pic"
                               data-placement="bottom" data-toggle="tooltip"> PI</p>
                            <textarea @focus="ShowTextArea(selectedData)"
                                      class="form-control commentInput"
                                      data-grow="auto"
                                      placeholder="Add comment">
                            </textarea>

                            <div class="SubmitButton" id="SubmitButton">
                                <a class="btn btn-default btn-sm" style="background: #7BB348;">Post</a>
                                <a class="btn btn-default btn-sm" style="border: 1px solid #f1efe6" @click="HideTextArea">Cancel</a>
                                <a @click="addAttachment(selectedData)" class="btn btn-default btn-sm"
                                   style="border: 1px solid #f1efe6">
                                    <i class="fa fa-paperclip"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div aria-labelledby="profile-tab" class="tab-pane" id="profile" role="tabpanel">
                <span>
                    <div class="log-data">
                        <h3 class="p-3"> Log data for <b>{{selectedData.text}}</b></h3>
                        <div class="task-log">
                            <table class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th scope="col">Title</th>
                                        <th scope="col">Log Type</th>
                                        <th scope="col">Action Type</th>
                                        <th scope="col">Action At</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="log in task_logs">
                                        <td>{{log.title}}</td>
                                        <td>{{log.log_type}}</td>
                                        <td>{{log.action_type}}</td>
                                        <td>{{log.action_at}}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                    </div>
                </span>

            </div>
        </div>
    </div>
</template>

<script>
    import switches from 'vue-switches';
    import Datepicker from 'vuejs-datepicker';
    import VueTagsInput from '@johmun/vue-tags-input';
    import ClickOutside from 'vue-click-outside';

    export default {
        components: {switches, Datepicker, VueTagsInput},
        name: "TaskDetails",
        props: ['selectedData', 'task_logs'],
        data() {
            return {
                disabledDates: {
                    id: null
                },
                id: 0,
                tag: null,
                tags: [],
                tag1: '',
            }
        },
        mounted() {

        },
        created() {

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
            updateDate(date) {
                date = new Date(date);
                var _this = this;
                var month = (parseFloat(date.getMonth() + 1) > 9) ? parseFloat(date.getMonth() + 1) : '0' + parseFloat(date.getMonth() + 1);
                var formatedDate = date.getFullYear() + '-' + month + '-' + date.getDate();

                var postData = {
                    id: _this.selectedData.id,
                    date: formatedDate
                };
                axios.post('/api/task-list/update', postData)
                    .then(response => response.data)
                    .then(response => {
                        _this.getTaskList()
                    })
                    .catch(error => {
                        console.log('Api for task date update not Working !!!')
                    });
            },
            HideListDetails() {
                var _this = this;
                $('.submitdetails').hide();
            },
            updateDescription() {
                var _this = this;
                var postData = {
                    id: _this.selectedData.id,
                    details: _this.selectedData.description
                };
                axios.post('/api/task-list/update', postData)
                    .then(response => response.data)
                    .then(response => {
                        console.log(response)
                        // _this.getTaskList()
                        // $('#dropdown' + data._id).toggle();
                        // _this.selectedData.tags = tag
                    })
                    .catch(error => {
                        console.log('Api for move down task not Working !!!')
                    });

            },
            HideTextArea() {
                var _this = this;
                $('.SubmitButton').hide();
            },
            ShowTextArea(data) {
                this.$emit('textArea', data)
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
                let refData = data._id;
                $('#file' + refData).click();
            },
            showImage(data, image) {
                this.modalImg = image;
                $("#imageModal").modal();
            },
        },
        directives: {
            ClickOutside
        },
    }
</script>

<style scoped>
</style>
