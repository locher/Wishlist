<script>
import IconBaby from '@/components/icons/IconBaby.vue'
import IconTshirt from '@/components/icons/IconTshirt.vue'
import IconPant from '@/components/icons/IconPant.vue'
import IconShoe from '@/components/icons/IconShoe.vue'

export default {
  name: 'UserDetails',
  components: { IconShoe, IconPant, IconTshirt, IconBaby },
  props: {
    user: {
      type: Object,
      required: true
    }
  }
}
</script>

<template>
  <section class="user-infos">
    <div class="inner-user-infos">
      <h1>{{ user.name }}</h1>

      <div class="user-infos-details-wrapper">
        <div class="user-infos-details">
          <div class="birthday-infos">
            <IconBaby />
            <span class="birthday">{{ user.birthdayDate }}</span>
            <span class="age">{{ user.age }}</span>
          </div>

          <div class="size-infos">
            <div class="wrapper-size" title="Taille haut">
              <IconTshirt />
              <span class="top">{{ user.size_top }}</span>
            </div>

            <div class="wrapper-size" title="Taille pantalon">
              <IconPant />
              <span class="bottom">{{ user.size_bottom }}</span>
            </div>

            <div class="wrapper-size" title="Taille chaussure">
              <IconShoe />
              <span class="feet">{{ user.size_feet }}</span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>

<style scoped></style>
