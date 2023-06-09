import { createApp } from 'vue'
import { createPinia } from 'pinia'
import store from "./stores"
import './style.css'
import App from './App.vue'
import router from './routes'

createApp(App)
  .use(createPinia())
  .use(store)
  .use(router)
  .mount('#app')
