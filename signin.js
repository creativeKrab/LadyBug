import { initializeApp } from "https://www.gstatic.com/firebasejs/11.1.0/firebase-app.js";
import { getAuth, GoogleAuthProvider, signInWithPopup, getRedirectResult } from "https://www.gstatic.com/firebasejs/11.1.0/firebase-auth.js";


  const firebaseConfig = {
    apiKey: "AIzaSyASJbuwrFdATUjH567tSBWpzkU_V06CTCU",
    authDomain: "ladybug-c670d.firebaseapp.com",
    projectId: "ladybug-c670d",
    storageBucket: "ladybug-c670d.firebasestorage.app",
    messagingSenderId: "1076432862708",
    appId: "1:1076432862708:web:933ba5070219033bbf4da1"
  };

  const app = initializeApp(firebaseConfig);
  const auth = getAuth(app);
  auth.languageCode = 'en';
  const provider = new GoogleAuthProvider();
  
  const googleLogin = document.getElementById("google-login-btn");
  googleLogin.addEventListener("click", function() {
    signInWithPopup(auth, provider)
  .then((result) => {
    const credential = GoogleAuthProvider.credentialFromResult(result);
    const user = result.user;
    console.log(user);
    window.location.href = "http://localhost/php_backend/ladybugwebsite/mainpage.html";
  }).catch((error) => {
    const errorCode = error.code;
    const errorMessage = error.message;
  });
  })

 