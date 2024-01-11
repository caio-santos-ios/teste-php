<template>
    <FormRegister v-if="myRegister" @submit.prevent="registerRevision()">
        <div class="gender">
            <input value="hidraulico" type="radio" id="hidraulico" v-model="typeRevision" />
            <label for="hidraulico">Hidraulico</label>
        </div>

        <div class="gender">
            <input value="borracharia" type="radio" id="borracharia" v-model="typeRevision" />
            <label for="borracharia">Borracharia</label>
        </div>

        <div class="gender">
            <input value="eletrica" type="radio" id="eletrica" v-model="typeRevision" />
            <label for="eletrica">Eletrica</label>
        </div>

        <div class="gender">
            <input value="pintura" type="radio" id="pintura" v-model="typeRevision" />
            <label for="pintura">Pintura</label>
        </div>

        <div class="gender">
            <input value="troca de oleo" type="radio" id="troca_oleo" v-model="typeRevision" />
            <label for="troca_oleo">Troca de óleo</label>
        </div>
        
        
        <label for="value">Valor
            <input type="text" placeholder="R$ 100,00" v-model="value">
        </label>

        <label for="descrition">Descrição
            <input type="text" placeholder="Descrição do serviço" v-model="description">
        </label>

        <button type="submit" class="btn_register btn_finish" >Finalizar</button>
    </FormRegister>   
</template>

<script setup>
    import axios from 'axios';
    import FormRegister from './FormRegister.vue';
    import { useStore } from 'vuex';
    import { ref, computed } from 'vue';

    const baseURL = 'http://localhost:8000'

    const store = useStore()

    const myRegister = computed(() => store.getters.myRegister);
    const myOwner = computed(() => store.getters.myOwner);
    const myIdVehicle = computed(() => store.getters.myIdVehicle);

    const isFinish = ref(false)
    const typeRevision = ref('')
    const value = ref('')
    const description = ref('')

    const registerRevision = async () => {
        const myRevision = {
            value: value.value,
            description: description.value,
            owner_id: myOwner.value.id, 
            type_revision: typeRevision.value
        }
        console.log(myRevision)
        try {
            const response = await axios.post(`${baseURL}/revision/${myIdVehicle.value}`, myRevision)
            store.commit('setRegister')
            document.location.reload()
        } catch (error) {
            console.log(error)
        }
    }
</script>

<style>
    
</style>