<template>
    <Report>
        <div id="header_report">            
            <h3>Relatorios - Revisões</h3>
            <div id="container_type_report">
                <div id="container_date">
                    De:
                    <VueDatePicker :disabled="loading" v-model="dateInitial" utc></VueDatePicker>
                    Até:
                    <VueDatePicker :disabled="loading" v-model="dateLast" utc></VueDatePicker>
                    <button @click="filterByDate" :disabled="loading" id="btn_report">Buscar</button>
                </div>
                <button :disabled="loading" id="btn_report"  @click="orderByAll">Todas as Revisões</button>
                <button :disabled="loading" id="btn_report"  @click="orderByBrand">Marcas com mais</button>
                <button :disabled="loading" id="btn_report"  @click="orderByOwner">Pessoas com mais</button>
            </div>
        </div>
        <List>
            <li id="title_list">
                <p v-if="isReportOwner || !isReportVehicles">Proprietario</p>
                <p v-if="!isReportOwner && !isReportVehicles">Marca</p>
                <p v-if="!isReportOwner && !isReportVehicles">Valor</p>
                <p v-if="!isReportOwner && !isReportVehicles">Tipo</p>
                <p v-if="isReportVehicles">Marca</p>
                <p v-if="isReportVehicles || isReportOwner">Veiculos em revisão</p>
            </li>
            <li id="item_list" v-if="!loading" :value="item.id" v-for="item in paginatedList" :key="item.id">
                <h4 v-if="!loading && paginatedList.length === 0">Sem Revisões</h4>
                <p v-if="!isReportOwner && !isReportVehicles">{{ item.owner.name }}</p>
                <p v-if="!isReportOwner && !isReportVehicles">{{ item.vehicle.brand }}</p>
                <p v-if="!isReportOwner && !isReportVehicles">{{ item.value }}</p>
                <p v-if="!isReportOwner && !isReportVehicles">{{ item.type_revision }}</p>
                <p v-if="isReportVehicles || isReportOwner">{{ isReportVehicles ? item.brand : item.name }}</p>
                <p v-if="isReportVehicles || isReportOwner">{{ item.revision_vehicles.length }}</p>
            </li>
            <Loading style="height: 10rem; width: 10rem;" v-if="loading"/>

            <div id="footer_page">
                <button @click="prevPage" :disabled="currentPage === 1">
                    <i class="fa-solid fa-chevron-left"></i>
                </button>
                <span>Página {{ currentPage }} de {{ totalPages }}</span>
                <button @click="nextPage" :disabled="currentPage <= 1">
                    <i class="fa-solid fa-chevron-right"></i>
                </button>
            </div>
        </List>
    </Report>
</template>

<script setup>
    import axios from 'axios';
    import { ref, onMounted, computed } from 'vue';
    import List from './List.vue';
    import Loading from './Loading.vue';
    import Report from './Report.vue'
    import moment from 'moment';

    const loading = ref(true)
    const typeReport = ref('all')
    const isOwner = ref(false)

    const dateInitial = ref('')
    const dateLast = ref('')

    const baseURL = import.meta.env.VITE_LINK_URL;    

    const reportVehicles = ref([])
    const isReportVehicles = ref(false)
    const reportOwner = ref([])
    const isReportOwner = ref(false)
    const listSelected = ref([]) 
    const allReport = ref([])

    const isApplicationFilter = ref(true)
    const itemsPerPage = 16
    const currentPage = ref(1) 
    
    const paginatedList = computed(() => {
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

     /* carrega o relatorio de todas as pessoas */
     onMounted(async () => {
        try {
            const response = await axios.get(`${baseURL}/owners`)            
            response.data.map(el => {
                if(el.revision_vehicles.length > 0){
                    reportOwner.value.push(el)
                }
            })
        } catch (error) {
            loading.value = false
            console.log(error)
        }
    })

    /* carrega o relatorio de todos os veiculos */
    onMounted(async () => {
        try {
            const response = await axios.get(`${baseURL}/vehicles`)
            response.data.map(el => {
                if(el.revision_vehicles.length > 0){
                    if(el.revision_vehicles.length > 0){
                        reportVehicles.value.push(el)
                    }
                }
            })
        } catch (error) {
            loading.value = false
            console.log(error)
        }
    })

    /* carrega o relatorio de todas as revisões */
    onMounted(async () => {
        try {
            const response = await axios.get(`${baseURL}/revisions`)
            response.data.map(el => {
                loading.value = false
                listSelected.value.push(el)
                allReport.value.push(el)
            })
        } catch (error) {
            loading.value = false
            console.log(error)
        }
    })

    /* busca pelo relatorio dentro de um periodo */
    const filterByDate = () => {
        if(!dateInitial.value || !dateLast.value) return

        dateInitial.value = dateInitial.value.slice(0, 10)
        dateLast.value = dateLast.value.slice(0, 10)

        const startDate = moment(dateInitial.value).format('YYYY-MM-DD');
        const endDate = moment(dateLast.value).format('YYYY-MM-DD');


        listSelected.value = allReport.value.filter(revision => {
            const revisionDate = moment(revision.created_at).format('YYYY-MM-DD')
            return moment(revisionDate).isBetween(startDate, endDate, null, '[]')
        })
    }

    /* relatorio de todos as revisões */
    const orderByAll = () => {
        isReportOwner.value = false
        isReportVehicles.value = false
        listSelected.value = allReport.value
    }

    /* relatorio das marcas com maior número de revisões */
    const orderByBrand = () => {
        isReportVehicles.value = true
        isReportOwner.value = false
        listSelected.value = reportVehicles.value
    }

    /* relatorio das pessoas com maior número de revisões */
    const orderByOwner = () => {
        isReportOwner.value = true
        isReportVehicles.value = false
        listSelected.value = reportOwner.value
    }

    /* Médie de revisão de uma pessoa  */

    /* Próxima revisão baseada no tempo médio da ultima revisão */

</script>

<style>
    #container_date {
        padding: 0.5rem;
        width: 100%;
        
        > h3 {
            text-align: center;
        }

        > button {
            width: 100%;
            margin: 0.5rem 0;
            height: 2rem;
        } 
    }
</style>