<template>
    <Modal v-if="modal.isOpenModal">
        <FormRegisterOwner v-if="isCreate" />
        <FormUpdateOwner :owner="ownerUpdate" v-if="isUpdate"/>
    </Modal>
    <section>
        <div id="header_report">
            <button v-on:click="openModalCreate" id="btn_add_client">Cadastrar cliente</button>
            <input :disabled="loading" maxlength="11" v-model="search" id="search" placeholder="Busque pelo cpf do cliente" type="text">
            
            <div class="container_type_report">
                <button id="btn_report" @click="report">Todas as pessoas</button>
                <button id="btn_report" @click="reportWoman">Somente mulheres</button>
                <button id="btn_report" @click="reportMan">Somente homens</button>
            </div>
        </div>
        <List :titles="['Cliente', 'CPF', 'Veiculos', 'Veiculos em Revisão']">
            <Loading v-if="loading" style="height: 7rem; width: 7rem;"/>
            <li id="item_list" v-for="item in list.listOwner">
                <p>{{ item.name }}</p>
                <p>{{ item.cpf }}</p>
                <p>{{ item.vehicles.length }}</p>
                <p>{{ item.revision_vehicles.length }}</p>
                <i @click="remove" :id="item.id" class="fa-solid fa-trash"></i>                
                <i @click="openModalUpdate" :id="item.id" class="fa-solid fa-pen-to-square"></i>
                <i :id="item.id" class="fa-solid fa-square-plus"></i>
            </li>
            <div id="footer_page">
                <button @click="prevPage" :disabled="currentPage === 1">
                    <i class="fa-solid fa-chevron-left"></i>
                </button>
                <span>{{ currentPage }} de {{ totalPages }}</span>
                <button @click="nextPage" :disabled="totalPages <= 1">
                    <i class="fa-solid fa-chevron-right"></i>
                </button>
            </div>
        </List>
    </section>
    <!--
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
        <Loading style="height: 10rem; width: 10rem;" v-if="loading"/>
        <h2 v-if="!loading && listSelected.length === 0">Faça o cadastro de clientes</h2>
    </ul>
    <div class="footer_page_report">
        <button @click="prevPage" :disabled="currentPage === 1">Anterior</button>
        <span>Página {{ currentPage }} de {{ totalPages }}</span>
        <button @click="nextPage" :disabled="currentPage === totalPages">Próxima</button>
    </div>
    -->
</template>

<script setup>
    import axios from 'axios';
    import { ref, onMounted, computed, watch } from 'vue';
    import List from './List.vue';
    import Modal from './Modal.vue';
    import FormRegisterOwner from './FormRegister/FormRegisterOwner.vue';
    import FormUpdateOwner from './FormUpdate/FormUpdateOwner.vue';
    import Loading from './Loading.vue';
    import { useModalOpen, useListOwner } from '../store/store'
    import { destroy } from '../components/Report.vue'

    const loading = ref(true)
    const search = ref('')
    const isCreate = ref(false)
    const isUpdate = ref(false)
    const ownerUpdate = ref('')
    const reportGeral = ref([])
    const filterSelected = ref('')
    
    const modal = useModalOpen()
    const list = useListOwner()

    const apiUrl = import.meta.env.VITE_LINK_API;
    const baseUrl = 'http://localhost:8000'

    const itemsPerPage = 5
    const currentPage = ref(1) 

    const totalPages = computed(() => Math.ceil(list.listOwner.length / itemsPerPage))
    
    /* carrega a lista na montagem */
    onMounted(async () => {
        try {
            const response = await axios.get(`${baseUrl}/owners`)
            loading.value = false
            response.data.map((el) => {
                list.addOwnerList(el)
            })

            console.log(response)
        } catch (error) {
            loading.value = false
            console.log(error)
        }
    })

    /* abrir modal de criação */
    const openModalCreate = () => {
        modal.openModal()
        isCreate.value = true
        isUpdate.value = false
    } 

    /* abrir modal de edição */
    const openModalUpdate = (e) => {
        const filter = list.listOwner.find(el => el.id == e.target.id)
        ownerUpdate.value = filter

        modal.openModal()
        isCreate.value = false
        isUpdate.value = true
    }

    /* deletar cliente */
    const remove = async (e) => {
        const confirmed = confirm("Deseja deletar o cliente")
        const filter = list.listOwner.filter(el => el.id != e.target.id)

        if(confirmed){
            list.listOwner = filter
            await destroy(e.target.id, 'owners')
        }
    }

    /* busca cliente pelo cpf */
    watch(search, async () => {
        search.value = search.value.replace(/[^\d]/g, '');

        try {
            const response = await axios.get(`${apiUrl}/owners?cpf=${search.value}`)
            response.data.map((el) => {
                list.addOwnerList(el)
            })
        } catch (error) {
            console.log(error)
        }
    })

    /* relatorio de todas as pessoas */
    const report = () => {
       list.listOwner = reportGeral.value
    }

    /* relatorio somente das mulheres */
    const reportWoman = () => {
        if(!filterSelected.value){
            reportGeral.value = list.listOwner

            const newList = list.listOwner.filter(el => el.gender === 'feminino')
            list.listOwner = newList
            return
        }
        
        const newList = reportGeral.value.filter(el => el.gender === 'feminino')
        list.listOwner = newList
    }

    /* relatorio somente dos homens */
    const reportMan = () => {
        if(!filterSelected.value){
            reportGeral.value = list.listOwner
            
            const newList = list.listOwner.filter(el => el.gender === 'masculino')
            list.listOwner = newList
            filterSelected.value = 'masculino'
            return
        }

        const newList = reportGeral.value.filter(el => el.gender === 'masculino')
        list.listOwner = newList
    }
/*
    import axios from 'axios';
    import { ref, onMounted, computed } from 'vue';
    import { openItem, closeItem, update, destroy } from './Report.vue';
    import Loading from './Loading.vue';

    const loading = ref(true)

    const apiUrl = import.meta.env.VITE_LINK_API;
    console.log(apiUrl)

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
            const response = await axios.get(`${apiUrl}/owners`)
            return response.data
        } catch (error) {
            console.log(error)
        }
    }

    onMounted(async () => {
        try {
            const response = await axios.get(`${apiUrl}/owners`)
            console.log(response.data)
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
*/
</script>

<style>
    /* secão de cada pagina */
    section {
        width: 95vw;
        margin: 0 auto;
        padding-top: 5rem;
        display: flex;
        flex-flow: column;
        align-items: center;
        gap: 1rem;
    }

    /* header do relatorio */ 
    #header_report {
        display: flex;
        align-items: center;
        justify-content: center;
        flex-flow: wrap;
        gap: 0.5rem;
        width: 100%;
    }

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
        border: 0;
    }

    /* container com os tipos dos relatorios */
    .container_type_report {
        background-color: red;
        width: 100%;
        display: flex;
        justify-content: center;
        gap: 1rem;
        height: 2rem;
        transition: 1s
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
            max-width: 20rem;
            height: 2rem;
            padding: 0 0.5rem;
        }

        /* botão para adicionar cliente */
        #btn_add_client {
            padding: 0.5rem;
            border: 0;
        }

        /* container com os tipos dos relatorios */
        .container_type_report {
            background-color: red;
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