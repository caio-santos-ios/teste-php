<template>
     <FormRegister id="form_register_owner" @submit.prevent="registerOwner()">
        <label for="name">Nome cliente
            <input required type="text" placeholder="Nome do cliente" v-model="name">
        </label>
    
        <label for="cpf">
            CPF
            <input required maxlength="11" @input="verifyClient()" type="text" placeholder="Cpf do cliente" v-model="cpf">
        </label>
        
        <label for="age">Idade cliente
            <input maxlength="3" required @input="verifyAge()" type="text" placeholder="Idade do cliente" v-model="age">
        </label>
        
        <label id="gender">
            <p>Sexo</p>
            <div class="gender">
                <input value="masculino" type="radio" id="m" v-model="gender" />
                <label required for="m">Masculino</label>
            </div>
            <div class="gender">
                <input value="feminino" type="radio" id="f" v-model="gender" />
                <label required for="f">Feminino</label>
            </div>
        </label>
        
        <button v-if="!loading" :disabled="!isCreateBtn" type="submit" class="btn_register">Cadastrar</button>
        <button v-if="loading" :disabled="!isCreateBtn" type="submit" class="btn_register"><LoadingVue style="height: 3rem; width: 3rem;" /></button>

    </FormRegister> 
</template>

<script setup>
    import axios from 'axios';
    import { toast } from 'vue3-toastify';
    import 'vue3-toastify/dist/index.css';
    import { ref, watchEffect } from 'vue';
    import { useStore } from 'vuex';
    import FormRegister from './FormRegister.vue'; 
    import LoadingVue from '../Loading.vue';
    
    const store = useStore()

    const baseURL = 'http://https://controle-veiculo-c89a5c476b29.herokuapp.com'

    const btnSubmit = ref(true)
    const name = ref('')
    const cpf = ref('')
    const age = ref('')
    const gender = ref('')
    const validatedCpf = ref(false)
    const validatedAge = ref(false)
    const isCreateBtn = ref(true)
    const loading = ref(false)

    const registerOwner = async () => {
        isCreateBtn.value = false
        loading.value = true

        const owner = {
            name: name.value, cpf: cpf.value, age: age.value, gender: gender.value
        }
        
        if(!gender.value) return toast.error("Marque o genero")

        try {
            const response = await axios.post(`${baseURL}/owners`, owner)
            name.value = ''
            cpf.value = ''
            age.value = ''
            gender.value = ''
            isCreateBtn.value = true
            loading.value = false

            if(response.status === 200){
                loading.value = false
                isCreateBtn.value = true
                toast.error("Cliente já está cadastrado, faça uma busca");
            }else{
                toast.success("Cliente cadastrado");
                loading.value = false
                isCreateBtn.value = true
                store.commit('selectedOwner', response.data)
            }
        } catch (error) {
            isCreateBtn.value = true
            loading.value = false
            toast.error("Desculpe, tente criar novamente")
            console.log(error)
        }
    }

    const verifyClient = () => {
        const newCpf = Number(cpf.value)
        
        if(!newCpf && newCpf > 0) cpf.value = ''
        
        if(cpf.value.length === 11){
            const newCpf = cpf.value = `${cpf.value.slice(0, 3)}.${cpf.value.slice(3, 6)}.${cpf.value.slice(6, 9)}-${cpf.value.slice(9, 11)}` 
            validatedCpf.value = true
            return cpf.value = newCpf
        }

        if(cpf.value.length <= 14){
            validatedCpf.value = true
        }
    }

    const verifyAge = () => {
        const newAge = Number(age.value)

        if(!newAge) age.value = ''
        
        if(newAge > 130) age.value = ''
        
        if(age.value >= 18) return validatedAge.value = true

        if(String(age.value).length === 2 && age.value < 18) {
            age.value = ''
            return toast.error("Idade minima é  18")
        }

    }

    watchEffect(() => {
        if(gender.value && name.value && age.value && cpf.value){
            if(validatedCpf.value && validatedAge.value) return btnSubmit.value = false
        }
    })
    
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