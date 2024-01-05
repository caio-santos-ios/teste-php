<template>
    <div v-if="!myRegister" class="graphic_bar">
      <canvas id="bar"></canvas>
    </div>
  </template>
  
  <script setup>
    import axios from 'axios';
    import { onMounted, ref } from 'vue';
    import { Chart } from 'chart.js/auto';
    import { computed } from 'vue';
    import { useStore } from 'vuex';

    const baseURL = 'http://localhost:8000/revision';

    const store = useStore();
    const months = ref([ "Janeiro","Fevereiro","Março","Abril","Maio","Junho","Julho","Agosto","Setembro","Outubro", "Novembro", "Dezembro" ])
    const valueMonths = ref([])
    
    const myRegister = computed(() => store.getters.myRegister);

    onMounted(async () => {
        try {
            const response = await axios.get(baseURL)

            const j = response.data.filter(item => item.created_at.slice(5, 7) == '01').length
            const f = response.data.filter(item => item.created_at.slice(5, 7) == '02').length
            const m = response.data.filter(item => item.created_at.slice(5, 7) == '03').length
            const a = response.data.filter(item => item.created_at.slice(5, 7) == '04').length
            const mi = response.data.filter(item => item.created_at.slice(5, 7) == '05').length
            const jh = response.data.filter(item => item.created_at.slice(5, 7) == '06').length
            const jl = response.data.filter(item => item.created_at.slice(5, 7) == '07').length
            const ag = response.data.filter(item => item.created_at.slice(5, 7) == '08').length
            const s = response.data.filter(item => item.created_at.slice(5, 7) == '09').length
            const o = response.data.filter(item => item.created_at.slice(5, 7) == '10').length
            const n = response.data.filter(item => item.created_at.slice(5, 7) == '11').length
            const d = response.data.filter(item => item.created_at.slice(5, 7) == '22').length

            valueMonths.value = [j, f, m, a, mi, jh, jl, ag, s, o, n, d ]

            initialGraphic()
        } catch (error) {
            console.log(error)
        }
    })

    const initialGraphic = () => {
        const ctx = document.getElementById('bar').getContext('2d');
    
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: months.value,
                datasets: [{
                label: 'Média anual',
                data: valueMonths.value  ,
                backgroundColor: [
                'rgba(255, 99, 132)',
                'rgba(255, 159, 64)',
                'rgba(255, 205, 86)',
                'rgba(75, 192, 192)',
                'rgba(54, 162, 235)',
                'rgba(153, 102, 255)',
                'rgba(201, 203, 207)'
                ],
                hoverOffset: 4,
                borderColor: ['rgba(255, 99, 132, 1)', 'rgba(54, 162, 235, 1)', 'rgba(255, 206, 86, 1)'],
                borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    }

  </script>
  
  <style>
    .graphic_bar {
        background-color: white;
        max-width: 40rem; 
        width: 100%;
        border-radius: 1.5rem;
        padding: 1rem;
        margin-bottom: 1rem;
        display: flex;
        justify-content: center;
    }

    #bar {
        width: 100%;
        height: 10rem;
    }

    @media (min-width: 1562px) {
        .graphic_bar {
            max-width: 85rem;
        }
    }
  </style>
  