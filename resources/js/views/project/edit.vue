<template>

    <div class="col-md-6 offset-md-3">
        <div class="card card-default">
            <div class="card-header">
                Create Project
            </div>
            <div class="card-body">
                <form role="form">
                    <!-- Token Name -->
                    <div class="form-group row">
                        <label class="col-md-4 col-form-label text-md-right">Add Project Title</label>

                        <div class="col-md-6">
                            <input type="text" class="form-control" name="name" v-model="project.title">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-4 col-form-label text-md-right">Add Description</label>

                        <div class="col-md-6">
                            <input type="text" class="form-control" name="description" v-model="project.description">
                        </div>
                    </div>

                    <!-- Create Button -->
                    <div class="form-group row mb-0">
                        <div class="offset-md-4 col-md-6">
                            <button type="submit" class="btn btn-primary"
                                    @click.prevent="addProject">

                                Create
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Show Token Modal -->
    </div>

</template>
<script>

    import router from "../../routes";

    export default {
        data() {
            return {
                project : {
                    title : null,
                    description : null
                },

                id:this.$route.params.id
            }
        },
        mounted(){
            $('#header-item').text('Project Create')

        },
        methods:{
            addProject() {
                if (this.project.title.length > 3){
                    axios.post('/api/project', this.project)
                        .then(response => response.data)
                        .then(response => {
                            if (response.success == 1){
                                window.location.href = "/projects";
                            }
                        })
                        .catch(error => {

                        });
                }

            }
        }
    }
</script>
