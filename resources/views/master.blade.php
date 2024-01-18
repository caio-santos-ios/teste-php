<!DOCTYPE html>
<html lang="pt-Br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/app.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@100;400;500;700;900&display=swap" rel="stylesheet">
    <title>Controle Revisões</title>
</head>
<style>
    * {
        margin: 0;
        padding: 0;
    }
    
    body {
        box-sizing: border-box;
        background-color: #cbd5e1;
        font-family: 'Roboto', sans-serif;
    }
    
    input[type="number"]::-webkit-inner-spin-button,
    input[type="number"]::-webkit-outer-spin-button {
      -webkit-appearance: none;
    }
    

    li {
        list-style: none;
    }

    a {
        text-decoration: none
    }
</style>
<body>
   <div id="app">
        @yield('content')
   </div>

   @vite('resources/js/app.js')
</body>
</html>