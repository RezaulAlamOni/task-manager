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
                                                    Title <i data-v-095ab3dc="" class="fa float-right"></i></th>
                                                <th data-v-095ab3dc="" class="sortable" style="width: auto;">
                                                    Description <i data-v-095ab3dc="" class="fa float-right"></i></th>
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
                                                <td data-v-095ab3dc="" class="" @click="projectView(21)">
                                                   Project 1
                                                </td> <!---->

                                                <td data-v-095ab3dc="" class="numeric">
                                                    This is statis data.
                                                </td> <!---->
                                                <td data-v-095ab3dc="" class="">
                                                    Deactivated
                                                </td> <!----><!---->
                                                <td data-v-095ab3dc="" class=""><a class="btn btn-info"
                                                                                   href="#/edit_user?0">Edit</a></td>
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
                // let url = helper.getFilterURL(this.filterProjectForm);
                let url = '/';
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
