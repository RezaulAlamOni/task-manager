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
                                                        <input type="file" name="file"/>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="profile-head">
                                                    <h1>{{ profile.name }}</h1>
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
                                                               href="#profile" role="tab" aria-controls="profile"
                                                               aria-selected="false">Timeline</a>
                                                        </li>
                                                    </ul>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="tab-content profile-tab" id="myTabContent">
                                                            <div class="tab-pane fade show active" id="home"
                                                                 role="tabpanel"
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
                                                                        <div class="col-md-12 row pr-0">
                                                                            <div class="col-md-6 p-0">
                                                                                <p>{{ profile.email }}</p>
                                                                            </div>
                                                                            <div class="col-md-6 text-right pr-0">
                                                                                <a class="profile-edit-btn">
                                                                                    Verify  Email
                                                                                </a>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                                                                <div class="row">
                                                                    <div class="col-md-4">
                                                                        <label>Experience</label>
                                                                    </div>
                                                                    <div class="col-md-8">
                                                                        <p>Expert</p>
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
                                                    <a href="#">Website Link</a><br/>
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
    export default {
        components: {},
        data() {
            return {
                profile: {},
            };
        },
        created() {
            this.getUser();
        },
        mounted() {
        },
        methods: {
            getUser() {
                let _this = this;
                axios.get('/api/auth-user')
                    .then(response => response.data)
                    .then(response => {
                        _this.profile = response.user;
                    })
                    .catch(error => {
                        console.log(error);
                    });
            },
            // return response()->json(Schema::getColumnListing('task_lists'));
        },
        watch: {}
    }
</script>
