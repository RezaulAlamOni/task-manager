<template>
    <aside class="right-aside">
        <div class="container">
            <section class="content">
                <div class="row user-list">
                    <div class="col-lg-12">
                        <div class="card bg-primary-card">
                            <div class="card-header">
                                <h2>
                                    <i class="fa fa-fw fa-tasks"></i> Projects
                                    <router-link :to="{ name: 'project-create'}">
                                        <button class="btn btn-primary" style="float: right;margin-right: 20px;" type="submit"> Create Project</button>
                                    </router-link>
                                </h2>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive" >
                                    <div class="table-responsive" data-v-095ab3dc="" style="padding: 15px 10px;">
                                        <table class="table" data-v-095ab3dc="">
                                            <thead data-v-095ab3dc="">
                                            <tr data-v-095ab3dc="">
                                                <th class="sortable " data-v-095ab3dc="" style="width: auto;">
                                                    Title <i class="fa float-right" data-v-095ab3dc=""></i></th>
                                                <th class="sortable" data-v-095ab3dc="" style="width: auto;">
                                                    Description <i class="fa float-right" data-v-095ab3dc=""></i></th>
                                                <th class="sortable" data-v-095ab3dc="" style="width: auto;">
                                                    Status <i class="fa float-right" data-v-095ab3dc=""></i></th>
                                                <th class="sortable" data-v-095ab3dc="" style="width: auto;text-align: right">
                                                    Actions <i class="fa float-right" data-v-095ab3dc=""></i></th>
                                            </tr>
                                            </thead>
                                            <tbody data-v-095ab3dc="">
                                            <tr v-for="project in projects">
                                                <td @click="projectView(project.id,project.name)">
                                                    <span class="compltit-blue-link">{{project.name}}</span>
                                                </td>
                                                <td v-text="project.description ? project.description : ''"></td>
                                                <td>Active</td>
                                                <td class="table-option" style="text-align: right;">
                                                    <a @click.prevent="editProject(project)" class="compltit-blue-a badge badge-info"
                                                       href="javascript:void(0)"
                                                       title="edit_project"><i data-v-0ca4b43b="" class="fal fa-edit" aria-hidden="true"></i></a>
                                                    <a :key="project.id" @click.prevent="deleteProject(project)"
                                                       class="compltit-blue-a badge badge-danger" href="javascript:void(0)"
                                                       title="Delete"><i class="fal fa-trash-alt" aria-hidden="true"></i></a>
                                                </td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>
<!--                                    <div class="table-footer" data-v-095ab3dc="">-->
<!--                                        <div class="datatable-length float-left pl-3" data-v-095ab3dc=""><span-->
<!--                                            data-v-095ab3dc="">Rows per page:</span>-->
<!--                                            <select class="custom-select" data-v-095ab3dc="">-->
<!--                                                <option data-v-095ab3dc="" value="5">5</option>-->
<!--                                                <option data-v-095ab3dc="" value="10">10</option>-->
<!--                                                <option data-v-095ab3dc="" value="20">20</option>-->
<!--                                                <option data-v-095ab3dc="" value="50">50</option>-->
<!--                                                <option data-v-095ab3dc="" value="-1">All</option>-->
<!--                                            </select>-->
<!--                                            <div class="datatable-info  pb-2 mt-3" data-v-095ab3dc=""><span-->
<!--                                                data-v-095ab3dc="">Showing </span> 1 -10 of 20 <span-->
<!--                                                data-v-095ab3dc="">records</span>-->
<!--                                            </div>-->
<!--                                        </div>-->
<!--                                        <div class="float-right" data-v-095ab3dc="">-->
<!--                                            <div class="btn-group" data-v-095ab3dc="">-->
<!--                                                <a class="btn btn-default" data-v-095ab3dc="" href="javascript:void(0)"-->
<!--                                                   tabindex="0">-->
<!--                                                    <i class="fa fa-angle-left" data-v-095ab3dc=""></i>-->
<!--                                                </a>-->
<!--                                                <a class="btn btn-default" data-v-095ab3dc="" href="javascript:void(0)"-->
<!--                                                   tabindex="0">-->
<!--                                                    <i class="fa fa-angle-right" data-v-095ab3dc=""></i>-->
<!--                                                </a>-->
<!--                                            </div>-->
<!--                                        </div>-->
<!--                                    </div>-->
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
            $('#header-item').text('Projects');
            this.getProjects();
        },
        methods: {

            projectView(id, name) {
                localStorage.browser_last_project = JSON.stringify(id);
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
