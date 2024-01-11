<template>
    <FormRegister @submit.prevent="registerVehicle()">
        <label for="model">Marca do carro
            <input required type="text" placeholder="marca do carro" v-model="model">
        </label>
        
        <label for="year">Ano do carro
            <input maxlength="4" required type="text" placeholder="ano do carro" v-model="year">
        </label>

        <label for="plate">Placa do carro
            <input maxlength="7" required type="text" placeholder="placa do carro" v-model="plate">
        </label>

        <button v-if="loading" :disabled="!isCreateBtn" type="submit" class="btn_register">Cadastrar</button>
        <button v-if="!loading" :disabled="!isCreateBtn" type="submit" class="btn_register"><Loading style="height: 3rem; width: 3rem;" /></button>
    </FormRegister> 
</template>

<script setup>
    import axios from 'axios';
    import FormRegister from './FormRegister.vue';
    import { toast } from 'vue3-toastify';
    import 'vue3-toastify/dist/index.css';
    import { useStore } from 'vuex';
    import { ref, computed } from 'vue';
    import Loading from '../Loading.vue';

    const store = useStore()

    const myRegister = computed(() => store.getters.myRegister);
    const myIdVehicle = computed(() => store.getters.myIdVehicle);


    const baseURL = 'http://https://controle-veiculo-c89a5c476b29.herokuapp.com'
    const isFinish = ref(false)
    const model = ref('')
    const plate = ref('')
    const year = ref('')    

    const isCreateBtn = ref(true)
    const loading = ref(true) 

    const registerVehicle = async () => {
        isCreateBtn.value = false
        loading.value = false
        const idOwner = JSON.parse(localStorage.getItem('id'))
        const myVehicle = {
            model: model.value,
            year: year.value,
            plate: plate.value,
            owner_id: idOwner
        }
        try {
            const response = await axios.post(`${baseURL}/vehicles`, myVehicle)
            store.commit('setIdVehicle', response.data.id)
            toast.success("Carro cadastrdo")
            loading.value = (true)
            model.value = ''
            year.value = ''
            plate.value = ''

        } catch (error) {
            loading.value = (true)
            console.log(error)
        }
    }
</script>

<style>
  
</style>