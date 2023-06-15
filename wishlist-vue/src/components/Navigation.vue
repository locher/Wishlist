<script>
export default {
    name: "Navigation"
}
</script>

<template>
  <nav>
      <ul>
          <li><RouterLink to="/me">Ma liste</RouterLink></li>
          <li><RouterLink to="/users">Voir les listes</RouterLink></li>
          <li><button>Me déconnecter</button></li>
      </ul>
  </nav>
</template>

<style scoped>

</style>