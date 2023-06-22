<script setup>

import Btn from '@/components/Btn.vue'
import {useRouter} from "vue-router";
import {useAuthStore} from "@/stores/auth";
const router = useRouter()

const auth = useAuthStore()

// Properties

const props = defineProps({
    user: {
        type: Object,
        required: true
    },
    link: {
        type: String,
        required: false
    },
    linkTitle: {
        type: String,
        required: false,
        default: 'Voir'
    },
    type: {
        type: String,
        required: false,
        default: 'view'
    }
})

// Methods

const changePage = () => {
    if (props.type === 'connection') {
        // Actions when it's a connection button (from home)

        //Memorize user
        auth.login(props.user)

        //redirect to me page
        router.replace('/me')
    } else {
        // Actions when it's a link to user page

        //redirect to user page
        router.replace(`/user/${this.user?.id}`)
    }
}

</script>

<template>
  <div class="user-card">
    <div class="avatar-wrapper">
      <img :src="`/src/assets/img/avatar/avatar${user.picture_id}.png`" alt="" />
      <div class="svg-wrapper"></div>
    </div>
    <div class="user-card__content">
      <h3 class="user-card__title">{{ user?.name }}</h3>
      <Btn @click="changePage">{{ linkTitle }}</Btn>
    </div>
  </div>
</template>

<style lang="scss" scoped>
.user-card {
  padding: calc(var(--padding-global) / 1.5);
  border-radius: var(--border-radius);
  display: grid;
  grid-template-columns: 1fr 3fr;
  align-items: center;
  background-color: var(--color-white);
  gap: 15px;

  &__title {
    margin-left: 15px;
  }
}
</style>
