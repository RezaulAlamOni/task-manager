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
                    placeholder="card_last_four"
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
                    placeholder="card_country"
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
                    placeholder="billing_city"
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
                    placeholder="billing_state"
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
                    placeholder="billing_zip"
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
                    placeholder="billing_country"
                    v-model="billingInfo.billing_country"
                  />
                </div>
              </div>
              <div class="form-group row">
                <label for="vat_id" class="col-sm-2 col-form-label font-weight-bold">Vat ID</label>
                <div class="col-sm-10">
                  <input
                    type="text"
                    class="form-control"
                    id="vat_id"
                    placeholder="vat_id"
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
                    placeholder="extra_billing_information"
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
    // Inject your class names for animation
    /*
    this.$toastr.defaultTimeout = 1000;
    this.$toastr.s("Hello Toastr");
    */
  },
  methods: {
    updateProfile() {
      let _this = this;
      axios
        .post("/api/update-profile", _this.personalInfo)
        .then(response => response.data)
        .then(response => {
          $("#personal_info").modal("hide");
          _this.$emit("profileInfoUpdate");
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
          console.log(response);
          $("#card_info").modal("hide");
          _this.$emit("profileInfoUpdate");
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
          console.log(response);
          $("#billing_info").modal("hide");
          _this.$emit("profileInfoUpdate");
        })
        .catch(error => {
          console.log(error);
        });
    }
  }
};
</script>
