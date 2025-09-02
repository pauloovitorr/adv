<?php 
include_once('../../scripts.php');
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/pessoas/pessoas.css">
    <title>Pessoas</title>
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