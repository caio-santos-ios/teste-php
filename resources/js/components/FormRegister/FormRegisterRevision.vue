<template>
    <FormRegister id="form_register_owner" @submit.prevent="registerRevision()">
        <label for="type">Tipo da Revisão
            <select required @click="selectType" name="type" id="type">
                <option value=""></option>
                <option v-for="(item, index) in tablePrice" :key="index" :value="item.type">{{ item.type }}</option>
            </select>
        </label>

        <label for="value">
            Valor
            <button type="button" @click="enableValue">Editar valor</button>
            <input :disabled="!editValue || !revision.type_revision == 'Outros'" required maxlength="11" minlength="11" type="text" @input="validatedValue" placeholder="R$ 0" v-model="revision.value">
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
    import { ref, watch } from 'vue';
    import FormRegister from './FormRegister.vue'; 
    import LoadingVue from '../Loading.vue';
    import { useModalOpen, useListOwner } from '../../store/store'
    
    const modal = useModalOpen()
    const list = useListOwner()

    const baseURL = import.meta.env.VITE_LINK_URL;    

    const isCreateBtn = ref(true)
    const loading = ref(false)
    const editValue = ref(false)
    const tablePrice = ref([
        {
            type: 'Pintura',
            price: 100
        },
        {
            type: 'Troca de oleo',
            price: 60
        },
        {
            type: 'Troca de um pneu',
            price: 500
        },
        {
            type: 'Manutenção nos freios',
            price: 600
        },
        {
            type: 'Outros',
            price: 0
        }
    ])

    const idVehicle = localStorage.getItem('idVehicle')
    const idOwner = localStorage.getItem('idOwner')

    const revision = ref({
        type_revision: '',
        value: '',
        description: '',
        owner_id: idOwner
    })

    const enableValue = () => editValue.value = !editValue.value

    const closeModal = () => modal.openModal()

    /* seleciona o tipo da revisão */
    const selectType = async (e) => {
        if(!revision.value.value) {
            if(!e.target.value) return
            revision.value.type_revision = e.target.value
            revision.value.value = tablePrice.value.find(el => el.type == e.target.value).price
        }

        if(!e.target.value) return
        revision.value.type_revision = e.target.value
        revision.value.value = tablePrice.value.find(el => el.type == e.target.value).price
    }

    /* criação da revisão */
    const registerRevision = async () => {
        if(revision.value.value == 0) return toast.error('Coloque um valor na revisão')
        loading.value = true
        isCreateBtn.value = false   
        
        const findOwner = list.listOwner.findIndex(el => el.id == idOwner) 
        // return console.log(list.listOwner[findOwner].revision_vehicles)

        try {
            const response = await axios.post(`${baseURL}/revisions/${idVehicle}`, revision.value)
            list.listOwner[findOwner].revision_vehicles = [ response.data[0], ...list.listOwner[findOwner].revision_vehicles ]
            console.log(list.listOwner[findOwner].revision_vehicles)
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
        revision.value.value = revision.value.value.replace(/(\d{1})(\d{3})(\d{3})(\d{2})/, '$1.$2.$3,$4')
    }
</script>

<style>
</style>