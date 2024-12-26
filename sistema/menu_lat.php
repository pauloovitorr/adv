<style>
    .menu_lateral {
        position: fixed;
        width: 80px;
        height: 100vh;
        background-color: #061124;
        color: white;
        transition: width .5s;
    }

    .menu_lateral:hover {
        width: 200px;
    }

    .menu_lateral ul {
        list-style: none;
        width: 90%;
        margin: 0 auto;
        padding: 0;
    }

    .menu_lateral ul li {
        display: flex;
        justify-content: center;
        /* Alinhado ao centro por padrão */
        align-items: center;
        margin: 40px 0;
        transition: justify-content .3s;
        /* Transição suave */
    }

    .menu_lateral:hover ul li {
        justify-content: flex-start;
        /* Alinhado à esquerda no hover */
    }

    .menu_lateral ul li a {
        color: white;
        text-decoration: none;
        display: flex;
        align-items: center;
    }

    .menu_lateral ul li a p {
        opacity: 0;
        max-width: 0;
        overflow: hidden;
        white-space: nowrap;
        margin-left: 8px;
        transition: opacity 0.3s, max-width 0.3s;
    }

    .menu_lateral:hover ul li a p {
        opacity: 1;
        max-width: 150px;
    }

    .div_opcao_menu_lat {
        display: flex;
        align-items: center;
    }

    .div_opcao_menu_lat img {
        width: 30px;
    }
</style>


<nav class="menu_lateral">
    <ul>
        <li><a href="">
                <div class="div_opcao_menu_lat"><img src="../img/painel.png" alt="">
                    <p>PAINEL</p>
                </div>
            </a></li>
        <li><a href="">
                <div class="div_opcao_menu_lat"><img src="../img/crm.png" alt="">
                    <p>CRM</p>
                </div>
            </a></li>
        <li><a href="">
                <div class="div_opcao_menu_lat"><img src="../img/pessoas.png" alt="">
                    <p>PESSOAS</p>
                </div>
            </a></li>
        <li><a href="">
                <div class="div_opcao_menu_lat"><img src="../img/processos.png" alt="">
                    <p>PROCESSOS</p>
                </div>
            </a></li>
        <li><a href="">
                <div class="div_opcao_menu_lat"><img src="../img/atividades.png" alt="">
                    <p>ATIVIDADES</p>
                </div>
            </a></li>
        <li><a href="">
                <div class="div_opcao_menu_lat"><img src="../img/site.png" alt="">
                    <p>SITE</p>
                </div>
            </a></li>
        <li><a href="">
                <div class="div_opcao_menu_lat"><img src="../img/leads.png" alt="">
                    <p>LEADS</p>
                </div>
            </a></li>
        <li><a href="">
                <div class="div_opcao_menu_lat"><img src="../img/financeiro.png" alt="">
                    <p>FINANCEIRO</p>
                </div>
            </a></li>
            <li><a href="">
                <div class="div_opcao_menu_lat"><img src="../img/relatorios.png" alt="">
                    <p>RELATÓRIOS</p>
                </div>
            </a></li>
        <li><a href="">
                <div class="div_opcao_menu_lat"><img src="../img/config.png" alt="">
                    <p>CONFIGURAÇÕES</p>
                </div>
            </a></li>

    </ul>
</nav>