<template>
    <form class="form_register">
        <slot></slot>
    </form>
</template>
<script setup>

</script>
<style>
    .form_register {
        margin: 1rem 0;
        padding: 1rem;
        display: none;
        flex-flow: wrap;
        gap: 1.5rem;
        transition: 1s;
        border: 1px solid rgb(156, 156, 156);
    }
    
    label, select {
        height: 3rem;
        display: flex;
        flex-flow: wrap;
        gap: 0.2rem;
        width: 100%;
        margin: 0.1rem 0;
        
        > input {
            border: none;
            width: 100%;
            border-radius: 1rem;
            padding: 1rem;
            border: 0.1rem solid;
        }
    }

    select {
        border-radius: 1rem;
        border: 0.1rem solid;
    }

    /* footer com os botões do formulario */
    #footer_btns {
        width: 100%;
        display: flex;
        justify-content: space-between;
        
        > button {
            border: none;
            padding: 0.5rem;
            border-radius: 0.5rem;
            color: white;
            width: 7rem;
            height: 4rem;
            font-size: 0.9rem;
        }

        #btn_finish {
            background-color: rgb(39, 169, 0);
        }

        #btn_cancel {
            background-color: rgb(227, 30, 30);
        }

        #btn_finish:disabled {
            background-color: rgb(30, 119, 3);
            color: rgb(155, 157, 157);
        }
    }

    @media (min-width: 915px) {
        .form_register {
            height: 1rem;
        }


         /* footer com os botões do formulario */
        #footer_btns {
            justify-content: space-evenly;

            > button {
                width: 10rem;
                font-size: 1rem;
            }
        }
    }
</style>