<?php

include_once('../scripts.php');


?>


<style>
    .container_topo {
        width: calc(100% - 80px);
        height: 90px;
        margin-left: 80px; 
        display: flex;
        align-items: center;  
    }

    .pai_topo{
        width: 96%;
        max-width: 1260px;
        background-color: white;
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin: 0 auto;
    }

    .logo_pesquisa{
        width: 30%;
        display: flex;
        align-items: center;
        gap: 16px;
     
    }

    .logo_pesquisa img{
        width: 60px;
    }

    .container_pesquisa{
        width: 70%;
        height: 40px;
        display: flex;
        align-items: center;
       
    }

    .container_pesquisa input{
        width: 90%;
        height: 100%;
        padding: 8px;
        border: none;
        border-top-left-radius: 8px;
        border-bottom-left-radius: 8px;
        background-color: #F7F7F7;
        outline: none;
    }

    .container_pesquisa input::placeholder{
        font-size: 16px;
    }

    .icone_pesquisa{
        height: 100%;
        padding-right: 8px;
        display: flex;
        align-items: center;
        border: none;
        background-color: #F7F7F7;
        cursor: pointer;
        border-top-right-radius: 8px;
        border-bottom-right-radius: 8px;
    }

    .icone_pesquisa img{
        width: 16px;
    }

    .infos_menu{
        width: 30%;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .visu_site a{
        text-decoration: none;
        color: #878787;
    }

    .icone_notificacao{
        width: 30px;
    }

    .icone_notificacao img{
        width: 100%;
    }

    .perfil{
        width: 50px;
        height: 50px;
        border-radius: 50%;
        background-image: linear-gradient(180deg,rgb(216, 216, 216), #c3c3c3);
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .perfil div{
        color: white;
        font-size: 22px;
        font-weight: 500;
    }

</style>

<div class="container_topo">

 <div class="pai_topo">
 <div class="logo_pesquisa">
        <img src="../img/logo.png" alt="logo">

        <div class="container_pesquisa">
            <input type="text" placeholder="Pesquisar">
            <div class="icone_pesquisa"><img src="../img/icone_pesquisa.png" alt="logo"></div>
        </div>
    </div>

  <div class="infos_menu">
    <div class="visu_site"><a href="">Visualizar site</a></div>
    <div class="icone_notificacao"><img src="../img/notificacao.png" alt=""></div>
    <div> <button>Adicionar</button> </div>
    <div class="perfil"> <div> <?php echo substr($_SESSION['nome'], 0, 2) ?> </div> </div>

  </div>
 </div>

</div>