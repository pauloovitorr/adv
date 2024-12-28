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
        .infos_pagina {
            /* border: 1px solid red; */
            width: 100%;
            /* height: 50px; */
            margin-top: 24px;
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            gap: 16px;
        }


        .opcoes_funcoes {
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

        .btn_adicionar {
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

        .div_buscar {
            height: 40px;
            display: flex;
            align-items: center;
            color: #7D7D7D;
            padding: 0px 8px;
            border-radius: 8px;
            transition: .3s;
            background-color: #FCFCFC;
            border: 1px solid rgb(232, 232, 232);
        }



        .div_buscar input {
            height: 100%;
            width: 100px;
            padding: 10px 8px;
            border-radius: 8px;
            border: none;
            background-color: transparent;
            color: #7D7D7D;
            outline: none;
            transition: .3s;
            font-size: 14px;
        }

        .div_buscar input:focus,
        .div_buscar input:not(:placeholder-shown) {
            width: 200px;
        }


        .div_buscar img {
            width: 12px;
        }


        .div_buscar select {
            height: 100%;
            width: 150px;
            border-radius: 8px;
            border: none;
            background-color: transparent;
            color: #7D7D7D;
            outline: none;
            transition: .3s;
        }

        .div_buscar i {
            color: #7D7D7D;
            font-size: 14px;
        }
    </style>



</head>

<body>
    <main class="container_principal">
        <div class="pai_conteudo">

            <div class="infos_pagina">
                <button> <i class="fa-regular fa-user"></i> 3 Pessoas Cadastradas </button>
                <button> <i class="fa-regular fa-user"></i> 3 Pessoas Cadastradas </button>
                <button> <i class="fa-regular fa-user"></i> 3 Pessoas Cadastradas </button>
            </div>

            <div class="opcoes_funcoes">
                <button class="btn_adicionar"> <i class="fa-solid fa-plus"></i> Novo Contato </button>

                <div class="div_buscar">
                    <label for="buscar_pessoas"><i class="fa-solid fa-magnifying-glass"></i></label>
                    <input type="text" id="buscar_pessoas" name="buscar_pessoas" placeholder="Buscar">
                </div>

                <div class="div_buscar">
                    <img src="../img/funil.png" alt="">
                    <select name="" id="a">
                        <option value="">Filtrar</option>
                        <option value="aaa">Clientes</option>
                        <option value="aaa">Partes Contrárias</option>
                        <option value="aa">Com Processso em Andamento</option>
                        <option value="aaaa"> Sem Processso em Andamento </option>
                    </select>
                </div>


                <div class="div_buscar">
                    <img src="../img/funil.png" alt="">
                    <select name="" id="a">
                        <option value="">ordenar</option>
                        <option value="aaa">Nome da Pessoa</option>
                        <option value="aa">Mais Recentes</option>
                        <option value="aaaa">Mais Antigos</option>
                    </select>
                </div>

                <div class="div_buscar">
                    <img src="../img/funil.png" alt="">
                    <select name="" id="a">
                        <option value="">Exportar</option>
                        <option value="aaa">Excel (.xlsx)</option>
                        <option value="aa">Imprimir (.pdf)</option>
                    </select>
                </div>

              
            </div>

        </div>
    </main>





</body>

</html>