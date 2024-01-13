import { defineStore } from 'pinia';
import { ref } from 'vue';

export const useModalOpen = defineStore('modal', () => {
    const isOpenModal = ref(false)

    const openModal = () => {
      isOpenModal.value = !isOpenModal.value 
    }

    return { isOpenModal, openModal }
})

export const useListOwner = defineStore('list_owner', () => {
  const listOwner = []

  const addOwnerList = (newOwner) => {
    listOwner.push(newOwner)
  }

  return { listOwner, addOwnerList }
})