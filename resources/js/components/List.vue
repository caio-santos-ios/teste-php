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

/* item da lista */
#item {
  margin: 0 auto;
  display: flex;
  flex-flow: column;
  align-items: center;
  gap: 0.5rem;
  position: relative;
  width: 100%;
  max-width: 40rem;
  background-color: rgb(246, 245, 245);
}

/* titulo dos itens e o item da lista */
#title_list, #item_list {
  display: flex;
  align-items: center;
  width: 95%;
  height: 2rem;

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

/* cabeçalho do meu item */
#item_header {
  background-color: rgb(246, 245, 245);
  display: flex;
  width: 100%;
  height: 2rem;
}

/* item fechado */
.open_item_view {
  height: 0.1rem;
  transition: 1s;
  background-color: rgb(246, 245, 245);
  width: 95%;

}


/* item aberto com as informações dos clientes */
.is_open_item_view {
  border-top: 0.1rem solid;
  height: 15rem;
  transition: 1s;
  width: 95%;
  max-width: 40rem;
  display: flex;
  flex-flow: column;
  gap: 4rem;
}

#header_vehicle {
  font-weight: 800;
}

/* items de veiculos e revisões fechados */

.vehicles, .revision {
  display: none;
}

/* items de veiculos e revisões abertos */
.vehicles_open, .revision_open {
  display: flex;
  flex-flow: column;
  
  h5 {
    text-align: center;
  }

  div {
    display: flex;
    align-items: center;
    gap: 1rem;

    > p {
      width: 6rem;
    }
  }

  #item_vehicle {
    height: 5rem;
    display: block;    

    > .my_item {
      margin-bottom: 0.3rem;
      display: flex;
      align-items: center;
      background-color: red;
    }
  }
}

/* aviso que não tem veiculos cadastrado */
.notice_title {
  display: none;
}

.notice_title_open {
  display: flex;
}

/* icone de item fechado/aberto */
.icon_close, .icon_open {
  color: green;
  font-size: 2rem;
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
    color: rgb(255, 183, 0);
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
