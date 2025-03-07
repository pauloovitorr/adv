<?php

include_once('./menu_lat.php');
include_once('./topo.php');

?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Documentos</title>


    <style>
        .container_etapa_cadastro {
            width: 100%;
            height: 80px;
            margin-top: 24px;
            border-radius: 8px;
            background-color: white;
            display: flex;
            align-items: center;
            justify-content: space-between;
            /* border: 1px solid red; */
        }

        .etapa {
            /* border: 1px solid red; */
            width: auto;
            display: flex;
            align-items: center;
            padding-left: 16px;
            padding-right: 16px;
            gap: 16px;
        }

        .num {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background-color: #D9D9D9;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            color: white;
            font-size: 20px;
            font-weight: 500;
        }


        .descricao {
            color: #D9D9D9;
            font-size: 18px;
        }

        .separador {
            width: 20%;
            height: 1px;
            background-color: #D9D9D9;

        }


        .bg_selecionado {
            background-color: var(--azul-fundo);
        }

        .color_selecionado {
            color: var(--preto-primario);
        }
    </style>

</head>


<main class="container_principal">

    <div class="pai_conteudo">

        <section class="container_etapa_cadastro">
            <div class="etapa">
                <div class="num bg_selecionado">1º</div>
                <div class="descricao color_selecionado">Cadastro</div>
            </div>

            <div class="separador bg_selecionado"></div>

            <div class="etapa">
                <div class="num bg_selecionado">2º</div>
                <div class="descricao color_selecionado">Documentos</div>
            </div>

            <div class="separador bg_selecionado"></div>

            <div class="etapa">
                <div class="num">3º</div>
                <div class="descricao">Finalização</div>
            </div>

        </section>


        <section class="container_cadastro"> 
            <h1>Desenvolvimento</h1>
        </section>

    </div>

</main>



</body>

</html>