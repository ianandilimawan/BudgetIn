import "./bootstrap";
import Swal from "sweetalert2";

// FontAwesome (bundled instead of CDN)
import "@fortawesome/fontawesome-free/css/all.min.css";

// ApexCharts (bundled instead of CDN)
import ApexCharts from "apexcharts";
window.ApexCharts = ApexCharts;

window.Swal = Swal;

// Toast configuration for SweetAlert2
const Toast = Swal.mixin({
    toast: true,
    position: "top-end",
    showConfirmButton: false,
    timer: 3000,
    timerProgressBar: true,
    color: "inherit",
    customClass: {
        popup: "bg-white dark:bg-zinc-800 border dark:border-zinc-700 shadow-lg",
        title: "text-zinc-900 dark:text-white text-sm font-semibold",
    },
    didOpen: (toast) => {
        toast.addEventListener("mouseenter", Swal.stopTimer);
        toast.addEventListener("mouseleave", Swal.resumeTimer);
    },
});

window.Toast = Toast;
