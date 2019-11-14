<template>
    <div class="Rules">
        <div class="container rules-create">
            <section>
                <div class="container-header">
                    <h2><i class="fa fa-fw fa-magic"></i> Rules For <b>{{Project.name}}</b>
                        <!--                        <button type="submit" class="btn btn-primary pull-right"> Create Rule</button>-->
                    </h2>
                    <p class="compltit-p">{{Project.description}}</p>
                </div>
            </section>
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-default border-primary">
                        <div class="card-header bg-primary text-white">
                            <input class="rules-title" type="text" placeholder="Enter Here Rules Name"
                                   v-model="rule.name">
                            <span class="badge-pill badge-danger pull-right rule-header-btn-red" @click="PauseRule">Paused
                                <div class="radio-for">
                                     <i class="fa fa-check check-radio" v-if="rule.status===0"></i>
                                </div>
                            </span>
                            <span class="badge-pill badge-success pull-right mr-2 rule-header-btn" @click="LiveRule">Live
                                <div class="radio-for">
                                    <i class="fa fa-check check-radio" v-if="rule.status===1"></i>
                                </div>
                            </span>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-xs-12 col-md-5 text-right">
                                    <img src="/img/from-arrow.png" class="img-responsive" style="max-width: 200px;">
                                </div>
                                <div class="col-xs-12 col-md-4">
                                    <div class="form-group">
                                        <select class="form-control select2" v-model="rule.move_from"
                                                style="width: 100%; margin-top: 10px; border-top: none; border-left: none; border-right: none; box-shadow: none; border-radius: 0px;">
                                            <option value="select column" disabled>Select a Board and Column</option>
                                            <template v-for="Board in BoardColumn">
                                                <optgroup :label="Board.board_title" v-if="Board.columns.length > 0">
                                                    <template v-for="column in Board.columns">
                                                        <option :value="column.id"
                                                                :disabled="(rule.move_to === column.id) ? true : false">
                                                            {{column.title}}
                                                        </option>
                                                    </template>
                                                </optgroup>
                                            </template>
                                        </select></div>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-xs-12 col-md-5 text-right">
                                    <img src="/img/to-arrow.png" class="img-responsive" style="max-width: 200px;">
                                </div>
                                <div class="col-xs-12 col-md-4">
                                    <div class="form-group">
                                        <select class="form-control select2" v-model="rule.move_to"
                                                style="width: 100%; margin-top: 10px; border-top: none; border-left: none; border-right: none; box-shadow: none; border-radius: 0px;">
                                            <option value="select column">Select a Board and Column</option>
                                            <template v-for="Board in BoardColumn">
                                                <optgroup :label="Board.board_title" v-if="Board.columns.length > 0">
                                                    <template v-for="column in Board.columns">
                                                        <option :value="column.id"
                                                                :disabled="(rule.move_from === column.id) ? true : false">
                                                            {{column.title}}
                                                        </option>
                                                    </template>
                                                </optgroup>
                                            </template>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-xs-12 col-md-5 text-right">
                                    <img src="/img/than-arrow.png" class="img-responsive" style="max-width: 200px;">
                                </div>
                                <div class="col-xs-12 col-md-4 mt-3">
                                    <div class="form-group">
                                        <select id="select22" class="form-control select2" multiple style="width:100%">
                                            <optgroup label="Users">
                                                <template v-for="user1 in user">
                                                    <option :value="user1.id">{{user1.name}}</option>
                                                </template>
                                                <option :value="32">UUUUUU</option>
                                            </optgroup>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row rules-create-bottom">
                                <button type="submit" class="btn btn-danger pull-left" v-if="rules_action === 'update'" @click="DeleteRule(rule.id)">
                                    Delete
                                </button>
                                <button type="submit" class="btn btn-info pull-left ml-2 "
                                        v-if="rules_action === 'update'" @click="UpdateRule"> Update
                                </button>
                                <button type="submit" class="btn btn-primary pull-right"
                                        v-if="rules_action === 'create'"
                                        @click="AddNewRules">
                                    Save
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</template>

<script>
    export default {
        props: ['project_id', 'id', 'action_type'],
        data() {
            return {
                rules_action: this.action_type,
                projectId: this.project_id,
                Project: {},
                BoardColumn: null,
                user: null,
                rule: {
                    id: this.id,
                    name: '',
                    status: 1,
                    move_from: 'select column',
                    move_to: 'select column',
                    assign_to: null,
                }
            }
        },
        mounted() {
            var _this = this;
            this.getProject();
            this.getBoardColumn();
            if (this.rules_action === 'update') {
                this.getRule();
            }

            $("#select22").select2({
                placeholder: "Select User For Assign"
            });


        },
        methods: {
            getProject() {
                var _this = this;
                axios.get('/api/project/' + _this.projectId)
                    .then(response => response.data)
                    .then(response => {
                        _this.Project = response.project;
                    })
                    .catch(error => {

                    });
            },
            getBoardColumn() {
                var _this = this;
                axios.get('/api/board-item-and-column/' + _this.projectId)
                    .then(response => response.data)
                    .then(response => {
                        _this.BoardColumn = response.data;
                        _this.user = response.users;
                    })
                    .catch(error => {

                    });
            },
            getRule() {
                var _this = this;
                axios.get('/api/rules/' + _this.rule.id)
                    .then(response => response.data)
                    .then(response => {
                        _this.rule.id = response.rule.id;
                        _this.rule.name = response.rule.name;
                        _this.rule.status = response.rule.status;
                        _this.rule.move_from = response.rule.move_from;
                        _this.rule.move_to = response.rule.move_to;
                        _this.rule.assign_to = JSON.parse(response.rule.assigned_users);
                        $("#select22").val(_this.rule.assign_to);
                        setTimeout(function () {
                            $("#select22").select2({
                                placeholder: "Select User For Assign"
                            });
                        }, 200)

                    })
                    .catch(error => {
                        //
                    });
            },
            AddNewRules() {
                var _this = this;
                _this.rule.assign_to = JSON.stringify($("#select22").val())
                // console.log(_this.rule.assign_to)
                if (_this.rule.move_from !== 'select column' && _this.rule.name !== '') {
                    _this.rule.project_id = _this.projectId;
                    axios.post('/api/add-rules', _this.rule)
                        .then(response => response.data)
                        .then(response => {

                            _this.rule.id = null;
                            _this.rule.name = '';
                            _this.rule.status = 1;
                            _this.rule.move_from = 'select column';
                            _this.rule.move_to = 'select column';
                            _this.rule.assign_to = 'Assign To';
                            swal('Rules Created', 'Rules Create Success', 'success');
                            _this.$emit('ruleUpdate')
                            $("#select22").val([])
                            setTimeout(function () {
                                $("#select22").select2({
                                    placeholder: "Select User For Assign"
                                });
                            }, 200)
                        })
                        .catch(error => {
                            //
                        });
                } else {
                    swal('Sorry', 'Required Field Can\'t be empty', 'warning');
                }
            },
            UpdateRule() {
                var _this = this;
                if (_this.rule.move_from !== 'select column' && _this.rule.name !== '') {
                    swal({
                            title: "Are you sure?",
                            text: "You want to update this Rule !!!",
                            type: "warning",
                            showCancelButton: true,
                            confirmButtonClass: "btn-danger",
                            confirmButtonText: "Yes, Update it!",
                            closeOnConfirm: false
                        },
                        function () {
                            _this.rule.assign_to = JSON.stringify($("#select22").val())
                            axios.post('/api/rules-update', _this.rule)

                                .then(response => response.data)
                                .then(response => {
                                    swal.close();
                                    _this.$emit('ruleUpdate')
                                })
                                .catch(error => {
                                    console.log('Api for complete task not Working !!!')
                                });

                        });

                } else {
                    swal('Sorry', 'Required Field Can\'t be empty', 'warning');
                }
            },
            DeleteRule(id) {
                var _this = this;
                swal({
                        title: "Are you sure?",
                        text: "You want to Delete this Rule !!!",
                        type: "warning",
                        showCancelButton: true,
                        confirmButtonClass: "btn-danger",
                        confirmButtonText: "Yes, Update it!",
                        closeOnConfirm: false
                    },
                    function () {
                        axios.post('/api/rules-delete' ,{'id': id})

                            .then(response => response.data)
                            .then(response => {
                                swal.close();
                                if (response.status === 'success'){
                                    _this.$emit('ruleUpdate');
                                    _this.rules_action = 'create';
                                    _this.rule.id = null;
                                    _this.rule.name = '';
                                    _this.rule.status = 1;
                                    _this.rule.move_from = 'select column';
                                    _this.rule.move_to = 'select column';
                                    _this.rule.assign_to = 'Assign To';

                                    setTimeout(function () {
                                        $("#select22").val([]);
                                        $("#select22").select2({
                                            placeholder: "Select User For Assign"
                                        });
                                        _this.rules_action = 'create';
                                    }, 200)
                                }
                            })
                            .catch(error => {
                                console.log('Api for Delete Rule not Working !!!')
                            });

                    });


            },

            LiveRule() {
                this.rule.status = 1;
            },
            PauseRule() {
                this.rule.status = 0;
            }
        },
        watch: {
            action_type: function (val) {
                this.rules_action = val;
                if (this.rules_action === 'create') {
                    this.rule.name = '';
                    this.rule.status = 1;
                    this.rule.move_from = 'select column';
                    this.rule.move_to = 'select column';
                    this.rule.assign_to = 'Assign To';
                }
            },
            id: function (val) {
                this.rule.id = val;
                if (this.rules_action === 'update') {
                    this.getRule()
                }
            }
        }
    };
</script>
