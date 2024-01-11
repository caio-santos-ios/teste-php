<template>
    <form class="form_register">
        <slot></slot>
    </form>
</template>
<script setup>
</script>
<style>
    .form_register {
        margin: 1rem 0;
        padding: 1rem;
        display: none;
        flex-flow: wrap;
        gap: 1.5rem;
        transition: 1s;
        border: 1px solid rgb(156, 156, 156);
    }
    
    label {
        height: 3rem;
        display: flex;
        flex-flow: wrap;
        gap: 0.2rem;
        width: 100%;
        margin: 0.1rem 0;
        
        > input {
            border: none;
            width: 100%;
            border-radius: 1rem;
            padding: 1rem;
            border: 1px solid;
        }
    }
        
    .gender {
        display: flex;
        flex-flow: column;
        justify-content: center;
    }

    .btn_register, .btn_finish {
        display: flex;
        justify-content: center;
        align-items: center;
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

    @media (min-width: 915px) {
        .form_register {
            height: 1rem;
        }
    }
</style>