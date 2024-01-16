<template>
    <FormRegister id="form_register_owner" @submit.prevent="registerOwner()">
        <label for="name">Nome cliente
            <input :disabled="loading" required type="text" placeholder="Nome do cliente" v-model="owner.name">
        </label>

        <label for="cpf">
        CPF
            <input :disabled="loading" required maxlength="11" minlength="11" type="text" @input="validatedCpf" placeholder="Cpf do cliente" v-model="owner.cpf">
        </label>

        <label for="age">Idade cliente
            <input :disabled="loading"  required maxlength="3" type="text" @input="validatedAge" placeholder="Idade do cliente" v-model="owner.age">
        </label>
        
        <label for="gender">
            Sexo
            <select :disabled="loading" required id="gender" v-model="owner.gender">
                <option value="">Selecione o sexo</option>
                <option value="masculino">Masculino</option>
                <option value="feminino">Feminino</option>
            </select>
        </label>

        <div id="footer_btns">
            <button id="btn_finish" v-if="!loading" type="submit" class="btn_register">Cadastrar</button>
            <button id="btn_finish" v-if="loading" :disabled="!isCreateBtn" type="submit" class="btn_register">
                <LoadingVue style="height: 3rem; width: 3rem;" />
            </button>
            <button id="btn_cancel" @click="closeModal" type="button">Cancelar</button>
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

    const owner = ref({
        name: '',
        cpf: '',
        age: '',
        gender: ''
    })

    const isCreateBtn = ref(true)
    const loading = ref(false)

    const closeModal = () => modal.openModal()

    const registerOwner = async () => {
        if(owner.value.age < 18) return toast.error("O cliente deve ser maior de 18")
        loading.value = true

        try {
            const response = await axios.post(`${baseURL}/owners`, owner.value)

            const returnOwner = {
                revision_vehicles: [],
                vehicles: [],
                ...response.data
            }

            list.listOwner.push(returnOwner)
            isCreateBtn.value = true
            loading.value = false
            toast.success("Cliente cadastrado");
            closeModal()
        } catch (error) {
            isCreateBtn.value = true
            loading.value = false
            console.log(error)
            if(error.response.status == 500){
                toast.error("Erro interno, tente novamente")
            }
        }
    }

    const validatedCpf = () => {
        owner.value.cpf = owner.value.cpf.replace(/[^\d]/g, '')
        owner.value.cpf = owner.value.cpf.replace(/(\d{3})(\d{3})(\d{3})(\d{2})/, '$1.$2.$3-$4')
    }

    const validatedAge = () => {    
        if (!/^\d+$/.test(owner.value.age)) {
            owner.value.age = ''
            return false;
        }
    
        owner.value.age = parseInt(owner.value.age)
    }
</script>

<style>
</style>