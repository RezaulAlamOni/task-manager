<template>
    <div class="col-md-6 offset-md-3">
        <div class="card card-default border-primary">
            <div id="header-item" class="card-header">
                <b><i class="fa fa-bell-o"/> Add or Remove Email & Notifications</b>
            </div>
            <div class="card-body">
                <form role="form">
                    <div class="form-group row">
                        <label class="col-md-4 col-form-label text-md-right">
                            Project Title
                        </label>
                        <div class="col-md-6">
                            <input name="name" type="text" class="form-control">
                        </div>
                    </div>
                    <div class="form-group row mb-0">
                        <div class="offset-md-4 col-md-6">
                            <button type="button" class="btn btn-primary">
                                Create
                            </button>
                            <router-link :to="{ name: 'NotificationSettings' }" class="btn btn-secondary">Cancel</router-link>
                        </div>
                    </div>
                </form>
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
