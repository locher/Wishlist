<script setup>
import Gift from '@/components/Gift.vue'

const props = defineProps({
    gifts: {
        type: Array,
        required: true
    },
    isAdmin: {
        type: Boolean,
        required: false,
        default: false
    }
})

</script>

<template>
  <section class="">
    <slot></slot>
    <div class="list-gifts">
      <Gift v-for="gift in gifts" :key="gift.id" :gift="gift" :isAdmin="isAdmin" />
    </div>
  </section>
</template>

<style scoped lang="scss">
  .list-gifts{
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: var(--gap);
  }
</style>
