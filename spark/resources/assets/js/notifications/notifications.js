module.exports = {
    props: ['notifications', 'hasUnreadAnnouncements', 'loadingNotifications'],

    /**
     * The component's data.
     */
    data() {
        return {
            showingNotifications: true,
            showingAnnouncements: false,
            comments : [],
            Socket : null,
        }
    },

    mounted(){

    },


    methods: {
        connectSocket: function () {
            let app = this;
            if (app.Socket == null) {
                // app.Socket = io.connect('http://localhost:4100/');
                app.Socket = io.connect(window.socket_url);
                // app.Socket.emit('loginId', 2)
                // app.Socket.on('newMessage', function (res) {
                //     console.log(res);
                // })


            }
        },
        /**
         * Show the user notifications.
         */
        showNotifications() {
            this.showingNotifications = true;
            this.showingAnnouncements = false;
        },

        showComments(){
            var _this = this;
            var projectId = _this.$route.params.projectId;
            axios.get('/api/get-all-comment/'+projectId)
                .then(response => response.data)
                .then(response => {
                    _this.comments = response.comments;
                })
                .catch(error => {
                    _this.$toastr.w('Field To connect!');
                });

        },
        /**
         * Show the product announcements.
         */
        showAnnouncements() {
            this.showingNotifications = false;
            this.showingAnnouncements = true;

            this.updateLastReadAnnouncementsTimestamp();
        },


        /**
         * Update the last read announcements timestamp.
         */
        updateLastReadAnnouncementsTimestamp() {
            axios.put('/user/last-read-announcements-at')
                .then(() => {
                    Bus.$emit('updateUser');
                });
        }
    },


    computed: {
        /**
         * Get the active notifications or announcements.
         */
        activeNotifications() {
            if ( ! this.notifications) {
                return [];
            }

            if (this.showingNotifications) {
                return this.notifications.notifications;
            } else {
                return this.notifications.announcements;
            }
        },


        /**
         * Determine if the user has any notifications.
         */
        hasNotifications() {
            return this.notifications && this.notifications.notifications.length > 0;
        },


        /**
         * Determine if the user has any announcements.
         */
        hasAnnouncements() {
            return this.notifications && this.notifications.announcements.length > 0;
        }
    }
};
