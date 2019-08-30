<template>
<!--    <div>-->

<!--        <div class="row">-->
<!--            <div class="col-12 col-sm-8 col-md-8">-->
<!--                <div class="card">-->
<!--                    <div class="card-body">-->
<!--                        <h3 class="card-title">Project List</h3>-->
<!--                        <h6 class="card-subtitle" v-if="projects">Total Result Found {{projects.total}}</h6>-->
<!--                        <h6 class="card-subtitle" v-else>no_result_found</h6>-->
<!--                        <div class="table-responsive" v-if="projects.total">-->
<!--                            <table class="table">-->
<!--                                <thead>-->
<!--                                <tr>-->
<!--                                    <th>Name</th>-->
<!--                                    <th>Description</th>-->
<!--                                    <th class="table-option">Action</th>-->
<!--                                </tr>-->
<!--                                </thead>-->
<!--                                <tbody>-->
<!--                                <tr v-for="project in projects.data">-->
<!--                                    <td class="router_td" @click="projectView(project.id)">{{project.name}}</td>-->
<!--                                    <td v-text="project.description ? project.description : ''"></td>-->
<!--                                    <td class="table-option">-->
<!--                                        <div class="btn-group">-->
<!--                                            <button class="btn btn-info btn-sm" v-if="1" v-tooltip="edit_project"-->
<!--                                                    @click.prevent="editProject(project)"><i-->
<!--                                                class="fas fa-pencil-alt"></i></button>-->
<!--                                            <button class="btn btn-danger btn-sm" v-if="0" :key="project.id"-->
<!--                                                    v-confirm="{ok: confirmDelete(project)}" v-tooltip="Delete"><i-->
<!--                                                class="fas fa-trash"></i></button>-->
<!--                                        </div>-->
<!--                                    </td>-->
<!--                                </tr>-->
<!--                                </tbody>-->
<!--                            </table>-->
<!--                        </div>-->
<!--                        <div v-else><p class="warning"> No data Found</p></div>-->
<!--                    </div>-->
<!--                </div>-->
<!--            </div>-->
<!--        </div>-->
<!--    </div>-->
    <aside class="right-aside">
        <section class="content">
            <div class="row user-list">
                <div class="col-lg-12">
                    <div class="card bg-primary-card">
                        <h4 class="card-header">
                            <div>Registered Users</div>
                        </h4>
                        <div class="card-body">
                            <div class="table-responsive">
                                <div data-v-095ab3dc="" class="card p-3">
                                    <div data-v-095ab3dc="" class="table-header"><h4 data-v-095ab3dc=""
                                                                                     class="table-title text-center mt-3"></h4>
                                    </div>
                                    <div data-v-095ab3dc="" class="text-left">
                                        <div data-v-095ab3dc="" id="search-input-container"><label
                                            data-v-095ab3dc=""><input data-v-095ab3dc="" type="search" id="search-input"
                                                                      placeholder="Search data"
                                                                      class="form-control mb-2"></label>
                                            <div data-v-095ab3dc="" class="actions float-right pr-4 mb-3"><a
                                                data-v-095ab3dc="" href="javascript:undefined" title="export excel"
                                                class="btn btn-info"><i data-v-095ab3dc=""
                                                                        class="fa fa-download"></i></a></div>
                                        </div>
                                    </div>
                                    <div data-v-095ab3dc="" class="table-responsive">
                                        <table data-v-095ab3dc="" class="table">
                                            <thead data-v-095ab3dc="">
                                            <tr data-v-095ab3dc="">
                                                <th data-v-095ab3dc="" class="sortable sorting-asc"
                                                    style="width: auto;">
                                                    ID <i data-v-095ab3dc="" class="fa float-right  fa fa-angle-up"></i>
                                                </th>
                                                <th data-v-095ab3dc="" class="sortable" style="width: auto;">
                                                    Name <i data-v-095ab3dc="" class="fa float-right"></i></th>
                                                <th data-v-095ab3dc="" class="sortable" style="width: auto;">
                                                    Email <i data-v-095ab3dc="" class="fa float-right"></i></th>
                                                <th data-v-095ab3dc="" class="sortable" style="width: auto;">
                                                    Age <i data-v-095ab3dc="" class="fa float-right"></i></th>
                                                <th data-v-095ab3dc="" class="sortable" style="width: auto;">
                                                    Status <i data-v-095ab3dc="" class="fa float-right"></i></th>
                                                <th data-v-095ab3dc="" class="sortable" style="width: auto;">
                                                    Actions <i data-v-095ab3dc="" class="fa float-right"></i></th>
                                            </tr>
                                            </thead>
                                            <tbody data-v-095ab3dc="">
                                            <tr data-v-095ab3dc="">
                                                <td data-v-095ab3dc="" class="numeric">
                                                    16023
                                                </td> <!---->
                                                <td data-v-095ab3dc="" class="">
                                                    Kassaundra Shiffman
                                                </td> <!---->
                                                <td data-v-095ab3dc="" class="">
                                                    LStruble@tempor.com
                                                </td> <!---->
                                                <td data-v-095ab3dc="" class="numeric">
                                                    22
                                                </td> <!---->
                                                <td data-v-095ab3dc="" class="">
                                                    Deactivated
                                                </td> <!----><!---->
                                                <td data-v-095ab3dc="" class=""><a class="btn btn-info"
                                                                                   href="#/edit_user?0">Edit</a></td>
                                            </tr>
                                            <tr data-v-095ab3dc="">
                                                <td data-v-095ab3dc="" class="numeric">
                                                    16024
                                                </td> <!---->
                                                <td data-v-095ab3dc="" class="">
                                                    Helena Zensen
                                                </td> <!---->
                                                <td data-v-095ab3dc="" class="">
                                                    BTrautman@magna.com
                                                </td> <!---->
                                                <td data-v-095ab3dc="" class="numeric">
                                                    33
                                                </td> <!---->
                                                <td data-v-095ab3dc="" class="">
                                                    Deactivated
                                                </td> <!----><!---->
                                                <td data-v-095ab3dc="" class=""><a class="btn btn-info"
                                                                                   href="#/edit_user?1">Edit</a></td>
                                            </tr>
                                            <tr data-v-095ab3dc="">
                                                <td data-v-095ab3dc="" class="numeric">
                                                    16025
                                                </td> <!---->
                                                <td data-v-095ab3dc="" class="">
                                                    Gautam Nye
                                                </td> <!---->
                                                <td data-v-095ab3dc="" class="">
                                                    SThomad@turpis.ly
                                                </td> <!---->
                                                <td data-v-095ab3dc="" class="numeric">
                                                    35
                                                </td> <!---->
                                                <td data-v-095ab3dc="" class="">
                                                    Deactivated
                                                </td> <!----><!---->
                                                <td data-v-095ab3dc="" class=""><a class="btn btn-info"
                                                                                   href="#/edit_user?2">Edit</a></td>
                                            </tr>
                                            <tr data-v-095ab3dc="">
                                                <td data-v-095ab3dc="" class="numeric">
                                                    16026
                                                </td> <!---->
                                                <td data-v-095ab3dc="" class="">
                                                    Wichaya Wagner
                                                </td> <!---->
                                                <td data-v-095ab3dc="" class="">
                                                    NLindsay@odio.org
                                                </td> <!---->
                                                <td data-v-095ab3dc="" class="numeric">
                                                    55
                                                </td> <!---->
                                                <td data-v-095ab3dc="" class="">
                                                    Deactivated
                                                </td> <!----><!---->
                                                <td data-v-095ab3dc="" class=""><a class="btn btn-info"
                                                                                   href="#/edit_user?3">Edit</a></td>
                                            </tr>
                                            <tr data-v-095ab3dc="">
                                                <td data-v-095ab3dc="" class="numeric">
                                                    16027
                                                </td> <!---->
                                                <td data-v-095ab3dc="" class="">
                                                    Dawn Devereaux
                                                </td> <!---->
                                                <td data-v-095ab3dc="" class="">
                                                    NStair@ipsum.net
                                                </td> <!---->
                                                <td data-v-095ab3dc="" class="numeric">
                                                    51
                                                </td> <!---->
                                                <td data-v-095ab3dc="" class="">
                                                    Deactivated
                                                </td> <!----><!---->
                                                <td data-v-095ab3dc="" class=""><a class="btn btn-info"
                                                                                   href="#/edit_user?4">Edit</a></td>
                                            </tr>
                                            <tr data-v-095ab3dc="">
                                                <td data-v-095ab3dc="" class="numeric">
                                                    16028
                                                </td> <!---->
                                                <td data-v-095ab3dc="" class="">
                                                    Marcus Wray
                                                </td> <!---->
                                                <td data-v-095ab3dc="" class="">
                                                    SPointelin@amet.com
                                                </td> <!---->
                                                <td data-v-095ab3dc="" class="numeric">
                                                    23
                                                </td> <!---->
                                                <td data-v-095ab3dc="" class="">
                                                    Activated
                                                </td> <!----><!---->
                                                <td data-v-095ab3dc="" class=""><a class="btn btn-info"
                                                                                   href="#/edit_user?5">Edit</a></td>
                                            </tr>
                                            <tr data-v-095ab3dc="">
                                                <td data-v-095ab3dc="" class="numeric">
                                                    16029
                                                </td> <!---->
                                                <td data-v-095ab3dc="" class="">
                                                    Freddy Meng
                                                </td> <!---->
                                                <td data-v-095ab3dc="" class="">
                                                    SEtheridge@non.ly
                                                </td> <!---->
                                                <td data-v-095ab3dc="" class="numeric">
                                                    32
                                                </td> <!---->
                                                <td data-v-095ab3dc="" class="">
                                                    Deactivated
                                                </td> <!----><!---->
                                                <td data-v-095ab3dc="" class=""><a class="btn btn-info"
                                                                                   href="#/edit_user?6">Edit</a></td>
                                            </tr>
                                            <tr data-v-095ab3dc="">
                                                <td data-v-095ab3dc="" class="numeric">
                                                    16030
                                                </td> <!---->
                                                <td data-v-095ab3dc="" class="">
                                                    Culveretta Gilberto
                                                </td> <!---->
                                                <td data-v-095ab3dc="" class="">
                                                    SGlynn@tempor.com
                                                </td> <!---->
                                                <td data-v-095ab3dc="" class="numeric">
                                                    58
                                                </td> <!---->
                                                <td data-v-095ab3dc="" class="">
                                                    Activated
                                                </td> <!----><!---->
                                                <td data-v-095ab3dc="" class=""><a class="btn btn-info"
                                                                                   href="#/edit_user?7">Edit</a></td>
                                            </tr>
                                            <tr data-v-095ab3dc="">
                                                <td data-v-095ab3dc="" class="numeric">
                                                    16031
                                                </td> <!---->
                                                <td data-v-095ab3dc="" class="">
                                                    Rogelio Martin
                                                </td> <!---->
                                                <td data-v-095ab3dc="" class="">
                                                    BVollrath@at.io
                                                </td> <!---->
                                                <td data-v-095ab3dc="" class="numeric">
                                                    50
                                                </td> <!---->
                                                <td data-v-095ab3dc="" class="">
                                                    Activated
                                                </td> <!----><!---->
                                                <td data-v-095ab3dc="" class=""><a class="btn btn-info"
                                                                                   href="#/edit_user?8">Edit</a></td>
                                            </tr>
                                            <tr data-v-095ab3dc="">
                                                <td data-v-095ab3dc="" class="numeric">
                                                    16032
                                                </td> <!---->
                                                <td data-v-095ab3dc="" class="">
                                                    Suwanto Boyes
                                                </td> <!---->
                                                <td data-v-095ab3dc="" class="">
                                                    THeppelmann@molestie.net
                                                </td> <!---->
                                                <td data-v-095ab3dc="" class="numeric">
                                                    36
                                                </td> <!---->
                                                <td data-v-095ab3dc="" class="">
                                                    Activated
                                                </td> <!----><!---->
                                                <td data-v-095ab3dc="" class=""><a class="btn btn-info"
                                                                                   href="#/edit_user?9">Edit</a></td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div data-v-095ab3dc="" class="table-footer">
                                        <div data-v-095ab3dc="" class="datatable-length float-left pl-3"><span
                                            data-v-095ab3dc="">Rows per page:</span> <select data-v-095ab3dc=""
                                                                                             class="custom-select">
                                            <option data-v-095ab3dc="" value="5">5</option>
                                            <option data-v-095ab3dc="" value="10">10</option>
                                            <option data-v-095ab3dc="" value="20">20</option>
                                            <option data-v-095ab3dc="" value="50">50</option>
                                            <option data-v-095ab3dc="" value="-1">All</option>
                                        </select>
                                            <div data-v-095ab3dc="" class="datatable-info  pb-2 mt-3"><span
                                                data-v-095ab3dc="">Showing </span> 1 -10 of 20 <span data-v-095ab3dc="">records</span>
                                            </div>
                                        </div>
                                        <div data-v-095ab3dc="" class="float-right">
                                            <ul data-v-095ab3dc="" class="pagination">
                                                <li data-v-095ab3dc=""><a data-v-095ab3dc="" href="javascript:undefined"
                                                                          tabindex="0" class="btn link"><i
                                                    data-v-095ab3dc="" class="fa fa-angle-left"></i></a></li>
                                                <li data-v-095ab3dc=""><a data-v-095ab3dc="" href="javascript:undefined"
                                                                          tabindex="0" class="btn link"><i
                                                    data-v-095ab3dc="" class="fa fa-angle-right"></i></a></li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div id="right" class="" style="margin-top: 0px;">
                <div id="right-slim">
                    <div class="rightsidebar-right">
                        <div id="__BVID__196" class="tabs"><!---->
                            <div class="card-header">
                                <ul role="tablist" tabindex="0" id="__BVID__196__BV_tab_controls_"
                                    class="nav nav-tabs card-header-tabs">
                                    <li role="presentation" class="nav-item"><a role="tab" tabindex="-1" href="#"
                                                                                id="__BVID__197___BV_tab_button__"
                                                                                aria-selected="true" aria-setsize="1"
                                                                                aria-posinset="1"
                                                                                aria-controls="__BVID__196__BV_tab_container_"
                                                                                class="nav-link active">&lt;i
                                        class='ti-comments'&gt;&lt;/i&gt;</a></li>
                                </ul>
                            </div>
                            <div id="__BVID__196__BV_tab_container_" class="tab-content">
                                <div role="tabpanel" id="__BVID__197" aria-hidden="false" aria-expanded="true"
                                     aria-lablelledby="__BVID__197___BV_tab_button__"
                                     class="tab-pane card-body show fade active">
                                    <div id="slim_t1">
                                        <div><h5 class="rightsidebar-right-heading text-uppercase text-xs"><i
                                            class="menu-icon  fa fa-fw ti-user"></i>
                                            Contacts
                                        </h5>
                                            <ul class="list-unstyled margin-none">
                                                <li class="rightsidebar-contact-wrapper"><a href="javascript:void(0)"
                                                                                            class="rightsidebar-contact"><img
                                                    src="/images/avatar6.jpg?71bfec8fc58afc8a950d31fa2414525c"
                                                    alt="avatar-image" class="rounded-circle float-right"> <i
                                                    class="fa fa-circle text-xs text-primary"></i> Annette
                                                </a></li>
                                                <li class="rightsidebar-contact-wrapper"><a href="javascript:void(0)"
                                                                                            class="rightsidebar-contact"><img
                                                    src="/images/avatar.jpg?411dee33b51de39bfbcda58698b02382"
                                                    alt="avatar-image" class="rounded-circle float-right"> <i
                                                    class="fa fa-circle text-xs text-primary"></i> Jordan
                                                </a></li>
                                                <li class="rightsidebar-contact-wrapper"><a href="javascript:void(0)"
                                                                                            class="rightsidebar-contact"><img
                                                    src="/images/avatar2.jpg?c880ad025efbef8ee5ae438b9799636f"
                                                    alt="avatar-image" class="rounded-circle float-right"> <i
                                                    class="fa fa-circle text-xs text-primary"></i> Stewart
                                                </a></li>
                                                <li class="rightsidebar-contact-wrapper"><a href="javascript:void(0)"
                                                                                            class="rightsidebar-contact"><img
                                                    src="/images/avatar3.jpg?5cc977990977a9c107216d8366aba18f"
                                                    alt="avatar-image" class="rounded-circle float-right"> <i
                                                    class="fa fa-circle text-xs text-warning"></i> Alfred
                                                </a></li>
                                                <li class="rightsidebar-contact-wrapper"><a href="javascript:void(0)"
                                                                                            class="rightsidebar-contact"><img
                                                    src="/images/avatar4.jpg?b8f96d84f21362b28c3784deeccd6aab"
                                                    alt="avatar-image" class="rounded-circle float-right"> <i
                                                    class="fa fa-circle text-xs text-danger"></i> Eileen
                                                </a></li>
                                                <li class="rightsidebar-contact-wrapper"><a href="javascript:void(0)"
                                                                                            class="rightsidebar-contact"><img
                                                    src="/images/avatar5.jpg?dd46e2e2d1f85d751bf6a5990d05678b"
                                                    alt="avatar-image" class="rounded-circle float-right"> <i
                                                    class="fa fa-circle text-xs text-muted"></i> Robert
                                                </a></li>
                                                <li class="rightsidebar-contact-wrapper"><a href="javascript:void(0)"
                                                                                            class="rightsidebar-contact"><img
                                                    src="/images/avatar7.jpg?ccdd6115cac1528731d3400efd86d19d"
                                                    alt="avatar-image" class="rounded-circle float-right"> <i
                                                    class="fa fa-circle text-xs text-muted"></i> Cassandra
                                                </a></li>
                                            </ul>
                                            <h5 class="rightsidebar-right-heading text-uppercase text-xs"><i
                                                class="fa fa-fw ti-export"></i>
                                                Recent Updates
                                            </h5>
                                            <div>
                                                <ul class="list-unstyled">
                                                    <li class="rightsidebar-notification"><a
                                                        href="javascript:void(0)"><i
                                                        class="fa ti-comments-smiley fa-fw text-primary"></i> New
                                                        Comment
                                                    </a></li>
                                                    <li class="rightsidebar-notification"><a
                                                        href="javascript:void(0)"><i
                                                        class="fa ti-twitter-alt fa-fw text-success"></i> 3 New
                                                        Followers
                                                    </a></li>
                                                    <li class="rightsidebar-notification"><a
                                                        href="javascript:void(0)"><i
                                                        class="fa ti-email fa-fw text-info"></i> Message Sent
                                                    </a></li>
                                                    <li class="rightsidebar-notification"><a
                                                        href="javascript:void(0)"><i
                                                        class="fa ti-write fa-fw text-warning"></i> New Task
                                                    </a></li>
                                                    <li class="rightsidebar-notification"><a
                                                        href="javascript:void(0)"><i
                                                        class="fa ti-export fa-fw text-danger"></i> Server Rebooted
                                                    </a></li>
                                                    <li class="rightsidebar-notification"><a
                                                        href="javascript:void(0)"><i
                                                        class="fa ti-info-alt fa-fw text-primary"></i> Server Not
                                                        Responding
                                                    </a></li>
                                                    <li class="rightsidebar-notification"><a
                                                        href="javascript:void(0)"><i
                                                        class="fa ti-shopping-cart fa-fw text-success"></i> New Order
                                                        Placed
                                                    </a></li>
                                                    <li class="rightsidebar-notification"><a
                                                        href="javascript:void(0)"><i
                                                        class="fa ti-money fa-fw text-info"></i> Payment Received
                                                    </a></li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div><!----></div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </aside>
</template>
<style>
    .router_td {
        color: #656565;
    }

    .router_td:hover {
        cursor: pointer;
        color: #656565;
        background-color: #d0ddba;
    }
</style>

<script>
    import projectForm from './form'

    export default {
        components: {projectForm},
        data() {
            return {
                projects: {},
                filterProjectForm: {
                    name: '',
                    sortBy: 'name',
                    order: 'asc',
                },
                showFilterPanel: false
            };
        },
        mounted() {
            this.getProjects();
        },
        methods: {

            projectView(id) {
                this.$router.push({name: 'project-dashboard', params: {projectId: id}});
            },
            getProjects(page) {

                if (typeof page === 'undefined') {
                    page = 1;
                }
                let url = helper.getFilterURL(this.filterProjectForm);
                console.log(url)
                axios.get('/api/project')
                    .then(response => response.data)
                    .then(response => {
                        this.projects = response.projects;
                        console.log(this.projects)
                    })
                    .catch(error => {

                    });
            },
            editProject(project) {
                this.$router.push('/project/' + project.id + '/edit');
            },
            confirmDelete(project) {
                return dialog => this.deleteProject(project);
            },
            deleteProject(project) {
                axios.delete('/api/project/' + project.id)
                    .then(response => response.data)
                    .then(response => {
                        toastr.success(response.message);
                        this.getProjects();
                    }).catch(error => {

                });
            }
        },
        watch: {
            filterProjectForm: {
                handler(val) {
                    this.getProjects();
                },
                deep: true
            }
        }
    }
</script>
