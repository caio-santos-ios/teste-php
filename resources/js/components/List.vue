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
  margin: 0 auto;
  display: flex;
  flex-flow: column;
  align-items: center;
  gap: 0.5rem;
  background-color: white;
  position: relative;
  padding: 0 0 2.5rem 0;
  min-height: 35rem;
  width: 100%;
  max-width: 40rem;
}

/* titulo dos itens e o item da lista */
#title_list, #item_list {
  display: flex;
  align-items: center;
  background-color: rgb(246, 245, 245);
  padding: 0.5rem;
  width: 95%;
  max-width: 55rem;

  > p {
    width: 8rem;
    text-align: center;
  }
  
  /* icones da lista */
  i {
    width: 1.5rem;
  }
}

#title_list {
  font-weight: 700;
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
  padding: 1rem 0;

  > button {
    background-color: #cbd5e1;
    border: none;
    padding: 0.3rem;
    width: 2rem;
    height: 2rem;
    border-radius: 50%;
  }
}

@media (min-width: 400px) {
  /* lista */
  #list {
    min-height: 27rem;
  }
}

@media (min-width: 915px) {
  /* lista */
  #list {
    height: 35rem;
    width: 100%;
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

  /* icone de item feito */
  .fa-square-check {
    color: blue;
  }

  /* o footer dos relatorio */
  #footer_page {

    > button {
      background-color: #cbd5e1;
      border: none;
      padding: 0.5rem;
      width: 4rem;
      height: 4rem;
      border-radius: 50%;
    }
  }

}
</style>
