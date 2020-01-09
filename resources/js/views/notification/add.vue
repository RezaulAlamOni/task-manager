<template>
    <div class="container">
        <div class="col-md-12">
            <div class="card card-default">
                <div id="header-item" class="card-header">
                    <b><i class="fa fa-bell-o"/> Add or Remove Email & Notifications</b>
                </div>
                <div class="card-body">
                    <form role="form" @submit.prevent="saveNotification">
                        <div class="form-group row">
                            <label class="col-md-4 col-form-label text-md-right">
                                Notification Title
                            </label>
                            <div class="col-md-6">
                                <input name="name" type="text" class="form-control" v-model="notification.title">
                                <span class="text-danger">{{ error.title }}</span>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-4 col-form-label text-md-right">
                                Unique ID
                            </label>
                            <div class="col-md-6">
                                <input name="name" type="text" class="form-control" v-model="notification.unique_id">
                                <span class="text-danger">{{ error.unique_id }}</span>
                            </div>
                        </div>
                        <div class="form-group row mb-0">
                            <div class="offset-md-4 col-md-6 text-right">
                                <button type="submit" class="btn btn-primary">
                                    Create
                                </button>
                                <router-link :to="{ name: 'NotificationSettings' }" class="btn btn-secondary">Cancel
                                </router-link>
                            </div>
                        </div>
                    </form>
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
                notification: {
                    title: '',
                    unique_id: '',
                },
                error: {
                    title: null,
                    unique_id: null,
                }
            };
        },
        created() {

        },
        mounted() {
            let _this = this;
            _this.error.title = null;
            _this.error.unique_id = null;
        },
        methods: {
            saveNotification() {
                let _this = this;
                _this.error.title = null;
                _this.error.unique_id = null;
                if (_this.notification.title.length > 0 && _this.notification.unique_id.length > 0) {
                    axios.post('/api/notifications/create', _this.notification)
                        .then(response => response.data)
                        .then(response => {
                            console.log(response);
                            if (response.status === 400) {
                                if (response.error.title) {
                                    _this.error.title = response.error.title[0];
                                }
                                if (response.error.unique_id) {
                                    _this.error.unique_id = response.error.unique_id[0];
                                }
                            } else {
                                this.$router.push('/notification-settings');
                            }
                        })
                        .catch(error => {
                            console.log(error);
                        });
                } else {
                    if (_this.notification.title.length === 0) {
                        _this.error.title = 'Title Field Must not Be empty!';
                    }
                    if (_this.notification.unique_id.length === 0) {
                        _this.error.unique_id = 'unique id Field Must not Be empty!';
                    }
                }
            },
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
</style>
