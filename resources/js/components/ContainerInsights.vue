<template>
    <div class="insights">
        <!--
            <Graphic />
            <FormRegisterOwner />
            <FormRegisterVehicle />
            <FormRegisterRevision />
        -->
    </div>
</template>

<script setup>
    import FormRegisterOwner from './FormRegister/FormRegisterOwner.vue';
    import FormRegisterRevision from './FormRegister/FormRegisterRevision.vue';
    import FormRegisterVehicle from './FormRegister/FormRegisterVehicle.vue';
    import Graphic from './Graphic.vue';
    import axios from 'axios';
    import { onMounted, computed, ref } from 'vue';
    import { useStore } from 'vuex';

    const store = useStore()

    const myRegister = computed(() => store.getters.myRegister);

    const baseURL = 'http://localhost:8000/revisions';

    const list = ref('')

    onMounted(async () => {
        try {
            const response = await axios.get(baseURL)
            list.value = response.data
            store.commit('setRevision', response.data.data)
        } catch (error) {
            console.log(error)
        }
    })
</script>

<style>   
    .insights {
        height: 29rem;
        max-width: 40rem; 
        width: 100%;
    }
    
</style>