<template>
    <div class="loading-container">
        <div class="loading"></div>
    </div>
</template>

<script setup></script>

<style>
    
.loading-container {
    text-align: center;
    display: flex;
    justify-content: center;
    align-items: center;
    margin: 0 auto;
}

.loading {
    border: 10% solid #f3f3f3;
    border-top: 0.3rem solid #3498db;
    border-radius: 50%;
    width: 80%;
    height: 80%;
    animation: spin 1s linear infinite;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

</style>