<script setup>
// Emits
const emit = defineEmits(['addItem'])

// Methods
const EmitAddItem = () => {
  console.log('ajouter un élément')
  emit('addItem', true)
}

</script>

<template>
  <button @click="EmitAddItem">Ajouter un élément</button>
</template>

<style scoped></style>
