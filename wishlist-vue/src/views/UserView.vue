<script setup>
import { onMounted, ref, watch } from 'vue'
import UserDetails from '@/components/UserDetails.vue'
import GiftList from '@/components/GiftList.vue'
import { getGiftsPerUserId } from '@/apis/gifts'
import UserList from "@/components/UserList.vue";
import {getUser, getUsers} from "@/apis/users";
import {useRoute, useRouter} from "vue-router";

const userGifts = ref([])
const otherUsers = ref([])
const route = useRoute()
const router = useRouter()
const user = ref([])

const loadData = async () => {
    try{
        window.scrollTo(0,0)

        // Get user
        user.value = await getUser(route.params.id);

        // Get all gifts
        userGifts.value = await getGiftsPerUserId(user?.value.id)

        // Get other users
        otherUsers.value = await getUsers({exclude: [user?.value.id]})

    } catch(error){
        console.error(error)
    }
}

watch(() => route.params.id, async () => {
    await loadData()
})

onMounted(async () => {
    await loadData()
})

</script>

<template>
    <div class="wrapper">
        <UserDetails v-if="user" :user="user" />
        <GiftList :gifts="userGifts">
            <h2>Ses envies</h2>
        </GiftList>
        <UserList :users="otherUsers" link-title="Voir la liste" />
    </div>
</template>

<style lang="scss"></style>
