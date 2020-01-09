<template>
    <aside class="right-aside" style="height: calc(100vh - 77px); overflow: auto;">
        <div class="container">
            <section class="content">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card bg-primary-card">
                            <div id="header-item" class="card-header text-black">
                                <div class="row">
                                    <div class="col text-left">
                                        <i class="fa fa-bell-o"/> Add or Remove Email & Notifications
                                    </div>
                                    <div class="col text-right">
                                        <router-link :to="{ name: 'NotificationCreate' }" class="profile-edit-btn">Add New Notification</router-link>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body" style="margin: 5px 50px;">
                                <div class="row">
                                    <table class="table table-hover" v-for="notification in notifications">
                                        <thead>
                                        <tr>
                                            <th>{{ notification.title }}</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr class="d-flex" v-for="child in notification.children">
                                            <td class="col-10">{{ child.title }}</td>
                                            <td class="col-2" style="text-align: right;">
                                                <button class="btn btn-danger btn-sm" type="button"
                                                        @click="deleteNotification(child.id)"><i class="fa fa-close"/>
                                                </button>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
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
                notifications: {}
            };
        },
        created() {
            this.getAllNotifications();
        },
        mounted() {
        },
        methods: {
            getAllNotifications() {
                let _this = this;
                axios.get('/api/users-notifications')
                    .then(response => response.data)
                    .then(response => {
                        console.log(response)
                        _this.notifications = response;
                    })
                    .catch(error => {
                        console.log(error);
                    });
            },
            deleteNotification(id) {
                let _this = this;
                axios.post('/api/delete-notification/' + id)
                    .then(response => response.data)
                    .then(response => {
                        if (response.success === true) {
                            _this.notifications.forEach((value, index) => {
                                value.children.forEach((child_v, key) => {
                                    if (child_v.id === id) {
                                        _this.notifications[index].children.splice(key, 1);
                                    }
                                })
                            })
                        }
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
    .btn-sm, .btn-group-sm > .btn {
        padding: 0.25rem 0.5rem;
        font-size: 0.875rem;
        line-height: 1.5;
        border-radius: 0.2rem;
    }
</style>
