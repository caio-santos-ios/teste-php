<template>
    <FormRegister id="form_register_owner" @submit.prevent="updateVehicle()">
        <h4>Editar Cliente</h4>
        <label for="type">Tipo do veiculo
            <select required @click="selectType" name="type" id="typr">
                <option value=""></option>
                <option value="carros">Carros</option>
                <option value="motos">Motos</option>
                <option value="caminhoes">Caminhões</option>
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
    import { ref } from 'vue';
    import FormRegister from '../FormRegister/FormRegister.vue'; 
    import LoadingVue from '../Loading.vue';
    import { useModalOpen } from '../../store/store';
    import { onMounted } from  'vue'
    
    const modal = useModalOpen()

    const baseURL = import.meta.env.VITE_LINK_URL;    

    let idVehicle = localStorage.getItem('idVehicle')
    idVehicle = JSON.parse(idVehicle)

    const vehicles = ref({
       
    })

    const isCreateBtn = ref(true)
    const loading = ref(false)

    /* carrega as informações do veiculo selecionado */
    onMounted(() => {
        console.log("Aqui")
    })

    /* fecha o modal */
    const closeModal = () => modal.openModal()

    /* atualiza o cliente */
    const updateVehicle = async () => {
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