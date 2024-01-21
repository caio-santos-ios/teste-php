<template>
    <Modal v-if="modal.isOpenModal">
        <FormRegisterOwner v-if="isCreateOwner" />
        <FormUpdateOwner v-if="isUpdate"/>
        <FormRegisterVehicle v-if="isCreateVehicle" />
        <FormRegisterRevision v-if="isCreateRevision"/>
    </Modal>
    <section>
        <div id="header_report">
            <div id="container_date">
                <h3>Relatorios - Pessoas</h3>
            </div>
            <button :disabled="loading" v-on:click="openModalCreate" id="btn_add_client">Cadastrar cliente</button>
            <h4>Média de idade: {{ ageAverage }}</h4>
            <input :disabled="loading" maxlength="14" v-model="search" id="search" placeholder="Busque pelo cpf do cliente" type="text">
            
            <div id="container_type_report">
                <button :disabled="loading" id="btn_report" @click="report">Todas as pessoas</button>
                <button :disabled="loading" id="btn_report" @click="reportWoman">Somente mulheres</button>
                <button :disabled="loading" id="btn_report" @click="reportMan">Somente homens</button>
            </div>
        </div>
        <List>
            <li id="title_list">
                <i></i>
                <p>Cliente</p>
                <p>CPF</p>
                <p>Veiculos</p>
                <p>Revisões</p>
                <i></i>
                <i></i>
                <i></i>
            </li>
            <li id="item" v-if="!loading" v-for="item in paginatedList">
                <div id="item_list">
                    <h4 v-if="paginatedList.length === 0">Sem clientes</h4>
                    <i v-if="isViewItem != item.id" @click="detailsOwner" :id="item.id" class="fa-solid fa-caret-right icon_open"></i>
                    <i v-if="isViewItem == item.id" @click="detailsOwner" :id="item.id" class="fa-solid fa-caret-down icon_close"></i>
                    <p>{{ item.name }}</p>
                    <p>{{ item.cpf }}</p>
                    <p>{{ item.vehicles.length }}</p>
                    <p>{{ item.revision_vehicles.length }}</p>
                    <i @click="remove" :id="item.id" class="fa-solid fa-trash"></i>                
                    <i @click="openModalUpdate" :id="item.id" class="fa-solid fa-pen-to-square"></i>
                    <i @click="openModalCreateVehicle" :id="item.id" class="fa-solid fa-square-plus"></i>
                </div>
                <div class="open_item_view" :id="item.id">
                    <!-- Veiculos -->
                    <div class="vehicles" :id="item.id">
                        <h4 v-if="item.vehicles.length === 0">Nenhum veiculo</h4>
                        <h4 v-if="item.vehicles.length > 0">Veiculos</h4>
                        <div id="header_vehicle">
                            <p v-if="item.vehicles.length != 0">Marca</p>
                            <p v-if="item.vehicles.length != 0">Modelo</p>
                            <p v-if="item.vehicles.length != 0">Ano</p>
                            <p v-if="item.vehicles.length != 0">Placa</p>
                            <i></i>
                            <i></i>
                        </div>
                        <div id="item_vehicle">
                            <div class="my_item" v-for="item in item.vehicles" :key="item.id">
                                <p>{{ item.brand }}</p>
                                <p>{{ item.model }}</p>
                                <p>{{ item.year }}</p>
                                <p>{{ item.plate }}</p>
                                <i @click="removeVehicles" :id="item.id" class="fa-solid fa-trash"></i>
                                <i @click="openModalCreateRevision" :id="item.id" class="fa-solid fa-square-plus"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Revisões -->
                    <div class="revision" :id="item.id">
                        <h4 v-if="item.revision_vehicles.length === 0">Nenhuma revisão</h4>
                        <h4 v-if="item.revision_vehicles.length > 0">Revisões</h4>
                        <div id="header_revision">
                            <p v-if="item.revision_vehicles.length > 0">Tipo</p>
                            <p v-if="item.revision_vehicles.length > 0">Valor</p>
                            <p v-if="item.revision_vehicles.length > 0">Entrada</p>
                            <i></i>
                        </div>

                        <div id="item_vehicle">
                            <div class="my_item" v-for="item in item.revision_vehicles" :key="item.id">
                                <p>{{ item.type_revision }}</p>
                                <p>{{ item.value }}</p>
                                <p>{{ item.created_at.slice(0, 10) }}</p>
                                <i @click="finishRevision" :id="item.id" class="fa-regular fa-square-check"></i>
                            </div>
                        </div>
                    </div>

                </div>
            </li>
            <Loading v-if="loading" style="height: 7rem; width: 7rem;"/>
            <div id="footer_page">
                <button @click="prevPage" :disabled="currentPage === 1">
                    <i class="fa-solid fa-chevron-left"></i>
                </button>
                <span>{{ currentPage }} de {{ totalPages }}</span>
                <button @click="nextPage" :disabled="totalPages <= 1 || totalPages === currentPage">
                    <i class="fa-solid fa-chevron-right"></i>
                </button>
            </div>
        </List>
    </section>
</template>

<script setup>
    import axios from 'axios';
    import { ref, onMounted, computed, watch } from 'vue';
    import List from './List.vue';
    import Modal from './Modal.vue';
    import FormRegisterOwner from './FormRegister/FormRegisterOwner.vue';
    import FormUpdateOwner from './FormUpdate/FormUpdateOwner.vue';
    import FormRegisterVehicle from './FormRegister/FormRegisterVehicle.vue';
    import FormRegisterRevision from './FormRegister/FormRegisterRevision.vue'
    import Loading from './Loading.vue';
    import { useModalOpen, useListOwner } from '../store/store'
    import {destroy} from './Report.vue'

    const loading = ref(true)
    const search = ref('')

    const baseURL = import.meta.env.VITE_LINK_URL;    

    const isCreateOwner = ref(false)
    const isCreateVehicle = ref(false)
    const isCreateRevision = ref(false)
    const isUpdate = ref(false)
    const isViewItem = ref()

    const ownerUpdate = ref('')
    const allReport = ref([])
    const ageAverage = ref(0)
    const filterSelected = ref('')
    
    const modal = useModalOpen()
    const list = useListOwner()

    const itemsPerPage = 10
    const currentPage = ref(1) 

    const paginatedList = computed(() => {
        const startPage = ( currentPage.value - 1 ) * itemsPerPage
        const endPage = startPage + itemsPerPage
        return list.listOwner.slice(startPage, endPage)
    })

    const totalPages = computed(() => Math.ceil(list.listOwner.length / itemsPerPage))
    
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

    watch(list.listOwner, () => {
       // console.log(list.listOwner)
    })

    /* carrega o relatorio de todas as pessoas */
    onMounted(async () => {
        try {
            const response = await axios.get(`${baseURL}/owners`)
            loading.value = false
            response.data.map(el => {
                allReport.value.push(el)
                list.listOwner.push(el)
            })
            list.listOwner.sort(function (a, b) {
                if (a.vehicles.length > b.vehicles.length) {
                    return 1;
                }

                if (a.vehicles.length < b.vehicles.length) {
                    return -1;
                }
                return 0;
            }).reverse()

            const sumAge = response.data.reduce((totalAge, owner) => Number(totalAge) + Number(owner.age), 0)
            ageAverage.value = calculeedAgeAverage(sumAge, response.data.length)
        } catch (error) {
            loading.value = false
            console.log(error)
        }
    })

    /* abrir modal de criação de clientes */
    const openModalCreate = () => {
        modal.openModal()
        isCreateOwner.value = true
        isCreateVehicle.value = false
        isUpdate.value = false
    } 

    /* abrir modal de edição */
    const openModalUpdate = (e) => {
        const findOwner = list.listOwner.find(el => el.id == e.target.id)
        localStorage.setItem('ownerUpdate', JSON.stringify(findOwner))
        modal.openModal()
        isCreateOwner.value = false
        isCreateVehicle.value = false
        isUpdate.value = true
    }

    /* abrir modal de criação de veiculos */
    const openModalCreateVehicle = (e) => {
        isCreateVehicle.value = true
        isCreateOwner.value = false
        isUpdate.value = false
        isCreateRevision.value = false
        
        localStorage.setItem('idVehicle', JSON.stringify(e.target.id))
        modal.openModal()
    }

    /* abrir modal de criação de revisão */
    const openModalCreateRevision = (e) => {
        isCreateRevision.value = true
        isCreateOwner.value = false
        isCreateVehicle.value = false
        isUpdate.value = false

        const owner = localStorage.getItem('idOwner')

        localStorage.setItem('idVehicle', e.target.id)
        localStorage.setItem('idOwner', owner)
        modal.openModal()
    }

    /* deletar cliente */
    const remove = async (e) => {
        const confirmed = confirm("Deseja deletar o cliente")
        
        if(confirmed){
            list.listOwner = list.listOwner.filter(el => el.id != e.target.id)
            await destroy(e.target.id, 'owners')
        }
    }

    /* finaliza revisão */
    const finishRevision = async (e) => {
        const confirmed = confirm('Deseja finalizar essa revisão?')

        if(confirmed){

            const idOwner = localStorage.getItem('idOwner')
            const indexOwner = list.listOwner.findIndex(el => el.id == idOwner)
            
            list.listOwner[indexOwner].revision_vehicles = list.listOwner[indexOwner].revision_vehicles.filter(el => el.id != e.target.id)
            
            try {
                await axios.patch(`${baseURL}/revisions/${e.target.id}`, { is_done: true })
            } catch (error) {
                console.log(error)
            }
        }
    }

    /* deletar veivulo */
    const removeVehicles = async (e) => {
        const confirmed = confirm('Deseja deletar o veiculo?')

        if(confirmed){   
            const idOwner = localStorage.getItem('idOwner')
            const indexOwner = list.listOwner.findIndex(el => el.id == idOwner)
            
            const removed = list.listOwner[indexOwner].vehicles.filter(el => el.id != e.target.id)
            list.listOwner[indexOwner].vehicles = removed
            try {
                await axios.delete(`${baseURL}/vehicles/${e.target.id}`)
            } catch (error) {
                console.log(error)
            }
        }
    }

    /* busca cliente pelo cpf */
    watch(search, async () => {
        search.value = search.value.replace(/[^0-9a-zA-Z.-]/g, '')
        const cpf = '[0-9.-]'

        if(filterSelected.value === 'feminino') {
            if(search.value.match(cpf)){
                list.listOwner = list.listOwner.filter(el => el.cpf.includes(search.value))
            }else {
                if(!search.value){
                    list.listOwner = allReport.value.filter(el => el.gender == 'feminino')
                }

                list.listOwner = list.listOwner.filter(el => el.name.toLocaleLowerCase().includes(search.value.toLocaleLowerCase()))
            }
            return
        }

        if(filterSelected.value === 'masculino') {
            if(search.value.match(cpf)){
                list.listOwner = list.listOwner.filter(el => el.cpf.includes(search.value))
            }else {
                if(!search.value){
                    list.listOwner = allReport.value.filter(el => el.gender == 'masculino')
                }    
                list.listOwner = list.listOwner.filter(el => el.name.toLocaleLowerCase().includes(search.value.toLocaleLowerCase()))
            }
            return
        }
        

        if(search.value.match(cpf)){
            list.listOwner = allReport.value.filter(el => el.cpf.includes(search.value))
        }else {
            list.listOwner = allReport.value.filter(el => el.name.toLocaleLowerCase().includes(search.value.toLocaleLowerCase()))
        }
    })

    /* relatorio de todas as pessoas */
    const report = () => {
       list.listOwner = allReport.value
       const sumAge = allReport.value.reduce((totalAge, owner) => Number(totalAge) + Number(owner.age), 0)
       ageAverage.value = calculeedAgeAverage(sumAge, allReport.value.length)
    }

    /* relatorio somente das mulheres */
    const reportWoman = () => {
        filterSelected.value = 'feminino'
        if(!filterSelected.value){
            
            const newList = list.listOwner.filter(el => el.gender === 'feminino')
            list.listOwner = newList
            const sumAge = newList.reduce((totalAge, owner) => Number(totalAge) + Number(owner.age), 0)
            ageAverage.value = calculeedAgeAverage(sumAge, newList.length)
            return
        }

        const newList = allReport.value.filter(el => el.gender === 'feminino')
        list.listOwner = newList
        const sumAge = newList.reduce((totalAge, owner) => Number(totalAge) + Number(owner.age), 0)
        ageAverage.value = calculeedAgeAverage(sumAge, newList.length)
    }

    /* relatorio somente dos homens */
    const reportMan = () => {
        filterSelected.value = 'masculino'
        if(!filterSelected.value){
            
            const newList = list.listOwner.filter(el => el.gender === 'masculino')
            list.listOwner = newList
            const sumAge = newList.reduce((totalAge, owner) => Number(totalAge) + Number(owner.age), 0)
            ageAverage.value = calculeedAgeAverage(sumAge, newList.length)
            return
        }
    
        const newList = allReport.value.filter(el => el.gender === 'masculino')
        list.listOwner = newList
        const sumAge = newList.reduce((totalAge, owner) => Number(totalAge) + Number(owner.age), 0)
        ageAverage.value = calculeedAgeAverage(sumAge, newList.length)
    }

    /* calcula a idade media dos clientes */
    const calculeedAgeAverage = (total, qtd) => {
        const result = total / qtd
        return parseInt(result)
    }

    /* detalhe de cada pessoa */
    const detailsOwner = (e) => {        
        const itemVehicle = document.querySelectorAll('.vehicles')
        itemVehicle.forEach(el => {
            if(el.id == e.target.id){
                setTimeout(() => {
                    el.classList.toggle('vehicles_open')
                }, 300)
            }else {
                el.classList.remove('vehicles_open')
            }
        })

        const itemRevision = document.querySelectorAll('.revision')
        itemRevision.forEach(el => {
            if(el.id == e.target.id){
                setTimeout(() => {
                    el.classList.toggle('revision_open')
                }, 500)
            }else {
                el.classList.remove('revision_open')
            }
        })

        const item = document.querySelectorAll('.open_item_view')
        item.forEach((el, i) => {
            if(el.id == e.target.id){
                localStorage.setItem('idOwner', e.target.id)
                el.classList.toggle('is_open_item_view')
                if(isViewItem.value == el.id) {
                    isViewItem.value = ''
                }else {
                    isViewItem.value = el.id
                }
            }else {
                el.classList.remove('is_open_item_view')
            }
        })
    }
</script>

<style>
    /* barra de busca do cliente */
    #search {
        width: 100%;
        max-width: 30rem;
        height: 2rem;
        padding: 0 0.5rem;
    }
    
    /* botão para adicionar cliente */
    #btn_add_client {
        padding: 0.5rem;
    }

   

    /* botao para mudar os relatorios */
    #btn_report {
        width: 4rem;
    }

    @media (min-width: 915px) {
        /* secão de cada pagina */
        section {
            width: 70vw;
            margin: 0 auto;
            padding-top: 2rem;
        }
        
        
        /* header do relatorio */ 
        #header_report {
            justify-content: space-between;
            max-width: 55rem;
        }
        
        /* barra de busca do cliente */
        #search {
            width: 100%;
            max-width: 55rem;
            height: 2rem;
            padding: 0 0.5rem;
            margin: 0 auto;
        }

        /* botão para adicionar cliente */
        #btn_add_client {
            padding: 0.5rem;
            border: 0;
        }

        /* container com os tipos dos relatorios */
        .container_type_report {
            width: 100%;
            display: flex;
            justify-content: space-between;
            height: 2rem;
            transition: 1s
        }

        /* botao para mudar os relatorios */
            #btn_report {
                width: 10rem;
            }
    }
</style>