<script setup>
import { ref } from 'vue'
import { insertItem } from '@/apis/item'

// Refs
const title = ref('')
const description = ref('')
const link = ref('')

// Props
const props = defineProps({
  idUser: {
    type: Number,
    required: true
  },
  type: {
    type: String,
    required: true
  }
})

// Methods
const addItem = async (type) => {
  try {
    return await insertItem({
      title: title.value,
      description: description.value,
      link: link.value,
      id_user: props.idUser,
      type: type
    })
  } catch (error) {
    console.error(error)
  }
}

const clearForm = () => {
  title.value = null
  description.value = null
  link.value = null
}

const submitForm = () => {
  let result = null

  if (props.type === 'addGift') {
    result = addItem('gift')
  } else if (props.type === 'addList') {
    result = addItem('list')
  } else if (props.type === 'addDonation') {
    result = addItem('donation')
  }

  if (result) {
    clearForm()
  }
}
</script>

<template>
  <form>
    <button class="close-modale" type="button">
      <span>Close modal</span>
    </button>

    <div class="form-wrapper">
      <h2>Titre</h2>
      <p class="h3">{{ title }}</p>

      <div class="wrap-form">
        <div class="label-wrap">
          <label for="">Désignation</label>
        </div>
        <input type="text" name="designation" v-model.trim="title" />
      </div>

      <div class="wrap-form">
        <div class="label-wrap">
          <label for="">Lien</label>
          <span class="helper">Facultatif</span>
        </div>
        <input type="text" name="link" v-model.trim="link" />
      </div>

      <div class="wrap-form">
        <div class="label-wrap">
          <label for="">Description</label>
          <span class="helper">Facultatif</span>
        </div>
        <textarea name="description" v-model.trim="description"></textarea>
      </div>

      <div class="bt-wrapper">
        <button type="submit" @click.prevent="submitForm">Ajouter</button>
      </div>
    </div>
  </form>
</template>

<style scoped></style>
