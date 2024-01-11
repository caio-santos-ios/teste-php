<template>
    <div class="container_filter">
        <div class="filter">
            <button value="" @click="addFilter" class="btn_filter">
                <i class="fa-solid fa-filter-circle-xmark"></i>
            </button>
            <button value="name" @click="addFilter" class="btn_filter">
                Nome cliente
                <i class="fa-solid fa-arrow-up-wide-short"></i>
            </button>
            <button value="model" @click="addFilter" class="btn_filter">
                Marca
                <i class="fa-brands fa-buromobelexperte"></i>
            </button>
            <button value="homen-mulher" @click="addFilter" class="btn_filter">
                Homem/Mulher
                <i class="fa-solid fa-user-check"></i>
            </button>
        </div>
    </div>
    <ul class="list_vehicle">
        <li v-if="!loading" class="header_title">
            <div>
                <p>Proprietario</p>
                <p>Entrada</p>
                <p>Marca</p>
                <p>Placa</p>    
            </div>
        </li>
        <li v-if="!loading" class="item_report" :id="item.id" v-for="item in paginatedListReport" :key="item.id">
            <div class="title_report_item">
                <p>{{ item.owner.name }}</p>
                <p>{{ formateDate(item.created_at.slice(0, 10)) }}</p>
                <p>{{ item.model }}</p>
                <p>{{ item.plate }}</p>
            </div>
            <div id="report_icons">
                <i v-if="isFinish && selectedItem == item.id" @click="finished" class="fa-solid fa-check"></i>
                <i v-if="isCancel && selectedItem == item.id" @click="canceled" class="fa-solid fa-xmark"></i>
                <i v-if="isCreate && !isFinish || selectedItem != item.id" :id="item.id" @click="updated" class="atualizar fa-solid fa-pen-to-square"></i>
                <i v-if="isUpdate && !isFinish || selectedItem != item.id" :id="item.id" @click="created" class="criar fa-regular fa-square-plus"></i>
                <i v-if="isDeleted || selectedItem != item.id" :id="item.id" @click="deleted" class="fa-regular fa-trash-can"></i>
            </div>

            <form :id="item.id" class="form_report_update">
                <input disabled type="text" :value="item.owner.name">
                <input disabled type="text" :value="formateDate(item.created_at.slice(0, 10))">
                <input :disabled="!isUpdate" type="text" :value="isUpdate ? item.model : inputModel" @input="inputModel = $event.target.value">
                <input :disabled="!isUpdate" type="text" :value="isUpdate ? item.plate : inputPlate" @input="inputPlate = $event.target.value">
            </form>

            <form :id="item.id" class="form_report_create">
                <input required v-model="inputDescription" placeholder="Descrição da revisão" type="text">
                <input required v-model="inputTypeRevision" placeholder="Tipo da revisão" type="text">
                <input required v-model="inputValue" placeholder="Valor cobrado" type="number">
            </form>
        </li>
        <Loading style="height: 80%; width: 80%;" v-if="loading"/>
        <h2 v-if="!loading && listSelected.length === 0">Sem Veiculos</h2>
    </ul>
    <div class="footer_page_report">
        <button @click="prevPage" :disabled="currentPage === 1">Anterior</button>
        <span>Página {{ currentPage }} de {{ totalPages }}</span>
        <button @click="nextPage" :disabled="currentPage === totalPages">Próxima</button>
    </div>
</template>

<script setup>
    import axios from 'axios';
    import { ref, onMounted, computed } from 'vue';
    import { createRevision, update, destroy, formateDate, openItem, closeItem } from './Report.vue';
    import { toast } from 'vue3-toastify';
    import 'vue3-toastify/dist/index.css';
    import Loading from './Loading.vue';

    const loading = ref(true)

    const baseURL = 'http://localhost:8000';    
    const listSelected = ref([]) 
    const filter = ref('')
    const itemsPerPage = 5
    const currentPage = ref(1) 
    
    const selectedItem = ref('')
    const isUpdate = ref(true)
    const isCreate = ref(true)
    const isDeleted = ref(true)
    const isCancel = ref(false)
    const isFinish = ref(false)

    const inputModel = ref('')
    const inputPlate = ref('')

    const inputIdVehicle = ref('')
    const inputIdOwner = ref('')
    const inputDescription = ref('')
    const inputTypeRevision = ref('')
    const inputValue = ref('')

    const addFilter = async (e) => {
        const typeFilter = e.target.value
        
        if(typeFilter === undefined) {
            const res = await newRequest()
            listSelected.value = res
        }

        if(typeFilter === 'name'){
            filter.value = typeFilter
            orderName(listSelected.value)   
        } 

        if(typeFilter === 'model'){

            orderModel(listSelected.value)
        }
        
        if(typeFilter === 'homen-mulher'){
            const res = await newRequest()
            const h = res.filter(el => el.owner.gender === 'masculino')
            const m = res.filter(el => el.owner.gender === 'feminino')
            
            if(h.length > m.length){
                listSelected.value = h
            }else {
                listSelected.value = m
            }
        }
    }

    const paginatedListReport = computed(() => {
        const startPage = ( currentPage.value - 1 ) * itemsPerPage
        const endPage = startPage + itemsPerPage
        return listSelected.value.slice(startPage, endPage)
    })

    const totalPages = computed(() => Math.ceil(listSelected.value.length / itemsPerPage))

    const prevPage = () => {
        if(currentPage.value > 1){
            currentPage.value--
        }
    }

    const nextPage = () => {
        if(currentPage.value < totalPages.value){
            currentPage.value++
        }
    }

    const orderName = (list) => {
        list.sort((a, b) => {
            const nameA = a.owner.name.toLocaleUpperCase()
            const nameB = b.owner.name.toLocaleUpperCase()

            if (nameA > nameB) { return 1}

            if (nameA < nameB) { return -1}

            return 0;
        })
    }

    const orderModel = (list) => {
        list.sort((a, b) => {
            const nameA = a.model.toLocaleUpperCase()
            const nameB = b.model.toLocaleUpperCase()

            if (nameA > nameB) { return 1}

            if (nameA < nameB) { return -1}

            return 0;
        })
    }

    const newRequest = async () => {
        try {
            const response = await axios.get(`${baseURL}/vehicles`)
            return response.data
        } catch (error) {
            console.log(error)
        }
    }

    onMounted(async () => {
        try {
            const response = await axios.get(`${baseURL}/vehicles`)
            listSelected.value = response.data 
            const h = response.data.filter(el => el.owner.gender === 'masculino')
            const m = response.data.filter(el => el.owner.gender === 'feminino')
            loading.value = false
            sumPercentage(response.data.length, h.length)
        } catch (error) {
            loading.value = false
            console.log(error)
        }
    })

    const finished = async () => {
        if(isCreate.value){   
            if(!inputDescription.value || !inputTypeRevision.value || !inputValue.value) return toast.error("Preencha todos os campos")

            const revision = {
                type_revision: inputTypeRevision.value,
                description: inputDescription.value,
                value: inputValue.value,
                vehicle_id: inputIdVehicle.value,
                owner_id: inputIdOwner.value,
            }    
            toast.success("Revisão criada")
            await createRevision(inputIdVehicle.value, revision)
        } else {
            const indexVehicle = listSelected.value.findIndex((el, i) => el.id == inputIdVehicle.value)

            if(!inputPlate.value && inputModel.value){
                const response = await update(inputIdVehicle.value, 'vehicles', {model: inputModel.value})
                listSelected.value[indexVehicle].model = response.data.model
            } else if(!inputModel.value && inputPlate.value) {
                const response = await update(inputIdVehicle.value, 'vehicles', {plate: inputPlate.value})
                listSelected.value[indexVehicle].plate = response.data.plate
                return
            } else if(inputModel.value && inputPlate.value) {
                const response = await update(inputIdVehicle.value, 'vehicles', { plate: inputPlate.value, model: inputModel.value })
                listSelected.value[indexVehicle].model = response.data.model
                listSelected.value[indexVehicle].plate = response.data.plate
            }
        }
        
        inputIdOwner.value = ''
        inputIdVehicle.value = ''
        selectedItem.value = ''
        isFinish.value = false
        isCreate.value = true
        isUpdate.value = true
        closeItem()
    }

    const canceled = (e) => {
        closeItem()
        isFinish.value = false
        isCancel.value = false
        isCreate.value = true
        isUpdate.value = true
        isDeleted.value = true
        selectedItem.value = ''
        inputIdOwner.value = ''
        inputIdVehicle.value = ''
    }

    const created = (e) => {
        const vehicle = listSelected.value.find(el => el.id == e.target.id)

        inputIdOwner.value = vehicle.owner.id
        inputIdVehicle.value = vehicle.id

        if(selectedItem.value) {
            closeItem()
            if(selectedItem.value == e.target.id){
                openItem(e.target.id, 'create')
                isFinish.value = true
                isCancel.value = true
                isUpdate.value = false
                isCreate.value = true
                isDeleted.value = false
                return
            }

            selectedItem.value = e.target.id
            isCreate.value = true
            isUpdate.value = true
            isDeleted.value = false
            isCancel.value = true
            isFinish.value = true
            openItem(e.target.id, 'create')
            return
        }

        selectedItem.value = e.target.id
        openItem(e.target.id, 'create')
        isFinish.value = true
        isCancel.value = true
        isUpdate.value = false
        isCreate.value = true
        isDeleted.value = false
    }

    const updated = (e) => { 
        inputIdVehicle.value = e.target.id

        if(selectedItem.value) {
            closeItem()
            if(selectedItem.value == e.target.id){
                openItem(e.target.id, 'update')
                isFinish.value = true
                isCancel.value = true
                isUpdate.value = true
                isCreate.value = false
                isDeleted.value = false
                return
            }

            selectedItem.value = e.target.id
            isCreate.value = true
            isUpdate.value = true
            isDeleted.value = false
            isCancel.value = true
            isFinish.value = true
            openItem(e.target.id, 'update')
            return
        }

        selectedItem.value = e.target.id
        openItem(e.target.id, 'update') 
        isFinish.value = true
        isCancel.value = true
        isUpdate.value = true
        isCreate.value = false
        isDeleted.value = false
    }

    const deleted = async (e) => {
        if(selectedItem.value) {
            closeItem()
            isFinish.value = false
            isCancel.value = false
            isCreate.value = true
            isUpdate.value = true
            isDeleted.value = true
            confirm("Deseja deletar este veiculo?")
            return
        }
        
        const result = confirm("Deseja deletar este veiculo?")
        
        if(result){ 
            const list = listSelected.value.filter(el => el.id != e.target.id)
            listSelected.value = list

            await destroy(e.target.id, 'vehicles')
        }
    }
</script>

<style>
    .list_vehicle {
        display: flex;
        flex-flow: column;
        align-items: center;
        border-radius: 1.5rem;
        padding: 1.5rem;
        position: relative;
        margin: 0 auto;
        height: 100%;
        width: 100%;
        gap: 1rem;
    }

    .header_title {
        width: 100%;
        gap: 2rem;
        
        > div {
            display: flex;
            justify-content: space-between;
            width: 100%;
            max-width: 40rem;

            > p {
                width: 5.5rem;
            }
        }
    }

    @media (min-width: 915px) {
        .form_create_revision_open {
            flex-flow: row;
        }
    }

</style>