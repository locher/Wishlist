<script setup>
import { onMounted, ref, watch } from 'vue'
import UserDetails from '@/components/UserDetails.vue'
import GiftList from '@/components/GiftList.vue'
import { getItems } from '@/apis/item'
import UserList from '@/components/UserList.vue'
import { getUser, getUsers } from '@/apis/users'
import { useRoute } from 'vue-router'
import { useAuthStore } from '@/stores/auth'

const userGifts = ref([])
const otherUsers = ref([])
const user = ref([])

const route = useRoute()
const authStore = useAuthStore()

const loadData = async () => {
  try {
    window.scrollTo(0, 0)

    // Get displayed user
    user.value = await getUser(route.params?.id)

    // Get all gifts
    userGifts.value = await getItems(user?.value.id, 'gifts')

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
    <GiftList :gifts="userGifts">
      <h2>Ses envies</h2>
    </GiftList>
    <UserList :users="otherUsers" link-title="Voir la liste" />
  </div>
</template>

<style lang="scss"></style>
