<template>
  <div style="height: calc(100vh - 77px);overflow: auto;}">
    <div class="container">
      <section class="content">
        <div class="row">
          <div class="col-lg-12">
            <div class="card bg-primary-card">
              <div id="header-item" class="card-header">
                <div class="row">
                  <div class="col text-left">
                    <b>
                      <i class="fa fa-user"></i> Profile
                    </b>
                  </div>
                  <div class="col mr-4 text-right" style="display: none;">
                    <router-link :to="{ name: 'ProfileEdit' }" class="profile-edit-btn">Edit Profile</router-link>
                  </div>
                </div>
              </div>
              <div class="card-body">
                <div class="container emp-profile">
                  <div class="row">
                    <div class="col-4">
                      <div class="profile-img">
                        <img :src="profile.photo_url" alt="Avatar" />
                        <div class="file btn btn-lg btn-primary">
                          <input type="file" name="file" />
                        </div>
                      </div>
                    </div>
                    <div class="col-8">
                      <div class="card">
                        <div class="card-header bg-secondary text-white">{{ profile.name }}</div>
                        <div class="card-body">
                          <div class="row ml-2">
                            <div class="col-md-12">
                              <div class="row">
                                <div class="col-md-2 font-weight-bold">Name</div>
                                <div class="col-md-10">
                                  <p>{{ profile.name }}</p>
                                </div>
                              </div>
                              <div class="row">
                                <div class="col-md-2 font-weight-bold">Email</div>
                                <div class="col-md-10">
                                  <div class="row pr-0">
                                    <div class="col-md-6">
                                      <p>{{ profile.email }}</p>
                                    </div>
                                    <div
                                      class="col-md-6 pr-0"
                                      v-if="profile.email_verified_at === null"
                                    >
                                      <a class="profile-edit-btn">Verify Email</a>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-4">
                      <div class="card">
                        <div class="card-header bg-secondary text-white">About</div>
                        <div class="card-body">
                          <div class="profile-work">
                            <p>WORK LINK</p>
                            <a href="#">Website Link</a>
                            <br />
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>
    </div>
  </div>
</template>
<script>
export default {
  components: {},
  data() {
    return {
      profile: {}
    };
  },
  created() {
    this.getUser();
  },
  mounted() {},
  methods: {
    getUser() {
      let _this = this;
      axios
        .get("/api/auth-user")
        .then(response => response.data)
        .then(response => {
          _this.profile = response.user;
        })
        .catch(error => {
          console.log(error);
        });
    }
    // return response()->json(Schema::getColumnListing('task_lists'));
  },
  watch: {}
};
</script>
<style>
.card-header b {
  font-size: 25px;
}
</style>
