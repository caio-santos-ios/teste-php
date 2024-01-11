<template>
    <aside>
        <div id="icons">
            <i v-if="!isOpenMenu" id="icon_menu" @click="openMenu" class="fa-solid fa-bars"></i>
            <i v-if="isOpenMenu" id="icon_menu" @click="openMenu" class="fa-solid fa-xmark"></i>
        </div>

        <ul id="aside_menu">
            <div class="item_menu">
                <div>
                    <i class="fa-solid fa-gauge"></i>
                    <span>Dashboard</span>
                </div>
            </div>

            <div @click="detailViewReport" class="item_menu report_list">
                <div>
                    <i class="fa-solid fa-chart-line"></i>
                    <span>Relatórios</span>
                    <i v-if="!viewReportOpen" id="view_report" class="fa-solid fa-caret-down"></i>
                    <i v-if="viewReportOpen" id="view_report" class="fa-solid fa-caret-right"></i>
                </div>

                <div id="report_menu" class="report_menu">
                    <a href="owner">
                        Clientes
                    </a>
                    <a href="vehicle">
                        Veiculos
                    </a>
                    <a href="revision">
                        Revisões
                    </a>
                </div>
            </div>             
        
        </ul>
    </aside>
</template>

<script setup>
    import { ref } from 'vue';

    const isOpenMenu = ref(false)
    const viewReportOpen = ref(false)

    const openMenu = () => {
        isOpenMenu.value = !isOpenMenu.value

        const aside = document.querySelector("aside")
        aside.classList.toggle("aside_open")

        const menu = document.querySelector('ul')
        if (menu.id === 'menu_open') return menu.id = 'aside_menu'
        
        if (menu.id === 'aside_menu') return menu.id = 'menu_open'
    }

    const detailViewReport = () => {
        viewReportOpen.value = !viewReportOpen.value

        const menuReport = document.querySelector(".report_list")
        menuReport.classList.toggle("open_menu_report")

        const reports = document.querySelector(".report_menu")
        if(reports.id === 'report_menu_open') return reports.id = 'report_menu'

        if(reports.id === 'report_menu') return setTimeout(() => reports.id = 'report_menu_open', 500)
    }
</script>

<style>
    aside {
        background-color: white;
        padding: 1rem;
        transition: 1s;
        height: 4rem;
        display: flex;
        flex-flow: column;
        align-items: flex-start;
        position: fixed;
        width: 100%;
        z-index: 2;
        gap: 1rem;

        > i {
            font-size: 1rem;
        }
    }

    .aside_open {
        height: 100%;
    }

    #icons {
        padding: 0 0.5rem;
    }

    #aside_menu {
        align-items: center;
        justify-content: space-between;
        padding: 0 1rem;
        display: none;
        flex-flow: column;        
    }

    #menu_open {
        display: flex;
        gap: 0.5rem;
        flex-flow: column;
        width: 100%;
    }
    
    .item_menu {
        cursor: pointer;
        border-radius: 0.5rem;
        border: 1px solid transparent;
        width: 40%;
        transition: 1s;
        height: 3rem;

        > div{
            display: flex;
            gap: 0.5rem;
            padding: 0.5rem;
            align-items: center;
        }

        #view_report {
            font-size: 1.5rem;
        }
    }

    .item_menu:hover {
        background-color: rgb(201, 201, 201);
        border-bottom: 1px solid;
        width: 100%;
        transition: 1s;

        > #report_menu {
            display: none;
        }
    }

    .report_list {
        transition: 1s;
    }

    .open_menu_report {
        transition: 1s;
        height: 10rem;
    }

    #report_menu {
        display: none;
    }

    #report_menu_open {
        display: flex;
        flex-flow: column;
        justify-content: flex-start;
        align-items: flex-start;

        > a {
            color: black;
        }
    }

    @media (min-width: 915px) {
        aside {
            position: relative;
            transition: 1s;
            width: 5rem;
            height: 100vh;
            padding: 0;
        }

        
        aside:hover{
            padding: 0 0.5rem;
            transition: 1s;
            width: 15rem;
            
            > #aside_menu {
                padding: 1rem 0;
                display: flex;
                transition: 1s;
                justify-content: center;
                gap: 0.5rem;
                width: 100%;
                justify-content: center;   
            }
        }
        
        #icons {
            display: none;
        }

        .item_menu {
            width: 100%;
            flex-flow: column;
        }

        .report_list {
            display: flex;
        }
         
        #report_menu {
            background-color: aqua;
        }
       
    }
</style>