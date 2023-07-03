<script setup>
import { RouterView, useRoute } from 'vue-router'
import HeaderNav from '@/components/HeaderNav.vue'
import { computed, onBeforeMount } from 'vue'
import { useUsersStore } from '@/stores/users'
import { getUsers } from '@/apis/users'
import User from '@/classes/User'

// Vérifie si on est sur la home pour afficher ou non la nav
const isRootPage = computed(() => {
  return useRoute().path !== '/'
})

// Store all users
const usersStore = useUsersStore()
onBeforeMount(async () => {
  try {
    // Get all users
    const users = await getUsers()
    usersStore.users = users.map((user) => new User(user))
  } catch (error) {
    console.error(error)
  }
})
</script>

<template>
  <header v-if="isRootPage">
    <div class="wrapper">
      <HeaderNav />
    </div>
  </header>

  <main>
    <RouterView />
  </main>
</template>
