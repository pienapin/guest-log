import { createRouter, createWebHashHistory } from "vue-router";
import { certCookies } from "@/plugins/cookies";
import Home from "@/views/Home.vue";
import Beranda from "@/views/Beranda.vue";
import TabelKunjungan from "@/views/TabelKunjungan.vue";
import AdminLayout from "@/layouts/AdminLayout.vue";
import KepalaLayout from "@/layouts/KepalaLayout.vue";
import PSTLayout from "@/layouts/PSTLayout.vue";
import DashboardKepala from "@/views/kepala/DashboardKepala.vue";
import DaftarPengunjungKepala from "@/views/kepala/DaftarPengunjungKepala.vue";
import DaftarKunjunganKepala from "@/views/kepala/DaftarKunjunganKepala.vue";
import DaftarPelayananKepala from "@/views/kepala/DaftarPelayananKepala.vue";
import Dashboard from "@/views/admin/Dashboard.vue";
import DaftarPengunjung from "@/views/admin/DaftarPengunjung.vue";
import DaftarKunjungan from "@/views/admin/DaftarKunjungan.vue";
import DaftarPengguna from "@/views/admin/DaftarPengguna.vue";
import DaftarPelayanan from "@/views/admin/DaftarPelayanan.vue";
import DaftarKategori from "@/views/admin/DaftarKategori.vue";
import DashboardPST from "@/views/pst/DashboardPST.vue";
import DaftarPengunjungPST from "@/views/pst/DaftarPengunjungPST.vue";
import DaftarKunjunganPST from "@/views/pst/DaftarKunjunganPST.vue";
import DaftarPelayananPST from "@/views/pst/DaftarPelayananPST.vue";
import Unauthorized from "@/views/Unauthorized.vue";
import Login from '@/views/auth/Login.vue';
import useAuthStore from "../stores/auth";

const routes = [
  {
    name: "Home",
    path: "/",
    component: Beranda,
    meta: { guest: true }
  },
  {
    name: "IsiBukuTamu",
    path: "/isi",
    component: Home,
    meta: { guest: true }
  },
  {
    name: "TabelKunjungan",
    path: "/kunjungan",
    component: TabelKunjungan,
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
      {
        path: "daftar-kategori",
        component: DaftarKategori,
        meta: {
          title: "Daftar Kategori"
        }
      }
    ],
    meta: { auth: true, admin: true }
  },
  {
    path: "/kepala",
    component: KepalaLayout,
    props: (route) => {
      return {
        title: route.meta.title
      }
    },
    children: [
      {
        path: "",
        name: "kepala-dashboard",
        component: DashboardKepala,
        meta: {
          title: "Dashboard",
        },
      },
      {
        path: "daftar-pengunjung",
        component: DaftarPengunjungKepala,
        meta: {
          title: "Daftar Pengunjung",
        },
      },
      {
        path: "daftar-kunjungan",
        component: DaftarKunjunganKepala,
        meta: {
          title: "Daftar Kunjungan",
        },
      },
      {
        path: "daftar-pelayanan",
        component: DaftarPelayananKepala,
        meta: {
          title: "Daftar Pelayanan",
        },
      },
    ],
    meta: { auth: true, kepala: true }
  },
  {
    path: "/pst",
    component: PSTLayout,
    props: (route) => {
      return {
        title: route.meta.title
      }
    },
    children: [
      {
        path: "",
        name: "pst-dashboard",
        component: DashboardPST,
        meta: {
          title: "Dashboard",
        },
      },
      {
        path: "daftar-pengunjung",
        component: DaftarPengunjungPST,
        meta: {
          title: "Daftar Pengunjung",
        },
      },
      {
        path: "daftar-kunjungan",
        component: DaftarKunjunganPST,
        meta: {
          title: "Daftar Kunjungan",
        },
      },
      {
        path: "daftar-pelayanan",
        component: DaftarPelayananPST,
        meta: {
          title: "Daftar Pelayanan",
        },
      },
    ],
    meta: { auth: true, pst: true }
  },
  {
    path: '/unauthorized',
    name: 'Unauthorized',
    component: Unauthorized,
    meta: { auth: true, fallback: true }
  }
];

const router = createRouter({
  history: createWebHashHistory(),
  routes
});

router.beforeEach(async (to, from, next) => {
  const authStore = useAuthStore()
  const user = await authStore.setUser();
  if (to.meta.auth && !to.meta.fallback) {
    if (!user) {
      next({ name: 'Login'});
      return
    }

    if (to.meta.admin && user.role_id == 1) {
      next();
      return
    } else if (to.meta.kepala && user.role_id == 2) {
      next();
      return
    } else if (to.meta.pst && user.role_id == 3) {
      next();
      return
    } else {
      console.log('sini');
      next({ name: 'Unauthorized' });
    }
    return
  } else if (to.meta.guest) {
    if (!user) {next(); return}
    
    else {
      if (user.role_id == 1) {next({ name: 'admin-dashboard' });return}
      if (user.role_id == 2) {next({ name: 'kepala-dashboard' });return}
      if (user.role_id == 3) {next({ name: 'pst-dashboard'});return}
    }
  }
  next()
  return

});

export default router;