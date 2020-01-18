<template>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body" style="padding-top: 1px;padding-left: 5px;">
                    <div class="row ml-2">
                        <div class="col-12">
                            <div class="panel-tabs" style="width: 100%;">
                                <ul class="nav nav-tabs nav-float" role="tablist">
                                    <li class="nav-item text-center">
                                        <a href="#r_tab1" role="tab" data-toggle="tab" class="nav-link active ">
                                            Due Date Task Panel
                                        </a>
                                    </li>
                                    <li class="text-center nav-item">
                                        <a href="#r_tab2" role="tab" data-toggle="tab" class="nav-link">
                                            Priority Task Panel
                                        </a>
                                    </li>
                                </ul>
                            </div>
                            <div class="tab-content">
                                <div class="tab-pane active" id="r_tab1">
                                    <div class="pt-4">
                                        <ul class="list-group" v-for="dueTaskList in dueTaskLists">
                                            <li class="list-group-item active mt-4">{{ dueTaskList.name }}</li>
                                            <li v-for="task in dueTaskList.tasks" class="list-group-item">{{ task.title }}</li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="r_tab2">
                                    <div id="slim_t52" class="pt-4">
                                        Priority Task Panel
                                    </div>
                                </div>
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
                dueTaskLists: {}
            };
        },
        props: [],
        mounted() {
            this.getDueTask();
        },
        methods: {
            getDueTask() {
                let _this = this;
                axios.get('/api/get-weekly-due-tasks/')
                    .then(response => response.data)
                    .then(response => {
                        _this.dueTaskLists = response.data;
                        console.log(_this.dueTaskLists);
                    })
                    .catch(error => {
                        helper.showDataErrorMsg(error);
                    });
            }
        }
    }
</script>
<style scoped>
    .nav-tabs .nav-link.active, .nav-tabs .nav-item.show .nav-link {
        color: #ffffff;
        background-color: #868e96;
        border-color: #dee2e6 #dee2e6 #fff;
    }
    .list-group-item {
        position: relative;
        display: block;
        padding: 0.75rem 1.25rem !important;
        background-color: #fff;
        border: 1px solid rgba(0, 0, 0, 0.125);
    }
    .list-group-item.active {
        z-index: 2;
        color: #fff;
        background-color: #6699cc;
        border-color: #6699cc;
    }
</style>
