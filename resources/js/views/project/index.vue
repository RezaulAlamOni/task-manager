<template>
    <aside class="right-aside">
        <div class="container">
            <section>
                <div class="container-header">
                    <h2>
                        <i class="fa fa-fw fa-tasks"></i> Projects
                        <router-link :to="{ name: 'project-create'}">
                            <button type="submit" class="btn btn-primary pull-right"> Create Project</button>
                        </router-link>
                    </h2>
                </div>
            </section>
            <section class="content">
                <div class="row user-list">
                    <div class="col-lg-12">
                        <div class="card bg-primary-card">
                            <div class="card-body">
                                <div class="table-responsive">


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
                                                <td @click="projectView(project.id,project.name)">
                                                    <span class="compltit-blue-link">{{project.name}}</span>
                                                </td>
                                                <td v-text="project.description ? project.description : ''"></td>
                                                <td>Active</td>
                                                <td class="table-option">
                                                    <a href="javascript:void(0)" class="compltit-blue-a" title="edit_project" @click.prevent="editProject(project)">Edit</a>
                                                    <a href="javascript:void(0)" class="compltit-blue-a" :key="project.id" title="Delete" @click.prevent="deleteProject(project)">Delete</a>
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
                                                    data-v-095ab3dc="">Showing </span> 1 -10 of 20 <span
                                                    data-v-095ab3dc="">records</span>
                                            </div>
                                        </div>
                                        <div data-v-095ab3dc="" class="float-right">
                                            <div data-v-095ab3dc="" class="btn-group">
                                                <a data-v-095ab3dc="" href="javascript:void(0)" tabindex="0"
                                                   class="btn btn-default">
                                                    <i data-v-095ab3dc="" class="fa fa-angle-left"></i>
                                                </a>
                                                <a data-v-095ab3dc="" href="javascript:void(0)" tabindex="0" class="btn btn-default">
                                                    <i data-v-095ab3dc="" class="fa fa-angle-right"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
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
                this.$router.push({name: 'project-dashboard', params: {projectId: id, name: name}});
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
                        if (response.success == 1) {
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
