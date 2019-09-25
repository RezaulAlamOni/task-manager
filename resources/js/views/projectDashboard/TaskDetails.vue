<template>
    <div class="detailsContainer tree_view_list" v-if="selectedData != null">
        <h3 class="">
            <input type="text" class="inp input-hide" v-model="selectedData.text ">
        </h3>
        <ul class="nav nav-tabs" id="myTab" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" id="_details" data-toggle="tab" href="#home" role="tab"
                   aria-controls="home" aria-selected="true">Details</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="_log" data-toggle="tab" href="#profile" role="tab"
                   aria-controls="profile" aria-selected="false">Logs</a>
            </li>
        </ul>

        <!-- Tab panes -->
        <div class="tab-content">
            <div class="tab-pane active" id="home" role="tabpanel" aria-labelledby="home-tab">
                <div class="row pl-3 pt-3">
                    <div>
                        <a class="calender li-opacity clickHide" v-if="!selectedData.date">
                            <i class="outline-event icon-image-preview" title="toggle"
                               data-toggle></i>
                        </a>

                        <datepicker
                            :disabled-dates="disabledDates"
                            input-class="dateCal"
                            v-model="selectedData.date"
                            format='dd MMM'
                            @selected="updateDate"
                            calendar-button-icon='<i class="outline-event icon-image-preview"></i>'
                        >
                        </datepicker>
                    </div>
                    <hr>
                    <div>
                        <a class="user">
                                        <span data-toggle="dropdown">
                                    <i class="outline-person icon-image-preview li-opacity dropdown-toggle-split"></i>
                                    <span class="i-text">Add assignee</span>
                                </span>
                            <div class="dropdown-menu">
                                <diV class="collapse show switchToggle">
                                    <li class="assignUser">
                                        <input type="text" class="input-group searchUser"
                                               placeholder="Set assignee by name and email">
                                        <label class="pl-2 ">
                                            <small style="font-size: 12px">Or invite a new member by
                                                email address
                                            </small>
                                        </label>
                                    </li>
                                    <li class="assignUser">
                                        <div class="row">
                                            <div class="col-md-3 pt-2 pl-5">
                                                <img
                                                    src="data:image/jpeg;base64,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"
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
                                                <img
                                                    src="data:image/jpeg;base64,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"
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


                                <li class="border-top pl-2">

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

                    <div class="col-md-12" style="cursor: pointer; background-color: #F8F8F8">
                        <div class="row">
                            <a>
                                <i v-if="selectedData.tags.length > 0"
                                   class="dropdown-toggle-split"
                                   data-toggle="dropdown">
                                    <template v-for="tag in selectedData.tags">
                                                <span class="badge badge-warning" v-if="tag.text !== null"
                                                      v-bind:style="[{'background':tag.color},{'margin-left' : 1 +'px'},{'float' : 'left'}]">
                                                    {{(selectedData.tags.length > 2 ) ? tag.text.substring(0,2) : tag.text.substring(0,3) }}
                                                </span>
                                        <span class="badge badge-warning" v-else
                                              v-bind:style="[{'background':tag.color},{'margin-left' : 1 +'px'}]">
                                                    ;:
                                                </span>

                                    </template>
                                </i>

                                <i v-else :id="'tag-'+selectedData._id"
                                   class="outline-local_offer icon-image-preview li-opacity"
                                   data-toggle="dropdown">

                                </i>
<!--                                <div class="dropdown-menu dropdown-menu-right" :id="'dropdown'+selectedData._id">-->

<!--                                    <diV class="collapse show switchToggle" style="">-->
<!--                                        <li class="assignUser">-->
<!--                                            <vue-tags-input-->
<!--                                                v-model="tag1"-->
<!--                                                :tags="selectedData.tags"-->
<!--                                                :allow-edit-tags="true"-->
<!--                                                @tags-changed="newTags => (changeTAg(newTags))"-->
<!--                                                @before-deleting-tag="DeleteTag"-->

<!--                                            />-->
<!--                                            <label class="pl-2 pt-3">-->
<!--                                                <template v-for="tag in selectedData.tags">-->
<!--                                                            <span class="badge m-1" v-if="tag.text !== 'Dont Forget'"-->
<!--                                                                  v-bind:style="[{'background': tag.color },{'margin-left' : 1 +'px'}]">-->
<!--                                                                {{(tag.text !== null) ?tag.text.substring(0,12) : ''}}-->
<!--                                                            </span>-->
<!--                                                </template>-->
<!--                                                <span class="badge m-1" style="background: red" @click="addExistingTag($event,data ,'Dont Forget')"> Dont Forget </span>-->

<!--                                                <a href="javascript:void(0)" class="badge badge-primary tag-manager"-->

<!--                                                   @click="showTagManageModel"-->
<!--                                                >-->
<!--                                                    Manage Tag-->

<!--                                                </a>-->

<!--                                            </label>-->
<!--                                        </li>-->

<!--                                    </diV>-->

<!--                                </div>-->


                            </a>
                        </div>
                    </div>
                </div>
                <span>
                                <div class="listDetails">
                                <div class="textAreaExtend" v-click-outside="HideListDetails">
                                    <textarea class="form-control detailsInput" data-grow="auto"
                                              placeholder="Add description"
                                              @focus="ShowListDetails(selectedData)" v-model="selectedData.description"
                                    >
                                    </textarea>
                                    <div class="submitdetails" id="submitdetails">
                                        <a href="javascript:void(0)" class="btn btn-default"
                                           style="background: #7BB348;"
                                           @click="updateDescription">Post</a>
                                        <a href="javascript:void(0)" class="btn btn-default"
                                           style="border: 1px solid #f1efe6">Cancel</a>
                                    </div>

                                    <div v-if="selectedData.files && selectedData.files.length !== 0">
                                        <template v-for="fl in selectedData.files">
                                            <img class="task-img-right-pane" :src="'/images/'+fl.file_name">
<!--                                                 @click="showImage(selectedData.files, fl.file_name)"-->
                                        </template>
                                    </div>
                                    <div style="cursor: pointer; background-color: #F8F8F8; margin:10px 0;">
                                        <input type="file" :ref="selectedData._id" :id="'file'+selectedData._id"
                                               style="display: none;">
                                        <a @click="addAttachment(selectedData)"><i class="fa fa-paperclip"></i></a>
                                    </div>
                                </div>
                            </div>
                                <div class="detailsFooter">
                                <img src="/images/avatar.png" alt="user" class="commentPic">
                                <div class="textAreaExtend" v-click-outside="HideTextArea">
                                    <textarea class="form-control commentInput" data-grow="auto"
                                              placeholder="Add comment"
                                              @focus="ShowTextArea(selectedData)"
                                    >
                                    </textarea>
                                    <div class="SubmitButton" id="SubmitButton">
                                        <a class="btn btn-default btn-sm" style="background: #7BB348;">Post</a>
                                        <a class="btn btn-default btn-sm" style="border: 1px solid #f1efe6">Cancel</a>
                                        <a class="btn btn-default btn-sm" style="border: 1px solid #f1efe6"
                                           @click="addAttachment(selectedData)"><i class="fa fa-paperclip"></i></a>
                                    </div>
                                </div>
                            </div>
                            </span>
            </div>
            <div class="tab-pane" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                            <span>
                                <div class="log-data">
                                    <h3 class="p-3">Log data for <b>{{selectedData.text}}</b></h3>
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
        components: { switches, Datepicker, VueTagsInput},
        name: "TaskDetails",
        props: ['selectedData','task_logs'],
        data() {
            return {
                disabledDates: {
                    id: null
                },
                id: 0,
                tag : null,
                tags: [],
                tag1: '',
            }
        },
        mounted() {
            console.log('Data');
            console.log(this.selectedDa);
        },
        created(){

        },
        methods:{
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
                var month = (parseFloat(date.getMonth() + 1) > 9) ? parseFloat(date.getMonth() + 1) : '0' + parseFloat(date.getMonth() + 1)
                var formatedDate = date.getFullYear() + '-' + month + '-' + date.getDate()

                var postData = {
                    id: _this.selectedData.id,
                    date: formatedDate
                }
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
                }
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
