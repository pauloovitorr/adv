<?php include_once('../../scripts.php');?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pessoas</title>

    <style>
        .container_lista_pessoas {
            /* border: 1px solid red; */
            margin-top: 48px;
        }

        .container_lista_pessoas table {
            border-collapse: collapse;
            width: 100%;
        }

        .container_lista_pessoas table thead td {
            /* border: 1px solid red; */
            height: 50px;
            padding-left: 16px;
            /* text-align: center; */
            font-size: 14px;
            color: rgb(19, 19, 19);
        }

        .container_lista_pessoas table thead td:nth-child(1),
        .container_nome {
            width: 35%;

        }

        .container_lista_pessoas table thead td:nth-child(2),
        .container_lista_pessoas table thead td:nth-child(3),
        .container_contato,
        .container_cidade {
            width: 20%;
        }


        .container_dt,
        .container_lista_pessoas table thead td:nth-child(4) {
            width: 15%;
        }

        .container_lista_pessoas table thead td:nth-child(5),
        .container_acao {
            width: 10%;
        }


        .dados_pessoa {
            border-radius: 8px;
            background-color: white;
            margin-left: -1px;
            margin-top: 24px;
            width: 100%;
            height: 90px;
            box-shadow: rgba(0, 0, 0, 0.04) 0px 3px 5px;
            display: flex;
            position: relative;
            cursor: pointer;
        }

        .cliente::before {
            content: '';
            position: absolute;
            width: 5px;
            height: 100%;
            background-color: #1C6FCB;
            border-top-left-radius: 8px;
            border-bottom-left-radius: 8px;
        }

        .contrario::before {
            content: '';
            position: absolute;
            width: 5px;
            height: 100%;
            background-color: #CD0909;
            border-top-left-radius: 8px;
            border-bottom-left-radius: 8px;
        }


        .conteudo_pessoa {
            height: 100%;
            /* border: 1px solid #1C6FCB; */
        }

        .container_nome {
            display: flex;
            align-items: center;
            /* border: 1px solid #1C6FCB; */
        }

        .icone {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background-image: linear-gradient(180deg, rgb(216, 216, 216), #c3c3c3);
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            color: white;
            font-size: 22px;
            font-weight: 500;
            margin-left: 16px;
            margin-right: 16px;
        }

        .nome_pessoa {
            width: 75%;
            /* border: 1px solid red; */
        }

        .nome_pessoa p {
            color: #141414;
            font-size: 18px;
        }

        .nome_pessoa span {
            color: #909090;
            font-size: 12px;
        }

        .container_contato,
        .container_cidade,
        .container_dt,
        .container_acao {
            /* border: 1px solid red; */
            display: flex;
            align-items: center;
            padding-left: 16px;
        }

        .container_contato a img {
            width: 20px;
        }

        .container_contato a {
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 4px;
            color: rgb(94, 94, 94);
            font-size: 14px;
        }

        .container_cidade p,
        .container_dt p {
            color: rgb(94, 94, 94);
            font-size: 14px;
        }


        .opcoes_acao {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            border: 1px solid rgb(94, 94, 94);
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            color: rgb(94, 94, 94);
            font-size: 18px;
            font-weight: 500;
            cursor: pointer;
            position: relative;
            transition: .3s;

        }

        .opcoes_acao:hover {
            background-color: var(--hover-opcoes);
        }

        .opcoes_pessoa {
            min-width: 150px;
            height: auto;
            position: absolute;
            top: 40px;
            border: 1px solid #c3c3c3;
            background-color: white;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            /* opacity: 0;
            pointer-events: none; */
            transition: .4s;
            z-index: 2;
            display: none;
        }

        .show {
            opacity: 1;
            pointer-events: all;
        }

        .opcoes_pessoa ul {
            width: 100%;
            height: 100%;
            list-style: none;
        }

        .opcoes_pessoa ul a {
            text-decoration: none;
            gap: 4px;
            color: rgb(94, 94, 94);
            font-size: 14px;

        }

        .opcoes_pessoa ul a li {
            width: 100%;
            padding: 8px;
            transition: .3s;
        }

        .opcoes_pessoa ul a li i {
            margin-right: 4px;
        }

        .opcoes_pessoa ul a li:hover {
            background-color: var(--hover-opcoes);
        }
    </style>

</head>

<?php
include_once('../geral/menu_lat.php');
include_once('../geral/topo.php');
?>

<body>
    <main class="container_principal">
        <div class="pai_conteudo">

            <div class="infos_pagina">
                <button> <i class="fa-regular fa-user"></i> 3 Pessoas Cadastradas </button>
                <button> <i class="fa-regular fa-user"></i> 3 Pessoas Cadastradas </button>
                <button> <i class="fa-regular fa-user"></i> 3 Pessoas Cadastradas </button>
            </div>

            <div class="opcoes_funcoes">
                <button class="btn_adicionar" id="add_pessoa"> <i class="fa-solid fa-plus"></i> Nova Pessoa </button>

                <div class="div_pai_funcoes">
                    <label for="buscar_pessoas"><i class="fa-solid fa-magnifying-glass"></i></label>
                    <input type="text" id="buscar_pessoas" name="buscar_pessoas" placeholder="Buscar">
                </div>

                <div class="div_pai_funcoes">
                    <img src="../img/funil.png" alt="">
                    <select name="" id="a">
                        <option value="">Filtrar</option>
                        <option value="aaa">Clientes</option>
                        <option value="aaa">Partes Contrárias</option>
                        <option value="aa">Com Processso em Andamento</option>
                        <option value="aaaa"> Sem Processso em Andamento </option>
                    </select>
                </div>


                <div class="div_pai_funcoes">
                    <i class="fa-solid fa-arrow-up-wide-short"></i>
                    <select name="" id="a">
                        <option value="">ordenar</option>
                        <option value="aaa">Nome da Pessoa</option>
                        <option value="aa">Mais Recentes</option>
                        <option value="aaaa">Mais Antigos</option>
                    </select>
                </div>

                <div class="div_pai_funcoes">
                    <i class="fa-solid fa-file-arrow-down"></i>
                    <select name="" id="a">
                        <option value="">Exportar</option>
                        <option value="aaa">Excel (.xlsx)</option>
                        <option value="aa">Imprimir (.pdf)</option>
                    </select>
                </div>


            </div>


            <section class="container_lista_pessoas">

                <table>

                    <thead>
                        <tr>
                            <td>Nome</td>
                            <td>Contato</td>
                            <td>Cidade/UF</td>
                            <td>Data de Cadastro</td>
                            <td>Ações</td>
                        </tr>
                    </thead>

                    <tbody>
                        <tr>

                            <td colspan="5">

                                <div class="dados_pessoa cliente">
                                    <div class="conteudo_pessoa container_nome">
                                        <div class="icone">LA</div>
                                        <div class="nome_pessoa">
                                            <p>Laisla Maria Candido</p>
                                            <span>Cliente</span>
                                        </div>
                                    </div>

                                    <div class="conteudo_pessoa container_contato">
                                        <a href="https://web.whatsapp.com/send?phone=+55" class="whatsapp"><img src="../../img/whatsapp.png" alt="whatsapp"> (18) 99987-5566 </a>
                                    </div>

                                    <div class="conteudo_pessoa container_cidade">
                                        <p>Presidente Prudente/SP</p>
                                    </div>

                                    <div class="conteudo_pessoa container_dt">
                                        <p>28/12/2024</p>
                                    </div>

                                    <div class="conteudo_pessoa container_acao">
                                        <div class="opcoes_acao">
                                            <i class="fa fa-ellipsis-h"></i>

                                            <div class="opcoes_pessoa">
                                                <ul>
                                                    <a href="">
                                                        <li><i class="fa-regular fa-file-lines"></i> Ficha</li>
                                                    </a>

                                                    <a href="javascript:void(0)">
                                                        <li><i class="fa-regular fa-id-card"></i> Documentos</li>
                                                    </a>

                                                    <a href="">
                                                        <li><i class="fa-regular fa-folder"></i> Criar Processo</li>
                                                    </a>

                                                    <a href="">
                                                        <li><i class="fa-regular fa-pen-to-square"></i> Editar</li>
                                                    </a>

                                                    <a href="">
                                                        <li><i class="fa-regular fa-trash-can"></i> Excluir</li>
                                                    </a>
                                                </ul>
                                            </div>
                                        </div>

                                    </div>

                                </div>
                            </td>
                        </tr>

                    

                     
                    </tbody>

                </table>

            </section>


        </div>
    </main>


    
<!-- Script para exibir as opções quando os 3 prontinhos da ação são clicados -->
    <script>
        $(document).ready(function() {
            $('.opcoes_acao').on('click', function(e) {
                e.stopPropagation(); // Impede o clique no elemento de propagar para o documento

                var opcoesPessoa = $(this).find('.opcoes_pessoa');

                // Verifica se o menu está visível e alterna
                if (opcoesPessoa.is(':visible')) {
                    opcoesPessoa.hide(); // Esconde o menu
                } else {
                    opcoesPessoa.show(); // Exibe o menu
                }
            });

            $(document).on('click', function() {
                // Esconde qualquer menu aberto ao clicar fora
                $('.opcoes_pessoa').hide();
            });
        });
    </script>

    <script>
        $(document).ready(function(){
            $('#add_pessoa').click(function(){
                window.open('./cadastro_pessoa.php', '_self');
            })
        })
    </script>

</body>

</html>