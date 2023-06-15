<script setup>
import Btn from '@/components/Btn.vue'
import {defineProps, ref} from 'vue'
import {deleteGift, insertGift} from "@/apis/gifts";

const props = defineProps({
    gift: {
        type: Object,
        required: true
    },
    isAdmin: {
        type: Boolean,
        default: false
    }
})

const isDeleted = ref(false)

const deleteTheGift = async () => {
    isDeleted.value = true

    if(await deleteGift(props.gift?.id)){
        console.log('gift deleted')
    }else{
        console.error('gift pas deleted')
        isDeleted.value = false
    }
}

const restoreGift = async () => {
    await insertGift(props.gift)
    isDeleted.value = false
}


</script>

<template>
  <div :class="`gift ${isDeleted && 'deleted'}`">
    <div class="gift__header">
      <h3>{{ props.gift.title }}</h3>
      <Btn v-if="props.gift.link && !isDeleted" type="a" :href="props.gift.link" target="_blank" size="tiny" :border="true" color="secondary">Voir</Btn>
    </div>

    <div v-if="props.gift.description" class="gift__description">
        <p>{{ props.gift.description }}</p>
    </div>

    <div v-if="props.isAdmin && !isDeleted" class="gift__edit">
      <Btn color="red" size="tiny" :border="true" @click="deleteTheGift">Supprimer</Btn>
      <Btn color="white" size="tiny" :border="true">Modifier</Btn>
    </div>

    <div v-if="!props.isAdmin && !isDeleted" class="gift__edit">
        <Btn color="white" size="tiny" :border="true">Réserver</Btn>
    </div>

    <div v-if="isDeleted" class="gift__deleted-message">
        <p>Cadeau supprimé !</p>
        <Btn size="tiny" :border="true" @click="restoreGift">Annuler la suppression</Btn>
    </div>
  </div>
</template>

<style scoped lang="scss">

  .gift{
    --bg-color: var(--color-primary);
    --text-color: var(--color-white);

    transition: all ease-in-out .2s;
    padding: calc(var(--padding-global)/2.6);
    background-color: var(--bg-color);
    border-radius: 10px;
    border: 2px solid var(--bg-color);
    color: var(--text-color);
    flex-shrink: 0;
    width: 100%;
    display: flex;
    flex-direction: column;
    justify-content: space-between;

    &.deleted{
      --bg-color: var(--color-disabled-bg);
      --text-color: var(--color-disabled-text);
    }

    &__header{
      display: flex;
      align-items: center;

      h3{
        font-size: 1.7rem;
        line-height: 1.2em;
        flex-grow: 1;
      }

      .bt{
        margin-left: 15px;
        flex-shrink: 0;
        align-self: flex-start;
      }
    }

    &__description{
      font-size: 1.3rem;
      line-height: 1.2em;
      margin-top: .5rem;
    }

    &__edit{
      margin-top: 2rem;
      color: var(--color-white);
      display: inline-flex;
      align-items: center;

      form{
        margin-right: .8rem;
      }
    }

    &__deleted-message{
      display: flex;
      align-items: center;
      gap: 2rem;

      p{
        color: var(--color-primary);
        flex:1;
      }
    }
  }

</style>
