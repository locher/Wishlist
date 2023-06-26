import { defineStore } from 'pinia'
import { StorageSerializers, useLocalStorage } from '@vueuse/core'

export const useItemStore = defineStore('item', {
  state: () => ({
    item: useLocalStorage('item', null, { serializer: StorageSerializers.object })
  })
})
