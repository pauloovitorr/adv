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

    .pai_topo {
        width: 96%;
        max-width: 1260px;
        background-color: white;
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin: 0 auto;
    }

    .logo_pesquisa {
        width: 30%;
        display: flex;
        align-items: center;
        gap: 16px;

    }

    .logo_pesquisa img {
        width: 60px;
    }

    .container_pesquisa {
        width: 70%;
        height: 40px;
        display: flex;
        align-items: center;

    }

    .container_pesquisa input {
        width: 90%;
        height: 100%;
        padding: 8px;
        border: none;
        border-top-left-radius: 8px;
        border-bottom-left-radius: 8px;
        background-color: #F7F7F7;
        outline: none;
    }

    .container_pesquisa input::placeholder {
        font-size: 16px;
    }

    .icone_pesquisa {
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

    .icone_pesquisa img {
        width: 16px;
    }

    .infos_menu {
        width: 30%;
        display: flex;
        align-items: center;
        justify-content: space-between;

    }

    .visu_site a {
        text-decoration: none;
        color: #878787;
    }

    .icone_notificacao {
        width: 25px;
        height: 35px;
     
    }

    .icone_notificacao img {
        width: 100%;
        height: 100%;
    }

    .perfil {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        background-image: linear-gradient(180deg, rgb(216, 216, 216), #c3c3c3);
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .perfil div {
        color: white;
        font-size: 22px;
        font-weight: 500;
    }

    .btn_add {
        width: 130px;
        height: 40px;
        margin-top: 5px;
        position: relative;
        /* border: 1px solid red; */
    }

    .btn_add button {
        width: 100%;
        height: 35px;
        background-color: #378ADC;
        border: none;
        border-radius: 8px;
        color: white;
        font-size: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
    }

    .btn_add button img {
        width: 15px;
    }

    .opcoes_add {
    width: 100%;
    border: 1px solid #c3c3c3;
    background-color: white;
    opacity: 0;
    pointer-events: none;
    position: absolute;
    top: 40px; /* Ajusta a posição do menu */
    left: 0; /* Alinha o menu ao botão */
    z-index: 100;
    transition: opacity 0.3s;
    border-radius: 8px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); /* Adiciona sombra */
}

.opcoes_add::before {
    content: ''; 
    position: absolute;
    top: -12px; 
    left: 100px; 
    width: 0;
    height: 0;
    border-left: 10px solid transparent;
    border-right: 10px solid transparent; 
    border-bottom: 12px solid #c3c3c3;
    z-index: 101;
}

.opcoes_add::after {
    content: ''; 
    position: absolute;
    top: -10px; 
    left: 102px; 
    width: 0;
    height: 0;
    border-left: 8px solid transparent; 
    border-right: 8px solid transparent; 
    border-bottom: 10px solid white; 
    z-index: 102;
}

    .opcoes_add ul{
        list-style: none;
        overflow: hidden;
        border-radius: 8px;
    }

    .opcoes_add ul li{
        width: 100%;
        padding: 8px;
        transition: 0.3s;
    }

    .opcoes_add ul li:hover{
        background-color: #c3c3c3;
    
    }

    .btn_add:hover .opcoes_add,
    .btn_add .opcoes_add:hover {
        opacity: 1;
        pointer-events: auto;
        
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
            <div class="icone_notificacao"><img src="../img/notificacao.png" alt="icone de notificação"></div>

            <div class="btn_add">
                <button>Adicionar <img src="../img/seta_down.png" alt="seta para baixo"> </button>

                <div class="opcoes_add">
                    <ul>
                        <li>aaaaa</li>
                        <li>aaaaa</li>
                        <li>aaaaa</li>
                    </ul>
                </div>

            </div>

            <div class="perfil">
                <div> <?php echo substr($_SESSION['nome'], 0, 2) ?> </div>
            </div>

        </div>
    </div>

</div>