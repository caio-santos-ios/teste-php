<template>
    <div class="container_master">
        <div class="report">
            <h2>Relatório de {{ reportSelect }}</h2>
            <div class="header_report">
                <button v-if="reportSelect !== 'Veiculos'"  @click="alterReport" value="Veiculos">Relatório de Veiculos</button>
                <button v-if="reportSelect !== 'Pessoas'"    @click="alterReport" value="Pessoas">Relatório de Pessoas</button>
                <button v-if="reportSelect !== 'Revisões'" @click="alterReport" value="Revisões">Relatório de Revisões</button>
            </div>
            <ReportVehicle v-if="reportSelect === 'Veiculos'" />
            <ReportRevision v-if="reportSelect === 'Revisões'" />
            <ReportOwner v-if="reportSelect === 'Pessoas'" />
        </div>
        <Graphic />
    </div>
</template>

<script setup>
    import ReportVehicle from './ReportVehicle.vue';
    import ReportRevision from './ReportRevision.vue';
    import ReportOwner from './ReportOwner.vue'
    import Graphic from './Graphic.vue';
    import { ref } from 'vue';

    const reportSelect = ref('Veiculos')

    const alterReport = (e) => reportSelect.value = e.target.value
    
</script>

<style>
    .header_report {
        width: 100%;
        height: 5rem;
        display: flex;
        justify-content: space-around;

        > button {
            padding: 0.5rem;
            border-radius: 0.5rem;
            border: none;
            font-size: 1rem;
        }
    }
    
    .container_master {
        display: flex;
        flex-flow: wrap;
        justify-content: center;
        gap: 1rem;
        width: 90%;
        margin: 0 auto;
        padding: 1rem 0;
    }
    
    .report {
        height: 60rem;
        display: flex;
        flex-flow: column;
        align-items: center;
        background-color: white;
        margin: 0 auto;
        width: 100%;
        max-width: 60rem;
        border-radius: 1.5rem;
        padding: 1rem;
        position: relative;

        > h2 {
            text-align: center;
        }
    }
    
    .container_filter {
        width: 100%;
        
        > h3 {
            display: flex;
            align-items: center;
            gap: 0.5rem;            
        }
    }

    .filter {
        padding: 1rem 0;
        display: flex;
        gap: 1rem;
        justify-content: center;
    }

    .btn_filter {
        border: none;
        border-radius: 0.5rem;
        padding: 0.5rem;
        font-size: 1rem;
        cursor: pointer;
    }

    .footer_page_report {
        display: flex;
        justify-content: center;
        gap: 1rem;
        width: 20rem;
        padding: 2rem;
        position: absolute;
        bottom: 1rem;    

        > button {
            padding: 0.5rem;
            border-radius: 0.5rem;
            border: none;
        }
    }
</style>