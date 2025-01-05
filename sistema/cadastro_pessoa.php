<?php

include_once('./menu_lat.php');
include_once('./topo.php');

?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro</title>


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

        .container_cadastro {
            width: 100%;
            height: auto;
            /* padding: 16px; */
            /* border: 1px solid red; */
            margin-top: 24px;
            border-radius: 8px;
            background-color: white;
        }

        .topo_sessao {
            width: 100%;
            height: 80px;
            padding: 16px;
            display: flex;
            align-items: center;
            gap: 18px;
        }

        .topo_sessao i {
            color: var(--azul-fundo);
            font-size: 32px;
        }

        .topo_sessao p {
            color: var(--azul-fundo);
            font-size: 24px;
        }

        hr {
            height: 1px;
            background-color: #F1F1F1;
            border: none;
        }

        .container_field_form {
            height: auto;
            width: 100%;
            padding: 16px;
        }

        fieldset {
            padding: 16px 50px;
            border-radius: 8px;
            border: 1px solid #F1F1F1;
            ;
        }

        .bloco-formulario {
            padding: 0px 8px;

        }

        fieldset legend {
            width: auto;
            padding: 0px 8px;
            color: var(--azul-fundo);
            font-size: 20px;
        }


        .container_inputs {
            width: 100%;
            min-height: 90px;
            display: grid;
            grid-template-columns: repeat(5, minmax(150px, 1fr));
            gap: 16px;
            /* border: 1px solid red; */
        }

        .container_input {
            min-width: auto;
            height: auto;
            /* border: 1px solid red; */
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        .container_input select {
            height: 35px;
            border-radius: 5px;
            font-size: 14px;
            color: var(--preto-primario);
            border: 1px solid var(--branco-secundario)
        }

        .container_input input,
        .custo_add_arquivo {
            height: 35px;
            border-radius: 5px;
            border: 1px solid var(--branco-secundario);
            padding-left: 4px;
            font-size: 14px;
            color: var(--preto-primario);
        }

        .container_input input::placeholder,
        .custo_add_arquivo {
            color: rgb(153, 153, 153);
            font-size: 13px;
            font-weight: 400;
        }


        .custo_add_arquivo {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 4px;
            cursor: pointer;
        }


        #nome,
        #nome_mae {
            grid-column: span 2;
            min-width: auto;
        }


        /* Estilizando o input file */
        .custom-file-input {
            display: none;
        }




        @media (max-width: 1024px) {
            .container_inputs {
                grid-template-columns: repeat(3, 1fr);
                /* Reduz para 3 colunas em telas médias */
            }
        }

        @media (max-width: 768px) {
            .container_inputs {
                grid-template-columns: repeat(2, 1fr);
                /* Reduz para 2 colunas em telas pequenas */
            }
        }

        @media (max-width: 480px) {
            .container_inputs {
                grid-template-columns: 1fr;
                /* Uma coluna em telas muito pequenas */
            }
        }
    </style>

</head>

<body>
    <main class="container_principal">
        <div class="pai_conteudo">

            <section class="container_etapa_cadastro">
                <div class="etapa">
                    <div class="num bg_selecionado">1º</div>
                    <div class="descricao color_selecionado">Cadastro</div>
                </div>

                <div class="separador bg_selecionado"></div>

                <div class="etapa">
                    <div class="num">2º</div>
                    <div class="descricao">Documentos</div>
                </div>

                <div class="separador"></div>

                <div class="etapa">
                    <div class="num">3º</div>
                    <div class="descricao">Finalização</div>
                </div>

            </section>

            <section class="container_cadastro">
                <div class="topo_sessao">
                    <i class="fa-solid fa-user-plus"></i>
                    <p>Nova Pessoa</p>
                </div>

                <hr>

                <div class="container_field_form">
                    <fieldset>
                        <legend>Dados Pessoais</legend>

                        <div class="bloco-formulario">

                            <form action="" method="POST">

                                <div class="container_inputs">
                                    <div class="container_input">
                                        <label for="pessoa">Pessoa <span style="color: red;">*</span></label>
                                        <select name="pessoa" id="pessoa">
                                            <option value="pf">Pessoa Física</option>
                                            <option value="pf">Pessoa Jurídica</option>
                                        </select>
                                    </div>

                                    <div class="container_input" id="nome">
                                        <label for="nome_pessoa">Nome <span style="color: red;">*</span></label>
                                        <input type="text" name="nome" id="nome_pessoa" placeholder="EX: Paulo Vitor">
                                    </div>

                                    <div class="container_input">
                                        <label for="num_doc">CPF/CNPJ</label>
                                        <input type="text" name="num_doc" id="num_doc" placeholder="999.999.99-99">
                                    </div>

                                    <div class="container_input">
                                        <label for="rg">RG</label>
                                        <input type="text" name="rg" id="rg" placeholder="Número do RG">
                                    </div>


                                </div>

                                <div class="container_inputs">
                                    <div class="container_input">
                                        <label for="dt_nascimento">Data de nascimento</label>
                                        <input type="date" name="dt_nascimento" id="dt_nascimento">
                                    </div>

                                    <div class="container_input">
                                        <label for="profissao">Profissão</label>
                                        <input type="text" name="profissao" id="profissao" placeholder="EX: Autônomo">
                                    </div>

                                    <div class="container_input">
                                        <label for="ctps">CTPS</label>
                                        <input type="text" name="ctps" id="ctps" placeholder="Carteira de trabalho">
                                    </div>

                                    <div class="container_input">
                                        <label for="pis">PIS/PASEP</label>
                                        <input type="text" name="pis" id="pis" placeholder="999.9999.999-9">
                                    </div>

                                    <div class="container_input">
                                        <label for="origem">Origem <span style="color: red;">*</span></label>
                                        <select name="pessoa" name="origem" id="origem">
                                            <option value="">Selecione a origem</option>
                                            <option value="">Escritório</option>
                                            <option value="">Indicação</option>
                                            <option value="">Anúncio</option>
                                            <option value="">Facebook</option>
                                        </select>
                                    </div>


                                </div>

                                <div class="container_inputs">
                                    <div class="container_input">
                                        <label for="sexo">Sexo</label>
                                        <select name="sexo" id="sexo">
                                            <option value="">Selecione o sexo</option>
                                            <option value="">Masculino</option>
                                            <option value="">Feminino</option>
                                        </select>
                                    </div>

                                    <div class="container_input">
                                        <label for="estado_civil">Estado civil</label>
                                        <select name="estado_civil" id="estado_civil">
                                            <option value="">Selecione o estado civil</option>
                                            <option value="">Casado(a)</option>
                                            <option value="">Divorciado(a)</option>
                                            <option value="">Separado(a)</option>
                                            <option value="">Solteiro(a)</option>
                                            <option value="">União Estável</option>
                                        </select>
                                    </div>

                                    <div class="container_input" id="nome_mae">
                                        <label for="nome_mae">Nome da mãe</label>
                                        <input type="text" name="nome_mae" id="nome_mae" placeholder="EX: Eliete de Sousa">
                                    </div>

                                    <div class="container_input">
                                        <label for="foto">Foto</label>
                                        <input type="file" name="foto" id="foto" class="custom-file-input">
                                        <div class="custo_add_arquivo" onclick="document.getElementById('foto').click()">
                                            <p>Selecione o arquivo</p>
                                            <i class="fa-solid fa-arrow-up-from-bracket"></i>
                                        </div>
                                    </div>



                                </div>

                            </form>

                        </div>

                    </fieldset>
                </div>



            </section>


        </div>
    </main>




</body>

</html>