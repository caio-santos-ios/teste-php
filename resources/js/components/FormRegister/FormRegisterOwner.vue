<template>
     <form class="form_register_owner" @submit.prevent="registerOwner()">
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
        
        <div class="gender">
            <input value="masculino" type="radio" id="m" v-model="gender" />
            <label required for="m">Masculino</label>
        </div>
        <div class="gender">
            <input value="feminino" type="radio" id="f" v-model="gender" />
            <label required for="f">Feminino</label>
        </div>
        
        <button type="submit" class="btn_register" >Cadastrar</button>
    </form> 
</template>

<script setup>
    import axios from 'axios';
    import { toast } from 'vue3-toastify';
    import 'vue3-toastify/dist/index.css';
    import { ref, watchEffect } from 'vue';
    import { useStore } from 'vuex';
    
    const store = useStore()

    const baseURL = 'http://localhost:8000'

    const btnSubmit = ref(true)
    const name = ref('')
    const cpf = ref('')
    const age = ref('')
    const gender = ref('')
    const validatedCpf = ref(false)
    const validatedAge = ref(false)
    

    
    const registerOwner = async () => {
        const owner = {
            name: name.value, cpf: cpf.value, age: age.value, gender: gender.value
        }
        
        try {
            const response = await axios.post(`${baseURL}/owners`, owner)
            
            name.value = ''
            cpf.value = ''
            age.value = ''
            gender.value = ''
            if(response.status === 200){
                toast.error("Cliente já está cadastrado, faça uma busca");
            }else{
                toast.success("Cliente cadastrado");
                store.commit('selectedOwner', response.data)
            }
        } catch (error) {
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
    console.log(store.getters.myOwners)
    
</script>

<style>
    .form_register_owner {
        padding: 1rem;
        border-radius: 1.5rem;
        display: flex;
        flex-flow: wrap;
        gap: 1rem;
        background-color: white;
        width: 47rem;
        height: 20rem;
    }
    
    label {
        display: flex;
        flex-flow: column;
        width: 48%;
        height: 5rem;
        
        > input {
            height: 3rem;
            outline: none;
            border: 1px solid #cbd5e1;;
            padding: 0.5rem;
            border-radius: 0.5rem;
        }
    }

    .gender {
        display: flex;
        flex-flow: column;
        justify-content: center;
    }

    .btn_register, .btn_finish {
        padding: 1rem;
        border: none;
        border-radius: 1rem;
        background-color: #2929e0;
        color: white;
        font-size: 1rem;
        width: 48%;
        height: 4rem;
        margin: 0 auto;
    }

    .btn_finish {
        height: 4rem;
        width: 60%;
    }
    
    .btn_register:disabled {
        background-color: #a2a2f4;
    }
</style>