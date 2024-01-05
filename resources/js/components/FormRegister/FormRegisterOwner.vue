<template>
     <FormRegister v-if="!myRegister" @submit.prevent="registerOwner()">
        <label for="name">Nome cliente
            <input type="text" placeholder="Nome do cliente" v-model="name">
        </label>
    
        <label for="cpf">
            CPF
            <input required minlength="11" @input="verifyClient()" type="text" placeholder="Cpf do cliente" v-model="cpf">
        </label>
        
        <label for="age">Idade cliente
            <input required @input="verifyAge()" type="number" placeholder="Idade do cliente" v-model="age">
        </label>
        
        <div class="gender">
            <input value="masculino" type="radio" id="m" v-model="gender" />
            <label required for="m">Masculino</label>
        </div>
        <div class="gender">
            <input value="feminino" type="radio" id="f" v-model="gender" />
            <label required for="f">Feminino</label>
        </div>
        
        <button type="submit" class="btn_register" :disabled="btnSubmit">Cadastrar</button>
    </FormRegister> 
</template>

<script setup>
    import axios from 'axios';
    import FormRegister from './FormRegister.vue';
    import { toast } from 'vue3-toastify';
    import 'vue3-toastify/dist/index.css';
    import { useStore } from 'vuex';
    import { ref, computed, watchEffect } from 'vue';

    const store = useStore()

    const myRegister = computed(() => store.getters.myRegister);

    const baseURL = 'http://localhost:8000'

    const btnSubmit = ref(true)
    const name = ref('')
    const cpf = ref('')
    const age = ref('')
    const gender = ref('')
    const validatedCpf = ref(false)
    const validatedAge = ref(false)

    const registerOwner = async () => {
        const client = {
            name: name.value, cpf: cpf.value, age: age.value, gender: gender.value
        }
       
        try {
            const response = await axios.post(`${baseURL}/owners`, client)
            store.commit('selectedOwner', response.data)
            store.commit('setRegister')
            
            name.value = ''
            cpf.value = ''
            age.value = ''
            gender.value = ''

            toast("Usuário já esta cadastrdo", { theme: 'dark' }, { autoClose: 3000, });
        } catch (error) {
            console.log(error)
        }
    }

    const verifyClient = () => {
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
        const newAge = String(age.value)

        if(age.value >= 18) return validatedAge.value = true

        if(String(age.value).length === 2 && age.value < 18) {
            age.value = ''
            return toast("Idade minima é  18", { theme: 'dark' }, { autoClose: 3000, })
        }
    }

    watchEffect(() => {
        if(gender.value && name.value && age.value && cpf.value){
            if(validatedCpf.value && validatedAge.value) return btnSubmit.value = false
        }
    })

</script>