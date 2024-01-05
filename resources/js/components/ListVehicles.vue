<template>
  <div class="container">
    <div class="header_container">
      <h4 class="title">Revisões em aberto</h4>
      <div class="container_insights">
        <div>
          <h3>{{ is_finish }}</h3>
          <span>Finalizadas</span>
        </div>
        <div>
          <h3>{{ is_done }}</h3>
          <span>Em processo</span>
        </div>
      </div>
    </div>
    <table>
      <thead>
        <tr>
          <th>Proprietario</th>
          <th>Entrada</th>
          <th>Modelo</th>
          <th>Tipo de revisão</th>
          <th>Valor</th>
        </tr>
      </thead>
      <tbody>
        <tr :id="item.id" v-for="item in paginatedListVehicles" :key="item.id">
          <td>{{ item.owner.name }}</td>
          <td>{{ formateDate(item.created_at) }}</td>
          <td>{{ item.vehicle.model }}</td>
          <td>{{ item.type_revision }}</td> 
          <td>R$ {{ item.value }}</td>
        </tr>
      </tbody>
    </table>
    <div class="footer_page">
      <button @click="prevPage" :disabled="currentPage === 1">Anterior</button>
      <span>Página {{ currentPage }} de {{ totalPages }}</span>
      <button @click="nextPage" :disabled="currentPage === totalPages">Próxima</button>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import axios from 'axios';

const baseURL = 'http://localhost:8000/revision';
const listVehicles = ref([])
const is_done = ref(0)
const is_finish = ref(0)

const itemsPerPage = 10
const currentPage = ref(1)

const paginatedListVehicles = computed(() => {
  const startIndex = (currentPage.value - 1) * itemsPerPage;
  const endIndex = startIndex + itemsPerPage;
  return listVehicles.value.slice(startIndex, endIndex);
})

const totalPages = computed(() => Math.ceil(listVehicles.value.length / itemsPerPage));

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
    const response = await axios.get(baseURL);
    listVehicles.value = response.data;
    response.data.forEach((el, i, arr) => {
      if (el.is_done === '0') return is_done.value = arr.length;
      is_finish.value = arr.length;
    });
  } catch (error) {
    console.error(error);
  }
});

const formateDate = (data) => `${data.substring(8, 10)}/${data.substring(5, 7)}/${data.substring(0, 4)}`;
</script>

<style>
.container {
  height: 31.8rem;
  max-width: 40rem;
  width: 100%;
  display: flex;
  flex-flow: column;
  align-items: center;
  background-color: white;
  border-radius: 1.5rem;
  padding: 1rem;
  position: relative;
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

.title {
  padding: 1rem 0;
}

table {
  border-collapse: collapse;
  width: 100%;
}

th,
td {
  padding: 0.5rem;
  color: black;
  text-align: center;
  cursor: default;
}

tr:hover {
  background-color: #cbd5e1;
}

thead {
  border-top: 0.1rem solid #a4a4a4;
  border-bottom: 0.1rem solid #a4a4a4;
}

.footer_page {
  position: absolute;
  bottom: 1rem;
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
</style>
