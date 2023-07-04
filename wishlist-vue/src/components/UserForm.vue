<script setup>
import {computed, ref, watch} from "vue";
import {insertUser} from "@/apis/users";

// Props
const props = defineProps({
    parents: {
        type: Object,
        required: true
    }
})

// Refs
const id = ref(null)
const name = ref(null)
const birthday = ref(null)
const sizeTop = ref(null)
const sizeBottom = ref(null)
const sizeFeet = ref(null)
const illustration = ref(null)
const isChildAccount = ref(false)
const parent = ref([])

// Computed
const isFormValid = computed(() => {
    return !!(name.value && illustration.value);
})

// Methods
const addUser = async () => {
    try {
        return await insertUser({
            id: id.value,
            name: name.value,
            picture_id: illustration.value,
            is_child_account: isChildAccount.value,
            birthday_date: birthday.value,
            size_top: sizeTop.value,
            size_bottom: sizeBottom.value,
            size_feet: sizeFeet.value,
            parent: parent.value
        })
    } catch (error) {
        console.error(error)
    }
}
const submitForm = () => {
    let result = null

    // Ajout
    if (!id.value) {
        result = addUser()
    } else {
        result = updateUser()
    }

    if (result) {
        clearForm()
    }
}

const clearForm = () => {
    name.value = null
    birthday.value = null
    sizeTop.value = null
    sizeBottom.value = null
    sizeFeet.value = null
    illustration.value = null
    isChildAccount.value = null
    parent.value = null
}

</script>

<template>
    <form>
        <div>
            <label for="name">Prénom</label>
            <input type="text" id="name" v-model="name">
        </div>

        <div>
            <label for="birthday">Date de naissance <span>JJ/MM/AAAA</span></label>
            <input type="date" id="birthday" v-model="birthday">
        </div>

        <fieldset>
            <legend>Tailles <span>facultatif</span></legend>
            <div>
                <label for="size_top">Haut</label>
                <input type="text" id="size_top" v-model="sizeTop">
            </div>
            <div>
                <label for="size_bottom">Bas</label>
                <input type="text" id="size_bottom" v-model="sizeBottom">
            </div>
            <div>
                <label for="size_feet">Pieds</label>
                <input type="text" id="size_feet" v-model="sizeFeet">
            </div>
        </fieldset>

        <fieldset>
            <legend>Illustration</legend>
            <div v-for="i in 5">
                <input type="radio" :id="`illu${i}`" v-model="illustration" name="illustration" :value="i">
                <label :for="`illu${i}`">
                    <span class="avatar-wrapper">
                        <img src="" alt="Photo">
                    </span>
                </label>
            </div>
        </fieldset>

        <fieldset>
            <legend>Compte enfant ?</legend>
            <div>
                <input type="checkbox" id="childAccount" v-model="isChildAccount">
                <label for="childAccount">Oui, compte enfant</label>
            </div>
        </fieldset>

        <fieldset v-if="isChildAccount">
            <legend>Qui peut modifier cette liste ?</legend>

            <div v-for="user in parents">
                <input type="checkbox" name="parent" :id="`parent${user.id}`" :value="user.id" v-model="parent">
                <label :for="`parent${user.id}`">
                    <img src="" :alt="user.picture_id">
                    <span>{{ user.name }}</span>
                </label>
            </div>
        </fieldset>

        <button type="submit" @click.prevent="submitForm" :disabled="!isFormValid">Ajouter l'utilisateur</button>

    </form>
</template>

<style scoped>
</style>