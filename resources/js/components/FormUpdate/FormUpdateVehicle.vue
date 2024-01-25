<template>
    <FormRegister id="form_register_owner" @submit.prevent="updateVehicle()">
        <h4>Editar Veiculo</h4>
        <label for="type">Tipo do veiculo
            <select required @click="(e) => newVehicle.type = e.target.value">
                <option :selected="item == vehicles.type" v-for="item in type_revision">{{ item }}</option>
            </select>
        </label>

        <label for="brand">
            Marca
            <select @click="selectBrand">
                <option v-if="listBrand.length == 0">{{ vehicles.brand }}</option>
                <option :selected="vehicles.brand == brand.nome" v-for="brand in listBrand" :value="brand.codigo">{{ brand.nome }}</option>
            </select>
        </label>

        <label for="model">
            Modelo
            <select @click="selectModel">
                <option v-if="listModel.length == 0">{{ vehicles.model }}</option>
                <option v-for="model in listModel" :value="model.codigo">{{ model.nome }}</option>
            </select>
        </label>

        <label for="year">
            Ano
            <select >

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
    import { watch, onMounted } from 'vue';

    const type_revision = ref(['carros', 'motos', 'caminhoes'])
    const listBrand = ref([])
    const listModel = ref([])
    const listYear = ref([])

    const modal = useModalOpen()

    const baseURL = import.meta.env.VITE_LINK_URL
    const apiVehicles = 'https://parallelum.com.br/fipe/api/v1'

    let vehicleUpdate = localStorage.getItem('vehicleUpdate')
    vehicleUpdate = JSON.parse(vehicleUpdate)

    const vehicles = ref({
       type: vehicleUpdate.type,
       brand: vehicleUpdate.brand,
       model: vehicleUpdate.model
    })

    const newVehicle = ref({
        type: '',
        brand: '',
        model: ''
    })

    const isCreateBtn = ref(true)
    const loading = ref(false)

    /* fecha o modal */
    const closeModal = () => modal.openModal()

    /* ao mudar o tipo do veiculo, faz uma nova busca pelas marcas */
    watch(newVehicle.value, async () => {
        if(vehicles.value.type != newVehicle.value.type){
            vehicles.value.type = newVehicle.value.type
            vehicles.value.model = ''

            try {
                const response = await axios.get(`${apiVehicles}/${newVehicle.value.type}/marcas`)
                listBrand.value = response.data
            } catch (error) {
                console.log(error)
            }
        }
    })

    /* carrega todas as marcas */
    onMounted( async () => {
        try {
            const response = await axios.get(`${apiVehicles}/${vehicles.value.type}/marcas`)
            response.data.map(el => {
                listBrand.value.push(el)
            })
        } catch (error) {
            console.log(error)
        }
    })

    /* seleciona a nova marca */
    const selectBrand = async (e) => {
        try {
            const response = await axios.get(`${apiVehicles}/${vehicles.value.type}/marcas/${e.target.value}/modelos`)
            listModel.value = response.data.modelos
        } catch (error) {
            console.log(error)
        }
    }

    /* seleciona o novo modelo */
    const selectModel = async (e) => {
        console.log(e.target.value)
        try {
            const response = await axios.get(`${apiVehicles}/${newVehicle.value.type}/marcas/${newVehicle.value.brand}/modelos/${e.target.value}/anos`)
            console.log(response.data)
            //listModel.value = response.data.modelos
        } catch (error) {
            console.log(error)
        }
    }

    /* atualiza o cliente */
    const updateVehicle = async () => {
        // loading.value = true

        console.log(newVehicle.value)
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