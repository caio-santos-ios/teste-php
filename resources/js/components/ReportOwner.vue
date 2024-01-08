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
            <P>Media de idade: {{ parseInt(ageAverage) }}</P>
        </div>
    </div>
    <ul class="list_owner">
        <li class="header_title">
            <p>Nome</p>
            <p>Cpf</p>
            <p>Sexo</p>
            <p>idade</p>    
            <i></i>      
            <i></i>  
        </li>   
        <li class="item_report" :id="item.id" v-for="item in paginatedListReport" :key="item.id">
            <p>{{ item.name }}</p>
            <p>{{ item.cpf }}</p>
            <p>{{ item.gender }}</p>
            <p>{{ item.age }}</p> 
            
            <i v-if="!isUpdate" :id="item.id" @click="deleteOwner" class="fa-solid fa-trash"></i>
            <i v-if="isUpdate" :id="item.id" @click="saveUpdateOwner" class="fa-solid fa-check"></i>
            <i :id="item.id" @click="updateOwner" class="fa-solid fa-pen-to-square"></i>
            
            <form :id="item.id" class="form_update_report">
                <input :disabled="!isUpdate" class="input_update" type="text" :value="isUpdate ? item.name : inputName" @input="inputName = $event.target.value">
                <input :disabled="!isUpdate" class="input_update" type="text" :value="isUpdate ? item.cpf : inputCpf" @input="inputCpf = $event.target.value">
                <input disabled class="input_update" type="text" :value="item.gender">
                <input :disabled="!isUpdate" class="input_update" type="text" :value="isUpdate ? item.age : inputAge" @input="inputAge = $event.target.value">
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
    import { update, saveUpdate, destroy } from './Report.vue';
    

    const baseURL = 'http://localhost:8000';    
    const listSelected = ref([]) 
    const myFilter = ref('')
    const itemsPerPage = 10
    const currentPage = ref(1) 
    const ageAverage = ref(0)
    const isUpdate = ref(false)
    const inputName = ref('')
    const inputCpf = ref('')
    const inputGender = ref('')
    const inputAge = ref('')
    const itemOpen = ref('')  

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
        } catch (error) {
            console.log(error)
        }
    })

    const calculeedAgeAverage = (total, qtd) => {
        const result = total / qtd
        return result
    }

    
    const updateOwner = (e) => {
        update(e.target.id)
        isUpdate.value = !isUpdate.value
        
        const owner = listSelected.value.find(el => el.id == e.target.id)
        
        inputName.value = owner.name
        inputCpf.value = owner.cpf
        inputGender.value = owner.gender
        inputAge.value = owner.age
    }
    
    const saveUpdateOwner = async (e) => {
        isUpdate.value = !isUpdate.value

        const ownerUpdate = {
            name: inputName.value,
            cpf: inputCpf.value,
            gender: inputGender.value,
            age: inputAge.value
        }
        
        const indexOwner = listSelected.value.findIndex(el => el.id == e.target.id)
        listSelected.value[indexOwner].name = ownerUpdate.name
        listSelected.value[indexOwner].cpf = ownerUpdate.cpf
        listSelected.value[indexOwner].gender = ownerUpdate.gender
        listSelected.value[indexOwner].age = ownerUpdate.age
        
        await saveUpdate(e.target.id, 'owners', ownerUpdate)
    }
  
    const deleteOwner = async (e) => {
        const filter = listSelected.value.filter(el => el.id != e.target.id)
        listSelected.value = filter 
        destroy(e.target.id, 'owners')
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