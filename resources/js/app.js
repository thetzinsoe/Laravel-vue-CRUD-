import "bootstrap";
import "bootstrap/dist/css/bootstrap.css";
import Swal from "sweetalert2";
window.Toast = Swal.mixin({
    toast: true,
    position: "top-end",
    showConfirmButton: false,
    timer: 3000,
    timerProgressBar: true,
    didOpen: (toast) => {
        toast.onmouseenter = Swal.stopTimer;
        toast.onmouseleave = Swal.resumeTimer;
    },
});

import { createApp } from "vue";
import App from "./App.vue";
// createApp(App).mount("#app");
import VueProgressBar from "@aacassandra/vue3-progressbar";

const options = {
    color: "#0d6efd",
    failedColor: "#0d6efd",
    thickness: "5px",
    transition: {
        speed: "0.2s",
        opacity: "0.6s",
        termination: 300,
    },
    autoRevert: true,
    location: "top",
    inverse: false,
};

createApp(App).use(VueProgressBar, options).mount("#app");
