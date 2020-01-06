<template>
    <form @keydown="projectForm.errors.clear($event.target.name)" @submit.prevent="proceed">
        <div class="form-group">
            <label for="">Name</label>
            <input :placeholder="Name" class="form-control" name="name" type="text" v-model="projectForm.name" value="">
            <show-error :form-name="projectForm" prop-name="name"></show-error>
        </div>
        <div class="form-group">
            <label for="">Description </label>
            <textarea :placeholder="Description " class="form-control" name="description" rows="2" type="text"
                      v-model="projectForm.description" value=""></textarea>
            <show-error :form-name="projectForm" prop-name="description"></show-error>
        </div>
        <button class="btn btn-info waves-effect waves-light" type="submit">
            <span v-if="id">fdf</span>
            <span v-else>aassa</span>
        </button>
        <router-link class="btn btn-danger waves-effect waves-light" to="/location" v-show="id">asdda</router-link>
    </form>
</template>


<script>
    // import vSelect from 'vue-multiselect'
    // import switches from 'vue-switches'

    export default {
        data() {
            return {
                projectForm: new Form({
                    name: '',
                    description: ''
                }),
                selected_top_project: null
            };
        },
        props: ['id'],
        mounted() {
            if (this.id)
                this.getProjects();

        },
        methods: {
            proceed() {
                if (this.id)
                    this.updateProject();
                else
                    this.storeProject();
            },
            storeProject() {
                var _this = this;
                this.projectForm.post('/api/project')
                    .then(response => {
                        toastr.success(response.message);
                        // this.topProjects.push(response.new_project);
                        _this.$emit('completed');

                    })
                    .catch(error => {
                        helper.showErrorMsg(error);
                    });
            },
            getProjects() {
                axios.get('/api/project/' + this.id)
                    .then(response => response.data)
                    .then(response => {
                        this.projectForm.name = response.project.name;
                        this.projectForm.description = response.project.description;
                    })
                    .catch(error => {
                        helper.showDataErrorMsg(error);
                        this.$router.push('/projects');
                    });
            },
            updateProject() {
                this.projectForm.patch('/api/projects/' + this.id)
                    .then(response => {
                        toastr.success(response.message);
                        this.$router.push('/project');
                    })
                    .catch(error => {
                        helper.showErrorMsg(error);
                    });
            },
            onTopProjectSelect(selectedOption) {
                this.projectForm.top_project_id = selectedOption.id;
            }
        }
    }
</script>
