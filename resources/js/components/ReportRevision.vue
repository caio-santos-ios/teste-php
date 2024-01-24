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
                <i></i>
            </li>
            <li id="item_list" v-if="!loading" :value="item.id" v-for="item in paginatedList" :key="item.id">
                <p v-if="!isReportOwner && !isReportVehicles">{{ item.owner.name }}</p>
                <p v-if="!isReportOwner && !isReportVehicles">{{ item.vehicle.brand }}</p>
                <p v-if="!isReportOwner && !isReportVehicles">{{ item.value }}</p>
                <p v-if="!isReportOwner && !isReportVehicles">{{ item.type_revision }}</p>
                <p v-if="isReportVehicles || isReportOwner">{{ isReportVehicles ? item.brand : item.name }}</p>
                <p v-if="isReportVehicles || isReportOwner">{{ item.revision_vehicles.length }}</p>
                <i v-if="!isReportOwner && !isReportVehicles" @click="revisionDone" :id="item.id" class="fa-regular fa-square-check"></i>
            </li>
            <Loading style="height: 10rem; width: 10rem;" v-if="loading"/>
            <h4 v-if="!loading && listSelected.length === 0">Sem Revisões</h4>

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
        <GraphicBar v-if="!loading" type="scatter" :data="data" />
    </Report>
</template>

<script setup>
    import axios from 'axios';
    import { ref, onMounted, computed } from 'vue';
    import List from './List.vue';
    import Loading from './Loading.vue';
    import Report from './Report.vue'
    import moment from 'moment';
    import GraphicBar from './GraphicBar.vue'; 

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

    const arrayGraphic = ref([])
    const dataGraphic = ref([])

    const itemsPerPage = 10
    const currentPage = ref(1) 

    const data = ref({})
    
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

    /* finaliza uma revisão */
    const revisionDone = async (e) => {
        const res = confirm("Deseja finalizar essa revisão?")

        if(res){
            listSelected.value = listSelected.value.filter(el => el.id != e.target.id)
            try {
                const response = await axios.patch(`${baseURL}/revisions/${e.target.id}`, { is_done: true })
                console.log(response)
            } catch (error) {
                console.log(error)
            }
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
                        arrayGraphic.value.push(el.brand)
                        dataGraphic.value.push(el.revision_vehicles.length)
                    }
                }
            })
        } catch (error) {
            console.log(error)
        }
    })

    /* carrega o relatorio de todas as revisões */
    onMounted(async () => {
        try {
            const response = await axios.get(`${baseURL}/revisions`)
            loading.value = false
            response.data.map(el => {
                console.log(el)
                listSelected.value.push(el)
                allReport.value.push(el)

            })
            
            data.value = {
                labels: arrayGraphic,
                datasets: [{
                    type: 'bar',
                    label: 'Bar Dataset',
                    data: [10, 20, 30, 40],
                    borderColor: 'rgb(255, 99, 132)',
                    backgroundColor: 'rgba(255, 99, 132, 0.2)'
                }, {
                    type: 'line',
                    label: 'Line Dataset',
                    data: [50, 50, 50, 50],
                    fill: false,
                    borderColor: 'rgb(54, 162, 235)'
                }]
            };
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

</script>

<style>
    #list_revision {
        background-color: red;
        min-height: 25rem;
    }

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