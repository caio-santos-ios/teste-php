<template>
    <main class="main_report">
        <Aside />
        <Report />
    </main>
</template>

<script setup>
    import Aside from '../Aside.vue';
    import Report from '../Report.vue'

</script>

<style>
    .main_report {
        min-height: 100vh;
        width: 95vw;
        display: flex; 
    }
</style>