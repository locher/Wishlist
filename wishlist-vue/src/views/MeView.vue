<script setup>
import { onMounted, ref } from 'vue'
import { useAuthStore } from '@/stores/auth'
import UserDetails from '@/components/UserDetails.vue'
import GiftList from '@/components/GiftList.vue'
import { getGiftsPerUserId } from '@/apis/gifts'
import { getUsers } from "@/apis/users";

const store = useAuthStore()
const userGifts = ref([])
const user = store.currentUser
const otherUsers = ref([])

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
    <UserDetails v-if="user" :user="user" />
    <GiftList :gifts="userGifts" :is-admin="true">
        <h2>Mes cadeaux</h2>
    </GiftList>
  </div>
</template>

<style lang="scss"></style>
