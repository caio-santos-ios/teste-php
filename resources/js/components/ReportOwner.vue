<template>
    <Modal v-if="modal.isOpenModal">
        <FormRegisterOwner v-if="isCreate" />
        <FormUpdateOwner v-if="isUpdate" />
    </Modal>
    <section>
        <div id="header_report">
            <button v-on:click="openModalCreate" id="btn_add_client">Cadastrar cliente</button>
            <input :disabled="loading" maxlength="11" v-model="search" id="search" placeholder="Busque pelo cpf do cliente" type="text">
        </div>
        <List :titles="['Cliente', 'CPF', 'Veiculos', 'Veiculos em Revisão']">
            <Loading v-if="loading" style="height: 7rem; width: 7rem;"/>
            <li id="item_list" v-for="item in paginatedListVehicles">
                <p>{{ item.name }}</p>
                <p>{{ item.cpf }}</p>
                <p>{{ item.vehicles.length }}</p>
                <p>{{ item.revision_vehicles.length }}</p>
                <i :id="item.id" class="fa-solid fa-trash"></i>                
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
    import FormUpdateOwner from './FormUpdate/FormUpdateOwner.vue'
    import Loading from './Loading.vue';
    import { useModalOpen, useListOwner } from '../store/store'

    const loading = ref(true)
    const search = ref('')
    const isCreate = ref(false)
    const isUpdate = ref(false)
    const modal = useModalOpen()
    const list = useListOwner()
    const listOwner = ref([])

    const apiUrl = import.meta.env.VITE_LINK_API;

    const openModalCreate = () => {
        modal.openModal()
        isCreate.value = true
        isUpdate.value = false
    } 

    const openModalUpdate = () => {
        modal.openModal()
        isCreate.value = false
        isUpdate.value = true
    }

    onMounted(async () => {
        try {
            const response = await axios.get(`${apiUrl}/owners`)
            loading.value = false
            response.data.map((el) => {
                listOwner.value.push(el)
                // list.addOwnerList(el)
            })
        } catch (error) {
            loading.value = false
            console.log(error)
        }
    })

    watch(search, async () => {
        search.value = search.value.replace(/[^\d]/g, '');
        // search.value = search.value.replace(/(\d{3})(\d{3})(\d{3})(\d{2})/, '$1.$2.$3-$4')

        try {
            const response = await axios.get(`${apiUrl}/owners?cpf=${search.value}`)
            listOwner.value = response.data
            console.log(response.data)
        } catch (error) {
            console.log(error)
        }
    })

    const itemsPerPage = 16
    const currentPage = ref(1) 

    const paginatedListVehicles = computed(() => {
        const startIndex = (currentPage.value - 1) * itemsPerPage;
        const endIndex = startIndex + itemsPerPage;
        return listOwner.value.slice(startIndex, endIndex);
    })

    const totalPages = computed(() => Math.ceil(listOwner.value.length / itemsPerPage))
   
    const prevPage = () => {
        if (currentPage.value > 1) {
            currentPage.value--
        }
    }

    const nextPage = () => {
        if (currentPage.value < totalPages.value) {
            currentPage.value++
        }
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
        width: 100%;
        gap: 0.5rem;
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

    @media (min-width: 915px) {
        /* secão de cada pagina */
        section {
            width: 70vw;
            margin: 0 auto;
            padding-top: 2rem;
        }


        /* header do relatorio */ 
        #header_report {
            width: 55rem;
            justify-content: space-between;
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
    }

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