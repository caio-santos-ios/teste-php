<template>
    <FormRegister id="form_register_owner" @submit.prevent="registerRevision()">
        <label for="type">Tipo da Revisão
            <input type="text" placeholder="Tipo" v-model="revision.type_revision">
        </label>

        <label for="value">
            Valor
            <input required maxlength="8" minlength="1" type="text" @input="validatedValue" placeholder="R$ 0" v-model="revision.value">
        </label>

        <label for="description">
            Descrição
            <input required type="text" placeholder="Decrição" v-model="revision.description">
        </label>

        <div id="footer_btns">
            <button id="btn_finish" :disabled="!isCreateBtn" v-if="!loading">Cadastrar</button>
            <button id="btn_finish" v-if="loading" :disabled="!isCreateBtn" type="submit" class="btn_register">
                <LoadingVue style="height: 3rem; width: 3rem;" />
            </button>
            <button :disabled="!isCreateBtn" id="btn_cancel" @click="closeModal">Cancelar</button>
        </div>
    </FormRegister>
</template>

<script setup>
    import axios from 'axios';
    import { toast } from 'vue3-toastify';
    import 'vue3-toastify/dist/index.css';
    import { ref } from 'vue';
    import FormRegister from './FormRegister.vue'; 
    import LoadingVue from '../Loading.vue';
    import { useModalOpen, useListOwner } from '../../store/store'
    
    const modal = useModalOpen()
    const list = useListOwner()

    const baseURL = import.meta.env.VITE_LINK_URL;    

    const isCreateBtn = ref(true)
    const loading = ref(false)

    const idVehicle = localStorage.getItem('idVehicle')
    const idOwner = localStorage.getItem('idOwner')

    const revision = ref({
        type_revision: '',
        value: '',
        description: '',
        owner_id: idOwner
    })

    const closeModal = () => modal.openModal()

    /* criação da revisão */
    const registerRevision = async () => {
        if(revision.value.value == 0) return toast.error('Coloque um valor na revisão')
        loading.value = true
        isCreateBtn.value = false   
        
        const findOwner = list.listOwner.findIndex(el => el.id == idOwner) 

        try {
            const response = await axios.post(`${baseURL}/revisions/${idVehicle}`, revision.value)
            list.listOwner[findOwner].revision_vehicles = [ response.data[0], ...list.listOwner[findOwner].revision_vehicles ]
            isCreateBtn.value = true
            loading.value = false
            toast.success("Revisão cadastrada");
            closeModal()
        } catch (error) {
            isCreateBtn.value = true
            loading.value = false
            console.log(error)
        }
    }

    /* regex do valor da revisão */
    const validatedValue = () => {
        revision.value.value = revision.value.value.replace(/[^\d]/g, '')
        const value = revision.value.value

        revision.value.value = value.replace(/(\d{1})(\d{3})/, '$1.$2')

        if(revision.value.value.length > 5 && revision.value.value.length < 7){
            revision.value.value = value.replace(/(\d{2})(\d{3})/, '$1.$2')
        }
        
        if(revision.value.value.length > 6){
            revision.value.value = value.replace(/(\d{3})(\d{3})/, '$1.$2')
        }
        
        if(revision.value.value.length > 7){
            revision.value.value = value.replace(/(\d{1})(\d{3})(\d{3})/, '$1.$2.$3')
        }
    }
</script>

<style>
</style>