import { createRouter, createWebHistory } from "vue-router";

import Home from "@/views/Home.vue";
import AdminLayout from "@/layouts/AdminLayout.vue";
import Dashboard from "@/views/admin/Dashboard.vue";
import DaftarPengunjung from "@/views/admin/DaftarPengunjung.vue";
import DaftarKunjungan from "@/views/admin/DaftarKunjungan.vue";
import DaftarPengguna from "@/views/admin/DaftarPengguna.vue";

const routes = [
  {
    name: "Home",
    path: "/",
    component: Home
  },
  {
    path: "/admin",
    component: AdminLayout,
    props: (route) => {
      return {
        title: route.meta.title
      }
    },
    children: [
      {
        path: "",
        component: Dashboard,
        meta: {
          title: "Dashboard",
        },
      },
      {
        path: "daftar-pengunjung",
        component: DaftarPengunjung,
        meta: {
          title: "Daftar Pengunjung",
        },
      },
      {
        path: "daftar-kunjungan",
        component: DaftarKunjungan,
        meta: {
          title: "Daftar Kunjungan",
        },
      },
      {
        path: "daftar-pengguna",
        component: DaftarPengguna,
        meta: {
          title: "Daftar Pengguna",
        },
      }
    ],
  }
];

const router = createRouter({
  history: createWebHistory(),
  routes
});

export default router;