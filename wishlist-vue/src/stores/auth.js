import { defineStore } from 'pinia'
import { StorageSerializers, useLocalStorage } from '@vueuse/core'

export const useAuthStore = defineStore('auth', {
  state: () => ({
    currentUser: useLocalStorage('user', null, { serializer: StorageSerializers.object })
  }),
  actions: {
    login(user) {
      this.currentUser = user
    },
    logout(){
      this.currentUser = null
    }
  },
  getters: {
    isLoggedIn: (state) => state?.currentUser !== null
  }
})