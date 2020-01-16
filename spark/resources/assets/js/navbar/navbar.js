module.exports = {
    props: [
        'user', 'teams', 'currentTeam',
        'unreadAnnouncementsCount', 'unreadNotificationsCount'
    ],
    data() {
        return {
            Socket : null,
        }
    },
    mounted(){
        this.connectSocket();
    },

    computed: {
        notificationsCount() {
            return this.unreadAnnouncementsCount + this.unreadNotificationsCount;
        }
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
         * Show the user's notifications.
         */
        showNotifications() {
            Bus.$emit('showNotifications');
        },


        /**
         * Show the customer support e-mail form.
         */
        showSupportForm() {
            Bus.$emit('showSupportForm');
        }
    }
};
