<script setup>
import { RouterView, useRoute } from 'vue-router'
import HeaderNav from '@/components/HeaderNav.vue'
import { computed } from 'vue'

// Vérifie si on est sur la home pour afficher ou non la nav
const isRootPage = computed(() => {
  return useRoute().path !== '/'
})
</script>

<template>
  <header v-if="isRootPage">
    <div class="wrapper">
      <HeaderNav />
    </div>
  </header>

  <main>
    <RouterView />
  </main>
</template>
