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
        min-height: 100%;
        width: 5rem;
        display: flex;
        justify-content: flex-end;
        transition: 1s;
        padding: 5rem 0;
        display: none;
    }
    
    aside:hover{
        transition: 1s;
        width: 15rem;

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
        height: 100%;
        display: flex;
        flex-flow: column;
        align-items: center;
        gap: 1rem;
        padding: 0 0.2rem;
    }


    .option_aside {
        padding: 0.5rem;
        width: 4rem;
        list-style: none;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 1rem;
        border-radius: 0.7rem;
        border: none;
        color: black;
        text-decoration: none;
        font-size: 1.5rem;
    }   

    .option_aside:hover {
        background-color: #cbd5e1;
        cursor: pointer;
    }

    .name_option {
        display: none;
    }

    @media (min-width: 750px) {
        aside {
            display: flex;
        }   
    }
</style>