<template>
    <aside class="right-aside">
        <div class="container">
            <section>
                <div class="container-header">
                    <h2>
                        <i class="fa fa-user"></i> Profile
                    </h2>
                </div>
            </section>
            <section class="content">
                <div class="row user-list">
                    <div class="col-lg-12">
                        <div class="card bg-primary-card">
                            <div class="card-body">
                                <div class="container emp-profile">
                                    <form method="post">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="profile-img">
                                                    <img :src="profile.photo_url" alt="Avatar"/>
                                                    <div class="file btn btn-lg btn-primary">
                                                        Change Photo
                                                        <input type="file" name="file"/>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="profile-head">
                                                    <h5>{{ profile.name }}</h5>
                                                    <h6></h6>
                                                    <p class="proile-rating"><span></span></p>
                                                    <ul class="nav nav-tabs mt-10" id="myTab" role="tablist">
                                                        <li class="nav-item">
                                                            <a class="nav-link active" id="home-tab" data-toggle="tab"
                                                               href="#home" role="tab" aria-controls="home"
                                                               aria-selected="true">About</a>
                                                        </li>
                                                        <li class="nav-item">
                                                            <a class="nav-link" id="profile-tab" data-toggle="tab"
                                                               href="#profiles" role="tab" aria-controls="profile"
                                                               aria-selected="false">Timeline</a>
                                                        </li>
                                                    </ul>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="tab-content profile-tab" id="myTabContent">
                                                            <div class="tab-pane fade show active" id="home" role="tabpanel"
                                                                 aria-labelledby="home-tab">
                                                                <div class="row">
                                                                    <div class="col-md-4">
                                                                        <label>Name</label>
                                                                    </div>
                                                                    <div class="col-md-8">
                                                                        <p>{{ profile.name }}</p>
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <div class="col-md-4">
                                                                        <label>Email</label>
                                                                    </div>
                                                                    <div class="col-md-8">
                                                                        <div class="col-md-12 row pr-0" >
                                                                            <div class="col-md-6 p-0">
                                                                                <p>{{ profile.email }}</p>
                                                                            </div>
                                                                            <div class="col-md-6 text-right pr-0">
                                                                                <a class="profile-edit-btn">Verify Email</a>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="tab-pane fade" id="profile" role="tabpanel"
                                                                 aria-labelledby="profile-tab">
                                                                <div class="row">
                                                                    <div class="col-md-4">
                                                                        <label>Experience</label>
                                                                    </div>
                                                                    <div class="col-md-8">
                                                                        <p>Expert</p>
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <div class="col-md-6">
                                                                        <label>Hourly Rate</label>
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <p>10$/hr</p>
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <div class="col-md-6">
                                                                        <label>Total Projects</label>
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <p>230</p>
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <div class="col-md-6">
                                                                        <label>English Level</label>
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <p>Expert</p>
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <div class="col-md-6">
                                                                        <label>Availability</label>
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <p>6 months</p>
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <div class="col-md-12">
                                                                        <label>Your Bio</label><br/>
                                                                        <p>Your detail description</p>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <a class="profile-edit-btn">Edit Profile</a>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="profile-work">
                                                    <p>WORK LINK</p>
                                                    <a href="">Website Link</a><br/>
                                                </div>
                                            </div>

                                        </div>
                                    </form>
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
                profile: {},
                projects: {},
                filterProjectForm: {
                    name: '',
                    sortBy: 'name',
                    order: 'asc',
                },
                showFilterPanel: false
            };
        },
        created() {
            this.getUser();
        },
        mounted() {
            $('#header-item').text('Projects');
            this.getProjects();
        },
        methods: {
            getUser() {
                let _this = this;
                axios.get('/api/auth-user')
                    .then(response => response.data)
                    .then(response => {
                        _this.profile = response.user;
                    }).catch(error => {

                });
            },
            // return response()->json(Schema::getColumnListing('task_lists'));
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
