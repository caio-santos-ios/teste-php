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

/*
#container {
  display: flex;
  flex-flow: column;
  align-items: center;
  background-color: white;
  border-radius: 1.5rem;
  padding: 1.5rem;
  position: relative;
  margin: 0 auto;
  width: 100%;
  max-width: 50rem;
  min-height: 56rem;
}

.header_container {
  display: flex;
  align-items: center;
  justify-content: space-between;
  width: 100%;

  font-size: 0.8rem;
}

.container_insights {
  display: flex;
  justify-content: center;
  align-items: center;
  gap: 0.5rem;

  > div {
    display: flex;
    flex-flow: column;
    align-items: center;

    > span {
      font-size: 0.7rem;
      color: #a4a4a4;
    }
  }
}

.container_search {
  border: 1px solid;
  display: flex;
  align-items: center;
  padding: 0.5rem;
  border-radius: 1.5rem;
  gap: 0.5rem;
  width: 70%;
}

.search {
  border: none;
  outline: none;
  width: 100%;
  background-color: transparent;
}

.search::placeholder {
  font-size: 0.6rem;
}

.title {
  padding: 1rem 0;
}

.list_items {
  width: 100%;
  height: 90%;
  display: flex;
  flex-flow: column;
  gap: 1rem;
  font-size: 0.7rem;
}

#header_title {
  padding: 0.5rem;
  display: flex;
  justify-content: space-between;

  > p {
    width: 7rem;
    text-align: center;
  }
}

.item_is_open {
  padding: 0.5rem;
  list-style: none;
  background-color: #d6d6d6;
  border-radius: 1rem;
  height: 5.5rem;
  transition: 1s;
  
  p {
    text-align: center;
    width: 7rem;
  }
}

.item:hover{
  border-radius: 1rem;
}

.item_container {
  display: flex;
  flex-flow: wrap;
  justify-content: center;
  gap: 1rem;
}

#item_header {
  width: 100%;
  display: flex;
  align-items: center;
  justify-content: space-between;

  > p {
    width: 7rem;
  }
}

.open_item {
  height: 26.5rem;
  transition: 1s;
}


.btn_add_car{
  background-color: green;
  color: white;
  font-weight: 700;
  border: none;
  border-radius: 0.5rem;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 0.3rem;
  cursor: pointer;
  font-size: 0.6rem;
  padding: 0.3rem;
  min-width: 2rem;
  height: 2rem;

  > span {
    margin: 0 auto;
  }
}

.footer_page {
  position: absolute;
  bottom: 0.5rem;
  margin: 0 auto;

  > button {
    padding: 0.3rem;
    border-radius: 0.5rem;
    border: none;
    margin: 0 0.5rem;
  }

  > span {
    font-size: 0.9rem;
  }

}

.open_form_register {
  transition: 1s;
  width: 100%;
  display: flex;
  height: 20rem;
}


@media (min-width: 500px) {
  .btn_add_car {
    min-width: 6rem;

    > span {
      display: flex;
    }
  }
}

@media (min-width: 600px) {
  #header_title {
    width: 80%;
  }
  
  .item_container {
    margin-top: 1.2rem;
    justify-content: space-between;
    gap: 0;

    > i {
      align-self: center;
    }
  }

  #item_header {
    width: 80%;
  }
}

@media (min-width: 915px) {
  #container {
    width: 50rem;
  }

  .header_container {
    font-size: 1rem;
  }
  
  .list_items {
    font-size: 0.9rem;
  }
}
*/
</style>
