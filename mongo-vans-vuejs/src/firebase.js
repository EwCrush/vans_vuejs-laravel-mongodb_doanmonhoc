// Import the functions you need from the SDKs you need
import { initializeApp } from "firebase/app";
import { getAnalytics } from "firebase/analytics";
// TODO: Add SDKs for Firebase products that you want to use
// https://firebase.google.com/docs/web/setup#available-libraries

// Your web app's Firebase configuration
// For Firebase JS SDK v7.20.0 and later, measurementId is optional
const firebaseConfig = {
  apiKey: "AIzaSyBq1AQZs2DJI3Jb53xRURKB0BAURNnhJtU",
  authDomain: "vansvault-e5938.firebaseapp.com",
  projectId: "vansvault-e5938",
  storageBucket: "vansvault-e5938.appspot.com",
  messagingSenderId: "874211910943",
  appId: "1:874211910943:web:e041abcdb84c3fb56f7c2a",
  measurementId: "G-YQ250XEDZR"
};

// Initialize Firebase
const app = initializeApp(firebaseConfig);
const analytics = getAnalytics(app);
import {getStorage} from "firebase/storage";
const storage = getStorage(app);

export {storage}