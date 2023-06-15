import { createRouter, createWebHistory } from 'vue-router'

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/',
      name: 'login',
      component: () => import('../views/LoginView.vue')
    },
    {
      path: '/about',
      name: 'about',
      component: () => import('../views/AboutView.vue')
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
    }
  ]
})

export default router
