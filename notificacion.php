<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title></title>
</head>
<style>
    body{
        background-color: #dbdbdb;
    }

    .texto{
        position:fixed;
        top: 50%;
        left: 50%;
        width:40em;
        height:24em;
        margin-top: -20em; /*set to a negative number 1/2 of your height*/
        margin-left: -20em; /*set to a negative number 1/2 of your width*/
        border: 1px solid #ccc;
        background-color: rgba(196, 196, 196, 0.5);
        text-align: center;
        animation: blinker 3s linear infinite;
    }

    @keyframes blinker {
      50% {
        opacity: 0;
      }
    }

    .texto:hover{
        background-color: rgba(196, 196, 196, 1);
        color:#cc0000;
    }

    .cajaimg{
        position:fixed;
        top: 50%;
        left: 50%;
        width:40em;
        height:24em;
        margin-top: -12em; /*set to a negative number 1/2 of your height*/
        margin-left: -20em; /*set to a negative number 1/2 of your width*/
        border: 1px solid #ccc;
        background-color: rgba(196, 196, 196, 0.5);
    }

    img{
        max-width: 100%;
        max-height: 100%;
    }

</style>
<body>
    <div class="texto">
        <H2>PARA ACCEDER AL ISTEMA PARA EL SEGUIMIENTO DE MANTENIMIENTOS DE LA COORDINACIÓN DE INFORMÁTICA DEBE UTILIZAR EL NAVEGADOR CHROME</H2>
    </div>
    <div class="cajaimg">
        <img src="dist\img\chrome.jpg">
    </div>
     

</body>
</html>
