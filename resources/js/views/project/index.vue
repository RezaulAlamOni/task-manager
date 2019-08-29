<template>
    <div>

        <div class="row">
            <div class="col-12 col-sm-8 col-md-8">
                <div class="card">
                    <div class="card-body">
                        <h3 class="card-title">Project List</h3>
                        <h6 class="card-subtitle" v-if="projects">Total Result Found {{projects.total}}</h6>
                        <h6 class="card-subtitle" v-else>no_result_found</h6>
                        <div class="table-responsive" v-if="projects.total">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Description</th>
                                        <th class="table-option">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="project in projects.data">
                                        <td class="router_td" @click="projectView(project.id)">{{project.name}}</td>
                                        <td v-text="project.description ? project.description : ''"></td>
                                        <td class="table-option">
                                            <div class="btn-group">
                                                <button class="btn btn-info btn-sm" v-if="1" v-tooltip="edit_project" @click.prevent="editProject(project)"><i class="fas fa-pencil-alt"></i></button>
                                                <button class="btn btn-danger btn-sm" v-if="0" :key="project.id" v-confirm="{ok: confirmDelete(project)}" v-tooltip="Delete"><i class="fas fa-trash"></i></button>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div v-else><p class="warning"> No data Found</p></div>
                       </div>
                </div>
            </div>
        </div>
    </div>
</template>
<style>
    .router_td{
        color: #656565;
    }
    .router_td:hover{
        cursor:pointer;
        color: #656565;
        background-color: #d0ddba;
    }
</style>

<script>
    import projectForm from './form'

    export default {
        components : { projectForm },
        data() {
            return {
                projects: {},
                filterProjectForm: {
                    name: '',
                    sortBy : 'name',
                    order: 'asc',
                },
                showFilterPanel : false
            };
        },
        mounted(){
            this.getProjects();
        },
        methods: {

            projectView(id){
                this.$router.push({name: 'project-dashboard', params: { projectId: id }});
            },
            getProjects(page){

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
            editProject(project){
                this.$router.push('/project/'+project.id+'/edit');
            },
            confirmDelete(project){
                return dialog => this.deleteProject(project);
            },
            deleteProject(project){
                axios.delete('/api/project/'+project.id)
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
                handler(val){
                    this.getProjects();
                },
                deep: true
            }
        }
    }
</script>
