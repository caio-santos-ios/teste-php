<template>
    <div class="graphic_bar">
      <canvas id="bar"></canvas>
    </div>
  </template>
  
  <script setup>
    import axios from 'axios';
    import { onMounted, ref, watch, computed } from 'vue';
    import { Chart } from 'chart.js/auto';
    import { useStore } from 'vuex';

    const baseURL = 'https://controle-veiculo-c89a5c476b29.herokuapp.com';

    const store = useStore();

    const title1 = ref('Homem')
    const title2 = ref('Mulher')
    const m = ref(0)
    const f = ref(0)

    const props = defineProps(['m', 'f', 'title'])

    onMounted(async () => {
        initialGraphic()
    })
    
    const initialGraphic = () => {
        const ctx = document.getElementById('bar').getContext('2d')
        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: [title1.value, title2.value],
                datasets: [{
                    label: props.title,
                    data: [props.m, props.f],
                    backgroundColor: [
                    'rgb(54, 162, 235)',
                    'rgb(255, 99, 132)',
                    ]
                }]
            }
        })
    }

  </script>
  
  <style>
    .graphic_bar {
        background-color: white;
        border-radius: 1.5rem;
        padding: 1rem;
        margin-bottom: 1rem;
        display: flex;
        justify-content: center;
        width: 100%;
        max-width: 50rem;
        height: 40rem;
    }

    @media (min-width: 1562px) {
    }
  </style>
  