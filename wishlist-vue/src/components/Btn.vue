<script setup>

import {computed} from "vue";

const props = defineProps({
    type: {
        type: String,
        required: false,
        default: 'button'
    },
    href: {
        type: String,
        required: false,
        default: '#'
    },
    target: {
        type: String,
        required: false,
        default: '_self'
    },
    border: {
        type: Boolean,
        required: false,
        default: false
    },
    color: {
        type: String,
        required: false,
        default: 'primary'
    },
    size: {
        type: String,
        required: false,
        default: 'normal'
    }
})

const btnClasses = computed(() =>{
    let classes =  ['bt', `bt--${this.color}`, `bt--${this.size}`]
    this.border && classes.push('bt--border')

    return classes.join(' ')
})


</script>

<template>

  <button v-if="type === 'button'" :class="btnClasses">
      <slot></slot>
  </button>

  <a v-else-if="type === 'a'" :href="href" :target="target" :class="btnClasses">
      <slot></slot>
  </a>

</template>

<style lang="scss">

.bt{
  border-radius: 2em;
  padding: 1em 2.5em;
  text-decoration: none;
  display: inline-flex;
  font-weight: 500;
  font-size: 1.5rem;
  text-align: center;
  line-height: 1em;
  cursor: pointer;
  border: 2px solid transparent;

  & + .bt{
    margin-left: .7em;
  }

  &--tiny{
    font-size: 1rem;
    padding: .85em 2.2em .8em;
  }

  &--primary{
    background-color: var(--color-primary);
    border-color: var(--color-primary);
    color: var(--color-white);
  }

  &--red{
    background-color: var(--color-red);
    border-color: var(--color-red);
    color: var(--color-white);
  }

  &--white{
    background-color: var(--color-white);
    border-color: var(--color-white);
    color: var(--color-primary);
  }

  &--secondary{
    border-color: var(--color-secondary);
    color: var(--color-white);
  }

  &--border{
    background-color: transparent;

    &.bt--secondary{
      color: var(--color-white);
    }

    &.bt--primary{
      color: var(--color-primary);
    }

    &.bt--white{
      color: var(--color-white);
    }

    &.bt--red{
      color: var(--color-red);
    }
  }
}
</style>
