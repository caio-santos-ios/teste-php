<template>
    <main id="main_report">
        <Aside />
        <ReportVehicle />
    </main>
</template>

<script setup>
    import Aside from '../Aside.vue';
    import ReportVehicle from '../ReportVehicle.vue';
</script>

<style>
    p, i {
        font-size: 0.7rem;
    }

    h2 {
        font-size: 1rem;
    }

    .main_home {
        min-height: 100vh;
        display: block;
    }

    @media (min-width: 915px) {
        p, i {
        font-size: 1rem;
        }

        h2 {
            font-size: 1.5rem;
        }

        #main_report {
            min-height: 100vh;
            display: flex;   
            width: 95vw;
        }
    }
</style>