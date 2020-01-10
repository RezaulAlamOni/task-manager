<template>
    <div class="container">
        <div class="col-md-12 ">
            <div class="card card-default border-primary">
                <div class="card-header" id="header-item" style="font-weight: bold">
                    Create Project
                </div>
                <div class="card-body">
                    <form role="form">
                        <!-- Token Name -->
                        <div class="form-group row">
                            <label class="col-md-4 col-form-label text-md-right">Project Title</label>

                            <div class="col-md-6">
                                <input class="form-control" name="name" type="text" v-model="project.title">
                                <span class="text-danger">{{title_error}}</span>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-4 col-form-label text-md-right">Project Description</label>

                            <div class="col-md-6">
                                <input class="form-control" name="description" type="text" v-model="project.description">
                            </div>
                        </div>

                        <!-- Create Button -->
                        <div class="form-group row mb-0" style="text-align: right">
                            <div class="offset-md-4 col-md-6">
                                <button @click.prevent="addProject" class="btn btn-primary" type="submit"
                                        v-if="id === undefined">Create
                                </button>
                                <button @click.prevent="UpdateProject" class="btn btn-primary" v-else type="submit">Update
                                </button>
                                <a class="btn btn-default" href="/projects">Back</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Show Token Modal -->
        </div>
    </div>

</template>
<script>

    export default {
        data() {
            return {
                project: {
                    id: '',
                    title: '',
                    description: ''
                },
                title_error : null,
                id: this.$route.params.uuid
            }
        },
        mounted() {
            var _this = this
            _this.title_error = null;

            if (this.id !== undefined) {
                $('#header-item').text('Project Edit')
                this.FindProject();
            } else {
                $('#header-item').text('Project Create')
            }
        },
        methods: {
            addProject() {
                var _this = this;
                _this.title_error = null;
                if (this.project.title.length > 3) {
                    axios.post('/api/project', this.project)
                        .then(response => response.data)
                        .then(response => {
                            if (response.success == 1) {
                                window.location.href = "/projects";
                            } else {
                                _this.title_error = "Project title already taken!"
                            }
                        })
                        .catch(error => {

                        });
                } else {
                    if(this.project.title.length != 0){
                        _this.title_error = "Project title must have minimum 4 character!"
                    }
                    if(this.project.title.length == 0){
                        _this.title_error = "Project title can't Empty"
                    }
                }

            },
            FindProject() {
                var _this = this;
                axios.get('/api/project/' + _this.id)
                    .then(response => response.data)
                    .then(response => {
                        if (response.success == 1) {
                            var project = response.project;
                            _this.project.id = project.id;
                            _this.project.title = project.name;
                            _this.project.description = project.description;
                        }
                    })
                    .catch(error => {

                    });

            },
            UpdateProject() {
                var _this = this;
                _this.title_error = null;
                if (this.project.title.length > 3) {
                    axios.post('/api/project-update', _this.project)
                        .then(response => response.data)
                        .then(response => {
                            if (response.status === 'success') {
                                window.location.href = "/projects";
                                // console.log(response)
                            } else {
                                _this.title_error = "Project title already taken!"
                            }
                        })
                        .catch(error => {

                        });
                } else {
                    if(_this.project.title.length != 0){
                        _this.title_error = "Project title must have minimum 4 character!"
                    }
                    if(_this.project.title.length == 0){
                        _this.title_error = "Project title can't Empty"
                    }
                }

            },


        }
    }
</script>
