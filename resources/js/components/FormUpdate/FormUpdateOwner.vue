<template>
    <FormRegister id="form_register_owner" @submit.prevent="updateOwner()">
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
    
    const props = defineProps(['owner'])

    const modal = useModalOpen()
    const list = useListOwner()
    const listBrand = ref([])

    const apiUrl = import.meta.env.VITE_LINK_API;

    const owner = ref({
        name: props.owner.name,
        cpf: props.owner.cpf,
        age: props.owner.age,
        gender: props.owner.gender
    })

    const isCreateBtn = ref(true)
    const loading = ref(false)

    /* lista as marcas dos veiculos */
    onMounted( async () => {
        try {
            const response = await axios.get('https://parallelum.com.br/fipe/api/v1/carros') 
            console.log(response)
        } catch (error) {
            
        }
    })
    const listBrandVehicles = () => {}

    const closeModal = () => modal.openModal()

    const updateOwner = async () => {
        if(owner.value.age < 18) return toast.error("O cliente deve ser maior de 18")
               
        modal.openModal()

        // loading.value = true
        /*
        try {
            const response = await axios.post(`${apiUrl}/owners`, owner.value)
            list.listOwner.push(response.data)
            isCreateBtn.value = true
            loading.value = false
            toast.success("Cliente cadastrado");
            closeModal()
        } catch (error) {
            isCreateBtn.value = true
            loading.value = false
            console.log(error)
        }
        */
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