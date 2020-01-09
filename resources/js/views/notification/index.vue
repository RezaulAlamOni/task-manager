<template>
    <div class="container" style="height: calc(100vh - 77px); overflow: auto;">
        <div class="col-md-12">
            <div class="card card-default">
                <div id="header-item" class="card-header">
                    <b><i class="fa fa-bell-o"/> Email & Notifications</b>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12" v-for="notification in notifications">
                            <h2 class="mb-4">{{ notification.title }}</h2>
                            <div class="form-check mb-4" v-for="child in notification.children">
                                <input :id="child.unique_id" class="form-check-input" type="checkbox"
                                       data-toggle="toggle" data-style="ml-1" data-height="25"
                                       data-onstyle="success" data-offstyle="secondary"
                                       v-model="child.user">
                                <label :for="child.unique_id" class="form-check-label">
                                    <h5 v-text="child.title"></h5>
                                </label>
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
        components: {},
        data() {
            return {
                emailFreq: {
                    everydayUpdate: false,
                    dailyReport: false,
                    weeklyReport: false,
                    monthlyReport: false,
                },
                update_data: false,
                userNotification: {},
                notifications: {}
            };
        },
        created() {
        },
        mounted() {
            let _this = this;
            this.getAllNotifications();
        },
        methods: {
            getAllNotifications() {
                let _this = this;
                // axios.get('/api/get-notifications')
                axios.get('/api/users-notifications')
                    .then(response => response.data)
                    .then(response => {
                        _this.notifications = response;
                        setTimeout(function () {
                            $('.form-check-input').bootstrapToggle();

                            _this.changeFunc();
                        }, 400);
                    })
                    .catch(error => {
                        console.log(error);
                    });
            },
            setNotification(id, value) {
                let _this = this;
                axios.post('/api/set-notification/' + id, {
                    value: value
                })
                    .then(response => response.data)
                    .then(response => {
                        console.log(response);
                    })
                    .catch(error => {
                        console.log(error);
                    });
            },
            changeFunc() {
                let _this = this;
                _this.notifications.forEach(function (value, index) {
                    value.children.forEach((child_v, child_i) => {
                        // console.log(child_v.unique_id);
                        $('#' + child_v.unique_id).change(function () {
                            _this.notifications[index].children[child_i].user = !_this.notifications[index].children[child_i].user;
                            _this.setNotification(child_v.id, _this.notifications[index].children[child_i].user);
                            console.log(_this.notifications[index].children[child_i].user);
                        });
                    })
                });
            }
        },
        watch: {}
    }
</script>
<style>
    .card-header > b {
        font-size: 25px;
    }
    h2 {
        font-size: 20px;
        font-weight: bold;
    }
    .toggle-handle {
        display: none !important;
    }

    .toggle-off {
        background: #90a4b1 !important;
    }
</style>
