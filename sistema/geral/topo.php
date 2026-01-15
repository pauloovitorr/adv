<?php

$link_modelo = "";




?>


<!-- Css Geral e topo das páginas internas como btn add, buscar, filtrar etc... -->
<link rel="stylesheet" href="../css/geral.css">
<!-- <link rel="stylesheet" href="../css/topo_funcoes.css"> -->

<!-- Font awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
    integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg=="
    crossorigin="anonymous" referrerpolicy="no-referrer" />
<!-- Jquery  -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"
    integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
<!-- sweetalert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- Balão de title -->
<script src="https://unpkg.com/popper.js@1"></script>
<script src="https://unpkg.com/tippy.js@5"></script>
<link rel="stylesheet" href="https://unpkg.com/tippy.js@5/dist/backdrop.css" />

<!-- Js geral, exemplo balão do menu lateral -->
<script src="../js/geral.js" defer></script>

<style>
    .container_topo {
        height: 90px;
        background: white;
        border-bottom: 1px solid #e2e8f0;
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 0 32px;
        width: calc(100% - 80px);
        margin-left: 80px;

    }

    .pai_topo {
        width: 100%;
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

    .user {
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

    .opcoes_perfil ul a {
        text-decoration: none;
        color: rgb(58, 58, 58);
    }

    .opcoes_perfil ul li:hover {
        background-color: rgb(235, 235, 235);
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

        background: #4299e1;
        color: white;
        border: none;
        padding: 8px 16px;
        border-radius: 6px;
        font-size: 14px;
        font-weight: 500;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 8px;
        transition: all 0.2s ease;

    }

    .btn_add button :hover {
        background: #3182ce;
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
        top: 40px;
        /* Ajusta a posição do menu */
        left: 0;
        /* Alinha o menu ao botão */
        z-index: 100;
        transition: opacity 0.3s;
        border-radius: 8px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        /* Adiciona sombra */
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

        transition: 0.3s;
        font-size: 14px;
    }

    .opcoes_add ul li:hover {
        background-color: rgb(235, 235, 235);
    }

    .opcoes_add ul li a {
        text-decoration: none;
        color: rgb(58, 58, 58);
        display: block;
        padding: 8px;
        font-size: 12px;
    }

    .btn_add:hover .opcoes_add,
    .btn_add .opcoes_add:hover {
        opacity: 1;
        pointer-events: auto;
    }

    .search-container {
        position: relative;
    }

    .search-input {
        width: 300px;
        height: 40px;
        padding: 0 16px 0 40px;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        background: #f8f9fa;
        font-size: 14px;
    }

    .search-input:focus {
        outline: none;
        border-color: #4299e1;
        background: white;
    }

    .container_resultados {
        position: absolute;
        /* margin-top: 12px; */
        width: 100%;
        height: auto;
        /* padding: 10px; */
        background-color: white;
        border-radius: 8px;
        z-index: 101;
        border: 1px solid #c3c3c3;
        border-top: none;
        transition: opacity 0.3s;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
         overflow: hidden;
         display: none;
    }

    .container_resultados ul {
        list-style: none;
        
    }

    .container_resultados ul li {
        font-size: 14px;
         
    }

    .container_resultados ul li:hover {
        background-color: rgb(235, 235, 235);
    }

    .container_resultados ul li a {
        text-decoration: none;
        color: rgb(58, 58, 58);
        display: block;
        padding: 8px;
        width: 100%;
        height: 100%;
        font-size: 12px;
    }

    .sem_resultado{
        padding: 8px;
        width: 100%;
        height: 100%;
        font-size: 12px;
    }

    .search-icon {
        position: absolute;
        left: 12px;
        top: 50%;
        transform: translateY(-50%);
        color: #a0aec0;
        font-size: 14px;
    }

    .header-right {
        display: flex;
        align-items: center;
        gap: 20px;
    }

    .header-link {
        color: #6c757d;
        text-decoration: none;
        font-size: 14px;
    }

    .notification-icon {
        color: #a0aec0;
        font-size: 18px;
        cursor: pointer;
    }

    .search-container input::placeholder {
        font-size: 12px;
    }
</style>

<header class="container_topo">

    <div class="pai_topo">
        <div class="logo_pesquisa">
            <img src="../../img/logo.png" alt="logo">

            <div class="search-container">
                <div>
                    <input type="text" placeholder="Pesquise nome pessoa ou ref processo" class="search-input">
                    <i class="fas fa-search search-icon"></i>
                </div>
                <div class="container_resultados">
                    <ul>
                        
                    </ul>
                </div>
            </div>
        </div>



        <div class="infos_menu">

            <div class="header-right">
                <a href="<?php echo $link_modelo ?? '' ?>" id="visualizar_site" target="_blank"
                    style="text-decoration:none"><span class="header-link">Visualizar site</span></a>
                <i class="fas fa-bell notification-icon"></i>
            </div>

            <div class="btn_add">
                <button>Adicionar <img src="../../img/seta_down.png" alt="seta para baixo"> </button>

                <div class="opcoes_add">
                    <ul>
                        <li><a href="/adv/sistema/pessoa/cadastro_pessoa.php">Pessoa</a></li>
                        <li><a href="/adv/sistema/processo/cadastro_processo.php">Processo</a></li>
                        <li><a href="/adv/sistema/agenda/agenda.php">Compromisso</a></li>
                    </ul>
                </div>

            </div>

            <div class="perfil">
                <div class="user"><?php echo substr($_SESSION['nome'], 0, 2) ?></div>
                <div class="opcoes_perfil">
                    <ul>
                        <a href="/adv/sistema/site/modelos.php">
                            <li>Modelos de Site</li>
                        </a>
                        <a href="/adv/sistema/ia/chat.php">
                            <li>Chat com IA</li>
                        </a>
                        <a href="/adv/sistema/configuracoes/configuracoes.php">
                            <li>Configurações</li>
                        </a>
                        <a href="/adv/sistema/geral/logout.php">
                            <li>Sair</li>
                        </a>
                    </ul>
                </div>
            </div>

        </div>
    </div>



</header>