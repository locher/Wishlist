import './assets/styles/main.scss'

import { createApp } from 'vue'
import { createPinia } from 'pinia'
import { useAuthStore } from '@/stores/auth'

import App from './App.vue'
import router from './router'

const app = createApp(App)

app.use(createPinia())
app.use(router)

// Init la valeur de currentUser
const authStore = useAuthStore()
authStore.initializeCurrentUser()

app.mount('#app')
