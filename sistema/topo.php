<?php

include_once('../scripts.php');


?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>

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
    position: relative; 
    cursor: pointer; 
}

.user{
    color: white;
    font-size: 22px;
    font-weight: 500;
}

/* Estilos do dropdown de perfil */
.opcoes_perfil {
    width: 150px;
    border: 1px solid #c3c3c3;
    background-color: white;
    opacity: 0;
    pointer-events: none;
    position: absolute;
    top: 60px; 
    right: 0; 
    z-index: 100;
    transition: opacity 0.3s;
    border-radius: 8px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); 
}

.opcoes_perfil::before {
    content: ''; 
    position: absolute;
    top: -12px; 
    right: 20px; 
    width: 0;
    height: 0;
    border-left: 10px solid transparent;
    border-right: 10px solid transparent; 
    border-bottom: 12px solid #c3c3c3;
    z-index: 101;
}

.opcoes_perfil::after {
    content: ''; 
    position: absolute;
    top: -10px; 
    right: 22px; 
    width: 0;
    height: 0;
    border-left: 8px solid transparent; 
    border-right: 8px solid transparent; 
    border-bottom: 10px solid white; 
    z-index: 102;
}

.opcoes_perfil ul {
    list-style: none;
    overflow: hidden;
    border-radius: 8px;
}

.opcoes_perfil ul li {
    width: 100%;
    padding: 8px;
    transition: 0.3s;
    font-size: 14px;
}

.opcoes_perfil ul li a{
    text-decoration: none;
    color:rgb(58, 58, 58);
    width: 100%;
    height: 100%;
    display: block;
    padding: 10px 0px;
}

.opcoes_perfil ul li:hover {
    background-color: #c3c3c3;
}

/* Mostra o dropdown ao passar o mouse */
.perfil:hover .opcoes_perfil,
.opcoes_perfil:hover {
    opacity: 1;
    pointer-events: auto;
}

.btn_add {
    width: 130px;
    height: 40px;
    margin-top: 5px;
    position: relative;
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
    cursor: pointer;
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

.opcoes_add ul {
    list-style: none;
    overflow: hidden;
    border-radius: 8px;
}

.opcoes_add ul li {
    width: 100%;
    padding: 8px;
    transition: 0.3s;
    font-size: 14px;
}

.opcoes_add ul li:hover {
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
                <div class="user"><?php echo substr($_SESSION['nome'], 0, 2) ?></div>
                <div class="opcoes_perfil">
                    <ul>
                        <li>Perfil</li>
                        <li>Configurações</li>
                        <li><a href="./logout.php">Sair</a></li>
                    </ul>
                </div>
            </div>

        </div>
    </div>

</div>
