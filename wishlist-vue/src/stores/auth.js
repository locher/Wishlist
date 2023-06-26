import { defineStore } from 'pinia'
import { StorageSerializers, useLocalStorage } from '@vueuse/core'
import User from '@/classes/User'

export const useAuthStore = defineStore('auth', {
  state: () => ({
    currentUser: useLocalStorage('user', null, { serializer: StorageSerializers.object })
  }),
  actions: {
    login(user) {
      this.currentUser = new User(user)
    },
    logout() {
      this.currentUser = null
    }
  },
  getters: {
    isLoggedIn: (state) => state?.currentUser !== null
  }
})
