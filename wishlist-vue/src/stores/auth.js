import { defineStore } from 'pinia'
import { getStorage, setStorage } from '@/utils/storage'

export const useAuthStore = defineStore('auth', {
  state: () => ({
    currentUser: null
  }),
  actions: {
    setCurrentUser(user) {
      this.currentUser = user
      setStorage('currentUser', user)
    },
    initializeCurrentUser() {
      // Récupérez la valeur depuis le stockage persistant lors de l'initialisation
      const storedUser = getStorage('currentUser')
      if (storedUser) {
        this.currentUser = storedUser
      }
    }
  }
})