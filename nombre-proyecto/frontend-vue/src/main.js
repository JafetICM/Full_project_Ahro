//C:\Users\dell\nombre-proyecto\frontend-vue/src/main.js
import { createApp } from 'vue'
import App from './App.vue'
import router from './router'
import './assets/welcome-style.css'

const app = createApp(App)
app.use(router)
app.mount('#app')
