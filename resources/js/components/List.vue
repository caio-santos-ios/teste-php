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
  max-width: 55rem;
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
  width: 100%;
}


/* item aberto com as informações dos clientes */
.is_open_item_view {
  border-top: 0.1rem solid;
  height: 20rem;
  transition: 1s;
  width: 100%;
  max-width: 50rem;
  display: flex;
  flex-flow: column;
  gap: 2rem;
}

/* items de veiculos e revisões fechados */
.vehicles, .revision {
  display: none;
}

/* items de veiculos e revisões abertos */
.revision_open, .vehicles_open {
  display: flex;
  flex-flow: column;
  width: 100%;
  max-width: 50rem;
  
  h4 {
    text-align: center;
  }
  
  p {
    width: 4rem;
    text-align: center;
  }

  #header_vehicle, #header_revision {
    font-weight: 800;
    display: flex;
    justify-content: space-around;
    width: 98%;    
  }
  
  #item_vehicle, #item_revision {
    overflow-y: auto;
    height: 5rem;
    display: block;    

    > .my_item {
      width: 100%;
      background-color: white;
      min-height: 2rem;
      margin-bottom: 0.3rem;
      display: flex;
      justify-content: space-around;
      align-items: center;
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

/* icone de revisão finalizada */
.fa-square-check {
  color: #bfbf1f
}

/* o footer dos relatorio */
#footer_page {
  display: flex;
  justify-content: center;
  gap: 1rem;
  width: 100%;
  align-items: center;
  margin-top: auto;

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
    min-height: 35rem;
    width: 100%;
    max-width: 55rem;
  } 

  /* titulo dos itens e o item da lista */
  #title_list, #item_list {
    width: 100%;
    max-width: 55rem;

    > p {
      width: 15rem;
    }

    > i {
      margin-right: 0.3rem
    }
  }

  /* items de veiculos e revisões abertos */
  .revision_open, .vehicles_open {    
    p {
      width: 7rem;
      text-align: center;
    }

    i {
      font-size: 1rem;
      width: 1rem;
      text-align: center;
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
