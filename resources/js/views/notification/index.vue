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
                                                   v-model="child.unique_id">
                                            <label :for="child.unique_id" class="form-check-label">
                                                <h5 v-text="child.title"></h5>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <h2 class="mb-4">Email frequency</h2>
                                        <div class="form-check mb-4">
                                            <input id="everyUpdate" class="form-check-input" type="checkbox"
                                                   data-toggle="toggle" data-style="ml-1" data-height="25"
                                                   data-onstyle="success" data-offstyle="secondary"
                                                   v-model="emailFreq.everydayUpdate">
                                            <label for="everyUpdate" class="form-check-label">
                                                <h5>Every Update</h5>
                                            </label>
                                        </div>
                                        <div class="form-check mb-4">
                                            <input id="dailyReport" class="form-check-input" type="checkbox"
                                                   data-toggle="toggle" data-style="ml-1" data-height="25"
                                                   data-onstyle="success" data-offstyle="secondary"
                                                   v-model="emailFreq.dailyReport">
                                            <label for="dailyReport" class="form-check-label">
                                                <h5>Daily Report</h5>
                                            </label>
                                        </div>
                                        <div class="form-check mb-4">
                                            <input id="weeklyReport" class="form-check-input" type="checkbox"
                                                   data-toggle="toggle" data-style="ml-1" data-height="25"
                                                   data-onstyle="success" data-offstyle="secondary"
                                                   v-model="emailFreq.weeklyReport">
                                            <label for="dailyReport" class="form-check-label">
                                                <h5>Weekly Report</h5>
                                            </label>
                                        </div>
                                        <div class="form-check mb-4">
                                            <input id="monthlyReport" class="form-check-input" type="checkbox"
                                                   data-toggle="toggle" data-style="ml-1" data-height="25"
                                                   data-onstyle="success" data-offstyle="secondary"
                                                   v-model="emailFreq.monthlyReport">
                                            <label for="dailyReport" class="form-check-label">
                                                <h5>Monthly Report</h5>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-12 mt-5">
                                        <h2 class="mb-4">What would you like to be emailed - Each item will have yes or
                                            no</h2>
                                        <div class="form-check mb-4">
                                            <input id="ss1" class="form-check-input" type="checkbox"
                                                   data-toggle="toggle" data-style="ml-1" data-height="25"
                                                   data-onstyle="success" data-offstyle="secondary"
                                                   v-model="emailFreq.everydayUpdate">
                                            <label for="everyUpdate" class="form-check-label">
                                                <h5>Have a checkbox for email me with anything I am on</h5>
                                            </label>
                                        </div>
                                        <div class="form-check mb-4">
                                            <input id="ss2" class="form-check-input" type="checkbox"
                                                   data-toggle="toggle" data-style="ml-1" data-height="25"
                                                   data-onstyle="success" data-offstyle="secondary"
                                                   v-model="emailFreq.everydayUpdate">
                                            <label for="everyUpdate" class="form-check-label">
                                                <h5>Email me everything</h5>
                                            </label>
                                        </div>
                                        <div class="form-check mb-4">
                                            <input id="ss3" class="form-check-input" type="checkbox"
                                                   data-toggle="toggle" data-style="ml-1" data-height="25"
                                                   data-onstyle="success" data-offstyle="secondary"
                                                   v-model="emailFreq.everydayUpdate">
                                            <label for="everyUpdate" class="form-check-label">
                                                <h5>When you are added to a task</h5>
                                            </label>
                                        </div>
                                        <div class="form-check mb-4">
                                            <input id="ss4" class="form-check-input" type="checkbox"
                                                   data-toggle="toggle" data-style="ml-1" data-height="25"
                                                   data-onstyle="success" data-offstyle="secondary"
                                                   v-model="emailFreq.everydayUpdate">
                                            <label for="everyUpdate" class="form-check-label">
                                                <h5>When you are removed from a task</h5>
                                            </label>
                                        </div>

                                        <h2 class="mb-4">When a task you're on is updated</h2>
                                        <div class="form-check mb-4">
                                            <input id="ss5" class="form-check-input" type="checkbox"
                                                   data-toggle="toggle" data-style="ml-1" data-height="25"
                                                   data-onstyle="success" data-offstyle="secondary"
                                                   v-model="emailFreq.everydayUpdate">
                                            <label for="everyUpdate" class="form-check-label">
                                                <h5>Title changes</h5>
                                            </label>
                                        </div>
                                        <div class="form-check mb-4">
                                            <input id="ss6" class="form-check-input" type="checkbox"
                                                   data-toggle="toggle" data-style="ml-1" data-height="25"
                                                   data-onstyle="success" data-offstyle="secondary"
                                                   v-model="emailFreq.everydayUpdate">
                                            <label for="everyUpdate" class="form-check-label">
                                                <h5>Description added or updated</h5>
                                            </label>
                                        </div>
                                        <div class="form-check mb-4">
                                            <input id="ss7" class="form-check-input" type="checkbox"
                                                   data-toggle="toggle" data-style="ml-1" data-height="25"
                                                   data-onstyle="success" data-offstyle="secondary"
                                                   v-model="emailFreq.everydayUpdate">
                                            <label for="everyUpdate" class="form-check-label">
                                                <h5>Comment left</h5>
                                            </label>
                                        </div>

                                        <h2>When you are direct messaged (@ in a comment)</h2>
                                        <div class="form-check mb-4">
                                            <input id="ss8" class="form-check-input" type="checkbox"
                                                   data-toggle="toggle" data-style="ml-1" data-height="25"
                                                   data-onstyle="success" data-offstyle="secondary"
                                                   v-model="emailFreq.everydayUpdate">
                                            <label for="everyUpdate" class="form-check-label">
                                                <h5>When any task is added</h5>
                                            </label>
                                        </div>
                                        <div class="form-check mb-4">
                                            <input id="ss9" class="form-check-input" type="checkbox"
                                                   data-toggle="toggle" data-style="ml-1" data-height="25"
                                                   data-onstyle="success" data-offstyle="secondary"
                                                   v-model="emailFreq.everydayUpdate">
                                            <label for="everyUpdate" class="form-check-label">
                                                <h5>When any task is updated</h5>
                                            </label>
                                        </div>
                                        <div class="form-check mb-4">
                                            <input id="ss10" class="form-check-input" type="checkbox"
                                                   data-toggle="toggle" data-style="ml-1" data-height="25"
                                                   data-onstyle="success" data-offstyle="secondary"
                                                   v-model="emailFreq.everydayUpdate">
                                            <label for="everyUpdate" class="form-check-label">
                                                <h5>When any task is commented on</h5>
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
        components: {

        },
        data() {
            return {
                emailFreq: {
                    everydayUpdate: false,
                    dailyReport: false,
                    weeklyReport: false,
                    monthlyReport: false,
                },
                userNotification: {},
                notifications: {}
            };
        },
        created(){
        },
        mounted() {
            let _this = this;
            $(function () {
                $('#everyUpdate').change(function () {
                    _this.emailFreq.everydayUpdate = !_this.emailFreq.everydayUpdate;
                });
                $('#dailyReport').change(function () {
                    _this.emailFreq.dailyReport = !_this.emailFreq.dailyReport;
                });
                $('#weeklyReport').change(function () {
                    _this.emailFreq.weeklyReport = !_this.emailFreq.weeklyReport;
                });
                $('#monthlyReport').change(function () {
                    _this.emailFreq.monthlyReport = !_this.emailFreq.monthlyReport;
                });
            });
            this.getAllNotifications();
            this.getUserNotification();
        },
        methods: {
            getAllNotifications() {
                let _this = this;
                axios.get('/api/get-notifications')
                    .then(response => response.data)
                    .then(response => {
                        _this.notifications = response;
                        setTimeout(function () {
                            $('.form-check-input').bootstrapToggle();
                        }, 400)
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
