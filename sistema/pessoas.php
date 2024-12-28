<?php 

include_once('./menu_lat.php');
include_once('./topo.php');



?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <link rel="stylesheet" href="./css/geral.css">

    <style>
        .infos_pagina{
            /* border: 1px solid red; */
            width: 100%;
            /* height: 50px; */
            margin-top: 24px;
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            gap: 16px;
        }

        
        .opcoes_funcoes{
            /* border: 1px solid red; */
            width: 100%;
            /* height: 50px; */
            margin-top: 24px;
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            gap: 16px;
        }

        .infos_pagina button {
            width: auto;
            height: 40px;
            background-color: #FCFCFC;
            border: 1px solid rgb(232, 232, 232);
            padding: 10px 8px;
            border-radius: 8px;
            color: #7D7D7D;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            font-size: 14px;
            line-height: 14px;
        }

        .btn_adicionar{
            width: auto;
            height: 40px;
            background-color: #2F81F7;
            border: 1px solid rgb(232, 232, 232);
            padding: 10px 8px;
            border-radius: 8px;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            font-size: 14px;
            line-height: 14px;
            cursor: pointer;
        }

        .infos_pagina button img{
            width: 14px;
        }

        
        .btn_adicionar img{
            width: 12px;
        }

    </style>

</head>
<body>
    <main class="container_principal">
        <div class="pai_conteudo">

            <div class="infos_pagina">
                <button> <img src="../img/icone_pessoa.png" alt="ícone de pessoa"> 3 Pessoas Cadastradas </button>
                <button> <img src="../img/icone_pessoa.png" alt="ícone de pessoa"> 3 Pessoas Cadastradas </button>
                <button> <img src="../img/icone_pessoa.png" alt="ícone de pessoa"> 3 Pessoas Cadastradas </button>
            </div>

            <div class="opcoes_funcoes">
                <button class="btn_adicionar"> <img src="../img/icone_add.png" alt="ícone de pessoa"> Novo Contato </button>
                <!-- <button> <img src="../img/icone_pessoa.png" alt="ícone de pessoa"> 3 Pessoas Cadastradas </button>
                <button> <img src="../img/icone_pessoa.png" alt="ícone de pessoa"> 3 Pessoas Cadastradas </button> -->
            </div>

        </div>
    </main>
</body>
</html>