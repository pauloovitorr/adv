<?php

include_once('../../scripts.php');

$id_user = $_SESSION['cod'];


?>


<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/pessoas/cadastro_pessoa.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <title>ADV Conectado</title>
</head>

<?php
include_once('../geral/menu_lat.php');
include_once('../geral/topo.php');
?>

<body>
    <main class="container_principal">
        <div class="pai_conteudo">

            <section class="container_etapa_cadastro">
                <div class="etapa">
                    <div class="num bg_selecionado">1º</div>
                    <div class="descricao color_selecionado">Cadastro</div>
                </div>

                <div class="separador"></div>
                <div class="etapa">
                    <div class="num">2º</div>
                    <div class="descricao">Documentos</div>
                </div>

                <div class="separador"></div>
                <div class="etapa">
                    <div class="num">2°</div>
                    <div class="descricao">Finalização</div>
                </div>

            </section>

            <section class="container_cadastro">
                <div class="topo_sessao">
                    <i class="fa-solid fa-user-plus"></i>
                    <p>Novo Processo</p>
                </div>

                <hr>

                <div class="container_field_form">
                    <form action="" method="POST" enctype="multipart/form-data" id="<?php echo ($_GET['acao'] ?? '') ? 'editar' : 'cadastrar' ?>">
                        <fieldset>
                            <legend>Dados do Processo</legend>

                            <div class="bloco-formulario">

                                <div class="container_inputs">

                                    <div class="container_input">
                                        <label for="pessoa">Cliente <span style="color: red;">*</span></label>
                                        <select name="tipo_pessoa" id="pessoa" required>
                                            <option value="PF">Paulo Vitor</option>
                                            <option value="">Carlos Antônio</option>
                                        </select>
                                    </div>

                                    <div class="container_input">
                                        <label for="pessoa">Contrário</label>
                                        <select name="tipo_pessoa" id="pessoa">
                                            <option value="">Paulo Vitor</option>
                                            <option value="">Carlos Antônio</option>
                                        </select>
                                    </div>

                                    <div class="container_input">
                                        <label for="referencia">Referência</label>
                                        <input
                                            type="text"
                                            name="referencia"
                                            id="referencia"
                                            value=""
                                            minlength="4"
                                            maxlength="40"
                                            placeholder="Ex: PA_001 "
                                            required>
                                    </div>

                                    <div class="container_input">
                                        <label for="">Grupo de Ação</label>
                                        <select name="tipo_pessoa" id="" required>
                                            <option value="">Administrativo</option>
                                            <option value="">Trabalhista</option>
                                        </select>
                                    </div>

                                    <div class="container_input">
                                        <label for="">Tipo de Ação</label>
                                        <select name="tipo_pessoa" id="" required>
                                            <option value="">Nulidade de Licitação</option>
                                        </select>
                                    </div>

                                </div>

                                <div class="container_inputs">

                                    <div class="container_input">
                                        <label for="referencia">Núm do Processo</label>
                                        <input
                                            type="text"
                                            name="referencia"
                                            id="referencia"
                                            value=""
                                            minlength="4"
                                            maxlength="40"
                                            placeholder="Ex: PA_001 "
                                            required>
                                    </div>

                                    <div class="container_input">
                                        <label for="referencia">Núm do Protocolo</label>
                                        <input
                                            type="text"
                                            name="referencia"
                                            id="referencia"
                                            value=""
                                            minlength="4"
                                            maxlength="40"
                                            placeholder="Ex: PA_001 "
                                            required>
                                    </div>

                                    <div class="container_input">
                                        <label for="referencia">Processo Originário</label>
                                        <input
                                            type="text"
                                            name="referencia"
                                            id="referencia"
                                            value=""
                                            minlength="4"
                                            maxlength="40"
                                            placeholder="Ex: PA_001 "
                                            required>
                                    </div>

                                    <div class="container_input">
                                        <label for="referencia">Valor da Causa</label>
                                        <input
                                            type="text"
                                            name="referencia"
                                            id="referencia"
                                            value=""
                                            minlength="4"
                                            maxlength="40"
                                            placeholder="Ex: PA_001 "
                                            required>
                                    </div>

                                    <div class="container_input">
                                        <label for="referencia">Valor dos Honorários</label>
                                        <input
                                            type="text"
                                            name="referencia"
                                            id="referencia"
                                            value=""
                                            minlength="4"
                                            maxlength="40"
                                            placeholder="Ex: PA_001 "
                                            required>
                                    </div>

                                    

                                </div>

                                <!-- <div class="container_inputs">
                                    <div class="container_input" id="container_dt_nascimento">
                                        <label for="dt_nascimento">Data de nascimento</label>
                                        <input
                                            type="date"
                                            name="dt_nascimento"
                                            id="dt_nascimento"
                                            value="<?php echo htmlspecialchars($dados_pessoa['dt_nascimento'] ?? '') ?>">
                                    </div>

                                    <div class="container_input">
                                        <label for="profissao">Profissão</label>
                                        <input
                                            type="text"
                                            name="profissao"
                                            id="profissao"
                                            value="<?php echo htmlspecialchars($dados_pessoa['profissao'] ?? '') ?>"
                                            minlength="4"
                                            maxlength="40"
                                            placeholder="EX: Autônomo">
                                    </div>

                                    <div class="container_input" id="container_ctps">
                                        <label for="ctps">CTPS</label>
                                        <input
                                            type="text"
                                            name="ctps"
                                            id="ctps"
                                            value="<?php echo htmlspecialchars($dados_pessoa['ctps'] ?? '') ?>"
                                            minlength="4"
                                            maxlength="40"
                                            placeholder="Carteira de trabalho">
                                    </div>

                                    <div class="container_input" id="container_pis">
                                        <label for="pis">PIS/PASEP</label>
                                        <input
                                            type="text"
                                            name="pis"
                                            id="pis"
                                            value="<?php echo htmlspecialchars($dados_pessoa['pis'] ?? '') ?>"
                                            minlength="11"
                                            maxlength="14"
                                            placeholder="999.9999.999-9">
                                    </div>

                                    <div class="container_input">
                                        <label for="origem">Origem <span style="color: red;">*</span></label>
                                        <select name="origem" id="origem" required>
                                            <option value="">Selecione a origem</option>
                                            <option value="Escritório" <?php echo ($dados_pessoa['origem'] ?? '') == 'Escritório' ? 'selected' : '' ?>>Escritório</option>
                                            <option value="Indicação" <?php echo ($dados_pessoa['origem'] ?? '') == 'Indicação' ? 'selected' : '' ?>>Indicação</option>
                                            <option value="Anúncio" <?php echo ($dados_pessoa['origem'] ?? '') == 'Anúncio' ? 'selected' : '' ?>>Anúncio</option>
                                            <option value="Facebook" <?php echo ($dados_pessoa['origem'] ?? '') == 'Facebook' ? 'selected' : '' ?>>Facebook</option>
                                        </select>
                                    </div>
                                </div> -->




                            </div>




                        </fieldset>



                        <div class="container_btn_submit">
                            <button type="submit" class="btn_cadastrar"> <?php echo ($_GET['acao'] ?? '') ? 'Editar Pessoa' : 'Cadastrar Pessoa' ?> </button>
                        </div>

                    </form>



                </div>



            </section>


        </div>
    </main>



    <script src="https://cdn.jsdelivr.net/npm/jquery-mask-plugin@1.14.16/dist/jquery.mask.min.js"></script>
    <!-- <script>
        $(document).ready(function() {

            // mascaras campos

            // mascara nome e nome mae
            $('#nome_pessoa').on('input', function() {
                const valor = $(this).val();
                // Filtrar apenas letras e acentos
                const valorFiltrado = valor.replace(/[^a-zA-ZÀ-ÖÙ-öù-ÿ\s]/g, '');
                $(this).val(valorFiltrado);
            });

            $('#nome_mae').on('input', function() {
                const valor = $(this).val();
                // Filtrar apenas letras e acentos
                const valor_digitado = valor.replace(/[^a-zA-ZÀ-ÖÙ-öù-ÿ\s]/g, '');
                $(this).val(valor_digitado);
            });

            $('#cidade').on('input', function() {
                const valor = $(this).val();
                // Filtrar apenas letras e acentos
                const valor_digitado = valor.replace(/[^a-zA-ZÀ-ÖÙ-öù-ÿ\s]/g, '');
                $(this).val(valor_digitado);
            });

            var CpfCnMaskBehavior = function(val) {
                var len = val.replace(/\D/g, '').length;
                if (len <= 11) {
                    return '000.000.000-009';
                }
                return '00.000.000/0000-00';
            };

            // CPF/CNPJ
            var cpfCnpjpOptions = {
                onKeyPress: function(val, e, field, options) {
                    field.mask(CpfCnpjMaskBehavior.apply({}, arguments), options);
                },
                onComplete: function(val, e, field, options) {
                    var len = val.replace(/\D/g, '').length;
                    if (len === 11) {
                        $(field).mask('000.000.000-00');
                    } else if (len === 14) {
                        $(field).mask('00.000.000/0000-00');
                    }
                }
            };

            $('#num_doc').mask(CpfCnpjMaskBehavior, cpfCnpjpOptions);

            // Remove a máscara antes de enviar o formulário (opcional)
            // $('form').on('submit', function() {
            //     var documento = $('#num_doc').val();
            //     $('#num_doc').val(documento.replace(/[^\d]+/g, ''));
            // });


            // data de nascimento
            const hoje = new Date(); // Data atual
            const ano = hoje.getFullYear();
            const mes = String(hoje.getMonth() + 1).padStart(2, '0'); // Mês atual (ajustado para 0-11)
            const dia = String(hoje.getDate()).padStart(2, '0'); // Dia atual

            const dataMaxima = `${ano}-${mes}-${dia}`;

            // Define o atributo 'max' no campo de data
            $('#dt_nascimento').attr('max', dataMaxima);

            // pis
            $('#pis').mask('999.9999.999-9');

            // valida foto
            // Valida foto
            $('#foto').on('change', function() {
                const file = this.files[0]; // Obtém o primeiro arquivo selecionado
                const fileName = file.name.toLowerCase(); // Nome do arquivo em minúsculas
                const allowedExtensions = ['.jpg', '.jpeg', '.png']; // Extensões permitidas

                // Verifica se a extensão do arquivo está na lista permitida
                const isValid = allowedExtensions.some(ext => fileName.endsWith(ext));

                if (!isValid) {
                    Swal.fire({
                        icon: "error",
                        title: "Arquivo Inválido",
                        text: "Por favor, selecione um arquivo de imagem válido (JPG, JPEG, PNG).",
                    });
                    $(this).val(''); // Reseta o campo de input
                    $('#nome-arquivo').text('Selecione o arquivo'); // Volta para o texto padrão
                    $('.custo_add_arquivo').css('color', 'rgb(153, 153, 153)')
                } else {
                    // Atualiza o texto com o nome do arquivo selecionado
                    $('#nome-arquivo').text(file.name);
                    $('.custo_add_arquivo').css('color', '#141414')
                }
            });


            // telefones
            $('#telefone_principal').mask('(99) 99999-9999')
            $('#telefone_secundario').mask('(99) 99999-9999')
            $('#celular').mask('(99) 9999-9999')
            $('#cep').mask('99999-999')

            // num casa
            $('#num').on('input', function() {
                // Permite apenas números
                this.value = this.value.replace(/\D/g, '');

                // Limita a 6 caracteres
                if (this.value.length > 6) {
                    this.value = this.value.slice(0, 6);
                }
            });

        });
    </script> -->
    <!-- Ajax para cadastro de pessoa -->
    <!-- <script>
        $(document).ready(function() {

            let form = $("#cadastrar").length ? $("#cadastrar") : '';
            // Validação ao submeter o formulário
            $(form).on('submit', function(e) {

                $('.btn_cadastrar').attr('disabled', true)

                Swal.fire({
                    title: "Carregando...",
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                e.preventDefault();


                // Ajax para realizar o cadastro
                let dados_form = new FormData(this);
                $.ajax({
                    url: 'cadastro_pessoa.php',
                    type: 'POST',
                    data: dados_form,
                    processData: false,
                    contentType: false,
                    dataType: 'json',
                    success: function(res) {
                        if (res.status === 'erro') {

                            Swal.fire({
                                icon: "error",
                                title: "Erro",
                                text: res.message
                            });

                            $('.btn_cadastrar').attr('disabled', false)


                        } else if (res.status === 'success') {
                            Swal.close();

                            setTimeout(() => {
                                Swal.fire({
                                    title: "Sucesso!",
                                    text: res.message,
                                    icon: "success"
                                }).then((result) => {
                                    window.location.href = "./docs_pessoa.php?tkn=" + res.token;
                                });
                            }, 300);
                        }


                    },
                    error: function(err) {
                        Swal.fire({
                            icon: "error",
                            title: "Erro",
                            text: err.message,
                        });
                        $('.btn_cadastrar').attr('disabled', false)
                    }
                })



            });
        })
    </script> -->

    <!-- Ajax para atualização de pessoa -->
    <!-- <script>
        $(document).ready(function() {
            // Validação ao submeter o formulário
            let form = $("#editar").length ? $("#editar") : '';

            $(form).on('submit', function(e) {

                $('.btn_cadastrar').attr('disabled', true)

                Swal.fire({
                    title: "Carregando...",
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                e.preventDefault();


                // Ajax para realizar a atualização
                let dados_form = new FormData(this);
                $.ajax({
                    url: 'cadastro_pessoa.php',
                    type: 'POST',
                    data: dados_form,
                    processData: false,
                    contentType: false,
                    dataType: 'json',
                    success: function(res) {
                        if (res.status === 'erro') {

                            Swal.fire({
                                icon: "error",
                                title: "Erro",
                                text: res.message
                            });

                            $('.btn_cadastrar').attr('disabled', false)


                        } else if (res.status === 'success') {
                            // console.log('sucesso')
                            Swal.close();

                            setTimeout(() => {
                                Swal.fire({
                                    title: "Sucesso!",
                                    text: res.message,
                                    icon: "success"
                                }).then((result) => {
                                    window.location.reload()
                                });
                            }, 300);
                        }


                    },
                    error: function(err) {
                        Swal.fire({
                            icon: "error",
                            title: "Erro",
                            text: err.message,
                        });
                        $('.btn_cadastrar').attr('disabled', false)
                    }
                })



            });
        })
    </script> -->


    <!-- jQuery primeiro -->
    <!-- <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script> -->
    <!-- Depois Select2 -->
    <!-- <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script> -->

    <!-- Só depois seus scripts -->
    <!-- <script>
        $(document).ready(function() {
            $('#usuarios').select2({
                placeholder: "Digite para buscar usuário",
                minimumInputLength: 3, // só faz a busca a partir de 2 caracteres
                ajax: {
                    url: 'buscar_usuarios.php', // seu endpoint em PHP
                    type: 'GET', // ou 'POST'
                    dataType: 'json',
                    delay: 250, // atraso para não fazer várias requisições rápidas
                    data: function(params) {
                        return {
                            q: params.term // termo digitado pelo usuário
                        };
                    },
                    processResults: function(data) {
                        return {
                            results: data
                        };
                    }
                }
            });
        });
    </script> -->

</body>

</html>