<template>
    <div v-if="!myRegister" class="my_graphic">
      <canvas id="pizza"></canvas>
    </div>
  </template>
  
  <script setup>
    import axios from 'axios';
    import { ref, watchEffect, defineProps } from 'vue';
    import { Chart } from 'chart.js/auto';
    import { computed } from 'vue';
    import { useStore } from 'vuex';
    
    const props = defineProps(['foo'])

    const baseURL = 'http://localhost:8000/revision';

    const store = useStore();

    const m = ref(1)
    const f = ref(1)
    
    const myRegister = computed(() => store.getters.myRegister);

    watchEffect(async () => {
        try {
          const response = await axios.get(baseURL)
          
          m.value = response.data.filter(item => item.owner.gender === 'feminino').length;
          f.value = response.data.filter(item => item.owner.gender === 'masculino').length;
          initialGraphic()
        } catch (error) {
          // console.log(error)
        }
      })

    const initialGraphic = () => {
      const ctx = document.getElementById('pizza').getContext('2d');
      new Chart(ctx, {
          type: 'doughnut',
          data: {
              labels: ['Mulher', 'Homem' ],
              datasets: [{
              label: 'Media por sexo',
              data: [m.value, f.value],
              backgroundColor: [
              'rgb(255, 99, 132)',
              'rgb(54, 162, 235)',
              'rgb(255, 205, 86)'
              ],
              hoverOffset: 4,
              borderColor: ['rgba(255, 99, 132, 1)', 'rgba(54, 162, 235, 1)', 'rgba(255, 206, 86, 1)'],
              borderWidth: 1
              }]
          },
          options: {
              responsive: true,
              maintainAspectRatio: false
          }
      });
    }   
  
  </script>
  
  <style>
    .my_graphic {
        height: 50%;
        width: 40rem;
        border-radius: 1.5rem;
        padding: 1rem;
        margin-bottom: 1rem;
    }

    #pizza {
        height: 10rem;
    }
  </style>
  