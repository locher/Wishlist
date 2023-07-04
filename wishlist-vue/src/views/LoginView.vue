<script setup>
import { ref, onBeforeMount } from 'vue'
import UserList from '@/components/UserList.vue'
import { getUsers } from '@/apis/users'
import BtnDefault from '@/components/BtnDefault.vue'
import User from '@/classes/User'
import UserForm from "@/components/UserForm.vue";

const userList = ref([])
const openForm = ref(false)

onBeforeMount(async () => {
  try {
    // Get all users
    const allUsers = await getUsers({ children: 0 })
    userList.value = allUsers.map((user) => new User(user))
  } catch (error) {
    console.error(error)
  }
})
</script>

<template>
  <main>
    <div class="wrapper-home wrapper">
      <section class="home-header">
        <h1>Hello</h1>
        <p class="h1-subtitle">Pas de compte ?</p>

        <div class="multi-button">
          <BtnDefault>Me connecter en invité</BtnDefault>
          <BtnDefault border="border" @click="openForm = !openForm">Ajouter un compte</BtnDefault>
        </div>
      </section>

      <section class="list-connection">
        <UserList :users="userList" type="connection" link-title="Me connecter" />
      </section>
    </div>
  </main>

  <UserForm v-if="openForm" :parents="userList"/>

</template>

<style lang="scss" scoped>
main {
  display: flex;
  min-height: 100vh;

  .list-connection {
    margin-top: var(--padding-global);
    margin-bottom: var(--padding-global);
    min-width: 400px;

    @media (width < 800px) {
      width: 100%;
      min-width: 0;
    }
  }
}

.wrapper-home {
  display: grid;
  grid-template-columns: 1fr 1fr;
  align-items: center;
}

.home-header {
  h1 {
    font-size: clamp(4rem, 15vw, 14rem);
    font-family: var(--font-secondary);
  }

  .h1-subtitle {
    font-weight: bold;
  }
}
</style>
