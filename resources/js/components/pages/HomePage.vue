<template>
    <main id="main_home">
        <Aside />
        <!--
            <Dashboard />
        -->
    </main>
</template>

<script setup>
    import Aside from '../Aside.vue';
    import Dashboard from '../Dashboard.vue';

</script>

<style>
    #main_home {
        min-height: 100vh;
        width: 95vw;
        display: block;   
    }
    
    @media (min-width: 915px) {
        #main_home {
            min-height: 100vh;
            display: flex;   
        }
    }

</style>