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
    <table>
        <thead>
            <tr>
                <th>Nome</th>
                <th>Cpf</th>
                <th>Sexo</th>
                <th>idade</th>
            </tr>
        </thead>
        <tbody>
            <tr :id="item.id" v-for="item in paginatedListReport" :key="item.id">
                <td>{{ item.name }}</td>
                <td>{{ item.cpf }}</td>
                <td>{{ item.gender }}</td>
                <td>{{ item.age }}</td>
            </tr>
        </tbody>
    </table>
    <div class="footer_page_report">
        <button @click="prevPage" :disabled="currentPage === 1">Anterior</button>
        <span>Página {{ currentPage }} de {{ totalPages }}</span>
        <button @click="nextPage" :disabled="currentPage === totalPages">Próxima</button>
    </div>
</template>

<script setup>
    import axios from 'axios';
    import { ref, onMounted, computed } from 'vue';

    const baseURL = 'http://localhost:8000';    
    const listSelected = ref([]) 
    const myFilter = ref('')
    const itemsPerPage = 18
    const currentPage = ref(1) 
    const ageAverage = ref(0)

    const addFilter = (e) => {
        const typeFilter = e.target.value
    
        if(typeFilter === undefined){ 
            myFilter.value = ''
            request()
        } 

        if(typeFilter === 'masculino'){
            if(myFilter.value === 'feminino'){
                myFilter.value = 'masculino'
                newRequest('masculino')
                return
            }
            const filter = listSelected.value.filter(el => el.gender == "masculino")
            const sumAge = filter.reduce((totalAge, owner) => Number(totalAge) + Number(owner.age), 0)
            ageAverage.value = calculeedAgeAverage(sumAge, filter.length)
            listSelected.value = filter   
            myFilter.value = 'masculino'
        } 

        if(typeFilter === 'feminino'){
            
            if(myFilter.value === 'masculino'){
                myFilter.value = "feminino"
                newRequest('feminino')
                return
            }
            const filter = listSelected.value.filter(el => el.gender == "feminino")
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

    const request = async () => {
        try {
            const response = await axios.get(`${baseURL}/owners`)
            const sumAge = response.data.reduce((totalAge, owner) => Number(totalAge) + Number(owner.age), 0)
            ageAverage.value = calculeedAgeAverage(sumAge, response.data.length)
            listSelected.value = response.data 
        } catch (error) {
            console.log(error)
        }
    }

    const newRequest = async (gender) => {
        try {
            const response = await axios.get(`${baseURL}/owners`)
            const filter = response.data.filter(el => el.gender == gender)
            // const sumAge = filter.reduce((totalAge, owner) => Number(totalAge) + Number(owner.age), 0)
            // ageAverage.value = calculeedAgeAverage(sumAge, filter.length)
            listSelected.value = filter
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
</style>