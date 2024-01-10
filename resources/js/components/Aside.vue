<template>
    <aside>
        <ul class="menu_aside">
            <a href="/" class="option_aside">
                <i class="fa-solid fa-gauge"></i>
                <span class="name_option">
                    Dashboard
                </span>
            </a>
            <a href="/report" class="option_aside">
                <i class="fa-solid fa-flag"></i>
                <span class="name_option">
                    Relatórios
                </span>
            </a>
        </ul>
    </aside>
</template>

<style>
    aside {
        background-color: white;
        padding: 1rem 0;
    }
    
    .menu_aside {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 0 1rem;
    }

    .option_aside {
        padding: 0.5rem;
        list-style: none;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 1rem;
        border-radius: 0.7rem;
        border: none;
        color: black;
        text-decoration: none;
        font-size: 1rem;
    }   

    .option_aside:hover {
        background-color: #cbd5e1;
        cursor: pointer;
    }

    .name_option {
        display: flex;
    }

    @media (min-width: 915px) {
        aside {
            transition: 1s;
            width: 5rem;
        }

        aside:hover{
            transition: 1s;
            width: 10rem;
            
            .name_option {
                display: flex;
            }
            
            .option_aside {
                justify-content: flex-start;
                width: 100%;
                font-size: 1rem;
            }
        }

        .menu_aside {
            flex-flow: column;
            justify-content: flex-start;
            padding: 1rem;
        }

        .name_option {
            display: none;
        }       
    }
</style>