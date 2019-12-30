
// importScripts('https://www.gstatic.com/firebasejs/7.6.1/firebase-messaging.js');

var firebaseConfig = {
    apiKey: "AIzaSyAptOmWCzJ3Xrh0H--BKWYynTIEIJQbCvM",
    authDomain: "compltit-2019.firebaseapp.com",
    databaseURL: "https://compltit-2019.firebaseio.com",
    projectId: "compltit-2019",
    storageBucket: "compltit-2019.appspot.com",
    messagingSenderId: "299665927099",
    appId: "1:299665927099:web:5a8140fc36c771545bf29e",
    measurementId: "G-24XSYB6Z38"
};
// Initialize Firebase
firebase.initializeApp(firebaseConfig);
const messaging = firebase.messaging()

// navigator.serviceWorker.register("{{env('APP_URL')}}/firebase-messaging-sw.js")
//     .then(function () {
//         const messaging = firebase.messaging();
//         messaging.requestPermission().then(function () {
//             return messaging.getToken();
//         }).then(function (token) {
//             registerFcmToken(token);
//         }).catch(function (err) {
//         });
//         messaging.onMessage(function (payload) {
//             console.log("Message received. ", payload);
//             //https://developer.mozilla.org/en-US/docs/Web/API/notification/Notification
//             navigator.serviceWorker.ready.then(function (registration) {
//                 registration.showNotification(payload.notification.title, {
//                     tag: payload.notification.tag,
//                     body: payload.notification.body,
//                     icon: payload.notification.icon,
//                     image: payload.notification.image,
//                     vibrate: [500, 110, 500, 110, 450, 110, 200, 110, 170, 40, 450, 110, 200, 110, 170, 40, 500],
//                     sound: 'https://notificationsounds.com/soundfiles/dd458505749b2941217ddd59394240e8/file-sounds-1111-to-the-point.ogg'
//                 });
//             });
//         });
//     });
