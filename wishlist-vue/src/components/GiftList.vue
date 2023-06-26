<script setup>
import GiftSingle from '@/components/GiftSingle.vue'

const props = defineProps({
  items: {
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
      <GiftSingle v-for="item in items" :key="`item${item.id}`" :item="item" :isAdmin="isAdmin" />
    </div>
  </section>
</template>

<style scoped lang="scss">
.list-gifts {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: var(--gap);
}
</style>
