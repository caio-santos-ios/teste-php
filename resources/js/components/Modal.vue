<template>
    <div id="container_modal">
       <slot></slot>
    </div>
</template>

<script setup></script>

<style>
    /* container do modal */
    #container_modal {
        position: fixed;
        width: 100%;
        height: 100%;
        z-index: 4;
        display: flex;
        justify-content: center;
        background-color: rgba(196, 212, 225, 0.475);
    }
</style>