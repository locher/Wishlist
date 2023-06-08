<script>
import Btn from '@/components/Btn.vue'

export default {
  name: 'Gift',
  components: { Btn },
  props: {
    gift: {
      type: Object,
      required: true
    }
  }
}
</script>

<template>
  <div class="gift">
    <div class="gift__header">
      <h3>{{ gift.title }}</h3>
      <Btn type="a" :href="gift.link" target="_blank" size="tiny" :border="true" color="secondary">Voir</Btn>
    </div>

    <div v-if="gift.description" class="gift__description">
        <p>{{ gift.description }}</p>
    </div>

    <div class="gift__edit">
      <Btn color="red" size="tiny" :border="true">Supprimer</Btn>
      <Btn color="white" size="tiny" :border="true">Modifier</Btn>
    </div>
  </div>
</template>

<style scoped lang="scss">
  .gift{
    padding: calc(var(--padding-global)/2.6);
    background-color: var(--color-primary);
    color: var(--color-white);
    border-radius: 10px;
    border: 2px solid var(--color-primary);
    flex-shrink: 0;
    width: 100%;
    display: flex;
    flex-direction: column;
    justify-content: space-between;

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
  }

</style>
