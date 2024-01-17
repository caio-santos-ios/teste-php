<template>
  <ul id="list">
      <slot></slot>
  </ul>
</template>

<script setup>   

</script>

<style>
/* lista */
#list {
  width: 100%;
  margin: 0 auto;
  display: flex;
  flex-flow: column;
  align-items: center;
  gap: 0.5rem;
  background-color: white;
  position: relative;
  padding: 0 0 2.5rem 0;
  min-height: 25rem;
}

/* titulo dos itens e o item da lista */
#title_list, #item_list {
  display: flex;
  align-items: center;
  background-color: rgb(246, 245, 245);
  padding: 0.5rem;
  width: 100%;

  > p {
    width: 8rem;
    text-align: center;
  }
  
  /* icones da lista */
  i {
    width: 1.5rem;
  }
}


/* icone de delete item */
.fa-trash {
  color: red;
}

/* icone de atualizar item */
.fa-pen-to-square {
  color: blue;
}

/* icone de adicionar item */
.fa-square-plus {
  color: green;
}

/* o footer dos relatorio */
#footer_page {
  display: flex;
  justify-content: center;
  gap: 1rem;
  width: 100%;
  position: absolute;
  bottom: 0;
  align-items: center;

  > button {
    background-color: #cbd5e1;
    border: none;
    padding: 0.3rem;
    width: 2rem;
    height: 2rem;
    border-radius: 50%;
  }
}

@media (min-width: 915px) {
  /* lista */
  #list {
    height: 35rem;
    max-width: 55rem;
  } 

  /* titulo dos itens e o item da lista */
  #title_list, #item_list {
    > p {
      width: 15rem;
    }
  }

  /* icone de delete item */
  .fa-trash {
    color: red;
    margin: 0 0.3rem;
  }

  /* icone de atualizar item */
  .fa-pen-to-square {
    color: blue;
    margin: 0 0.3rem;
  }

  /* icone de adicionar item */
  .fa-square-plus {
    color: green;
    margin: 0 0.3rem;
  }

  /* o footer dos relatorio */
  #footer_page {
    > button {
      background-color: #cbd5e1;
      border: none;
      padding: 0.3rem;
      width: 4rem;
      height: 4rem;
      border-radius: 50%;
    }
  }

}
</style>
