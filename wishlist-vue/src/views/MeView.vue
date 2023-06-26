<script setup>
import { onMounted, ref } from 'vue'
import { useAuthStore } from '@/stores/auth'
import UserDetails from '@/components/UserDetails.vue'
import GiftList from '@/components/GiftList.vue'
import BtnAddGift from '@/components/BtnAddGift.vue'
import GiftForm from '@/components/GiftForm.vue'
import User from "@/classes/User";

// Stores
const authStore = useAuthStore()
const user = new User(authStore.currentUser)

// Refs
const gifts = ref([])
const lists = ref([])
const donations = ref([])
const formType = ref(null)

// Hooks
onMounted(async () => {
  gifts.value = await user.getGifts()
  lists.value = await user.getLists()
  donations.value = await user.getDonation()
})
</script>

<template>
  <div class="wrapper">
    <UserDetails v-if="user" :user="user" />
    <GiftList v-if="gifts.length > 0" :items="gifts" :is-admin="true">
      <h2>Mes cadeaux</h2>
    </GiftList>
    <GiftList v-if="donations.length > 0" :items="donations" :is-admin="true">
        <h2>Mes dons</h2>
    </GiftList>
    <GiftList v-if="lists.length > 0" :items="lists" :is-admin="true">
    <h2>Mes listes</h2>
  </GiftList>

  </div>

  <BtnAddGift @open-form-type="(type) => (formType = type)" />

  <GiftForm v-if="formType" :id-user="user.id" :type="formType" />
</template>

<style lang="scss"></style>
