<template>
    <aside class="right-aside" style="height: calc(100vh - 77px); overflow: auto;">
        <div class="container">
            <section>
                <div class="container-header">
                    <h2>
                        <i class="fa fa-bell-o"/> Email & Notifications
                    </h2>
                </div>
            </section>
            <section class="content">
                <div class="row user-list">
                    <div class="col-lg-12">
                        <div class="card bg-primary-card">
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
            </section>
        </div>
    </aside>
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
            this.getUserNotification();


            // $('body').on('change', '#emailFreq_everydayUpdate', function () {
            //     alert('sfdsdf');
            //     _this.emailFreq.everydayUpdate = !_this.emailFreq.everydayUpdate;
            // });
            //
            // $('#weeklyReport').change(function () {
            //     _this.emailFreq.weeklyReport = !_this.emailFreq.weeklyReport;
            // });
            // $('#monthlyReport').change(function () {
            //     _this.emailFreq.monthlyReport = !_this.emailFreq.monthlyReport;
            // });
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
            getUserNotification() {
                let _this = this;
                axios.get('/api/users-notifications')
                    .then(response => response.data)
                    .then(response => {
                        _this.userNotification = response;
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
    .toggle-handle {
        display: none !important;
    }

    .toggle-off {
        background: #90a4b1 !important;
    }
</style>
