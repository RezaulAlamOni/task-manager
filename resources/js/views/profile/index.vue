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
                      <div class="card">
                        <div class="card-body">
                          <div class="profile-img">
                            <img :src="profile.photo_url" alt="Avatar" class="border mb-4 mt-4" />
                          </div>
                        </div>
                      </div>
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
                    <div class="col-8">
                      <div class="card">
                        <div class="card-header bg-secondary text-white">
                          Personal Information
                          <a
                            class="float-right profile-edit"
                            data-toggle="modal"
                            data-target="#personal_info"
                            @click="getProfile"
                          >
                            <i
                              class="fal fa-edit"
                              aria-hidden="true"
                              data-toggle="tooltip"
                              :data-original-title="profileUpdatedAt(profile.updated_at)"
                            ></i>
                          </a>
                        </div>
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
                              <div class="row">
                                <div class="col-md-2 font-weight-bold">Phone</div>
                                <div class="col-md-10">
                                  <p>{{ profile.phone ? profile.phone : '[Phone number not set]' }}</p>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="card">
                        <div class="card-header bg-secondary text-white">
                          Card Information
                          <a
                            class="float-right profile-edit"
                            data-toggle="modal"
                            data-target="#card_info"
                            @click="getCardInfo"
                          >
                            <i
                              class="fal fa-edit"
                              aria-hidden="true"
                              data-toggle="tooltip"
                              :data-original-title="profileUpdatedAt(profile.updated_at)"
                            ></i>
                          </a>
                        </div>
                        <div class="card-body">
                          <div class="row ml-2">
                            <div class="col-md-12">
                              <div class="row">
                                <div class="col-md-3 font-weight-bold">Card Brand</div>
                                <div class="col-md-9">
                                  <p>{{ profile.card_brand ? profile.card_brand : '-' }}</p>
                                </div>
                              </div>
                              <div class="row">
                                <div class="col-md-3 font-weight-bold">Card Last 4 Digit</div>
                                <div class="col-md-9">
                                  <p>{{ profile.card_last_four ? profile.card_last_four : '0000' }}</p>
                                </div>
                              </div>
                              <div class="row">
                                <div class="col-md-3 font-weight-bold">Card Country</div>
                                <div class="col-md-9">
                                  <p>{{ profile.card_country ? profile.card_country : '-' }}</p>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="card">
                        <div class="card-header bg-secondary text-white">
                          Billing Information
                          <a
                            class="float-right profile-edit"
                            data-toggle="modal"
                            data-target="#billing_info"
                            @click="getBillingInfo"
                          >
                            <i
                              class="fal fa-edit"
                              aria-hidden="true"
                              data-toggle="tooltip"
                              :data-original-title="profileUpdatedAt(profile.updated_at)"
                            ></i>
                          </a>
                        </div>
                        <div class="card-body">
                          <div class="row ml-2">
                            <div class="col-md-12">
                              <div class="row">
                                <div class="col-md-3 font-weight-bold">Billing Address</div>
                                <div class="col-md-9">
                                  <p>
                                    {{ profile.billing_address }}
                                    <br />
                                    {{ profile.billing_address_line_2 }}
                                  </p>
                                </div>
                              </div>
                              <div class="row">
                                <div class="col-md-3 font-weight-bold">City</div>
                                <div class="col-md-9">
                                  <p>{{ profile.billing_city ? profile.billing_city : '-'}}</p>
                                </div>
                              </div>
                              <div class="row">
                                <div class="col-md-3 font-weight-bold">State</div>
                                <div class="col-md-9">
                                  <p>{{ profile.billing_state ? profile.billing_state : '-'}}</p>
                                </div>
                              </div>
                              <div class="row">
                                <div class="col-md-3 font-weight-bold">Zip Code</div>
                                <div class="col-md-9">
                                  <p>{{ profile.billing_zip ? profile.billing_zip : '-' }}</p>
                                </div>
                              </div>
                              <div class="row">
                                <div class="col-md-3 font-weight-bold">Country</div>
                                <div class="col-md-9">
                                  <p>{{ profile.billing_country ? profile.billing_country : '-' }}</p>
                                </div>
                              </div>
                              <div class="row">
                                <div class="col-md-3 font-weight-bold">Vat ID</div>
                                <div class="col-md-9">
                                  <p>{{ profile.vat_id ? profile.vat_id : '-' }}</p>
                                </div>
                              </div>
                              <div class="row">
                                <div class="col-md-3 font-weight-bold">Extra Billing Information</div>
                                <div class="col-md-9">
                                  <p>{{ profile.extra_billing_information ? profile.extra_billing_information : '-' }}</p>
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
            </div>
          </div>
        </div>
      </section>
    </div>
    <editModal
      :personalInfo="userInfo"
      :cardInfo="cardInfo"
      :billingInfo="billingInfo"
      @profileInfoUpdate="getUser"
    ></editModal>
  </div>
</template>
<script>
import edit from "./edit";
export default {
  components: {
    editModal: edit
  },
  data() {
    return {
      profile: {},
      userInfo: {},
      cardInfo: {},
      billingInfo: {}
    };
  },
  created() {
    // moment.tz.setDefault("America/New_York");
    this.getUser();
  },
  mounted() {},
  methods: {
    profileUpdatedAt(value) {
      console.log(moment.utc());
      return (
        "Profile Updated " +
        moment
          .utc(value)
          .local()
          .locale("en-short")
          .fromNow()
      );
    },
    getUser() {
      let _this = this;
      axios
        .get("/api/profile")
        .then(response => response.data)
        .then(response => {
          _this.profile = response.user;
        })
        .catch(error => {
          console.log(error);
        });
    },
    getProfile() {
      let _this = this;
      axios
        .get("/api/get-info")
        .then(response => response.data)
        .then(response => {
          _this.userInfo = response;
        })
        .catch(error => {
          console.log(error);
        });
    },
    getCardInfo() {
      let _this = this;
      axios
        .get("/api/get-card-info")
        .then(response => response.data)
        .then(response => {
          _this.cardInfo = response.data;
        })
        .catch(error => {
          console.log(error);
        });
    },
    getBillingInfo() {
      let _this = this;
      axios
        .get("/api/get-billing-info")
        .then(response => response.data)
        .then(response => {
          _this.billingInfo = response.data;
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
