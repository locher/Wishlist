<script setup>
import {onUnmounted, ref, watch} from 'vue'
import {insertItem, updateItem} from '@/apis/item'
import {useItemStore} from "@/stores/item";

const itemStore = useItemStore()

// Refs
const title = ref(itemStore?.item.title || '')
const description = ref(itemStore?.item.description || '')
const link = ref(itemStore?.item.link || '')
const type = ref(itemStore?.item.type || 'gift')
const id = ref(itemStore?.item.id || null)

onUnmounted(() => {
    itemStore.item = {}
})

watch(itemStore, () => {
    title.value = itemStore.item.title
    description.value = itemStore.item.description
    link.value = itemStore.item.link
    type.value = itemStore.item.type
    id.value = itemStore.item.id
})

// Props
const props = defineProps({
  idUser: {
    type: Number,
    required: true
  }
})

// Methods
const addItem = async () => {
  try {
    return await insertItem({
      title: title.value,
      description: description.value,
      link: link.value,
      id_user: props.idUser,
      type: type.value
    })
  } catch (error) {
    console.error(error)
  }
}

const updateTheItem = async () => {
    try {
        return updateItem({
            title: title.value,
            description: description.value,
            link: link.value,
            id_user: props.idUser,
            type: type.value,
            id: id.value
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

  // Ajout
  if(!id.value){
      result = addItem()
  }else{
      result = updateTheItem()
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
      <h2 v-if="!id">Ajouter un élément</h2>
      <h2 v-else>Modifier un élément</h2>
        <div class="wrap-form">
            <div class="label-wrap">
                <label for="">Type d'élément</label>
            </div>
            <input type="radio" name="type" value="gift" id="gift" v-model="type" />
            <label for="gift">Cadeau</label>
            <input type="radio" name="type" value="list" id="list" v-model="type" />
            <label for="list">Liste</label>
            <input type="radio" name="type" value="donation" id="donation" v-model="type" />
            <label for="donation">Don</label>
        </div>

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
        <button v-if="id" type="submit" @click.prevent="submitForm">Modifier</button>
        <button v-else type="submit" @click.prevent="submitForm">Ajouter</button>
      </div>
    </div>
  </form>
</template>

<style scoped></style>
