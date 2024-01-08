<template>
  <div class="container">
    <div class="header_container">
      <h4 class="title">Clientes Cadastrados</h4>
      <section class="container_search">
        <i class="fa-solid fa-magnifying-glass"></i>
        <input v-model="mySearch" class="search" type="text" placeholder="Pesquise pelo cpf do cliente">
      </section>
    </div>
    <ul class="list_items">
      <li class="header_title">
        <p>Nome</p>
        <p>CPF</p>
        <p>Idade</p>
        <p>Sexo</p>
        <p></p>
      </li>
      <li class="item" :id="item.id" v-for="item in paginatedListVehicles" :key="item.id">
        <div class="item_header">
          <p>{{ item.name }}</p>
          <p>{{ item.cpf }}</p>
          <p>{{ item.age }}</p> 
          <p>{{ item.gender }}</p>
          <i :id="item.id" @click="addCar" class="fa-solid fa-car btn_add_car">
            <span :id="item.id">Adicionar</span>
          </i>
        </div>
        <FormRegisterVehicle :id="item.id" />
      </li>
    </ul>
    <div class="footer_page">
      <button @click="prevPage" :disabled="currentPage === 1">Anterior</button>
      <span>Página {{ currentPage }} de {{ totalPages }}</span>
      <button @click="nextPage" :disabled="currentPage === totalPages">Próxima</button>
    </div>
  </div>
</template>

<script setup>
import FormRegisterVehicle from './FormRegister/FormRegisterVehicle.vue';
import { ref, computed, onMounted, watch } from 'vue';
import axios from 'axios';
import { useStore } from 'vuex';

const store = useStore()

const baseURL = 'http://localhost:8000';
const mySearch = ref('')
const listOwner = ref([])
const isDone = ref(0)
const isFinish = ref(0)
const itemOpen = ref('')  

const itemsPerPage = 10
const currentPage = ref(1)

const paginatedListVehicles = computed(() => {
  const startIndex = (currentPage.value - 1) * itemsPerPage;
  const endIndex = startIndex + itemsPerPage;
  return listOwner.value.slice(startIndex, endIndex);
})

const totalPages = computed(() => Math.ceil(listOwner.value.length / itemsPerPage));

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

onMounted(async () => {
  try {
    const response = await axios.get(`${baseURL}/owners`);
    listOwner.value = response.data;
    response.data.forEach((el, i, arr) => {
      if (el.isDone === '0') return isDone.value = arr.length;
      isFinish.value = arr.length;
    });
  } catch (error) {
    console.error(error);
  }
});

watch(mySearch, async () => {
  if(!mySearch) return 
    
  try {
    const response = await axios.get(`${baseURL}/owners?cpf=${mySearch.value}`);
    listOwner.value = response.data;
  } catch (error) {
    console.error(error);
  }
})

watch(store.getters.myOwners, () => {
  const lastOwner = store.getters.myOwners[store.getters.myOwners.length - 1]
  listOwner.value.push(lastOwner)
})

const addCar = (e) => {
  const itemOwner = document.querySelectorAll(".item")
  const form = document.querySelectorAll(".form_register")

  form.forEach((el) => {
    if(!itemOpen.value && e.target.id == el.id) {
      itemOpen.value = e.target.id 
      setTimeout(() => {
        el.classList.toggle('open_form_register')
      }, 200)

      return 
    } 

    if(e.target.id == el.id){
      setTimeout(() => {
        el.classList.toggle('open_form_register')
      }, 200)
      }else{
        el.classList.remove('open_form_register')
    }
  })
  
  itemOwner.forEach((el) => {

    if(!itemOpen.value) {
      itemOpen.value = e.target.id 
      el.classList.toggle('open_item')      
      return 
    } 
    
    if(e.target.id == el.id){
      localStorage.setItem('id', JSON.stringify(e.target.id))
      el.classList.toggle('open_item')
    }else{
      localStorage.setItem('id', JSON.stringify(e.target.id))
      el.classList.remove('open_item')
    }

  })

}
</script>

<style>
.container {
  min-height: 63rem;
  width: 47rem;
  display: flex;
  flex-flow: column;
  align-items: center;
  background-color: white;
  border-radius: 1.5rem;
  padding: 1.5rem;
  position: relative;
  margin: 0 auto;
}

.header_container {
  display: flex;
  align-items: center;
  justify-content: space-between;
  width: 100%;
}

.container_insights {
  display: flex;
  justify-content: center;
  align-items: center;
  gap: 0.5rem;

  > div {
    display: flex;
    flex-flow: column;
    align-items: center;

    > span {
      font-size: 0.7rem;
      color: #a4a4a4;
    }
  }
}

.container_search {
  border: 1px solid;
  display: flex;
  align-items: center;
  padding: 0.5rem;
  border-radius: 1.5rem;
  gap: 0.5rem;
  width: 70%;
}

.search {
  border: none;
  outline: none;
  background-color: transparent;
}

.title {
  padding: 1rem 0;
}

.list_items {
  width: 100%;
  display: flex;
  flex-flow: column;
  gap: 1rem;
}

.item {
  display: flex;
  flex-flow: wrap;
  padding: 0.5rem;
  height: 4rem;
  transition: 1s;
  
  p {
    width: 8rem;
    text-align: center;
  }
}

.item:hover{
  background-color: #a4a4a4;
  border-radius: 1rem;
}

.header_title:hover {
  background-color: transparent;
}

.open_item {
  height: 21rem;
  transition: 1s;
}

.item_header {
  display: flex;
  align-items: center;
  width: 100%;
  gap: 1rem;
  height: 3rem;
}

.btn_add_car{
  background-color: #3838fa;
  color: white;
  font-weight: 700;
  border: none;
  border-radius: 0.5rem;
  margin: 0.5rem 0;
  height: 3rem;
  min-width: 9rem;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 0.3rem;
  cursor: pointer;
  font-size: 0.7rem;
}

.footer_page {
  position: absolute;
  bottom: 0.5rem;
  margin: 0 auto;

  > button {
    padding: 0.3rem;
    border-radius: 0.5rem;
    border: none;
    margin: 0 0.5rem;
  }

  > span {
    font-size: 0.9rem;
  }

}

.form_register {
  width: 40rem;
  transition: 1s;
  background-color: white;
  display: none;
  height: 5rem;
  border: 1px solid;
}

.open_form_register {
  transition: 1s;
  width: 100%;
  display: flex;
  height: 12rem;
}

@media (min-width: 950px) {
  .fa-car {
    > p {
      display: flex;
    }
  }
}
</style>
