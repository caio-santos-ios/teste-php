<template>
    <main class="maain_report">
        <Aside />
        <Report />
    </main>
</template>

<script setup>
    import Aside from '../Aside.vue';
    import Report from '../Report.vue'

</script>

<style>
    .maain_report {
        min-height: 100vh;
        width: 95vw;
        display: flex; 
    }
</style>