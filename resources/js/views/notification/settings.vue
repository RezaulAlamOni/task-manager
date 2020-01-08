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
                                <div class="row">

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
            return {};
        },
        created() {
        },
        mounted() {
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
        },
        watch: {}
    }
</script>
<style>

</style>
