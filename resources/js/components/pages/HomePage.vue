<template>
    <main id="main_home">
        <Aside />
        <Home />
    </main>
</template>

<script setup>
    import Aside from '../Aside.vue';
    import Home from '../Home.vue';

</script>

<style>
    #main_home {
        min-height: 100vh;
        display: block;   
    }
    
    @media (min-width: 915px) {
        #main_home {
            height: 100vh;
            display: flex;   
        }
    }

</style>