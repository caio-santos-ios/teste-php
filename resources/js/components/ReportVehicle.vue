<template>
     <Modal v-if="modal.isOpenModal">
        <FormRegisterVehicle v-if="isCreateNewVehicle" />
        <FormRegisterRevision v-if="isCreateRevision" />
    </Modal>
    <Report>
        <div id="header_report">            
            <div id="container_date">
                <h3>Relatorios - Veiculos</h3>
            </div>
            <input :disabled="loading" maxlength="14" v-model="search" id="search" placeholder="Busque os clientes" type="text">
            <div id="container_type_report">
                <button :disabled="isApplicationFilter" id="btn_report"  @click="orderByAll">Todas os veiculos</button>
                <button :disabled="isApplicationFilter" id="btn_report"  @click="orderByOwner">Por proprietario</button>
                <button :disabled="isApplicationFilter" id="btn_report"  @click="orderByMan">Somente homens</button>
                <button :disabled="isApplicationFilter" id="btn_report"  @click="orderByWoman">Somente mulheres</button>
            </div>
        </div>
        <ul v-if="search" id="list_search">
            <li id="item_list" v-for="item in listSearch">
                <p>{{ item.name }}</p>
                <p>{{ item.cpf }}</p>
                <i @click="openModalCreateVehicle" :id="item.id" class="fa-solid fa-square-plus"></i>
            </li>
        </ul>
        <List>
            <li id="title_list">
                <p>Proprietario</p>
                <p>{{ isOwner ? 'Veiculos' : 'Entrada' }}</p>
                <p>{{ isOwner ? 'Em Revisão' : 'Marca'}}</p>
                <p>{{ isOwner ? 'Entrada' : 'Placa' }}</p>
                <i></i>
                <i></i>
            </li>
            <li id="item_list" v-if="!loading" :value="item.id" v-for="item in paginatedList" :key="item.id">
                <p >{{  isOwner ? item.name : item.owner.name }}</p>
                <p >{{  isOwner ? item.vehicles.length : item.created_at.slice(0, 10) }}</p>
                <p >{{  isOwner ? item.revision_vehicles.length : item.brand }}</p>
                <p >{{  isOwner ? item.created_at.slice(0, 10) : item.plate }}</p>
                <i @click="removeVehicle" :id="item.id" class="fa-solid fa-trash"></i>  
                <i @click="openModalCreateRevision" :id="item.id" class="fa-solid fa-square-plus"></i>       
            </li>
            <Loading style="height: 10rem; width: 10rem;" v-if="loading"/>
            <h2 v-if="!loading && listSelected.length === 0">Sem Veiculos</h2>

            <div id="footer_page">
                <button @click="prevPage" :disabled="currentPage === 1">
                    <i class="fa-solid fa-chevron-left"></i>
                </button>
                <span>Página {{ currentPage }} de {{ totalPages }}</span>
                <button @click="nextPage" :disabled="totalPages <= 1 || totalPages === currentPage">
                    <i class="fa-solid fa-chevron-right"></i>
                </button>
            </div>
        </List>
        <GraphicBar v-if="!loading" type="doughnut" :data="data"/>
    </Report>
</template>

<script setup>
    import axios from 'axios';
    import { ref, onMounted, computed, watch } from 'vue';
    import List from './List.vue';
    import Loading from './Loading.vue';
    import Report from './Report.vue';
    import { useModalOpen } from '../store/store'
    import FormRegisterVehicle from './FormRegister/FormRegisterVehicle.vue';
    import FormRegisterRevision from './FormRegister/FormRegisterRevision.vue';
    import Modal from './Modal.vue';
    import GraphicBar from './GraphicBar.vue'; 
    import { porcentionGender } from './Report.vue'

    const modal = useModalOpen()

    const search = ref('')

    const loading = ref(true)
    const isOwner = ref(false)

    const baseURL = import.meta.env.VITE_LINK_URL;    

    let listSearch = ref([])
    const listSelected = ref([]) 
    const allReport = ref([])
    const listOwners = ref([])
    const listVehicles = ref([])
    const isApplicationFilter = ref(true)
    const isCreateNewVehicle = ref(false)
    const isCreateRevision = ref(false)

    const data = ref({})

    const itemsPerPage = 10
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
                listOwners.value.push(el)
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
            isApplicationFilter.value = false
            loading.value = false
            response.data.map(el => {
                listSelected.value.push(el)
                allReport.value.push(el)
                listVehicles.value.push(el)
            })

        const m = response.data.filter(el => el.owner.gender == 'masculino')
        
        data.value = {
            labels: ['Homem', 'Mulher'],
            datasets: [{
                label: '% de veiculos por sexo',
                data: [ porcentionGender(m.length, response.data.length), 100 - porcentionGender(m.length, response.data.length) ],
                backgroundColor: [
                'rgb(54, 162, 235)',
                'rgb(255, 99, 132)',
                ]
            }]
        }

        } catch (error) {
            isApplicationFilter.value = false
            loading.value = false
            console.log(error)
        }
    })

    /* relatorio de todos os veiculos */
    const orderByAll = () => {
        isOwner.value = false
        listSelected.value = allReport.value
    }

    /* relatorio por proprietario */
    const orderByOwner = () => {
        isOwner.value = true

        listOwners.value.sort((a, b) => {
            if (a.vehicles.length > b.vehicles.length) {
                return 1;
            }
            
            if (a.vehicles.length < b.vehicles.length) {
                return -1;
            }
            return 0;
        }).reverse()

        listSelected.value = listOwners.value
    }

    /* relatorio somente dos homens */
    const orderByMan = () => {
        isOwner.value = false
        listSelected.value = allReport.value.filter(el => el.owner.gender == 'masculino')
    }

    /* relatorio somente das mulheres */
    const orderByWoman = () => {
        isOwner.value = false
        listSelected.value = allReport.value.filter(el => el.owner.gender == 'feminino')
    }

    /* busca pelo cpf do cliente */
    watch(search, async () => {
        isCreateNewVehicle.value = false
        isCreateRevision.value = false
        
        search.value = search.value.replace(/[^0-9a-zA-Z.-]/g, '')
        const cpf = '[0-9.-]'

        if(search.value.match(cpf)){
            const newSearch = search.value.replace(/[^0-9]/g, '')
            listSearch = listOwners.value.filter(el => el.cpf.replace(/[^0-9]/g, '').includes(newSearch))
        }else {
            listSearch = listOwners.value.filter(el => el.name.toLocaleLowerCase().includes(search.value.toLocaleLowerCase()))
        }
    })

    /* abre modal de criação de veiculos */
    const openModalCreateVehicle = (e) => {
        localStorage.setItem('idVehicle', JSON.stringify(e.target.id))

        isCreateNewVehicle.value = true
        isCreateRevision.value = false
        
        modal.openModal()
    }

    /* abre o modal de criação de revisão */
    const openModalCreateRevision = (e) => {
        const findOwner = allReport.value.find(el => el.id == e.target.id)

        localStorage.setItem('idVehicle', e.target.id)
        localStorage.setItem('idOwner', findOwner.owner_id)

        isCreateNewVehicle.value = false
        isCreateRevision.value = true
        
        modal.openModal()
    }

    /* remove veiculo */
    const removeVehicle = async (e) => {
        const newList = listSelected.value.filter(el => el.id != e.target.id)

        const res = confirm("Deseja deletar o veiculo?")
        if(res){
            
            listSelected.value = newList
            try {
                const res = await axios.delete(`${baseURL}/vehicles/${e.target.id}`)
                console.log(res)
            } catch (error) {
                console.log(error)
            }
        }
    }
</script>

<style>
    #list_search {
        position: fixed;
        width: 100%;
        top: 15rem;
        z-index: 1;
        display: flex;
        flex-flow: column;
        align-items: center;
        gap: 0.5rem;
        background-color: white;
        height: 30rem;
        overflow-y: auto;
    }

    @media (min-width: 915px) {
        #list_search {
            width: 70%;
            max-width: 55rem;
            top: 7rem;
        }
    }
</style>