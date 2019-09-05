<template>
    <form @submit.prevent="proceed" @keydown="projectForm.errors.clear($event.target.name)">
        <div class="form-group">
            <label for="">{{trans('location.name')}}</label>
            <input class="form-control" type="text" value="" v-model="projectForm.name" name="name" :placeholder="trans('location.name')">
            <show-error :form-name="projectForm" prop-name="name"></show-error>
        </div>
        <div class="form-group">
            <label for="">{{trans('location.description')}}</label>
            <textarea class="form-control" type="text" value="" v-model="projectForm.description" rows="2" name="description" :placeholder="trans('location.description')"></textarea>
            <show-error :form-name="projectForm" prop-name="description"></show-error>
        </div>
        <button type="submit" class="btn btn-info waves-effect waves-light">
            <span v-if="id">{{trans('general.update')}}</span>
            <span v-else>{{trans('general.save')}}</span>
        </button>
        <router-link to="/location" class="btn btn-danger waves-effect waves-light" v-show="id">{{trans('general.cancel')}}</router-link>
    </form>
</template>


<script>
    import vSelect from 'vue-multiselect'
    import switches from 'vue-switches'

    export default {
        data() {
            return {
                projectForm: new Form({
                    name : '',
                    description: ''
                }),
                selected_top_project: null
            };
        },
        props: ['id'],
        mounted() {
            if(this.id)
                this.getProjects();

        },
        methods: {
            proceed(){
                if(this.id)
                    this.updateProject();
                else
                    this.storeProject();
            },
            storeProject(){
                this.projectForm.post('/api/project')
                    .then(response => {
                        toastr.success(response.message);
                        this.topProjects.push(response.new_project);
                        this.$emit('completed');
                    })
                    .catch(error => {
                        helper.showErrorMsg(error);
                    });
            },
            getProjects(){
                axios.get('/api/project/'+this.id)
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
            updateProject(){
                this.projectForm.patch('/api/projects/'+this.id)
                    .then(response => {
                        toastr.success(response.message);
                        this.$router.push('/project');
                    })
                    .catch(error => {
                        helper.showErrorMsg(error);
                    });
            },
            onTopProjectSelect(selectedOption){
                this.projectForm.top_project_id = selectedOption.id;
            }
        }
    }
</script>
