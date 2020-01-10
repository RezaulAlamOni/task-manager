<template>
  <aside class="right-aside" style="height: calc(100vh - 77px); overflow: auto;">
    <div class="container">
      <section class="content">
        <div class="row">
          <div class="col-lg-12">
            <div class="card bg-primary-card">
              <div id="header-item" class="card-header text-black">
                <div class="row">
                  <div class="col text-left">
                    <b>
                      <i class="fa fa-bell-o" /> Email & Notifications
                    </b>
                  </div>
                  <div class="col text-right">
                    <router-link :to="{ name: 'NotificationCreate' }" class="profile-edit-btn">
                      Add
                      New Notification
                    </router-link>
                  </div>
                </div>
              </div>
              <div class="card-body" style="margin: 5px 50px;">
                <div class="row">
                  <table
                    class="table table-hover border"
                    v-for="notification in notifications"
                    :key="notification.id"
                  >
                    <thead class="bg-gray-light">
                      <tr>
                        <th>{{ notification.title }}</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr class="d-flex" v-for="child in notification.children" :key="child.id">
                        <td class="col-10">{{ child.title }}</td>
                        <td class="col-2" style="text-align: right;">
                          <button
                            class="btn btn-danger btn-sm"
                            type="button"
                            data-toggle="tooltip"
                            title="Delete!"
                            data-placement="right"
                            @click="deleteNotification(child.id)"
                          >
                            <i class="fal fa-trash-alt" aria-hidden="true" />
                          </button>
                        </td>
                      </tr>
                    </tbody>
                  </table>
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
      notifications: {}
    };
  },
  created() {
    this.getAllNotifications();
  },
  mounted() {},
  methods: {
    getAllNotifications() {
      let _this = this;
      axios
        .get("/api/users-notifications")
        .then(response => response.data)
        .then(response => {
          console.log(response);
          _this.notifications = response;
          setTimeout(() => {
            $('[data-toggle="tooltip"]').tooltip();
          }, 400);
        })
        .catch(error => {
          console.log(error);
        });
    },
    deleteNotification(id) {
      let _this = this;
      swal(
        {
          title: "Are you sure you want to delete?",
          text: "",
          type: "warning",
          showCancelButton: true,
          confirmButtonClass: "btn-danger",
          confirmButtonText: "Delete!",
          confirmButtonColor: "#f56065",
          cancelButtonText: "Cancel",
          cancelButtonColor: "#c3dda3",
          closeOnConfirm: false,
          closeOnCancel: true,
          dangerMode: true,
          buttons: true
        },
        function(isConfirm) {
          if (isConfirm) {
            axios
              .post("/api/delete-notification/" + id)
              .then(response => response.data)
              .then(response => {
                if (response.success === true) {
                  _this.getAllNotifications();
                  // _this.notifications.forEach((value, index) => {
                  //     value.children.forEach((child_v, key) => {
                  //         if (child_v.id === id) {
                  //             _this.notifications[index].children.splice(key, 1);
                  //         }
                  //     })
                  // });
                  swal.close();
                }
              })
              .catch(error => {
                console.log(error);
              });
          }
        }
      );
    }
  },
  watch: {}
};
</script>
<style>
.card-header b {
  font-size: 25px;
}

h2 {
  font-size: 20px;
  font-weight: bold;
}

.table > thead > tr > th {
  font-size: 20px;
}

.table {
  margin-bottom: 40px;
}

.btn-sm,
.btn-group-sm > .btn {
  padding: 0.25rem 0.5rem;
  font-size: 0.875rem;
  line-height: 1.5;
  border-radius: 0.2rem;
}
</style>
