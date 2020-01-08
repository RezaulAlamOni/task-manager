<template>
    <aside class="right-aside" style="height: calc(100vh - 77px); overflow: auto;">
        <div class="container">
            <section>
                <div class="container-header">
                    <h2>
                        <i class="fa fa-bell-o"/> Add or Remove Email & Notifications
                    </h2>
                </div>
            </section>
            <section class="content">
                <div class="row user-list">
                    <div class="col-lg-12">
                        <div class="card bg-primary-card">
                            <div class="card-body">
                                <div class="row p-5">
                                    <table class="table table-hover mt-5" v-for="notification in notifications">
                                        <thead>
                                        <tr>
                                            <th>{{ notification.title }}</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr class="d-flex" v-for="child in notification.children">
                                            <td class="col-10">{{ child.title }}</td>
                                            <td class="col-2">
                                                <button class="btn btn-danger btn-sm" @click="deleteNotification(child.id)"><i class="fa fa-close"/></button>
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
                        _this.notifications = response;
                    })
                    .catch(error => {
                        console.log(error);
                    });
            },
            deleteNotification(id) {
                let _this = this;
                axios.post('/api/delete-notification', {id: id})
                    .then(response => response.data)
                    .then(response => {
                        _this.notifications = response;
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
