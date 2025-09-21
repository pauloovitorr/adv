<?php

include_once('../../scripts.php');

$id_user = $_SESSION['cod'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['cliente']) && !empty($_POST['grupo_acao']) && !empty($_POST['tipo_acao']) && !empty($_POST['referencia']) && $_POST['acao'] == 'cadastrar') {

    //     $cliente             = $conexao->escape_string(htmlspecialchars($_POST['cliente'] ?? ''));
    //     $contrario           = $conexao->escape_string(htmlspecialchars($_POST['contrario'] ?? ''));
    //     $grupo_acao          = $conexao->escape_string(htmlspecialchars($_POST['grupo_acao'] ?? ''));
    //     $tipo_acao           = $conexao->escape_string(htmlspecialchars($_POST['tipo_acao'] ?? ''));
    //     $referencia          = $conexao->escape_string(htmlspecialchars($_POST['referencia'] ?? ''));
    //     $numero_processo     = $conexao->escape_string(htmlspecialchars($_POST['numero_processo'] ?? ''));
    //     $numero_protocolo    = $conexao->escape_string(htmlspecialchars($_POST['numero_protocolo'] ?? ''));
    //     $processo_originario = $conexao->escape_string(htmlspecialchars($_POST['processo_originario'] ?? ''));
    //     $valor_causa         = $conexao->escape_string(htmlspecialchars($_POST['valor_causa'] ?? ''));
    //     $valor_honorarios    = $conexao->escape_string(htmlspecialchars($_POST['valor_honorarios'] ?? ''));
    //     $etapa_kanban        = $conexao->escape_string(htmlspecialchars($_POST['etapa_kanban'] ?? ''));
    //     $contingenciamento   = $conexao->escape_string(htmlspecialchars($_POST['contingenciamento'] ?? ''));
    //     $data_requerimento   = $conexao->escape_string(htmlspecialchars($_POST['data_requerimento'] ?? ''));
    //     $resultado_processo  = $conexao->escape_string(htmlspecialchars($_POST['resultado_processo'] ?? ''));
    //     $observacao          = $conexao->escape_string(htmlspecialchars($_POST['observacao'] ?? ''));
    //     $acao                = $conexao->escape_string(htmlspecialchars($_POST['acao'] ?? ''));

    //     $sql = "INSERT INTO processo (
    //     cliente_id,
    //     contrario_id,
    //     grupo_acao,
    //     tipo_acao_id,
    //     referencia,
    //     num_processo,
    //     num_protocolo,
    //     processo_originario,
    //     valor_causa,
    //     valor_honorarios,
    //     etapa_kanban,
    //     contingenciamento,
    //     data_requerimento,
    //     resultado_processo,
    //     observacao
    // ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    //     $stmt = $conexao->prepare($sql);

    //     $stmt->bind_param(
    //         "iisssssssssssss",
    //         $cliente,
    //         $contrario,
    //         $grupo_acao,
    //         $tipo_acao,
    //         $referencia,
    //         $numero_processo,
    //         $numero_protocolo,
    //         $processo_originario,
    //         $valor_causa,
    //         $valor_honorarios,
    //         $etapa_kanban,
    //         $contingenciamento,
    //         $data_requerimento,
    //         $resultado_processo,
    //         $observacao
    //     );

    //     if ($stmt->execute()) {
    //         echo "Processo cadastrado com sucesso! ID: " . $stmt->insert_id;
    //     } else {
    //         echo "Erro ao cadastrar processo: " . $stmt->error;
    //     }

    $stmt->close();
}

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
                                        <label for="cliente">Cliente <span style="color: red;">*</span></label>
                                        <select name="cliente" id="cliente" required>
                                            <option value="">Selecione o Cliente</option>
                                            <option value="73">Paulo Vitor</option>
                                        </select>
                                    </div>

                                    <div class="container_input">
                                        <label for="contrario">Contrário</label>
                                        <select name="contrario" id="contrario">
                                            <option value="">Selecione o Contrário</option>
                                            <option value="84">Fernanda</option>
                                        </select>
                                    </div>


                                    <div class="container_input">
                                        <label for="grupo_acao">Grupo de Ação <span style="color: red;">*</span></label>
                                        <select name="grupo_acao" id="grupo_acao" required>
                                            <option value="">Selecione...</option>
                                            <option value="administrativo">Administrativo</option>
                                            <option value="trabalhista">Trabalhista</option>
                                            <option value="civil">Cível</option>
                                            <option value="familia">Família e Sucessões</option>
                                            <option value="previdenciario">Previdenciário</option>
                                            <option value="tributario">Tributário</option>
                                            <option value="consumidor">Consumidor</option>
                                            <option value="empresarial">Empresarial</option>
                                            <option value="penal">Penal</option>
                                            <option value="imobiliario">Imobiliário</option>
                                            <option value="eleitoral">Eleitoral</option>
                                        </select>
                                    </div>

                                    <div class="container_input">
                                        <label for="tipo_acao">Tipo de Ação <span style="color: red;">*</span></label>
                                        <select name="tipo_acao" id="tipo_acao" required>
                                            <option value="">Selecione o grupo primeiro...</option>
                                        </select>
                                    </div>

                                    <div class="container_input">
                                        <label for="referencia">Referência <span style="color: red;">*</span></label>
                                        <input
                                            type="text"
                                            name="referencia"
                                            id="referencia"
                                            value=""
                                            maxlength="8"
                                            placeholder="Ex: PA_001 "
                                            required>
                                    </div>


                                </div>

                                <div class="container_inputs">

                                    <div class="container_input">
                                        <label for="numero_processo">Núm do Processo</label>
                                        <input
                                            type="text"
                                            name="numero_processo"
                                            id="numero_processo"
                                            value=""
                                            minlength="4"
                                            maxlength="40"
                                            placeholder="Ex: 0001234-56.2023.8.26.0100">
                                    </div>

                                    <div class="container_input">
                                        <label for="numero_protocolo">Núm do Protocolo</label>
                                        <input
                                            type="text"
                                            name="numero_protocolo"
                                            id="numero_protocolo"
                                            value=""
                                            minlength="4"
                                            maxlength="40"
                                            placeholder="Ex: PROT_2023_00123">
                                    </div>

                                    <div class="container_input">
                                        <label for="processo_originario">Processo Originário</label>
                                        <input
                                            type="text"
                                            name="processo_originario"
                                            id="processo_originario"
                                            value=""
                                            minlength="4"
                                            maxlength="40"
                                            placeholder="Ex: 0009876-54.2020.8.26.0100">
                                    </div>

                                    <div class="container_input">
                                        <label for="valor_causa">Valor da Causa</label>
                                        <input
                                            type="text"
                                            name="valor_causa"
                                            id="valor_causa"
                                            value=""
                                            placeholder="Ex: 150.000,00">
                                    </div>

                                    <div class="container_input">
                                        <label for="valor_honorarios">Valor dos Honorários</label>
                                        <input
                                            type="text"
                                            name="valor_honorarios"
                                            id="valor_honorarios"
                                            value=""
                                            placeholder="Ex: 30.000,00">
                                    </div>

                                </div>

                                <div class="container_inputs">


                                    <div class="container_input">
                                        <label for="etapa_kanban">Etapa Kanban</label>
                                        <select name="etapa_kanban" id="etapa_kanban" required>
                                            <option value="">Selecione...</option>
                                            <option value="analise_do_caso">Análise do Caso</option>
                                            <option value="negociacao">Negociação</option>
                                            <option value="aguardando_documentos">Aguardando Documentos</option>
                                            <option value="proposta">Proposta</option>
                                            <option value="andamento">Processo em Andamento</option>
                                            <option value="fechamento">Fechamento</option>
                                        </select>
                                    </div>


                                    <div class="container_input">
                                        <label for="contingenciamento">Contingenciamento</label>
                                        <select name="contingenciamento" id="contingenciamento" required>
                                            <option value="">Selecione...</option>
                                            <option value="provável/chance alta">Provável/Chance Alta</option>
                                            <option value="possível/talvez">Possível/Talvez</option>
                                            <option value="remota/difícil">Remota/Difícil</option>
                                        </select>
                                    </div>


                                    <div class="container_input">
                                        <label for="data_requerimento">Data Requerimento</label>
                                        <input
                                            type="date"
                                            name="data_requerimento"
                                            id="data_requerimento"
                                            required>
                                    </div>

                                    <div class="container_input">
                                        <label for="resultado_processo">Resultado Processo</label>
                                        <input
                                            type="text"
                                            name="resultado_processo"
                                            id="resultado_processo"
                                            value=""
                                            minlength="4"
                                            maxlength="100"
                                            placeholder="Ex: Sentença favorável, acordo homologado, improcedente..."
                                            required>
                                    </div>

                                    <div class="container_input">
                                        <label for="observacao">Observação</label>
                                        <input
                                            type="text"
                                            name="observacao"
                                            id="observacao"
                                            value=""
                                            maxlength="255"
                                            placeholder="Digite aqui alguma observação relevante">
                                    </div>


                                </div>

                                <input type="hidden" name="acao" value="<?php echo ($_GET['acao'] ?? '') ? 'editar' : 'cadastrar' ?>">

                            </div>

                        </fieldset>

                        <div class="container_btn_submit">
                            <button type="submit" class="btn_cadastrar"> <?php echo ($_GET['acao'] ?? '') ? 'Editar Processo' : 'Cadastrar Processo' ?> </button>
                        </div>

                    </form>



                </div>



            </section>


        </div>
    </main>



    <script>
        const tiposPorGrupo = {
            administrativo: [{
                    value: "nulidade_licitacao",
                    text: "Nulidade de Licitação"
                },
                {
                    value: "improbidade",
                    text: "Improbidade Administrativa"
                },
                {
                    value: "ms_administrativo",
                    text: "Mandado de Segurança Administrativo"
                }
            ],
            trabalhista: [{
                    value: "reclamacao_trabalhista",
                    text: "Reclamação Trabalhista"
                },
                {
                    value: "verbas_rescisorias",
                    text: "Verbas Rescisórias"
                },
                {
                    value: "adicional_insalubridade",
                    text: "Adicional de Insalubridade/Periculosidade"
                }
            ],
            civil: [{
                    value: "acao_cobranca",
                    text: "Ação de Cobrança"
                },
                {
                    value: "indenizacao_danos",
                    text: "Indenização por Danos"
                },
                {
                    value: "execucao_titulo",
                    text: "Execução de Título Extrajudicial"
                }
            ],
            familia: [{
                    value: "divorcio",
                    text: "Divórcio"
                },
                {
                    value: "guarda_pensao",
                    text: "Guarda e Pensão Alimentícia"
                },
                {
                    value: "inventario",
                    text: "Inventário e Partilha"
                }
            ],
            previdenciario: [{
                    value: "aposentadoria_invalidez",
                    text: "Aposentadoria por Invalidez"
                },
                {
                    value: "auxilio_doenca",
                    text: "Auxílio-Doença"
                },
                {
                    value: "pensao_morte",
                    text: "Pensão por Morte"
                }
            ],
            tributario: [{
                    value: "execucao_fiscal",
                    text: "Execução Fiscal"
                },
                {
                    value: "ms_tributario",
                    text: "Mandado de Segurança Tributário"
                },
                {
                    value: "anulatoria_debito",
                    text: "Ação Anulatória de Débito Fiscal"
                }
            ],
            consumidor: [{
                    value: "acao_revisional",
                    text: "Ação Revisional de Contrato"
                },
                {
                    value: "planos_saude",
                    text: "Ação contra Planos de Saúde"
                },
                {
                    value: "produto_defeituoso",
                    text: "Indenização por Produto/Serviço Defeituoso"
                }
            ],
            empresarial: [{
                    value: "recuperacao_judicial",
                    text: "Recuperação Judicial"
                },
                {
                    value: "falencia",
                    text: "Falência"
                },
                {
                    value: "dissolucao_sociedade",
                    text: "Dissolução de Sociedade"
                }
            ],
            penal: [{
                    value: "defesa_criminal",
                    text: "Defesa Criminal"
                },
                {
                    value: "habeas_corpus",
                    text: "Habeas Corpus"
                },
                {
                    value: "revisao_criminal",
                    text: "Revisão Criminal"
                }
            ],
            imobiliario: [{
                    value: "despejo",
                    text: "Despejo"
                },
                {
                    value: "usucapiao",
                    text: "Usucapião"
                },
                {
                    value: "acao_renovatoria",
                    text: "Ação Renovatória de Aluguel"
                }
            ],
            eleitoral: [{
                    value: "prestacao_contas",
                    text: "Prestação de Contas Eleitorais"
                },
                {
                    value: "aije",
                    text: "Ação de Investigação Judicial Eleitoral (AIJE)"
                }
            ]
        };

        document.getElementById("grupo_acao").addEventListener("change", function() {
            const grupoSelecionado = this.value;
            const tipoSelect = document.getElementById("tipo_acao");

            // limpa os tipos anteriores
            tipoSelect.innerHTML = "<option value=''>Selecione...</option>";

            // adiciona os tipos conforme o grupo
            if (tiposPorGrupo[grupoSelecionado]) {
                tiposPorGrupo[grupoSelecionado].forEach(tipo => {
                    const option = document.createElement("option");
                    option.value = tipo.value;
                    option.textContent = tipo.text;
                    tipoSelect.appendChild(option);
                });
            }
        });
    </script>


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



    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#cliente').select2({
                placeholder: "Selecione o Cliente",
                minimumInputLength: 1, // Só busca AJAX após digitar 1 caractere
                ajax: {
                    url: 'buscar_usuarios.php', // Seu endpoint (comente se ainda não estiver pronto)
                    type: 'GET',
                    dataType: 'json',
                    delay: 250,
                    data: function(params) {
                        return {
                            q: params.term // Parâmetro de busca
                        };
                    },
                    processResults: function(data) {
                        return {
                            results: data // Assume que o PHP retorna array como [{id: x, text: 'Nome'}]
                        };
                    }
                }
            });
        });
    </script>



    <style>
        .select2-container .select2-selection--single {
            height: 35px;
            border-radius: 5px;
            font-size: 12px;
            color: var(--preto-primario) !important;
            border: 1px solid var(--branco-secundario) !important;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 32px !important;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 32px !important;
        }
    </style>

</body>

</html>