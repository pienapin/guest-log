import { defineStore } from "pinia";
import { login, logout } from "../services/auth";
import { certCookies, setCookies, delCookies } from "../plugins/cookies";

const useAuthStore = defineStore({
  id: 'auth',
  state: () => ({
    user: undefined,
  }),
  actions: {
    async setUser() {
      try {
        const user = await certCookies();
        this.user = user;
        return user;
      } catch({ message }) {
        this.user = undefined;
        throw message
      }
    },
    async login(body) {
      try {
        const data = await login(body);
        setCookies('CERT', data.access_token, { datetime: data.expires_in });
        await this.setUser();
        return true;
      } catch ({ error, message }) {
        alert("Login Gagal!")
        throw message ?? error;
      }
    },
    async logout() {
      try {
        await logout();
        delCookies('CERT');
      } catch ({ error, message }) {
        throw message ?? error;
      }
    },
  },
});

export default useAuthStore;