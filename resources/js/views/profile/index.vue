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
                                                    <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcS52y5aInsxSm31CvHOFHWujqUx_wWTS9iM6s7BAm21oEN_RiGoog" alt=""/>
                                                    <div class="file btn btn-lg btn-primary">
                                                        Change Photo
                                                        <input type="file" name="file"/>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="profile-head">
                                                    <h5>
                                                        Kshiti Ghelani
                                                    </h5>
                                                    <h6>
                                                        Web Developer and Designer
                                                    </h6>
                                                    <p class="proile-rating">RANKINGS : <span>8/10</span></p>
                                                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                                                        <li class="nav-item">
                                                            <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">About</a>
                                                        </li>
                                                        <li class="nav-item">
                                                            <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false">Timeline</a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <input type="submit" class="profile-edit-btn" name="btnAddMore" value="Edit Profile"/>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="profile-work">
                                                    <p>WORK LINK</p>
                                                    <a href="">Website Link</a><br/>
                                                    <a href="">Bootsnipp Profile</a><br/>
                                                    <a href="">Bootply Profile</a>
                                                    <p>SKILLS</p>
                                                    <a href="">Web Designer</a><br/>
                                                    <a href="">Web Developer</a><br/>
                                                    <a href="">WordPress</a><br/>
                                                    <a href="">WooCommerce</a><br/>
                                                    <a href="">PHP, .Net</a><br/>
                                                </div>
                                            </div>
                                            <div class="col-md-8">
                                                <div class="tab-content profile-tab" id="myTabContent">
                                                    <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <label>User Id</label>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <p>Kshiti123</p>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <label>Name</label>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <p>Kshiti Ghelani</p>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <label>Email</label>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <p>kshitighelani@gmail.com</p>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <label>Phone</label>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <p>123 456 7890</p>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <label>Profession</label>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <p>Web Developer and Designer</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <label>Experience</label>
                                                            </div>
                                                            <div class="col-md-6">
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
