
<template>
    <div class="dashbord">
        <ListVehicles />
        <ContainerInsigths />
        <GraphicBar />
    </div>
</template>

<style>
    .dashbord {
        display: flex;
        justify-content: center;
        padding: 1rem;
        width: 90%;
        flex-flow: wrap;
        gap: 5rem;
        min-height: 100vh;
        margin: 0 auto;
    }
</style>

<script setup>
   import ListVehicles from './ListVehicles.vue';
   import ContainerInsigths from './ContainerInsights.vue';
   import GraphicBar from './GraphicBar.vue';
</script>