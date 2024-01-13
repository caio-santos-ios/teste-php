
<template>
    <div class="dashbord">
        <FormRegisterOwner />  
    </div>
</template>


<script setup>
    import FormRegisterOwner from './FormRegister/FormRegisterOwner.vue';
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