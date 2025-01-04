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
           border: 1px solid  #F1F1F1;;
        }

        .bloco-formulario{
            padding: 0px 8px;
          
        }

        fieldset legend {
            width: auto;
            padding: 0px 8px;
            color: var(--azul-fundo);
            font-size: 20px;
        }


        .container_inputs{
            width: 100%;
            height: 80px;
            /* border: 1px solid red; */
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .container_input{
            min-width: 20%;
            height: auto;
            /* border: 1px solid red; */
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        .container_input select{
            height: 35px;
            border-radius: 5px;
            border: 1px solid var(--branco-secundario)
        }

        .container_input input{
            height: 35px;
            border-radius: 5px;
            border: 1px solid var(--branco-secundario);
            padding-left: 4px;
        }

        #nome{
            width: 300px;
           
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
                                            <label for="pessoa">Pessoa</label>
                                            <select name="pessoa" id="pessoa">
                                                <option value="pf">Pessoa Física</option>
                                                <option value="pf">Pessoa Jurídica</option>
                                            </select>
                                        </div>

                                        <div class="container_input">
                                            <label for="pessoa">Nome <span style="color: red;">*</span></label>
                                            <input type="text" name="nome" id="nome" placeholder="EX: Paulo Vitor">
                                        </div>

                                        <div class="container_input">
                                            <label for="pessoa">CPF/CNPJ</label>
                                            <input type="text" name="num_doc"  placeholder="EX: Paulo Vitor">
                                        </div>

                                        <div class="container_input">
                                            <label for="pessoa">RG</label>
                                            <input type="text" name="rg"  placeholder="EX: Paulo Vitor">
                                        </div>
                                </div>

                                <div class="container_inputs">
                                        <div class="container_input">
                                            <label for="pessoa">Pessoa</label>
                                            <select name="pessoa" id="pessoa">
                                                <option value="pf">Pessoa Física</option>
                                                <option value="pf">Pessoa Jurídica</option>
                                            </select>
                                        </div>

                                        <div class="container_input">
                                            <label for="pessoa">Nome <span style="color: red;">*</span></label>
                                            <input type="text" name="nome" id="nome" placeholder="EX: Paulo Vitor">
                                        </div>

                                        <div class="container_input">
                                            <label for="pessoa">CPF/CNPJ</label>
                                            <input type="text" name="num_doc"  placeholder="EX: Paulo Vitor">
                                        </div>

                                        <div class="container_input">
                                            <label for="pessoa">RG</label>
                                            <input type="text" name="rg"  placeholder="EX: Paulo Vitor">
                                        </div>
                                </div>

                                <div class="container_inputs">
                                        <div class="container_input">
                                            <label for="pessoa">Pessoa</label>
                                            <select name="pessoa" id="pessoa">
                                                <option value="pf">Pessoa Física</option>
                                                <option value="pf">Pessoa Jurídica</option>
                                            </select>
                                        </div>

                                        <div class="container_input">
                                            <label for="pessoa">Nome <span style="color: red;">*</span></label>
                                            <input type="text" name="nome" id="nome" placeholder="EX: Paulo Vitor">
                                        </div>

                                        <div class="container_input">
                                            <label for="pessoa">CPF/CNPJ</label>
                                            <input type="text" name="num_doc"  placeholder="EX: Paulo Vitor">
                                        </div>

                                        <div class="container_input">
                                            <label for="pessoa">RG</label>
                                            <input type="text" name="rg"  placeholder="EX: Paulo Vitor">
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