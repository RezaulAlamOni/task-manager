<template>
    <main>
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <h2 style="margin: 20px;">This is the list name
                        <span style="float: right;">
                            <span data-toggle="dropdown" class="dropdown-toggle-split col-md-12 opacity">
                                <i class="fa fa-ellipsis-h"></i>
                            </span>
                            <div class="dropdown-menu">
                                <div class="collapse show switchToggle">
                                    <a href="#" class="dropdown-item"><i class="fa fa-edit opacity"></i> Add to list/board</a>
                                    <a href="#" class="dropdown-item"><i class="fa fa-edit opacity"></i> Create List</a>
                                    <a href="#" class="dropdown-item"><i class="fa fa-angle-double-left opacity"></i> Delete</a>
                                </div>
                            </div>
                        </span>
                    </h2>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12"><img src="/img/taskimg.png" class="img-responsive"></div>
            </div>

        </div>
    </main>
</template>

<script>
    export default {
        props: ['projectID'],
        data (){
            return {
                project_id : this.projectID,
                All_list : null,
            }
        },
        mounted() {
            var _this = this;
            _this.getAllList();
        },
        created() {

        },
        methods : {
            getAllList(){
                var _this = this;
                axios.get('/api/project-overview/'+ _this.project_id )
                    .then(response => response.data)
                    .then(response => {
                       _this.All_list = response.data;
                       console.log(_this.All_list)
                    })
                    .catch(error => {
                        console.log('Api is drag and drop not Working !!!')
                    });
            }
        }
    };
</script>
