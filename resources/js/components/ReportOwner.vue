<template>
    <div class="container_filter">
        <div class="filter">
            <button value="" @click="addFilter" class="btn_filter">
                <i class="fa-solid fa-filter-circle-xmark"></i>
            </button>
            <button value="masculino" @click="addFilter" class="btn_filter">
                Homem
                <i class="fa-solid fa-child"></i>
            </button>
            <button value="feminino" @click="addFilter" class="btn_filter">
                Mulher
                <i class="fa-solid fa-person-dress"></i>
            </button>
        </div>
    </div>
    <ul class="list_owner">
        <li class="header_title">
            <div>
                <p>Nome</p>
                <p>Cpf</p>
                <p>Sexo</p>
                <p>idade</p>    
            </div>  
        </li>   
        <li v-if="!loading" class="item_report" :id="item.id" v-for="item in paginatedListReport" :key="item.id">
            <div class="title_report_item">
                <p>{{ item.name }}</p>
                <p>{{ item.cpf }}</p>
                <p>{{ item.gender }}</p>
                <p>{{ item.age }}</p> 
            </div>
            <div id="report_icons">
                <i v-if="isFinish && selectedItem == item.id" @click="finished" class="fa-solid fa-check"></i>
                <i v-if="isCancel && selectedItem == item.id" @click="canceled" class="fa-solid fa-xmark"></i>                
                <i v-if="isCreate && !isFinish || selectedItem != item.id" :id="item.id" @click="updated" class="atualizar fa-solid fa-pen-to-square"></i>
                <i v-if="isDeleted || selectedItem != item.id" :id="item.id" @click="deleted" class="fa-regular fa-trash-can"></i>
            </div>
            
            <form :id="item.id" class="form_report_update">
                <input :disabled="!isUpdate" type="text" :value="isUpdate ? item.name : inputName" @input="inputName = $event.target.value">
                <input disabled type="text" :value="item.cpf">
                <input disabled type="text" :value="item.gender">
                <input :disabled="!isUpdate" type="text" :value="isUpdate ? item.age : inputAge" @input="inputAge = $event.target.value">
            </form>
        </li>
        <Loading style="height: 80%; width: 80%;" v-if="loading"/>
        <h2 v-if="!loading && listSelected.length === 0">Faça o cadastro de clientes</h2>
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
    import { openItem, closeItem, update, destroy } from './Report.vue';
    import Loading from './Loading.vue';

    const loading = ref(true)

    const baseURL = 'http://localhost:8000';    
    const listSelected = ref([]) 
    const myFilter = ref('')
    const itemsPerPage = 5
    const currentPage = ref(1) 
    const ageAverage = ref(0)

    const inputIdOwner = ref('')
    const selectedItem = ref('')
    const isUpdate = ref(false)
    const isDeleted = ref(true)
    const isCancel = ref(false)
    const isFinish = ref(false)

    const inputName = ref('')
    const inputGender = ref('')
    const inputAge = ref('')

    const addFilter = async (e) => {
        const typeFilter = e.target.value
        
        if(typeFilter === undefined){ 
            myFilter.value = ''
            const res = await newRequest()
            listSelected.value = res
            const sumAge = res.reduce((totalAge, owner) => Number(totalAge) + Number(owner.age), 0)
            ageAverage.value = calculeedAgeAverage(sumAge, res.length)
        } 

        if(typeFilter === 'masculino' && listSelected.value.length > 0){
            const filter = listSelected.value.filter(el => el.gender == "masculino")

            if(myFilter.value === 'feminino'){
                myFilter.value = 'masculino'

                const res = await newRequest()
                const filter = res.filter(el => el.gender == "masculino")
                const sumAge = filter.reduce((totalAge, owner) => Number(totalAge) + Number(owner.age), 0)
                ageAverage.value = calculeedAgeAverage(sumAge, filter.length)
                listSelected.value = filter   
                return
            }

            const sumAge = filter.reduce((totalAge, owner) => Number(totalAge) + Number(owner.age), 0)
            ageAverage.value = calculeedAgeAverage(sumAge, filter.length)
            listSelected.value = filter   
            myFilter.value = 'masculino'
        } 

        if(typeFilter === 'feminino' && listSelected.value.length > 0){
            const filter = listSelected.value.filter(el => el.gender == "feminino")

            if(myFilter.value === 'masculino'){
                myFilter.value = "feminino"
                
                const res = await newRequest()
                const filter = res.filter(el => el.gender == 'feminino')
                const sumAge = filter.reduce((totalAge, owner) => Number(totalAge) + Number(owner.age), 0)
                ageAverage.value = calculeedAgeAverage(sumAge, filter.length)
                listSelected.value = filter   
                return
            }
            const sumAge = filter.reduce((totalAge, owner) => Number(totalAge) + Number(owner.age), 0)
            ageAverage.value = calculeedAgeAverage(sumAge, filter.length)
            myFilter.value = "feminino"
            listSelected.value = filter   
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
            const response = await axios.get(`${baseURL}/owners`)
            return response.data
        } catch (error) {
            console.log(error)
        }
    }

    onMounted(async () => {
        try {
            const response = await axios.get(`${baseURL}/owners`)
            listSelected.value = response.data 
            loading.value = false
        } catch (error) {
            console.log(error)
            loading.value = false
        }
    })

    const calculeedAgeAverage = (total, qtd) => {
        const result = total / qtd
        return result
    }

    const finished = async () => {
        const indexOwner = listSelected.value.findIndex((el) => el.id == inputIdOwner.value)

        const owner = listSelected.value.find((el) => el.id == inputIdOwner.value)
        
        const ownerUpdate = {
            name: !inputName.value ? owner.name : inputName.value,
            age: !inputAge.value ? owner.age : inputName.value,
            gender: !inputGender.value ? owner.gender : inputName.value
        }

        const response = await update(inputIdOwner.value, 'owners', ownerUpdate)

        listSelected.value[indexOwner].name = response.data.name
        listSelected.value[indexOwner].gender = response.data.gender
        listSelected.value[indexOwner].age = response.data.age
        
        selectedItem.value = ''
        isFinish.value = false
        isUpdate.value = true
        closeItem()
    }

    const canceled = (e) => {
        closeItem()
        isFinish.value = false
        isCancel.value = false
        isUpdate.value = true
        isDeleted.value = true
        selectedItem.value = ''
    }

    const updated = (e) => { 
        inputIdOwner.value = e.target.id

        if(selectedItem.value) {
            closeItem()
            if(selectedItem.value == e.target.id){
                openItem(e.target.id, 'update')
                isFinish.value = true
                isCancel.value = true
                isUpdate.value = true
                isDeleted.value = false
                return
            }

            selectedItem.value = e.target.id
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
            confirm("Deseja deletar este cliente?")
            return
        }
        
        const result = confirm("Deseja deletar este cliente?")
        
        if(result){ 
            const list = listSelected.value.filter(el => el.id != e.target.id)
            listSelected.value = list

            await destroy(e.target.id, 'owners')
        }
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
        gap: 1rem;
    }
</style>