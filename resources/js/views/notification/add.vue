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
                                    <form @submit.prevent="saveNotification">
                                        <div class="form-group">
                                            <label for="title">Notification Title</label>
                                            <input id="title" :placeholder="'Notification Title'" class="form-control" type="text" v-model="notification.title">
                                            <show-error :form-name="notification" prop-name="title"></show-error>
                                        </div>
                                        <button class="btn btn-info waves-effect waves-light" type="submit">Add Notification</button>
                                    </form>
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
                notification: {
                    title: '',
                    unique_id: '',
                }
            };
        },
        created() {

        },
        mounted() {
        },
        methods: {
            saveNotification() {
                let _this = this;
                axios.post('/api/notifications/create', _this.notification)
                    .then(response => response.data)
                    .then(response => {
                        console.log(response)
                    })
                    .catch(error => {
                        console.log(error);
                    });
            },
        },
        watch: {}
    }
</script>
<style>
</style>
