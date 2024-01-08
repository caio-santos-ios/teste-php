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
                <span>{{{ timeAverage
 }}}</span>
            </button>
        </div>
    </div>
    <ul class="list_owner">
        <li class="header_title">
            <p>Proprietario</p>
            <p>Entrada</p>
            <p>Marca</p>
            <p>Valor</p>    
            <i></i>      
            <i></i>  
        </li>   
        <li class="item_report" :id="item.id" v-for="item in paginatedListReport" :key="item.id">
            <p>{{ item.owner.name }}</p>
            <p>{{ formateDate(item.created_at.slice(0, 10)) }}</p>
            <p>{{ item.vehicle.model }}</p>
            <p>R$ {{ item.value }}</p> 
            
            <i v-if="!isUpdate" :id="item.id" @click="finishRevision" class="fa-regular fa-square-check"></i>
            <i v-if="isUpdate" :id="item.id" @click="saveUpdateRevision" class="fa-solid fa-check"></i>
            <i :id="item.id" @click="updateRevision" class="fa-solid fa-pen-to-square"></i>
            <form :id="item.id" class="form_update_report">
                <input disabled class="input_update" type="text" :value="item.owner.name">
                <input disabled class="input_update" type="text" :value="item.created_at.slice(0, 10)">
                <input :disabled="!isUpdate" class="input_update" type="text" :value="isUpdate ? item.type_revision : inputType" @input="inputType = $event.target.value">
                <input :disabled="!isUpdate" class="input_update" type="text" :value="isUpdate ? item.value : inputValue" @input="inputValue = $event.target.value">
            </form>
        </li>
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
    import { update, saveUpdate, formateDate } from './Report.vue';
    import { toast } from 'vue3-toastify';
    import 'vue3-toastify/dist/index.css';

    const baseURL = 'http://localhost:8000';    
    const listSelected = ref([]) 
    const myFilter = ref('')
    const itemsPerPage = 10
    const currentPage = ref(1) 
    const isUpdate = ref(false)
    const timeAverage = ref(0)

    const inputType = ref('')
    const inputValue = ref('')

    const addFilter = async (e) => {
        const typeFilter = e.target.value

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
            
            response.data.map((el) => {
                if(!el.is_done){
                    listSelected.value.push(el) 
                }
            })
        } catch (error) {
            console.log(error)
        }
    })

    const calculeedAgeAverage = (total, qtd) => {
        const result = total / qtd
        return result
    }

    const finishRevision = async (e) => {
        toast.success("Revisão finalizada")
        const filter = listSelected.value.filter(el => el.id != e.target.id)
        listSelected.value = filter 
        saveUpdate(e.target.id, 'revisions', { is_done: true })
    }

    const updateRevision = (e) => {
        update(e.target.id)
        isUpdate.value = !isUpdate.value

        const revision = listSelected.value.find(el => el.id == e.target.id)
        inputType.value = revision.type_revision
        inputValue.value = revision.value
    }
    
    const saveUpdateRevision = async (e) => {
        isUpdate.value = !isUpdate.value
        
        const revisionUpdate = {
            type_revision: inputType.value,
            value: inputValue.value
        }

        const indexRevision = listSelected.value.findIndex(el => el.id == e.target.id)
        listSelected.value[indexRevision].type_revision = inputType.value
        listSelected.value[indexRevision].value = inputValue.value
        
        await saveUpdate(e.target.id, 'revisions', revisionUpdate)
    }
</script>

<style>
    .filter {
        display: flex;
        align-items: center;
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