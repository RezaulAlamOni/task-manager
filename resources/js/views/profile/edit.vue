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

          <form @submit.prevent="updateCardInfo">
            <!-- Modal body -->
            <div class="modal-body">
              <div class="form-group row">
                <label for="card_brand" class="col-sm-2 col-form-label font-weight-bold">Card Brand</label>
                <div class="col-sm-10">
                  <input
                    type="text"
                    class="form-control"
                    id="card_brand"
                    placeholder="Card Info"
                    v-model="cardInfo.card_brand"
                  />
                </div>
              </div>
              <div class="form-group row">
                <label
                  for="card_last_four"
                  class="col-sm-2 col-form-label font-weight-bold"
                >Card Last 4 Digit</label>
                <div class="col-sm-10">
                  <input
                    type="text"
                    class="form-control"
                    id="card_last_four"
                    placeholder="Card Last Four"
                    v-model="cardInfo.card_last_four"
                  />
                </div>
              </div>
              <div class="form-group row">
                <label
                  for="card_country"
                  class="col-sm-2 col-form-label font-weight-bold"
                >Card Country</label>
                <div class="col-sm-10">
                  <input
                    type="text"
                    class="form-control"
                    id="card_country"
                    placeholder="Card Country"
                    v-model="cardInfo.card_country"
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
    <div class="modal fade" id="billing_info">
      <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
          <!-- Modal Header -->
          <div class="modal-header">
            <h4 class="modal-title">Billing Information</h4>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
          </div>

          <form @submit.prevent="updateBillingInfo">
            <!-- Modal body -->
            <div class="modal-body">
              <div class="form-group row">
                <label
                  for="billing_address_line_2"
                  class="col-sm-2 col-form-label font-weight-bold"
                >Billing Address</label>
                <div class="col-sm-10">
                  <input
                    type="text"
                    class="form-control"
                    id="billing_address_line_2"
                    placeholder="Billing Address"
                    v-model="billingInfo.billing_address"
                  />
                  <input
                    type="text"
                    class="form-control"
                    id="billing_address_line_2_"
                    placeholder="Billing Address Line 2"
                    v-model="billingInfo.billing_address_line_2"
                  />
                </div>
              </div>
              <div class="form-group row">
                <label for="billing_city" class="col-sm-2 col-form-label font-weight-bold">City</label>
                <div class="col-sm-10">
                  <input
                    type="text"
                    class="form-control"
                    id="billing_city"
                    placeholder="Billing City"
                    v-model="billingInfo.billing_city"
                  />
                </div>
              </div>
              <div class="form-group row">
                <label for="billing_state" class="col-sm-2 col-form-label font-weight-bold">State</label>
                <div class="col-sm-10">
                  <input
                    type="text"
                    class="form-control"
                    id="billing_state"
                    placeholder="Billing State"
                    v-model="billingInfo.billing_state"
                  />
                </div>
              </div>
              <div class="form-group row">
                <label for="billing_zip" class="col-sm-2 col-form-label font-weight-bold">Zip Code</label>
                <div class="col-sm-10">
                  <input
                    type="text"
                    class="form-control"
                    id="billing_zip"
                    placeholder="Billing Zip"
                    v-model="billingInfo.billing_zip"
                  />
                </div>
              </div>
              <div class="form-group row">
                <label
                  for="billing_country"
                  class="col-sm-2 col-form-label font-weight-bold"
                >Country</label>
                <div class="col-sm-10">
                  <input
                    type="text"
                    class="form-control"
                    id="billing_country"
                    placeholder="Billing Country"
                    v-model="billingInfo.billing_country"
                  />
                </div>
              </div>
              <div class="form-group row">
                <label for="vat_id" class="col-sm-2 col-form-label font-weight-bold">VAT ID</label>
                <div class="col-sm-10">
                  <input
                    type="text"
                    class="form-control"
                    id="vat_id"
                    placeholder="VAT ID"
                    v-model="billingInfo.vat_id"
                  />
                </div>
              </div>
              <div class="form-group row">
                <label
                  for="extra_billing_information"
                  class="col-sm-2 col-form-label font-weight-bold"
                >Extra Billing Information</label>
                <div class="col-sm-10">
                  <input
                    type="text"
                    class="form-control"
                    id="extra_billing_information"
                    placeholder="Extra Billing Information"
                    v-model="billingInfo.extra_billing_information"
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
  props: ["personalInfo", "cardInfo", "billingInfo"],
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
          if (response.status === 400) {
            Object.values(response.error).forEach(error => {
              this.$toastr.e(error[0]);
            });
          } else {
            $("#personal_info").modal("hide");
            this.$toastr.s("Profile info Updated Successfully");
            _this.$emit("profileInfoUpdate");
          }
        })
        .catch(error => {
          console.log(error);
        });
    },
    updateCardInfo() {
      let _this = this;
      axios
        .post("/api/update-card-info", _this.cardInfo)
        .then(response => response.data)
        .then(response => {
          if (response.status === 400) {
            Object.values(response.error).forEach(error => {
              this.$toastr.e(error[0]);
            });
          } else {
            $("#card_info").modal("hide");
            this.$toastr.s("Card info Updated Successfully");
            _this.$emit("profileInfoUpdate");
          }
        })
        .catch(error => {
          console.log(error);
        });
    },
    updateBillingInfo() {
      let _this = this;
      axios
        .post("/api/update-billing-info", _this.billingInfo)
        .then(response => response.data)
        .then(response => {
          if (response.status === 400) {
            Object.values(response.error).forEach(error => {
              this.$toastr.e(error[0]);
            });
          } else {
            $("#billing_info").modal("hide");
            this.$toastr.s("Billing info Updated Successfully");
            _this.$emit("profileInfoUpdate");
          }
        })
        .catch(error => {
          console.log(error);
        });
    }
  }
};
</script>
