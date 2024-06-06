importScripts('https://www.gstatic.com/firebasejs/8.3.2/firebase-app.js');
importScripts('https://www.gstatic.com/firebasejs/8.3.2/firebase-messaging.js');



firebase.initializeApp({
    apiKey: "AIzaSyCfe9cb6yhTcZ01nkz_vbdTCSNWBZ_pUgk",
    authDomain: "edu-kai-6a52d.firebaseapp.com",
    projectId: "edu-kai-6a52d",
    storageBucket: "edu-kai-6a52d.appspot.com",
    messagingSenderId: "132041864750",
    appId: "1:132041864750:web:ecc97d8c28fe0e811751bf",
    measurementId: "G-VZNJEWF7P1"
});

const messaging = firebase.messaging();
messaging.setBackgroundMessageHandler(function({data:{title,body,icon}}) {
    return self.registration.showNotification(title,{body,icon});
});
