<template>
   <section>
     <slot></slot>
   </section>
</template>

<script>
    import axios from 'axios'

    const baseURL = import.meta.env.VITE_LINK_URL;    
    
    export const destroy = async (id, url) => {
        try {
            await axios.delete(`${baseURL}/${url}/${id}`)
        } catch (error) {
            console.log(error)
        }
    }

    export const porcentionGender = (value1, valueTotal) => {
        const result = 100 * value1
        return parseInt(result / valueTotal)
    }
   
</script>
<style>
    /* secão de cada pagina */
    section {
        width: 95vw;
        margin: 0 auto;
        padding-top: 10rem;
        display: flex;
        flex-flow: column;
        align-items: center;
        gap: 1rem;
        position: relative;
    }

    /* header do relatorio */ 
    #header_report {
        display: flex;
        align-items: center;
        justify-content: center;
        flex-flow: wrap;
        gap: 0.5rem;
        width: 100%;
        max-width: 40rem;
    }
    
    /* container com os tipos dos relatorios */
    #container_type_report {
        width: 100%;
        display: flex;
        justify-content: center;
        flex-flow: wrap;
        gap: 1rem;
        transition: 1s;
        
        > button {
            padding: 0.3rem;
            width: 6rem;
        }
    }
</style>