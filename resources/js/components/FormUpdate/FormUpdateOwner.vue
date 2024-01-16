<template>
    <FormRegister id="form_register_owner" @submit.prevent="updateOwner()">
        <h4>Editar Cliente</h4>
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
            <button id="btn_finish" v-if="!loading" type="submit" class="btn_register">Salvar</button>
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
    import { ref, onMounted } from 'vue';
    import FormRegister from '../FormRegister/FormRegister.vue'; 
    import LoadingVue from '../Loading.vue';
    import { useModalOpen, useListOwner } from '../../store/store'
    import { defineProps } from 'vue';
    
    const modal = useModalOpen()
    const list = useListOwner()
    const listBrand = ref([])

    const baseURL = import.meta.env.VITE_LINK_URL;    

    let ownerUpdate = localStorage.getItem('ownerUpdate')
    ownerUpdate = JSON.parse(ownerUpdate)

    const owner = ref({
        name: ownerUpdate.name,
        cpf: ownerUpdate.cpf,
        age: ownerUpdate.age,
        gender: ownerUpdate.gender
    })

    const isCreateBtn = ref(true)
    const loading = ref(false)

    const listBrandVehicles = () => {}

    /* fecha o modal */
    const closeModal = () => modal.openModal()

    /* atualiza o cliente */
    const updateOwner = async () => {
        loading.value = true

        if(owner.value.age < 18) return toast.error("O cliente deve ser maior de 18")
     
        try {
            await axios.patch(`${baseURL}/owners/${ownerUpdate.id}`, owner.value)
            localStorage.removeItem('ownerUpdate')

            isCreateBtn.value = true
            loading.value = false
            closeModal()
        } catch (error) {
            isCreateBtn.value = true
            loading.value = false
            console.log(error)
        }
    }

    /* faz o regex do cpf */
    const validatedCpf = () => {
        owner.value.cpf = owner.value.cpf.replace(/[^\d]/g, '')
        owner.value.cpf = owner.value.cpf.replace(/(\d{3})(\d{3})(\d{3})(\d{2})/, '$1.$2.$3-$4')
    }
    
    /* verifica se tem letra no input */
    const validatedAge = () => {    
        if (!/^\d+$/.test(owner.value.age)) {
            owner.value.age = ''
            return false;
        }
    
        owner.value.age = parseInt(owner.value.age)
    }
</script>

<style>
    #form_register_owner {
        display: flex;
        background-color: white;
        height: 30rem;
        width: 100%;
        max-width: 50rem;
        border: none;
        border-radius: 1.5rem;
    }

    #gender {
        display: flex;

        > p {
            width: 100%;
        }
        
        > div {
            display: flex;
            justify-content: center;
            align-items: center;
            width: 5rem;
            height: 2rem;
        }
    }
</style>