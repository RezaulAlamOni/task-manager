<template>
  <section>
    <!-- The Modal -->
    <div class="modal fade" id="personal_info">
      <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
          <!-- Modal Header -->
          <div class="modal-header">
            <h4 class="modal-title">Personal Information</h4>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
          </div>

          <form @submit.prevent="updateProfile">
            <!-- Modal body -->
            <div class="modal-body">
              <div class="form-group row">
                <label for="full_name" class="col-sm-2 col-form-label font-weight-bold">Name</label>
                <div class="col-sm-10">
                  <input
                    type="text"
                    class="form-control"
                    id="full_name"
                    placeholder="Full Name"
                    v-model="personalInfo.name"
                  />
                </div>
              </div>
              <div class="form-group row">
                <label for="phone" class="col-sm-2 col-form-label font-weight-bold">Phone</label>
                <div class="col-sm-10">
                  <input
                    type="text"
                    class="form-control"
                    id="phone"
                    placeholder="Phone"
                    v-model="personalInfo.phone"
                  />
                </div>
              </div>
            </div>

            <!-- Modal footer -->
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary mr-3" data-dismiss="modal">Close</button>
              <button type="submit" class="btn btn-success mr-2">Update</button>
            </div>
          </form>
        </div>
      </div>
    </div>
    <!-- The Modal -->
    <div class="modal fade" id="card_info">
      <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
          <!-- Modal Header -->
          <div class="modal-header">
            <h4 class="modal-title">Card Information</h4>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
          </div>

          <form @submit.prevent="updateProfile">
            <!-- Modal body -->
            <div class="modal-body">
              <div class="form-group row">
                <label for="full_name" class="col-sm-2 col-form-label font-weight-bold">Name</label>
                <div class="col-sm-10">
                  <input
                    type="text"
                    class="form-control"
                    id="full_name"
                    placeholder="Full Name"
                    v-model="personalInfo.name"
                  />
                </div>
              </div>
              <div class="form-group row">
                <label for="phone" class="col-sm-2 col-form-label font-weight-bold">Phone</label>
                <div class="col-sm-10">
                  <input
                    type="text"
                    class="form-control"
                    id="phone"
                    placeholder="Phone"
                    v-model="personalInfo.phone"
                  />
                </div>
              </div>
            </div>

            <!-- Modal footer -->
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary mr-3" data-dismiss="modal">Close</button>
              <button type="submit" class="btn btn-success mr-2">Update</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </section>
</template>
<script>
export default {
  props: ["personalInfo"],
  data() {
    return {
      profile: {}
    };
  },
  mounted() {
    this.profile = this.personalInfo;
  },
  methods: {
    updateProfile() {
      let _this = this;
      axios
        .post("/api/update-profile", _this.personalInfo)
        .then(response => response.data)
        .then(response => {
          console.log(response);
          $("#personal_info").modal("hide");
          _this.$emit("profileInfoUpdate");
        })
        .catch(error => {
          console.log(error);
        });
    }
  }
};
</script>
