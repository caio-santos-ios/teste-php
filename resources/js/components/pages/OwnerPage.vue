<template>
    <main id="main_report">
        <Aside />
        <ReportOwner />
    </main>
</template>

<script setup>
    import Aside from '../Aside.vue';
    import ReportOwner from '../ReportOwner.vue';
</script>

<style>
    p, i {
        font-size: 0.7rem;
    }

    h2 {
        font-size: 1rem;
    }
    
    @media (min-width: 915px) {
        p, i {
            font-size: 1rem;
        }
        
        h2 {
            font-size: 1.5rem;
        }
        
        #main_report {
            display: flex;   
            width: 95vw;
            height: 110vh;
        }
    }
</style>