<script setup>
import UserCard from '@/components/UserCard.vue'

const props = defineProps({
  users: {
    type: Array,
    required: true
  },
  linkTitle: {
    type: String,
    required: false,
    default: 'Voir'
  },
  type: {
    type: String,
    required: false,
    default: 'read'
  }
})
</script>

<template>
  <section class="user-list">
    <UserCard
      v-for="user in users"
      :user="user"
      :key="user.id"
      :link-title="linkTitle"
      :type="type"
    ></UserCard>
  </section>
</template>

<style lang="scss">
.user-list {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: var(--gap);
}
</style>
