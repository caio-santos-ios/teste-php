<template>
    <List :titles="['Proprietario', 'Entrada', 'Marca', 'Placa']">
        <li id="item_list" v-if="!loading" :value="item.id" v-for="item in paginatedListReport" :key="item.id">
           <p>{{ item.owner.name }}</p>
           <p>{{ item.created_at.slice(0, 10) }}</p>
           <p>{{ item.model }}</p>
           <p>{{ item.plate }}</p>
        </li>
        <Loading style="height: 10rem; width: 10rem;" v-if="loading"/>
        <h2 v-if="!loading && listSelected.length === 0">Sem Veiculos</h2>

        <div id="footer_page">
            <button @click="prevPage" :disabled="currentPage === 1">
                <i class="fa-solid fa-chevron-left"></i>
            </button>
            <span>Página {{ currentPage }} de {{ totalPages }}</span>
            <button @click="nextPage" :disabled="currentPage === totalPages">
                <i class="fa-solid fa-chevron-right"></i>
            </button>
        </div>
    </List>
</template>

<script setup>
    import axios from 'axios';
    import { ref, onMounted, computed } from 'vue';
    import List from './List.vue';
    import Loading from './Loading.vue';
    import { useListOwner } from '../store/store'

    const loading = ref(true)

    const baseURL = 'http://localhost:8000';    

    const listSelected = ref([]) 
    const itemsPerPage = 10
    const currentPage = ref(1) 
    
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

    onMounted(async () => {
        try {
            const response = await axios.get(`${baseURL}/vehicles`)
            loading.value = false

            response.data.map(el => {
                listSelected.value.push(el)
            })
        } catch (error) {
            loading.value = false
            console.log(error)
        }
    })
</script>

<style>
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