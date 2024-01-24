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

    const store = useStore();

    const title1 = ref('Homem')
    const title2 = ref('Mulher')
    const m = ref(0)
    const f = ref(0)

    const props = defineProps(['type', 'data'])

    onMounted(async () => {
        initialGraphic()
    })
    
    const initialGraphic = () => {
        const ctx = document.getElementById('bar').getContext('2d')
        new Chart(ctx, {
            type: props.type,
            data: props.data
        })
    }

  </script>
  
  <style>
    .graphic_bar {
      background-color: white;
      width: 100%;
      max-width: 40rem;
      height: 20rem;
      display: flex;
      justify-content: center;
      align-items: center;
    }

    @media (min-width: 915px) {
      .graphic_bar {
        max-width: 55rem;
      }
    }
  </style>
  