<template>
    <FormRegister id="form_register_owner" @submit.prevent="created()">
        <label for="type">Tipo do veiculo
            <select required @click="selectType" name="type" id="typr">
                <option value=""></option>
                <option value="carros">Carros</option>
                <option value="motos">Motos</option>
                <option value="caminhoes">Caminhões</option>
            </select>
        </label>

        <label for="brand">Marca do veiculo
            <select required @click="selectBrand" :disabled="isType" name="brand" id="brand">
                <option value=""></option>
                <option v-for="brand in listBrand" :value="brand.codigo">{{ brand.nome }}</option>
            </select>
        </label>

        <label for="model">Modelo do veiculo
            <select required @click="selectModel" :disabled="isType" name="model" id="model">
                <option value=""></option>
                <option v-for="model in listModel" :value="model.codigo">{{ model.nome }}</option>
            </select>
        </label>

        <label for="year">Ano do veiculo
            <select required @click="selectYear" :disabled="isType" name="year" id="year">
                <option value=""></option>
                <option v-for="year in listYear" :value="year.nome">{{ year.nome }}</option>
            </select>
        </label>

        <label for="plate">
            Placa do Veiculo
            <input required type="text" maxlength="7" minlength="7" name="plate" id="plate" placeholder="Placa" @input="validatedPlate" v-model="vehicle.plate">
        </label>

        <div id="footer_btns">
            <button id="btn_finish" :disabled="!isCreateBtn" v-if="!loading" type="submit" class="btn_register">Cadastrar</button>
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
    import { ref, watch } from 'vue';
    import FormRegister from './FormRegister.vue'; 
    import LoadingVue from '../Loading.vue';
    import { useModalOpen, useListOwner } from '../../store/store'
    
    const modal = useModalOpen()
    const list = useListOwner()

    const baseURL = import.meta.env.VITE_LINK_URL;    
    const apiVehicles = 'https://parallelum.com.br/fipe/api/v1'

    const vehicle = ref({
        type: '',
        brand: '',
        model: '',
        year: '',
        plate: '',
        owner_id: ''
    })

    const isType = ref(true)
    const listBrand = ref([])
    const listModel = ref([])
    const listYear = ref([])

    const isCreateBtn = ref(false)
    const loading = ref(false)

    const closeModal = () => modal.openModal()

    /* seleciona o tipo do veiculo */
    const selectType = async (e) => {
        if(!e.target.value) return
        vehicle.value.type = e.target.value
    
    try {
        const response = await axios.get(`${apiVehicles}/${e.target.value}/marcas`)
            isType.value = false
            listBrand.value = response.data
        } catch (error) {
            console.log(error)
        }
    }
 
    /* seleciona a marca do veiculo */
    const selectBrand = async (e) => {
        console.log(vehicle.value.brand)
        if(!e.target.value) return
        vehicle.value.brand = listBrand.value.find(el => el.codigo == e.target.value).nome

        try {
            const response = await axios.get(`${apiVehicles}/${vehicle.value.type}/marcas/${e.target.value}/modelos`)
            listModel.value = response.data.modelos
        } catch (error) {
            console.log(error)
        }

    }

    /* seleciona o modelo do veiculo */
    const selectModel = async (e) => {
        if(!e.target.value) return
        vehicle.value.model = listModel.value.find(el => el.codigo == e.target.value).nome
        const brand = listBrand.value.find(el => el.nome == vehicle.value.brand).codigo
        
        try {
            const response = await axios.get(`${apiVehicles}/${vehicle.value.type}/marcas/${brand}/modelos/${e.target.value}/anos`)
            listYear.value = response.data
        } catch (error) {
            console.log(error)
        }
    }

    /* seleciona o ano do veiculo */
    const selectYear = (e) => {
        if(!e.target.value) return
        vehicle.value.year = e.target.value
    }

    /* faz o regex da placa do veiculo */
    const validatedPlate = () => {
        const regex = '[A-Z]{3}[0-9][0-9A-Z][0-9]{2}'

        if(vehicle.value.plate.match(regex)){
            isCreateBtn.value = true
        }
    }

    /* finaliza cadastro */
    const created = async () => {
        const idVehicle = localStorage.getItem("idVehicle")
        const id = JSON.parse(idVehicle)
        vehicle.value.owner_id = Number(id)
        
        try {
            const response = await axios.post(`${baseURL}/vehicles`, vehicle.value)
            localStorage.removeItem('idVehicle')
            modal.openModal()
        } catch (error) {
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