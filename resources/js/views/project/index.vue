<template>
    <aside class="right-aside">
        <section class="content">
            <div class="row user-list">
                <div class="col-lg-12">
                    <div class="card bg-primary-card">
                        <h4 class="card-header">
                            <div>Projects</div>
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
                                            <div data-v-095ab3dc="" class="actions float-right pr-4 mb-3">


                                                <router-link class="nav-link activeTask" :to="{ name: 'project-create'}">
                                                    <a data-v-095ab3dc="" href="javascript:void(0)" title="Create Project" class="btn btn-info">
                                                        <i data-v-095ab3dc=""class="fa fa-plus"></i></a>
                                                </router-link>
                                            </div>
                                        </div>
                                    </div>
                                    <div data-v-095ab3dc="" class="table-responsive">
                                        <table data-v-095ab3dc="" class="table">
                                            <thead data-v-095ab3dc="">
                                            <tr data-v-095ab3dc="">
                                                <th data-v-095ab3dc="" class="sortable " style="width: auto;">
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
                                            <tr v-for="project in projects">
                                                <td class="router_td" @click="projectView(project.id,project.name)">
                                                    {{project.name}}
                                                </td>
                                                <td v-text="project.description ? project.description : ''"></td>
                                                <td>Active</td>
                                                <td class="table-option">
                                                    <div class="btn-group">
                                                        <button class="btn btn-info btn-sm" title="edit_project"
                                                                @click.prevent="editProject(project)"><i
                                                            class="fa fa-edit"></i></button>
                                                        <button class="btn btn-danger btn-sm" :key="project.id"
                                                                title="Delete" @click.prevent="deleteProject(project)">
                                                            <i
                                                                class="fa fa-trash"></i></button>
                                                    </div>
                                                </td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div data-v-095ab3dc="" class="table-footer">
                                        <div data-v-095ab3dc="" class="datatable-length float-left pl-3"><span
                                            data-v-095ab3dc="">Rows per page:</span> 
                                            <select data-v-095ab3dc="" class="custom-select">
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
                                                <li data-v-095ab3dc="">
                                                    <a data-v-095ab3dc="" href="javascript:void(0)" tabindex="0" class="btn link">
                                                        <i data-v-095ab3dc="" class="fa fa-angle-left"></i>
                                                    </a>
                                                </li>
                                                <li >
                                                    <a data-v-095ab3dc="" href="javascript:void(0)"  tabindex="0" class="btn link">
                                                        <i data-v-095ab3dc="" class="fa fa-angle-right"></i>
                                                    </a
                                                ></li>
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
            $('#header-item').text('Projects')
            this.getProjects();
        },
        methods: {

            projectView(id, name) {
                this.$router.push({name: 'project-dashboard', params: {projectId: id,name : name}});
            },
            getProjects() {
                axios.get('/api/project')
                    .then(response => response.data)
                    .then(response => {
                        this.projects = response.Projects;
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
                axios.post('/api/project/' + project.id)
                    .then(response => response.data)
                    .then(response => {
                        if (response.success == 1){
                            this.getProjects();
                        }
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
