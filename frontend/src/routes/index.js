import { createRouter, createWebHistory } from "vue-router";
import { certCookies } from "@/plugins/cookies";
import Home from "@/views/Home.vue";
import AdminLayout from "@/layouts/AdminLayout.vue";
import Dashboard from "@/views/admin/Dashboard.vue";
import DaftarPengunjung from "@/views/admin/DaftarPengunjung.vue";
import DaftarKunjungan from "@/views/admin/DaftarKunjungan.vue";
import DaftarPengguna from "@/views/admin/DaftarPengguna.vue";
import DaftarPelayanan from "@/views/admin/DaftarPelayanan.vue";
import Login from '@/views/auth/Login.vue'
import useAuthStore from "../stores/auth";

const routes = [
  {
    name: "Home",
    path: "/",
    component: Home,
    meta: { guest: true }
  },
  {
    name: "Login",
    path: "/login",
    component: Login,
    meta: { guest: true }
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
        name: "admin-dashboard",
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
      },
      {
        path: "daftar-pelayanan",
        component: DaftarPelayanan,
        meta: {
          title: "Daftar Pelayanan",
        },
      },
    ],
    meta: { auth: true, admin: true }
  }
];

const router = createRouter({
  history: createWebHistory(),
  routes
});

router.beforeEach(async (to, from, next) => {
  const authStore = useAuthStore()
  const user = await authStore.setUser();
  console.log(user)
  if (to.meta.auth) {
    if (!user) next({ name: 'Login'});
    if (to.meta.admin && user.role_id == 1) next();
    if (to.meta.kepala && user.role_id == 2) next();
    if (to.meta.pst && user.role_id == 3) next();
    // next({ name: 'Unauthorized' });
  } else if (to.meta.guest) {
    if (!user) next();
    else {
      if (user.role_id == 1) next({ name: 'admin-dashboard' });
      if (user.role_id == 2) next();
      if (user.role_id == 3) next();
    }
  }
  // next()
});

export default router;