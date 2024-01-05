<template>
    <main class="maain_home">
        <Aside />
        <Dashboard />
    </main>
</template>

<script setup>
    import Aside from '../Aside.vue';
    import Dashboard from '../Dashboard.vue';

</script>

<style>
    .maain_home {
        min-height: 100vh;
        display: flex;   
    }
</style>