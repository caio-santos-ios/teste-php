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
        <li class="header_title">
            <p>Proprietario</p>
            <p>Entrada</p>
            <p>Marca</p>
            <p>Placa</p>
            <i></i>
            <i></i>
            <i></i>
        </li>
        <li class="item_report" :id="item.id" v-for="item in paginatedListReport" :key="item.id">
            <p>{{ item.owner.name }}</p>
            <p>{{ formateDate(item.created_at.slice(0, 10)) }}</p>
            <p>{{ item.model }}</p>
            <p>{{ item.plate }}</p>
            <i v-if="!isUpdate && !isCreate" :id="item.id" @click="deleteVehicle" class="fa-solid fa-trash"></i>
            <i v-if="isUpdate && !isCreate" :id="item.id" @click="saveUpdateVehicle" class="fa-solid fa-check"></i>
            <i v-if="!isUpdate && isCreate" :id="item.id" @click="saveCreateRevision" class="fa-solid fa-check"></i>
            <i v-if="!isCreate" :id="item.id" @click="updateVehicle" class="fa-solid fa-pen-to-square"></i>
            <i v-if="!isCreate" :id="item.id" @click="createRevision" class="fa-regular fa-square-plus"></i>
            <i v-if="isCreate" :id="item.id" @click="cancelCreateRevision" class="fa-solid fa-xmark"></i>
            <form :id="item.id" class="form_update_report">
                <input disabled class="input_update" type="text" :value="item.owner.name">
                <input disabled class="input_update" type="text" :value="item.created_at.slice(0, 10)">
                <input :disabled="!isUpdate" class="input_update" type="text" :value="isUpdate ? item.model : inputModel" @input="inputModel = $event.target.value">
                <input :disabled="!isUpdate" class="input_update" type="text" :value="isUpdate ? item.plate : inputPlate" @input="inputPlate = $event.target.value">
            </form>

            <form :id="item.id" class="form_create_revision">
                <input required v-model="inputDescription" placeholder="Descrição da revisão" class="input_update" type="text">
                <input required v-model="inputTypeRevision" placeholder="Tipo da revisão" class="input_update" type="text">
                <input required v-model="inputValue" placeholder="Valor cobrado" class="input_update" type="number">
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
    import { update, destroy, saveUpdate, formateDate } from './Report.vue';
    import { toast } from 'vue3-toastify';
    import 'vue3-toastify/dist/index.css';
    import { useStore } from 'vuex';

    const baseURL = 'http://localhost:8000';    
    const listSelected = ref([]) 
    const itemOpen = ref('')
    const filter = ref('')
    const itemsPerPage = 10
    const currentPage = ref(1) 
    const isUpdate = ref(false)
    const isCreate = ref(false)
    const inputModel = ref('')
    const inputPlate = ref('')

    const inputIdOwner = ref('')
    const inputDescription = ref('')
    const inputTypeRevision = ref('')
    const inputValue = ref('')

    const percentageM = ref(0)
    const percentageF = ref(0)

    const store = useStore()

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

            sumPercentage(response.data.length, h.length)
        } catch (error) {
            console.log(error)
        }
    })

    const createRevision = async (e) => {
        isCreate.value = true
        if(isUpdate.value){
            isUpdate.value = false
            itemOpen.value = e.target.id
            const myForm = document.querySelectorAll(`.form_update_report`)
            myForm.forEach((el) => {
                el.classList.remove('form_update_report_open')
            })
        }

        const myItem = document.querySelectorAll(`.item_report`)
        const myForm = document.querySelectorAll(`.form_create_revision`)
        
        const vehicle = listSelected.value.find(el => el.id == e.target.id)
        inputIdOwner.value = vehicle.owner_id
        
        myItem.forEach((el) => {
            if(!itemOpen.value) {
                itemOpen.value = e.target.id 
                el.classList.toggle('open_item_report')
                return 
            } 
            
            if(e.target.id == el.id){
                el.classList.toggle('open_item_report')
            }else{
                el.classList.remove('open_item_report')
            }
        })

        myForm.forEach((el) => {
            if(!itemOpen.value){
                itemOpen.value = e.target.id
                setTimeout(() => {
                    el.classList.toggle('form_create_revision_open')
                }, 350)
                return
            }
            
            if(e.target.id == el.id){
                setTimeout(() => {
                    el.classList.toggle('form_create_revision_open')
                }, 350);
            }else {
                el.classList.remove('form_create_revision_open')
            }
        })       
    }

    const saveCreateRevision = async (e) => {
        if(e.target.id != itemOpen.value) return

        const revision = {
            type_revision: inputTypeRevision.value,
            value: inputValue.value,
            description: inputDescription.value,
            owner_id: inputIdOwner.value
        }

        if(revision.description && revision.type_revision && revision.value){
            try {
                await axios.post(`${baseURL}/revisions/${e.target.id}`, revision)

                toast.success("Revisão criada com sucesso")
                isCreate.value = false

                const myItem = document.querySelectorAll(`.item_report`)
                myItem.forEach((el) => {
                    el.classList.remove('open_item_report')
                })

                const myForm = document.querySelectorAll(`.form_create_revision`)
                myForm.forEach((el) => {
                    el.classList.remove('form_create_revision_open')
                })
            } catch (error) {
                console.log(error)
            }
        }else{
            toast.error("Preencha todos os campos")
        }

    }

    const cancelCreateRevision = (e) => {
        if(e.target.id != itemOpen.value) return

        isCreate.value = false
        
        const myItem = document.querySelectorAll(`.item_report`)
        myItem.forEach((el) => {
            el.classList.remove('open_item_report')
        })

        const myForm = document.querySelectorAll(`.form_create_revision`)
        myForm.forEach((el) => {
            setTimeout(() => {
                el.classList.remove('form_create_revision_open')
            }, 350)
        })
    }

    const updateVehicle = (e) => {
        itemOpen.value = e.target.id
        update(e.target.id)
        isUpdate.value = true
        
        const vehicle = listSelected.value.find(el => el.id == e.target.id)

        inputModel.value = vehicle.model
        inputPlate.value = vehicle.plate
    }

    const saveUpdateVehicle = async (e) => {
        if(e.target.id != itemOpen.value) return
        
        isUpdate.value = !isUpdate.value
        
        const vehicleUpdate = {
           model: inputModel.value,
           plate: inputPlate.value
        }

        const indexVehicle = listSelected.value.findIndex(el => el.id == e.target.id)
        listSelected.value[indexVehicle].model = inputModel.value
        listSelected.value[indexVehicle].plate = inputPlate.value
        
        await saveUpdate(e.target.id, 'vehicles', vehicleUpdate)
    }

    const deleteVehicle = async (e) => {
        const filter = listSelected.value.filter(el => el.id != e.target.id)
        listSelected.value = filter
        await destroy(e.target.id, 'vehicles')
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
        display: flex;
        flex-flow: row;
        gap: 2rem;
        justify-content: center;

    }

    .item_create_revision {
        height: 3rem;
    }

    .open_item_create_revision {
        height: 10rem;
        transition: 1s;
    }

    .form_create_revision {
        display: none;
    }

    .form_create_revision_open {
        display: flex;
        gap: 1rem;
        padding: 1rem 0;
        height: 5rem;
    }

</style>