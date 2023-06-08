<script>
import Gift from '@/components/Gift.vue'

export default {
  name: 'GiftList',
  components: { Gift },
  props: {
    gifts: {
      type: Array,
      required: true
    }
  }
}
</script>

<template>
  <section class="">
    <slot></slot>
    <div class="list-gifts">
      <Gift v-for="gift in gifts" v-key="gift.key" :gift="gift" />
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
