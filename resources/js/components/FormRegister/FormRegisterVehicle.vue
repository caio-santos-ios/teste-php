<template>
    <FormRegister @submit.prevent="registerVehicle()">
        <label for="model">Marca do carro
            <input required type="text" placeholder="marca do carro" v-model="model">
        </label>
        
        <label for="year">Ano do carro
            <input maxlength="6" required type="text" placeholder="ano do carro" v-model="year">
        </label>

        <label for="plate">Placa do carro
            <input maxlength="7" required type="text" placeholder="placa do carro" v-model="plate">
        </label>

        <button type="submit" class="btn_register" :disabled="isFinish">Cadastrar</button>
    </FormRegister> 
</template>

<script setup>
    import axios from 'axios';
    import FormRegister from './FormRegister.vue';
    import { toast } from 'vue3-toastify';
    import 'vue3-toastify/dist/index.css';
    import { useStore } from 'vuex';
    import { ref, computed } from 'vue';

    const store = useStore()

    const myRegister = computed(() => store.getters.myRegister);
    const myIdVehicle = computed(() => store.getters.myIdVehicle);


    const baseURL = 'http://localhost:8000'
    const isFinish = ref(false)
    const model = ref('')
    const plate = ref('')
    const year = ref('')    

    const registerVehicle = async () => {
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

            model.value = ''
            year.value = ''
            plate.value = ''

        } catch (error) {
            console.log(error)
        }
    }

</script>