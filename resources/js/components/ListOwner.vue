<template>
  <div id="container">
    <div class="header_container">
      <h4 class="title">Clientes Cadastrados</h4>
      <section class="container_search">
        <i class="fa-solid fa-magnifying-glass"></i>
        <input v-model="mySearch" class="search" type="text" placeholder="Pesquise pelo cpf do cliente">
      </section>
    </div>
    <ul class="list_items">
      <li id="header_title">
        <p>Nome</p>
        <p>CPF</p>
        <p>Idade</p>
        <p>Sexo</p>
      </li>
      <li v-if="!loading" class="item_is_open" :id="item.id" v-for="item in paginatedListVehicles" :key="item.id">
        <div class="item_container">
          <div id="item_header">
            <p>{{ item.name }}</p>
            <p>{{ item.cpf }}</p>
            <p>{{ item.age }}</p> 
            <p>{{ item.gender }}</p>
          </div>
          <i :id="item.id" @click="addCar" class="fa-solid fa-car btn_add_car">
            <span :id="item.id">Adicionar</span>
          </i>
        </div>
        <FormRegisterVehicle :id="item.id" />
      </li>
      <Loading style="height: 10rem; width: 10rem;" v-if="loading"/>
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
import axios from 'axios';
import { ref, computed, onMounted, watch } from 'vue';
import { useStore } from 'vuex';
import Loading from './Loading.vue';

const store = useStore()

const baseURL = 'https://controle-veiculo-c89a5c476b29.herokuapp.com';
const mySearch = ref('')
const listOwner = ref([])
const itemOpen = ref('') 
const loading = ref(true) 

const itemsPerPage = 7
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
    loading.value = false
  } catch (error) {
    loading.value = false;
    console.log(error)
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
  const itemOwner = document.querySelectorAll(".item_is_open")
  const form = document.querySelectorAll(".form_register")
  
  itemOpen.value = e.target.id 
  localStorage.setItem('id', JSON.stringify(e.target.id))
  
  form.forEach((el) => {
    if(!itemOpen.value && e.target.id == el.id) {
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
      el.classList.toggle('open_item')      
      return 
    } 
    
    if(e.target.id == el.id){
      el.classList.toggle('open_item')
    }else{
      el.classList.remove('open_item')
    }

  })

}
</script>

<style>
#container {
  display: flex;
  flex-flow: column;
  align-items: center;
  background-color: white;
  border-radius: 1.5rem;
  padding: 1.5rem;
  position: relative;
  margin: 0 auto;
  width: 100%;
  max-width: 50rem;
  min-height: 56rem;
}

.header_container {
  display: flex;
  align-items: center;
  justify-content: space-between;
  width: 100%;

  font-size: 0.8rem;
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
  width: 100%;
  background-color: transparent;
}

.search::placeholder {
  font-size: 0.6rem;
}

.title {
  padding: 1rem 0;
}

.list_items {
  width: 100%;
  height: 90%;
  display: flex;
  flex-flow: column;
  gap: 1rem;
  font-size: 0.7rem;
}

#header_title {
  padding: 0.5rem;
  display: flex;
  justify-content: space-between;

  > p {
    width: 7rem;
    text-align: center;
  }
}

.item_is_open {
  padding: 0.5rem;
  list-style: none;
  background-color: #d6d6d6;
  border-radius: 1rem;
  height: 5.5rem;
  transition: 1s;
  
  p {
    text-align: center;
    width: 7rem;
  }
}

.item:hover{
  border-radius: 1rem;
}

.item_container {
  display: flex;
  flex-flow: wrap;
  justify-content: center;
  gap: 1rem;
}

#item_header {
  width: 100%;
  display: flex;
  align-items: center;
  justify-content: space-between;

  > p {
    width: 7rem;
  }
}

.open_item {
  height: 26.5rem;
  transition: 1s;
}


.btn_add_car{
  background-color: green;
  color: white;
  font-weight: 700;
  border: none;
  border-radius: 0.5rem;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 0.3rem;
  cursor: pointer;
  font-size: 0.6rem;
  padding: 0.3rem;
  min-width: 2rem;
  height: 2rem;

  > span {
    margin: 0 auto;
  }
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

.open_form_register {
  transition: 1s;
  width: 100%;
  display: flex;
  height: 20rem;
}


@media (min-width: 500px) {
  .btn_add_car {
    min-width: 6rem;

    > span {
      display: flex;
    }
  }
}

@media (min-width: 600px) {
  #header_title {
    width: 80%;
  }
  
  .item_container {
    margin-top: 1.2rem;
    justify-content: space-between;
    gap: 0;

    > i {
      align-self: center;
    }
  }

  #item_header {
    width: 80%;
  }
}

@media (min-width: 915px) {
  #container {
    width: 50rem;
  }

  .header_container {
    font-size: 1rem;
  }
  
  .list_items {
    font-size: 0.9rem;
  }
}
</style>
