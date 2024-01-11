<template>
    <div class="container_filter">
        <div class="filter">
            <button value="" @click="addFilter" class="btn_filter">
                <i class="fa-solid fa-filter-circle-xmark"></i>
            </button>
            <button value="model" @click="addFilter" class="btn_filter">
                Marca
                <i class="fa-brands fa-buromobelexperte"></i>
            </button>
            <button value="owner" @click="addFilter" class="btn_filter">
                Proprietario
                <i class="fa-solid fa-child"></i>
            </button>
            <button value="media-tempo" @click="addFilter" class="btn_filter">
                Media de tempo
                <i class="fa-regular fa-clock"></i>
            </button>
        </div>
    </div>
    <ul class="list_owner">
        <li v-if="!loading" class="header_title">
            <div>
                <p>Proprietario</p>
                <p>Entrada</p>
                <p>Marca</p>
                <p>Valor</p>    
            </div>
        </li>   
        <li class="item_report" :id="item.id" v-for="item in paginatedListReport" :key="item.id">
            <div class="title_report_item">
                <p>{{ item.owner.name }}</p>
                <p>{{ formateDate(item.created_at.slice(0, 10)) }}</p>
                <p>{{ item.vehicle.model }}</p>
                <p>R$ {{ item.value }}</p> 
            </div>
            <div id="report_icons">
                <i v-if="isFinish || selectedItem == item.id" :id="item.id" @click="finished" class="fa-solid fa-check"></i>
                <i v-if="isCancel || selectedItem == item.id" @click="canceled" class="fa-solid fa-xmark"></i>
                <i v-if="isUpdate && !isFinish || selectedItem != item.id" :id="item.id" @click="finishedRevision" class="fa-regular fa-square-check"></i>
                <i v-if="isUpdate && !isFinish || selectedItem != item.id" :id="item.id" @click="updated" class="fa-solid fa-pen-to-square"></i>
            </div>
            <form :id="item.id" class="form_report_update">
                <input disabled type="text" :value="item.owner.name">
                <input disabled type="text" :value="formateDate(item.created_at.slice(0, 10))">
                <input disabled type="text" :value="item.vehicle.model">
                <input :disabled="isUpdate" type="text" :value="!isUpdate ? item.type_revision : inputType" @input="inputType = $event.target.value">
                <input :disabled="isUpdate" type="text" :value="!isUpdate ? item.description : inputDescription" @input="inputDescription = $event.target.value">
                <input :disabled="isUpdate" type="text" :value="!isUpdate ? item.value : inputValue" @input="inputValue = $event.target.value">

            </form>
        </li>
        <Loading style="height: 10rem; width: 10rem;" v-if="loading"/>
        <h2 v-if="!loading && listSelected.length === 0">Sem Revisões</h2>
    </ul>
    <div class="footer_page_report">
        <button @click="prevPage" :disabled="currentPage === 1">Anterior</button>
        <span>Página {{ currentPage }} de {{     totalPages }}</span>
        <button @click="nextPage" :disabled="currentPage === totalPages">Próxima</button>
    </div>
</template>

<script setup>
    import axios from 'axios';
    import { ref, onMounted, computed } from 'vue';
    import { update, formateDate, openItem, closeItem } from './Report.vue';
    import { toast } from 'vue3-toastify';
    import 'vue3-toastify/dist/index.css';
    import Loading from './Loading.vue';

    const loading = ref(true)

    const baseURL = 'https://controle-veiculo-c89a5c476b29.herokuapp.com';    
    const listSelected = ref([]) 
    const myFilter = ref('')
    const itemsPerPage = 7
    const currentPage = ref(1) 
    const timeAverage = ref(0)

    const selectedItem = ref('')
    const isUpdate = ref(true)
    const isCancel = ref(false)
    const isFinish = ref(false)

    const inputType = ref('')
    const inputValue = ref('')
    const inputDescription = ref('')

    const addFilter = async (e) => {
        const typeFilter = e.target.value
        console.log(e.target.value)

        if(typeFilter === undefined){ 
            myFilter.value = ''
            const list = await newRequest()
            listSelected.value = list
        } 

        if(typeFilter === 'owner'){
            const list = await newRequest()

            const filterModel = (listOwner) => {
                let count = {}
                let owner = []

                listOwner.forEach(el => {
                    const id = el.owner.id;
                    count[id] = (count[id] || 0) + 1
                })

                for (const id in count) {
                    if (count[id] > 1) {
                        const myListOwner = listOwner.filter(el => el.owner.id === parseInt(id))
                        owner.push(...myListOwner);
                    }
                }

                return owner;
            }

            return listSelected.value = filterModel(list)
        }

        if(typeFilter === 'model'){
            const list = await newRequest()

            const filterModel = (listModel) => {
                let count = {}
                let modelCar = []

                listModel.forEach(el => {
                    const id = el.vehicle.id;
                    count[id] = (count[id] || 0) + 1
                })

                for (const id in count) {
                    if (count[id] > 1) {
                        const myListModel = listModel.filter(el => el.vehicle.id === parseInt(id))
                        modelCar.push(...myListModel);
                    }
                }

                return modelCar;
            }

            return listSelected.value = filterModel(list)
        }

        if(typeFilter === 'media-tempo'){
            console.log("AQui")
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

    const newRequest = async () => {
        try {
            const response = await axios.get(`${baseURL}/revisions`)
            return response.data
        } catch (error) {
            console.log(error)
        }
    }

    onMounted(async () => {
        try {
            const response = await axios.get(`${baseURL}/revisions`)

            loading.value = false
            if(response.data.length > 0){
                response.data.map((el) => {
                    if(!el.is_done){
                        listSelected.value.push(el) 
                    }
                })
            }else {
                listSelected.value = []
            }
        } catch (error) {
            console.log(error)
            loading.value = false
        }
    })

    const finishedRevision = async (e) => {
        const result = confirm("Revisão finalizada?")
        if(result){ 
            const list = listSelected.value.filter(el => el.id != e.target.id)
            listSelected.value = list

            await update(e.target.id, 'revisions', { is_done: true })
        }
    }

    const finished = async (e) => {
        const revision = listSelected.value.find(el => el.id == e.target.id)
        const revisionUpdate = {
            description: !inputDescription.value ? revision.description : inputDescription.value,
            value: !inputValue.value ? revision.value: inputValue.value,
            type_revision: !inputType.value ? revision.type_revision : inputType.value
        }
        
        const response = await update(e.target.id, 'revisions', revisionUpdate)
       
        const indexRevision = listSelected.value.findIndex(el => el.id == e.target.id)

        listSelected.value[indexRevision].type_revision = response.data.type_revision
        listSelected.value[indexRevision].value = response.data.value
        listSelected.value[indexRevision].description = response.data.description
        
        closeItem()
        isFinish.value = false
        isCancel.value = false
        selectedItem.value = ''
    }

    const updated = async (e) => {
        if(!selectedItem.value){
            isCancel.value = false
            isUpdate.value = false
            selectedItem.value = e.target.id
            openItem(e.target.id, 'update')
            return
        }

        closeItem()
        selectedItem.value = ''
        isCancel.value = false
        isFinish.value = false
        isUpdate.value = false
    }

    const canceled = () => {
        closeItem()
        isFinish.value = false
        isCancel.value = false
        isUpdate.value = true
        selectedItem.value = ''
    }
</script>

<style>
    .filter {
        display: flex;
        align-items: center;
        flex-flow: wrap;
        justify-content: center;

            > P {
                border-radius: 0.3rem;
                padding: 0.5rem;
                border: 1px solid;
            }
    }

    .list_owner {
        display: flex;
        flex-flow: column;
        align-items: center;
        border-radius: 1.5rem;
        padding: 1.5rem;
        position: relative;
        margin: 0 auto;
        height: 100%;
        width: 100%;
    }
</style>