<script setup>
import { onBeforeMount, ref } from 'vue'
import { useAuthStore } from '@/stores/auth'
import UserList from '@/components/UserList.vue'
import { getUsers } from '@/apis/users'
import User from '@/classes/User'

const store = useAuthStore()
const user = store.currentUser
const otherUsers = ref([])

onBeforeMount(async () => {
  try {
    // Get other users
    otherUsers.value = await getUsers({ exclude: [user?.id] })
    otherUsers.value = otherUsers.value.map((user) => new User(user))
  } catch (error) {
    console.error(error)
  }
})
</script>

<template>
  <div class="wrapper">
    <UserList :users="otherUsers" link-title="Voir la liste" />
  </div>
</template>

<style lang="scss"></style>
