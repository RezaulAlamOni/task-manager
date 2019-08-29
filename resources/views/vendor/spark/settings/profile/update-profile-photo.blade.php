<spark-update-profile-photo :user="user" inline-template>
    <div class="card card-default border-primary" v-if="user">
        <div class="card-header bg-primary text-white">{{__('Profile Photo')}}</div>

        <div class="card-body">
            <div class="alert alert-danger" v-if="form.errors.has('photo')">
                @{{ form.errors.get('photo') }}
            </div>

            <form role="form">
                <div class="form-group row justify-content-center">
                    <div class="col-md-6 text-center align-items-center">
                        <div class="image-placeholder mx-auto ">
                            <span role="img" class="profile-photo-preview" :style="previewStyle"></span>
                        </div>
                        <div class="spark-uploader  mt-3">
                            <input ref="photo" type="file" class="spark-uploader-control" name="photo" @change="update" :disabled="form.busy">
                            <div class="btn btn-primary">{{__('Update Photo')}}</div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</spark-update-profile-photo>
