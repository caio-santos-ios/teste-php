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
        <Graphic :foo="[]" />
    </div>
</template>

<script setup>
    import ReportVehicle from './ReportVehicle.vue';
    import ReportRevision from './ReportRevision.vue';
    import ReportOwner from './ReportOwner.vue'
    import Graphic from './Graphic.vue';
    import { ref } from 'vue';

    const itemOpen = ref(false)

    const reportSelect = ref('Veiculos')

    const alterReport = (e) => reportSelect.value = e.target.value

</script>

<script>
    import { ref } from 'vue';
    import axios from 'axios';

    const baseURL = 'http://localhost:8000';    

    const itemOpen = ref('');

    export const formateDate = (data) => `${data.substring(8, 10)}/${data.substring(5, 7)}/${data.substring(0, 4)}`;

    export const destroy = async (id, url) => {
        try {
            await axios.delete(`${baseURL}/${url}/${id}`)
        } catch (error) {
            console.log(error)
        }
    }

    export const update = (id) => {
        const myItem = document.querySelectorAll(`.item_report`)
        const myForm = document.querySelectorAll(`.form_update_report`)
        myItem.forEach((el) => {
            if(!itemOpen.value) {
                if(el.id == id){

                    itemOpen.value = id 
                    el.classList.toggle('open_item_report')
                    return 
                }
            } 
            
            if(id == el.id){
                el.classList.toggle('open_item_report')
            }else{
                el.classList.remove('open_item_report')
            }
        })

        myForm.forEach((el) => {
            if(!itemOpen.value){
                itemOpen.value = id
                setTimeout(() => {
                    el.classList.toggle('form_update_report_open')
                }, 350)
                return
            }
            
            if(id == el.id){
                setTimeout(() => {
                    el.classList.toggle('form_update_report_open')
                }, 350);
            }else {
                el.classList.remove('form_update_report_open')
            }
        })

    }

    export const saveUpdate = async (id, url, payloadUpdate) => {
        const myItem = document.querySelectorAll('.item_report')
        const myForm = document.querySelectorAll('.form_update_report')

        myItem.forEach(el => {
            el.classList.remove('open_item_report')
        })
        
        myForm.forEach(el => {
            el.classList.remove('form_update_report_open')
        })

        try {
            await axios.patch(`${baseURL}/${url}/${id}`, payloadUpdate)
        } catch (error) {
            console.log(error)
        }
    }
    
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
        align-items: center;
        gap: 1rem;
        margin: 0 auto;
        padding: 2rem;
    }
    
    .report {
        display: flex;
        flex-flow: column;
        align-items: center;
        margin: 0 auto;
        border-radius: 1.5rem;
        padding: 1rem;
        position: relative;
        min-height: 62rem;
        width: 100%;
        background-color: white;

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
        gap: 2rem;
        width: 30rem;
        padding: 2rem;
        position: absolute;
        bottom: 1rem;    

        > button {
            padding: 0.5rem;
            border-radius: 0.5rem;
            border: none;
        }
    }

    .header_title {
        display: flex;
        justify-content: space-between;
        padding: 0 0.5rem;
        
        > p, i {
            display: flex;
            justify-content: center;
            text-align: center;
        }
        
        > i {
            width: 2rem;
        }
        
        >  p {
            width: 8rem;
        }
    }

    .item_report {
        display: flex;
        justify-content: space-between;
        gap: 0.2rem;
        border-radius: 1rem;
        transition: 1s;
        border: 1px solid;
        padding: 0 0.5rem;
        width: 100%;
        flex-flow: row wrap;
        padding-top: 0.8rem;
        height: 5rem;
        
        > p, i {
            display: flex;
            justify-content: center;
            text-align: center;
            padding-top: 0.4rem;
        }
        > i {
            width: 2rem;
        }
        > p {
            width: 8rem;
        }
    }

    .open_item_report {
        height: 10rem;
        transition: 1s;
    }

    .item_report:hover {
        background-color: rgb(179, 179, 179);
        border: 1px solid;
    }

    .form_update_report {
        display: none;
    }

    .form_update_report_open {
        display: flex;
        gap: 1rem;
        padding: 1rem 0;
        height: 5rem;
    }

    .input_update {
        width: 11rem;
        height: 3rem;
        outline: none;
        border-radius: 1rem;
        padding: 0.5rem;
    }

    @media (min-width: 550px) {
        .container_master {
            width: 100%;
            max-width: 60rem;
        }
    }

</style>