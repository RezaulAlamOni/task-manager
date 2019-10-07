<template>
    <form @keydown="locationForm.errors.clear($event.target.name)" @submit.prevent="proceed">
        <div class="form-group">
            <label for="">{{trans('location.name')}}</label>
            <input :placeholder="trans('location.name')" class="form-control" name="name" type="text" v-model="locationForm.name"
                   value="">
            <show-error :form-name="locationForm" prop-name="name"></show-error>
        </div>
        <div class="form-group">
            <label for="">{{trans('location.top_location')}}
                <show-tip module="location" tip="tip_top_location" type="field"></show-tip>
            </label>
            <v-select :options="topLocations" :placeholder="trans('location.select_top_location')" @close="locationForm.errors.clear('top_location_id')" @remove="locationForm.top_location_id = ''"
                      @select="onTopLocationSelect" id="top_location_id"
                      label="name" name="top_location_id"
                      v-model="selected_top_location"></v-select>
            <show-error :form-name="locationForm" prop-name="top_location_id"></show-error>
        </div>
        <div class="form-group">
            <switches class="m-l-20" color="success" theme="bootstrap" v-model="locationForm.is_default"></switches>
            {{trans('location.default')}}
            <show-tip module="location" tip="tip_default_location" type="field"></show-tip>
        </div>
        <div class="form-group">
            <label for="">{{trans('location.description')}}</label>
            <textarea :placeholder="trans('location.description')" class="form-control" name="description" rows="2" type="text"
                      v-model="locationForm.description" value=""></textarea>
            <show-error :form-name="locationForm" prop-name="description"></show-error>
        </div>
        <button class="btn btn-info waves-effect waves-light" type="submit">
            <span v-if="id">{{trans('general.update')}}</span>
            <span v-else>{{trans('general.save')}}</span>
        </button>
        <router-link class="btn btn-danger waves-effect waves-light" to="/location" v-show="id">
            {{trans('general.cancel')}}
        </router-link>
    </form>
</template>


<script>
    import vSelect from 'vue-multiselect'
    import switches from 'vue-switches'

    export default {
        components: {vSelect, switches},
        data() {
            return {
                locationForm: new Form({
                    name: '',
                    top_location_id: '',
                    description: '',
                    is_default: false
                }),
                topLocations: [],
                selected_top_location: null
            };
        },
        props: ['id'],
        mounted() {
            if (this.id)
                this.getLocation();

            if (!this.id)
                axios.get('/api/location/pre-requisite')
                    .then(response => response.data)
                    .then(response => {
                        this.topLocations = response.top_locations;
                    })
                    .catch(error => {
                        helper.showDataErrorMsg(error);
                    });
        },
        methods: {
            proceed() {
                if (this.id)
                    this.updateLocation();
                else
                    this.storeLocation();
            },
            storeLocation() {
                this.locationForm.post('/api/location')
                    .then(response => {
                        toastr.success(response.message);
                        this.topLocations.push(response.new_location);
                        this.$emit('completed');
                        this.selected_top_location = null;
                    })
                    .catch(error => {
                        helper.showErrorMsg(error);
                    });
            },
            getLocation() {
                axios.get('/api/location/' + this.id)
                    .then(response => response.data)
                    .then(response => {
                        this.locationForm.name = response.location.name;
                        this.locationForm.top_location_id = response.location.top_location_id;
                        this.locationForm.is_default = response.location.is_default;
                        this.selected_top_location = response.selected_top_location;
                        this.topLocations = response.top_locations;
                    })
                    .catch(error => {
                        helper.showDataErrorMsg(error);
                        this.$router.push('/location');
                    });
            },
            updateLocation() {
                this.locationForm.patch('/api/location/' + this.id)
                    .then(response => {
                        toastr.success(response.message);
                        this.$router.push('/location');
                    })
                    .catch(error => {
                        helper.showErrorMsg(error);
                    });
            },
            onTopLocationSelect(selectedOption) {
                this.locationForm.top_location_id = selectedOption.id;
            }
        }
    }
</script>
