<template>
    <aside>
        <div id="icons">
            <i v-if="!isOpenMenu" id="icon_menu" @click="openMenu" class="fa-solid fa-bars"></i>
            <i v-if="isOpenMenu" id="icon_menu" @click="openMenu" class="fa-solid fa-xmark"></i>
        </div>

        <ul id="aside_menu">

            <a href="/" class="item_menu">
                <div id="icon_item">
                    <i class="fa-solid fa-house"></i>
                    <p>Home</p>
                </div>
            </a>

            <div @click="detailViewReport" class="item_menu report_list">
                <div id="icon_item">
                    <i class="fa-solid fa-chart-line"></i>
                    <p>Relatórios</p>
                    <i v-if="!viewReportOpen" id="view_report" class="fa-solid fa-caret-down"></i>
                    <i v-if="viewReportOpen" id="view_report" class="fa-solid fa-caret-right"></i>
                </div>

                <div id="report_menu" class="report_menu">
                    <a id="report_item" href="owner">
                        Clientes
                    </a>
                    <a id="report_item" href="vehicle">
                        Veiculos
                    </a>
                    <a id="report_item" href="revision">
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
    const isOpenIcon = ref(false)    

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
                
        if(reports.id === 'report_menu') return setTimeout(() => { reports.id = 'report_menu_open' }, 650)
        
    }
</script>

<style>
    /* aside */
    aside {
        background-color: white;
        transition: 1s;
        height: 4rem;
        display: flex;
        flex-flow: column;
        position: fixed;
        width: 100%;
        z-index: 2;
        gap: 1rem;
        padding: 0 1rem;

        a {
            color: black;
        }
    }

    /* icones do menu - abrir e fechar menu */
    #icons {
        padding: 1rem 0;
        display: flex;
        align-items: center;

        > i {
            font-size: 1.3rem;
        }
    }

    /* menu aside */
    #aside_menu {
        display: none;
    }

    /* item do menu aisde */
    .item_menu {
        cursor: pointer;
        border: 1px solid transparent;
        width: 30%;
        transition: 1s;
        height: 3rem;
        
        #icon_item {
            width: 100%;
            display: flex;
            padding: 1rem;
            gap: 0.5rem;

            > i, p {
                font-size: 1rem;
            }
        }
    }

    .item_menu:hover {
        border-bottom: 1px solid;
        width: 100%;
        transition: 1s;
    }

    /* aside aberto */
    .aside_open {
        height: 100%;
    }

    /* menu aberto */
    #menu_open {
        display: flex;
        gap: 0.5rem;
        flex-flow: column;
        width: 100%;
    }

    /* item do relatorio aberto */
    .open_menu_report {
        transition: 1s;
        height: 8rem;
    }
    
    /* lista de relatorios fechado */
    #report_menu {
        display: none;
    }
    
    /* lista de relatorios aberto */
    #report_menu_open {
        display: flex;
        flex-flow: column;
        padding: 0 0.7rem;
        gap: 0.5rem
    }

    @media (min-width: 915px) {
        /* aside */
        aside {
            position: relative;
            transition: 1s;
            width: 7.5rem;
            height: 100vh;

            /* lista de relatorios fechado, com o aside fechado */
            #report_menu_open {
                display: none;
                flex-flow: column;
                padding: 0 1rem;
            }
        }

        /* menu aside */
        #aside_menu {
            width: 100%;
            padding: 1rem;
            display: flex;
            flex-flow: column;
            gap: 1.5rem;
        }
        
        /* item do menu aisde */
        .item_menu {
            display: flex;
            flex-flow: column;
            width: 100%;
            border: none;

            > #icon_item {
                height: 5rem;
                justify-content: center;
                align-items: center;

                > #view_report {
                    display: none;
                }

                > i {
                    padding: 0.5rem;
                }

                > p {
                    display: none;
                }
                
            }
        }

        /* aside aberto */
        aside:hover{
            transition: 1s;
            width: 15rem;
            
            /* menu aside aberto */
            #aside_menu {
                width: 100%;
            }
            
            /* item do menu aside aberto */
            .item_menu {
                border: none;

                #icon_item {
                    height: 4rem;

                    > #view_report {
                        display: flex;
                    }

                    > p {
                        display: flex;
                    }
                }

                /* lista de relatorios aberto, com o aside aberto */
                #report_menu_open {
                    display: flex;
                    flex-flow: column;
                    padding: 0 1rem;
                }
            }
        }
        
        /* icones para abrir e fechar o menu */
        #icons {
            display: none;
        }
    }
</style>