import { createRouter, createWebHistory } from 'vue-router'
import { useAuthStore } from '@/stores/auth'

// Login before navigation guard
function loginBeforeEnter() {
  const authStore = useAuthStore()

  if (authStore.isLoggedIn) {
    return { name: 'me' } // Redirect
  } else {
    return true
  }
}

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/',
      name: 'login',
      component: () => import('../views/LoginView.vue'),
      beforeEnter: loginBeforeEnter
    },
    {
      path: '/user/:id',
      name: 'user',
      component: () => import('../views/UserView.vue')
    },
    {
      path: '/me',
      name: 'me',
      component: () => import('../views/MeView.vue')
    },
    {
      path: '/users',
      name: 'users',
      component: () => import('../views/ListUsersView.vue')
    },
    {
      path: '/:pathMatch(.*)*',
      redirect: '/'
    }
  ]
})

router.beforeEach((to) => {
  const authStore = useAuthStore()

  // Check user logged in
  if (to.name !== 'login' && !authStore.isLoggedIn) {
    return { name: 'login' } // Redirect
  } else {
    return true
  }
})

export default router
