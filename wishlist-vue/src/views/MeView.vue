<script setup>
import { onMounted, ref } from 'vue'
import { useAuthStore } from '@/stores/auth'
import UserDetails from '@/components/UserDetails.vue'
import GiftList from '@/components/GiftList.vue'
import { getItems } from '@/apis/item'
import { getUsers } from '@/apis/users'
import BtnAddGift from '@/components/BtnAddGift.vue'
import GiftForm from '@/components/GiftForm.vue'

// Refs
const userGifts = ref([])
const userLists = ref([])
const otherUsers = ref([])
const formType = ref(null)

// Stores
const authStore = useAuthStore()
const user = authStore.currentUser

// Hooks
onMounted(async () => {
  try {
    // Get all gifts
    userGifts.value = await getItems(user?.id, 'gift')
    userLists.value = await getItems(user?.id, 'list')
  } catch (error) {
    console.error(error)
  }

  try {
    // Get other users
    otherUsers.value = await getUsers({ exclude: [user?.id] })
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

      <GiftList :gifts="userLists" :is-admin="true">
          <h2>Mes listes</h2>
      </GiftList>
  </div>

  <BtnAddGift @open-form-type="(type) => (formType = type)" />

  <GiftForm v-if="formType" :id-user="user.id" :type="formType" />
</template>

<style lang="scss"></style>
