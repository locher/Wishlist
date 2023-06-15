<script setup>
  import { RouterView, useRoute } from 'vue-router'
  import Navigation from "@/components/Navigation.vue"
  import { computed } from "vue";

  // Vérifie si on est sur la home
  const isRootPage = computed(() => {
      return useRoute().path !== '/'
  })

</script>

<template>

  <header v-if="isRootPage">
    <div class="wrapper">
        <Navigation></Navigation>
    </div>
  </header>

  <main>
      <RouterView />
  </main>

</template>
