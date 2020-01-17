<template>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body" style="padding-top: 1px;padding-left: 5px;">
                    <div class="row ml-2">
                        <div class="col-12">
                            <div class="panel-tabs" style="width: 100%;">
                                <ul class="nav nav-tabs nav-float" role="tablist">
                                    <li class="nav-item text-center">
                                        <a href="#r_tab1" role="tab" data-toggle="tab" class="nav-link active ">
                                            Due Date Task Panel
                                        </a>
                                    </li>
                                    <li class="text-center nav-item">
                                        <a href="#r_tab2" role="tab" data-toggle="tab" class="nav-link">
                                            Priority Task Panel
                                        </a>
                                    </li>
                                </ul>
                            </div>
                            <div class="tab-content">
                                <div class="tab-pane active" id="r_tab1">
                                    <div id="slim_t1" class="pt-4">
                                        Due Date Task Panel
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="r_tab2">
                                    <div id="slim_t52" class="pt-4">
                                        Priority Task Panel
                                    </div>
                                </div>
                            </div>


                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>


<script>

    export default {
        components: {},
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
        props: [],
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
<style>
    .nav-tabs .nav-link.active, .nav-tabs .nav-item.show .nav-link {
        color: #ffffff;
        background-color: #868e96;
        border-color: #dee2e6 #dee2e6 #fff;
    }
</style>
