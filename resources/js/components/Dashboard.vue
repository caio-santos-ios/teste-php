
<template>
    <div class="dashbord">
        <ListOwner />
        <FormRegisterOwner />  
        <GraphicBar />
    </div>
</template>


<script setup>
    import ListOwner from './ListOwner.vue';
    import FormRegisterOwner from './FormRegister/FormRegisterOwner.vue';
    import GraphicBar from './GraphicBar.vue';
</script>

<style>
    .dashbord {
        display: flex;
        justify-content: center;
        padding: 1rem;
        flex-flow: wrap;
        gap: 5rem;
        min-height: 100vh;
        margin: 0 auto;
    }
</style>