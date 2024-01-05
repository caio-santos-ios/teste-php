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
        </div>
    </div>
    <table>
        <thead>
            <tr>
                <th>Proprietario</th>
                <th>Entrada</th>
                <th>Marca</th>
                <th>Placa</th>
                <th>Ano</th>
            </tr>
        </thead>
        <tbody>
            <tr :id="item.id" v-for="item in paginatedListReport" :key="item.id">
                <td>{{ item.owner.name }}</td>
                <td>{{ item.created_at.slice(0, 10) }}</td>
                <td>{{ item.model }}</td>
                <td>{{ item.plate }}</td>
                <td>{{ item.year }}</td>                  
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
    const filter = ref('')
    const itemsPerPage = 18
    const currentPage = ref(1) 

    const addFilter = (e) => {
        const typeFilter = e.target.value
        
        if(typeFilter === undefined) request()

        if(typeFilter === 'name'){
            filter.value = typeFilter
            orderName(listSelected.value)   
        } 

        if(typeFilter === 'model'){

            orderModel(listSelected.value)
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

            if (nameA > nameB) { return 1 }

            if (nameA < nameB) { return -1}

            return 0;
        })
    }

    const orderModel = (list) => {
        list.sort((a, b) => {
            const nameA = a.model.toLocaleUpperCase()
            const nameB = b.model.toLocaleUpperCase()

            if (nameA > nameB) { return 1 }

            if (nameA < nameB) { return -1}

            return 0;
        })
    }


    const request = async () => {
        try {
            const response = await axios.get(`${baseURL}/vehicles`)
            listSelected.value = response.data 
        } catch (error) {
            console.log(error)
        }
    }

    onMounted(async () => {
        try {
            const response = await axios.get(`${baseURL}/vehicles`)
            listSelected.value = response.data 
        } catch (error) {
            console.log(error)
        }
    })

</script>