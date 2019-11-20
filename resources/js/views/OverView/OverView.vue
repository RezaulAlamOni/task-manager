<template>
    <main>
        <div class="container">
<!--            <div class="row">-->
<!--                <div class="col-md-12">-->
<!--                    <h2 style="margin: 20px;">This is the list name-->
<!--                        <span style="float: right;">-->
<!--                            <span data-toggle="dropdown" class="dropdown-toggle-split col-md-12 opacity">-->
<!--                                <i class="fa fa-ellipsis-h"></i>-->
<!--                            </span>-->
<!--                            <div class="dropdown-menu">-->
<!--                                <div class="collapse show switchToggle">-->
<!--                                    <a href="#" class="dropdown-item"><i class="fa fa-edit opacity"></i> Add to list/board</a>-->
<!--                                    <a href="#" class="dropdown-item"><i class="fa fa-edit opacity"></i> Create List</a>-->
<!--                                    <a href="#" class="dropdown-item"><i class="fa fa-angle-double-left opacity"></i> Delete</a>-->
<!--                                </div>-->
<!--                            </div>-->
<!--                        </span>-->
<!--                    </h2>-->
<!--                </div>-->
<!--            </div>-->
<!--            <div class="row">-->
<!--                <div class="col-md-12">-->
<!--                    <img src="/img/taskimg.png" class="img-responsive">-->
<!--                </div>-->
<!--            </div>-->

            <div class="accordion" id="listWithHandle">
                <div class="card overview-list">
                    <div class="card-header overview-list-header" id="headingOne" data-toggle="collapse"
                         data-target="" aria-expanded="false"
                         aria-controls="collapseOne">
                        <div class="col-md-12">
                            <img src="/img/task-icon/move.png" alt="" height="20px" width="20px" class="mr-2 sort-trigger" data-toggle="tooltip"
                                 title="Change sort-order lists">
                            <h2 style="margin: 10px 15px;cursor: pointer" data-placement="bottom" @click="dataCollapse('#collapseOne')" data-toggle="tooltip"
                                >
                                This is the list name
                            </h2>
                            <span class="option-btn">
                                    <span data-toggle="dropdown" class="dropdown-toggle-split col-md-12 opacity" >
                                        <i class="fa fa-ellipsis-h"></i>
                                    </span>
                                    <div class="dropdown-menu overview-dropdown">
                                        <div class="collapse show switchToggle">
                                            <a href="#" class="dropdown-item"><i class="fa fa-edit opacity"></i> Add to list/board</a>
                                            <a href="#" class="dropdown-item"><i class="fa fa-angle-double-left opacity"></i> Delete</a>
                                        </div>
                                    </div>
                                </span>
                        </div>
                        <div class="col-md-12">
                            <p>
                                Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad
                                squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa
                            </p>
                        </div>
                    </div>

                    <div id="collapseOne" class="collapse show multi-collapse" aria-labelledby="headingOne"
                         data-parent="#listWithHandle">
                        <div class="card-body">
                            <img src="/img/taskimg.png" class="img-responsive">
                        </div>
                    </div>
                </div>
            </div>


        </div>
    </main>
</template>

<script>
    import Sortable from 'sortablejs'

    export default {
        props: ['projectID'],
        components: [Sortable],
        data() {
            return {
                project_id: this.projectID,
                All_list: null,
            }
        },
        mounted() {
            var _this = this;
            _this.getAllList();
            setTimeout(function () {
                var listWithHandle = document.getElementById('listWithHandle');
                Sortable.create(listWithHandle, {
                    handle: '.sort-trigger',
                    animation: 150
                });
                $('[data-toggle="tooltip"]').tooltip();
            }, 500)

        },
        created() {
            // var data = [];
            // var data2 = [];
            // var test = [...data, ...data2]
        },
        methods: {
            getAllList() {
                var _this = this;
                axios.get('/api/project-overview/' + _this.project_id)
                    .then(response => response.data)
                    .then(response => {
                        _this.All_list = response.data;
                        console.log(_this.All_list)
                    })
                    .catch(error => {
                        console.log('Api is drag and drop not Working !!!')
                    });
            },
            dataCollapse(id) {
                $(id).slideToggle();
            }
        }
    };
</script>
