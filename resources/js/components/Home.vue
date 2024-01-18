
<template>
    <div id="home">
        <h1 id="title_home">Seja bem vindo!</h1>
        <img id="photo_welcom" :src="PhotoWelcom" alt="foto-de-bem-vindo">
    </div>
</template>


<script setup>
    import PhotoWelcom  from '../../../resources/assets/photo-welcom.png'
</script>

<style>
    /* container principal do dashboard */
    #home {
        display: flex;
        justify-content: center;
        flex-flow: column;
        gap: 1rem;
        min-height: 100vh;
        margin: 0 auto;
        padding: 5rem 1rem;
    }

    /* titulo da home */
    #title_home {
        padding: 0;
        height: 100%;
        text-align: center;
        color: rgb(86, 50, 250);
    }

    /* foto de bem vindo */
    #photo_welcom {
        width: 100%;
        height: 100%;
    }

    @media (min-width: 915px) {
         /* container principal do dashboard */
        #home {
            align-items: center;
        }

         /* titulo da home */
        #title_home {
            height: 3rem;
        }

        /* foto de bem vindo */
        #photo_welcom {
            width: 100%;
            height: 50%;
        }
    }
</style>