// Give the service worker access to Firebase Messaging.
// Note that you can only use Firebase Messaging here, other Firebase libraries
// are not available in the service worker.
importScripts('https://www.gstatic.com/firebasejs/7.5.0/firebase-app.js');
importScripts('https://www.gstatic.com/firebasejs/7.5.0/firebase-messaging.js');
var config = {
    apiKey: "AIzaSyCgD0QTsObsy9TqxCX0Pjohqp_xa9sgquM",
    authDomain: "shorttermrental-8c9e6.firebaseapp.com",
    databaseURL: "https://shorttermrental-8c9e6.firebaseio.com",
    projectId: "shorttermrental-8c9e6",
    storageBucket: "shorttermrental-8c9e6.appspot.com",
    messagingSenderId: "527825715913",
    appId: "1:527825715913:web:09ee2c5191556e8a468318",
    measurementId: "G-S701KN3GQM"
};
firebase.initializeApp(config);
// Retrieve an instance of Firebase Messaging so that it can handle background
// messages.
const messaging = firebase.messaging();
messaging.setBackgroundMessageHandler(function (payload) {
    console.log('[firebase-messaging-sw.js] Received background message ', payload);
    /*return self.registration.showNotification(notificationTitle,
   notificationOptions);*/
});
