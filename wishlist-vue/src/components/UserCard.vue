<script>
import Btn from '@/components/Btn.vue'
import { ref } from 'vue'
import { useAuthStore } from '@/stores/auth'

export default {
  name: 'UserCard',
  components: { Btn },
  props: {
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
  },
  methods: {
    changePage() {
      if (this.type === 'connection') {
        // Actions when it's a connection button (from home)

        //Memorize user
        const authStore = useAuthStore()
        authStore.setCurrentUser(this.user)

        //redirect to me page
        this.$router.push('/me')
      } else {
        // Actions when it's a link to user page

        //redirect to user page
        this.$router.push(`/user/${this.user?.id}`)
      }
    }
  },
  setup(props) {
    // Gestion de l'avatar
    const avatarPath = ref(null)
    import(`@/assets/img/avatar/avatar${props.user.picture_id}.png`)
      .then((module) => (avatarPath.value = module.default))
      .catch(() => {
        avatarPath.value = null
      })

    return {
      avatarPath
    }
  }
}
</script>

<template>
  <div class="user-card">
    <div class="avatar-wrapper">
      <img :src="avatarPath" alt="" />
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
