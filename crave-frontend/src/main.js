import axios from 'axios'
import { createApp } from 'vue'
import { createPinia } from 'pinia'

import App from './App.vue'
import router from './router'

const app = createApp(App)

app.use(createPinia())
app.use(router)

axios.defaults.withCredentials = true

axios
  .get('/sanctum/csrf-cookie')
  .catch(() => {
    // Ignore CSRF cookie errors and mount the app anyway.
  })
  .finally(() => {
    app.mount('#app')
  })
