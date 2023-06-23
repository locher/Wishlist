<script setup>
// Emits
const emit = defineEmits(['openFormType'])

// Methods
const addGift = () => {
  console.log('ajouter un cadeau')
  emit('openFormType', 'addGift')
}

const addList = () => {
  console.log('ajouter une liste')
  emit('openFormType', 'addList')
}

const addDonation = () => {
  console.log('ajouter un don')
  emit('openFormType', 'addDonation')
}
</script>

<template>
  <button @click="addGift">Ajouter un cadeau</button>
  <button @click="addList">Ajouter une liste</button>
  <button @click="addDonation">Ajouter une don</button>
</template>

<style scoped></style>
