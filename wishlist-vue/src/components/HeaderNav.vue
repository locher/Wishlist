<script setup>
import { useAuthStore } from '@/stores/auth'
import router from '@/router'

const authStore = useAuthStore()

const logout = () => {
  authStore.logout()
  router.push({ name: 'login' })
}
</script>

<template>
  <h1>Salut {{ authStore.currentUser?.name }} ! <button @click="logout">Me déconnecter</button></h1>

  <nav>
    <ul>
      <li><RouterLink to="/me">Ma liste</RouterLink></li>
      <li><RouterLink to="/users">Voir les listes</RouterLink></li>
    </ul>
  </nav>
</template>

<style scoped></style>
