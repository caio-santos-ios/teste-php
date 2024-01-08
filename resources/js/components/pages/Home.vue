<template>
    <main class="main_home">
        <Aside />
        <Dashboard />
    </main>
</template>

<script setup>
    import Aside from '../Aside.vue';
    import Dashboard from '../Dashboard.vue';

</script>

<style>
    .main_home {
        min-height: 100vh;
        display: flex;   
        width: 95vw;
    }
</style>