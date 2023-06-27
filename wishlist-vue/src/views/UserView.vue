<script setup>
import { onMounted, ref, watch } from 'vue'
import UserDetails from '@/components/UserDetails.vue'
import GiftList from '@/components/GiftList.vue'
import UserList from '@/components/UserList.vue'
import { getUser, getUsers } from '@/apis/users'
import { useRoute } from 'vue-router'
import { useAuthStore } from '@/stores/auth'
import User from '@/classes/User'

const user = ref()
const gifts = ref([])
const lists = ref([])
const donations = ref([])
const otherUsers = ref([])

const route = useRoute()
const authStore = useAuthStore()
const requestedUserId = route.params?.id

const loadData = async () => {
  try {
    window.scrollTo(0, 0)

    // Get displayed user
    const requestedUser = await getUser(requestedUserId)
    user.value = new User(requestedUser)

    // Get all items
    gifts.value = await user.value.getGifts()
    lists.value = await user.value.getLists()
    donations.value = await user.value.getDonation()

    // Get other users
    otherUsers.value = await getUsers({ exclude: [authStore.currentUser?.id, user?.value.id] })
  } catch (error) {
    console.error(error)
  }
}

watch(
  () => route.params.id,
  async () => {
    await loadData()
  }
)

onMounted(async () => {
  await loadData()
})
</script>

<template>
  <div class="wrapper">
    <UserDetails v-if="user" :user="user" />
    <GiftList v-if="lists.length > 0" :items="lists">
      <h2>Ses autres listes</h2>
    </GiftList>
    <GiftList v-if="gifts.length > 0" :items="gifts">
      <h2>Ses envies</h2>
    </GiftList>
    <GiftList v-if="donations.length > 0" :items="donations">
      <h2>Ses associations soutenues</h2>
    </GiftList>
    <UserList :users="otherUsers" link-title="Voir la liste" />
  </div>
</template>

<style lang="scss"></style>
