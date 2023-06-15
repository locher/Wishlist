<script setup>
import { onBeforeMount, onMounted, ref } from 'vue'
import router from '@/router'
import { useAuthStore } from '@/stores/auth'
import UserDetails from '@/components/UserDetails.vue'
import GiftList from '@/components/GiftList.vue'
import { getGiftsPerUserId } from '@/apis/gifts'
import UserList from "@/components/UserList.vue";
import {getUsers} from "@/apis/users";

const store = useAuthStore()
const userGifts = ref([])
const user = store.currentUser
const otherUsers = ref([])

onBeforeMount(async () => {
  try {
    // Check if a user is connected, redirect to home if not
    if (!store.currentUser) {
      await router.replace('/')
    }
  } catch (error) {
    console.error(error)
  }
})

onMounted(async () => {
  try {
    // Get all gifts
    userGifts.value = await getGiftsPerUserId(user?.id)
  } catch (error) {
    console.error(error)
  }

  try {
      // Get other users
      otherUsers.value = await getUsers({exclude: [user?.id]})
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
