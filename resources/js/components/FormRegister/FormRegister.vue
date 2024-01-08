<template>
    <form class="form_register">
        <slot></slot>
    </form>
</template>
<script setup>
</script>
<style>
    .form_register {
        padding: 1rem;
        border-radius: 1.5rem;
        display: flex;
        flex-flow: wrap;
        gap: 1rem;
    }
    
    label {
        display: flex;
        flex-flow: column;
        width: 48%;
        height: 5rem;
        
        > input {
            height: 3rem;
            outline: none;
            border: 1px solid #cbd5e1;;
            padding: 0.5rem;
            border-radius: 0.5rem;
        }
    }

    .gender {
        display: flex;
        flex-flow: column;
        justify-content: center;
    }

    .btn_register, .btn_finish {
        padding: 1rem;
        border: none;
        border-radius: 1rem;
        background-color: #2929e0;
        color: white;
        font-size: 1rem;
        width: 48%;
        height: 4rem;
        margin: 0 auto;
    }

    .btn_finish {
        height: 4rem;
        width: 60%;
    }
    
    .btn_register:disabled {
        background-color: #a2a2f4;
    }
</style>