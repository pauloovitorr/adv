<?php

include_once('../../scripts.php');

$id_user = $_SESSION['cod'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['busca_cliente'])) {

    $nome               = $conexao->escape_string(htmlspecialchars($_POST['busca_cliente'] ?? ''));
    $pessoa_localizadas = [];

    if ($nome !== 'padrao' &&  !empty($nome)) {
        $sql_busca_cliente   = "SELECT id_pessoa, nome FROM pessoas WHERE tipo_parte = 'cliente' AND nome LIKE '%$nome%' AND usuario_config_id_usuario_config = $id_user order by nome limit 15;";
        $res = $conexao->query($sql_busca_cliente);

        while ($pessoa = $res->fetch_assoc()) {
            array_push($pessoa_localizadas, $pessoa);
        }
    } else {
        $sql_busca_cliente   = "SELECT id_pessoa, nome FROM pessoas WHERE tipo_parte = 'cliente'  AND usuario_config_id_usuario_config = $id_user order by nome limit 15;";
        $res = $conexao->query($sql_busca_cliente);

        while ($pessoa = $res->fetch_assoc()) {
            array_push($pessoa_localizadas, $pessoa);
        }
    }

    echo json_encode($pessoa_localizadas);
    $conexao->close();
    exit;
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['busca_contrario'])) {

    $nome               = $conexao->escape_string(htmlspecialchars($_POST['busca_contrario'] ?? ''));
    $pessoa_localizadas = [];

    if ($nome !== 'padrao' &&  !empty($nome)) {
        $sql_busca_contrario   = "SELECT id_pessoa, nome FROM pessoas WHERE tipo_parte = 'contrário' AND nome LIKE '%$nome%' AND usuario_config_id_usuario_config = $id_user order by nome limit 15;";
        $res = $conexao->query($sql_busca_contrario);

        while ($pessoa = $res->fetch_assoc()) {
            array_push($pessoa_localizadas, $pessoa);
        }
    } else {
        $sql_busca_contrario   = "SELECT id_pessoa, nome FROM pessoas WHERE tipo_parte = 'contrário'  AND usuario_config_id_usuario_config = $id_user order by nome limit 15;";
        $res = $conexao->query($sql_busca_contrario);

        while ($pessoa = $res->fetch_assoc()) {
            array_push($pessoa_localizadas, $pessoa);
        }
    }

    echo json_encode($pessoa_localizadas);
    $conexao->close();
    exit;
}



if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['cliente']) && !empty($_POST['grupo_acao']) && !empty($_POST['tipo_acao']) && !empty($_POST['referencia']) && !empty($_POST['etapa_kanban']) && !empty($_POST['contingenciamento']) && $_POST['acao'] == 'cadastrar') {


    // var_dump($_POST);

    $cliente             = $conexao->escape_string(htmlspecialchars($_POST['cliente'] ?? ''));
    $contrario           = $conexao->escape_string(htmlspecialchars($_POST['contrario'] ?? ''));
    $token               = bin2hex(random_bytes(64 / 2));
    $grupo_acao          = $conexao->escape_string(htmlspecialchars($_POST['grupo_acao'] ?? ''));
    $tipo_acao           = $conexao->escape_string(htmlspecialchars($_POST['tipo_acao'] ?? ''));
    $referencia          = $conexao->escape_string(htmlspecialchars($_POST['referencia'] ?? ''));
    $numero_processo     = $conexao->escape_string(htmlspecialchars($_POST['num_processo'] ?? ''));
    $numero_protocolo    = $conexao->escape_string(htmlspecialchars($_POST['num_protocolo'] ?? ''));
    $processo_originario = $conexao->escape_string(htmlspecialchars($_POST['processo_originario'] ?? ''));
    $valor_causa         = $conexao->escape_string(htmlspecialchars($_POST['valor_causa'] ?? ''));
    $valor_honorarios    = $conexao->escape_string(htmlspecialchars($_POST['valor_honorarios'] ?? ''));
    $etapa_kanban        = $conexao->escape_string(htmlspecialchars($_POST['etapa_kanban'] ?? ''));
    $contingenciamento   = $conexao->escape_string(htmlspecialchars($_POST['contingenciamento'] ?? ''));
    $data_requerimento   = $conexao->escape_string(htmlspecialchars($_POST['data_requerimento'] ?? ''));
    $resultado_processo  = $conexao->escape_string(htmlspecialchars($_POST['resultado_processo'] ?? ''));
    $observacao          = $conexao->escape_string(htmlspecialchars($_POST['observacao'] ?? ''));
    $acao                = $conexao->escape_string(htmlspecialchars($_POST['acao'] ?? ''));

    $sql = "INSERT INTO processo (
        cliente_id,
        contrario_id,
        tk,
        grupo_acao,
        tipo_acao,
        referencia,
        num_processo,
        num_protocolo,
        processo_originario,
        valor_causa,
        valor_honorarios,
        etapa_kanban,
        contingenciamento,
        data_requerimento,
        resultado_processo,
        observacao,
        usuario_config_id_usuario_config
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?,?)";

    $stmt = $conexao->prepare($sql);

    $stmt->bind_param(
        "iisssssssssssssss",
        $cliente,
        $contrario,
        $token,
        $grupo_acao,
        $tipo_acao,
        $referencia,
        $numero_processo,
        $numero_protocolo,
        $processo_originario,
        $valor_causa,
        $valor_honorarios,
        $etapa_kanban,
        $contingenciamento,
        $data_requerimento,
        $resultado_processo,
        $observacao,
        $id_user
    );

    if ($stmt->execute()) {
        echo "Processo cadastrado com sucesso! ID: " . $stmt->insert_id;
    } else {
        echo "Erro ao cadastrar processo: " . $stmt->error;
    }

    $stmt->close();
    header('Location:' . "./docs_processo.php?tkn=$token");
}



if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['cliente']) && !empty($_POST['grupo_acao']) && !empty($_POST['tipo_acao']) && !empty($_POST['referencia']) && !empty($_POST['etapa_kanban']) && !empty($_POST['contingenciamento']) && $_POST['acao'] == 'editar') {


    $cliente_id           = $conexao->escape_string(htmlspecialchars($_POST['cliente'] ?? ''));
    $contrario_id         = $conexao->escape_string(htmlspecialchars($_POST['contrario'] ?? ''));
    $grupo_acao           = $conexao->escape_string(htmlspecialchars($_POST['grupo_acao'] ?? ''));
    $tipo_acao            = $conexao->escape_string(htmlspecialchars($_POST['tipo_acao'] ?? ''));
    $referencia           = $conexao->escape_string(htmlspecialchars($_POST['referencia'] ?? ''));
    $num_processo         = $conexao->escape_string(htmlspecialchars($_POST['num_processo'] ?? ''));
    $num_protocolo        = $conexao->escape_string(htmlspecialchars($_POST['num_protocolo'] ?? ''));
    $processo_originario  = $conexao->escape_string(htmlspecialchars($_POST['processo_originario'] ?? ''));
    $valor_causa          = $conexao->escape_string(htmlspecialchars($_POST['valor_causa'] ?? ''));
    $valor_honorarios     = $conexao->escape_string(htmlspecialchars($_POST['valor_honorarios'] ?? ''));
    $etapa_kanban         = $conexao->escape_string(htmlspecialchars($_POST['etapa_kanban'] ?? ''));
    $contingenciamento    = $conexao->escape_string(htmlspecialchars($_POST['contingenciamento'] ?? ''));
    $data_requerimento    = $conexao->escape_string(htmlspecialchars($_POST['data_requerimento'] ?? ''));
    $resultado_processo   = $conexao->escape_string(htmlspecialchars($_POST['resultado_processo'] ?? ''));
    $observacao           = $conexao->escape_string(htmlspecialchars($_POST['observacao'] ?? ''));
    $acao                 = $conexao->escape_string(htmlspecialchars($_POST['acao'] ?? ''));
    $pro_id               = $conexao->escape_string(htmlspecialchars($_POST['pro_id'] ?? ''));

    $sql = "UPDATE processo 
               SET cliente_id = ?, 
                   contrario_id = ?, 
                   grupo_acao = ?, 
                   tipo_acao = ?, 
                   referencia = ?, 
                   num_processo = ?, 
                   num_protocolo = ?, 
                   processo_originario = ?, 
                   valor_causa = ?, 
                   valor_honorarios = ?, 
                   etapa_kanban = ?, 
                   contingenciamento = ?, 
                   data_requerimento = ?, 
                   resultado_processo = ?, 
                   observacao = ?, 
                   usuario_config_id_usuario_config = ?
             WHERE id_processo = ?";

    $stmt = $conexao->prepare($sql);


    $stmt->bind_param(
        "iissssssssssssssi",
        $cliente_id,
        $contrario_id,
        $grupo_acao,
        $tipo_acao,
        $referencia,
        $num_processo,
        $num_protocolo,
        $processo_originario,
        $valor_causa,
        $valor_honorarios,
        $etapa_kanban,
        $contingenciamento,
        $data_requerimento,
        $resultado_processo,
        $observacao,
        $id_user,
        $pro_id
    );

    // Executa
    if ($stmt->execute()) {
        echo "Processo atualizado com sucesso!";
    } else {
        echo "Erro ao atualizar: " . $stmt->error;
    }

    $stmt->close();
}






if ($_SERVER['REQUEST_METHOD'] === 'GET' && !empty($_GET['acao']) && !empty($_GET['tkn'])) {

    $token_processo  = $conexao->escape_string(htmlspecialchars($_GET['tkn']));

    $sql_busca_processo_tkn = 'SELECT 
    p.id_processo,
    p.tk,
    p.grupo_acao,
    p.tipo_acao,
    p.referencia,
    p.num_processo,
    p.num_protocolo,
    p.processo_originario,
    p.valor_causa,
    p.valor_honorarios,
    p.etapa_kanban,
    p.contingenciamento,
    p.data_requerimento,
    p.resultado_processo,
    p.observacao,
    p.dt_cadastro_processo,
    p.dt_atualizacao_processo,

    -- Dados do cliente
    c.id_pessoa   AS cliente_id,
    c.nome        AS cliente_nome,
    c.tipo_parte  AS cliente_tipo_parte,

    -- Dados do contrário
    ct.id_pessoa  AS contrario_id,
    ct.nome       AS contrario_nome,
    ct.tipo_parte AS contrario_tipo_parte

FROM processo p
LEFT JOIN pessoas c  ON p.cliente_id   = c.id_pessoa
LEFT JOIN pessoas ct ON p.contrario_id = ct.id_pessoa
where p.tk = ? and p.usuario_config_id_usuario_config = ?;
';

    $stmt = $conexao->prepare($sql_busca_processo_tkn);
    $stmt->bind_param('si', $token_processo, $id_user);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $dados_processo = $result->fetch_assoc();
        // print_r($dados_processo);
        $conexao->close();
    } else {
        header('location: ./processos.php');
        $conexao->close();
        exit;
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $dados_processo = [];
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


<div class="container_breadcrumb">
    <div class="pai_topo">
        <div class="breadcrumb">
            <a href="./processos.php" class="breadcrumb-link">Processos</a>
            <span class="breadcrumb-separator">/</span>
            <span class="breadcrumb-current"><?php echo ($_GET['acao'] ?? '') ? 'Edição Processo' : 'Cadastro' ?></span>
            <span class="breadcrumb-separator">/</span>
        </div>
    </div>
</div>

<body>
    <main class="container_principal">
        <div class="pai_conteudo">

            <section class="container_etapa_cadastro">
                <div class="etapa">
                    <div class="num bg_selecionado">1º</div>
                    <div class="descricao color_selecionado"><?php echo count($dados_processo) > 0 ? 'Edição' : 'Cadastro'  ?></div>
                </div>



                <?php if (count($dados_processo) > 0): ?>
                    <div class="separador bg_selecionado" style="width: 50%;"></div>
                <?php else: ?>
                    <div class="separador bg_selecionado"></div>
                    <div class="etapa">
                        <div class="num">2º</div>
                        <div class="descricao">Documentos</div>
                    </div>

                    <div class="separador"></div>
                <?php endif ?>

                <div class="etapa">
                    <div class="num"><?php echo count($dados_processo) > 0 ? '2°' : '3°' ?></div>
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
                                        </select>
                                    </div>

                                    <div class="container_input">
                                        <label for="contrario">Contrário</label>
                                        <select name="contrario" id="contrario">
                                            <option value="">Selecione o Contrário</option>
                                        </select>
                                    </div>


                                    <div class="container_input">
                                        <label for="grupo_acao">Grupo de Ação <span style="color: red;">*</span></label>
                                        <select name="grupo_acao" id="grupo_acao" required>
                                            <option value="">Selecione...</option>
                                            <option value="administrativo" <?php echo ($dados_processo['grupo_acao'] ?? '') === 'administrativo' ? 'selected' : '' ?>>Administrativo</option>
                                            <option value="trabalhista" <?php echo ($dados_processo['grupo_acao'] ?? '') === 'trabalhista' ? 'selected' : '' ?>>Trabalhista</option>
                                            <option value="civil" <?php echo ($dados_processo['grupo_acao'] ?? '') === 'civil' ? 'selected' : '' ?>>Cível</option>
                                            <option value="familia" <?php echo ($dados_processo['grupo_acao'] ?? '') === 'familia' ? 'selected' : '' ?>>Família e Sucessões</option>
                                            <option value="previdenciario" <?php echo ($dados_processo['grupo_acao'] ?? '') === 'previdenciario' ? 'selected' : '' ?>>Previdenciário</option>
                                            <option value="tributario" <?php echo ($dados_processo['grupo_acao'] ?? '') === 'tributario' ? 'selected' : '' ?>>Tributário</option>
                                            <option value="consumidor" <?php echo ($dados_processo['grupo_acao'] ?? '') === 'consumidor' ? 'selected' : '' ?>>Consumidor</option>
                                            <option value="empresarial" <?php echo ($dados_processo['grupo_acao'] ?? '') === 'empresarial' ? 'selected' : '' ?>>Empresarial</option>
                                            <option value="penal" <?php echo ($dados_processo['grupo_acao'] ?? '') === 'penal' ? 'selected' : '' ?>>Penal</option>
                                            <option value="imobiliario" <?php echo ($dados_processo['grupo_acao'] ?? '') === 'imobiliario' ? 'selected' : '' ?>>Imobiliário</option>
                                            <option value="eleitoral" <?php echo ($dados_processo['grupo_acao'] ?? '') === 'eleitoral' ? 'selected' : '' ?>>Eleitoral</option>
                                        </select>
                                    </div>

                                    <div class="container_input">
                                        <label for="tipo_acao">Tipo de Ação <span style="color: red;">*</span></label>
                                        <select name="tipo_acao" id="tipo_acao" required>
                                            <?php if (!empty($dados_processo['tipo_acao'])): ?>
                                                <option value="<?php echo htmlspecialchars($dados_processo['tipo_acao']) ?>" selected>
                                                    <?php echo htmlspecialchars($dados_processo['tipo_acao']) ?>
                                                </option>
                                            <?php else: ?>
                                                <option value="">Selecione o grupo primeiro...</option>
                                            <?php endif; ?>
                                        </select>
                                    </div>

                                    <div class="container_input">
                                        <label for="referencia">Referência <span style="color: red;">*</span></label>
                                        <input
                                            type="text"
                                            name="referencia"
                                            id="referencia"
                                            value="<?php echo htmlspecialchars($dados_processo['referencia'] ?? '') ?>"
                                            maxlength="8"
                                            placeholder="Ex: PA_001 "
                                            required>
                                    </div>



                                </div>

                                <div class="container_inputs">

                                    <div class="container_input">
                                        <label for="num_processo">Núm do Processo</label>
                                        <input
                                            type="text"
                                            name="num_processo"
                                            id="num_processo"
                                            value="<?php echo htmlspecialchars($dados_processo['num_processo'] ?? '') ?>"
                                            minlength="4"
                                            maxlength="25"
                                            placeholder="Ex: 0001234-56.2023.8.26.0100">
                                    </div>

                                    <div class="container_input">
                                        <label for="num_protocolo">Núm do Protocolo</label>
                                        <input
                                            type="text"
                                            name="num_protocolo"
                                            id="num_protocolo"
                                            value="<?php echo htmlspecialchars($dados_processo['num_protocolo'] ?? '') ?>"
                                            minlength="4"
                                            maxlength="25"
                                            placeholder="Ex: PROT_2023_00123">
                                    </div>

                                    <div class="container_input">
                                        <label for="processo_originario">Processo Originário</label>
                                        <input
                                            type="text"
                                            name="processo_originario"
                                            id="processo_originario"
                                            value="<?php echo htmlspecialchars($dados_processo['processo_originario'] ?? '') ?>"
                                            minlength="4"
                                            maxlength="25"
                                            placeholder="Ex: 0009876-54.2020.8.26.0100">
                                    </div>

                                    <div class="container_input">
                                        <label for="valor_causa">Valor da Causa</label>
                                        <input
                                            type="text"
                                            name="valor_causa"
                                            id="valor_causa"
                                            value="<?php echo htmlspecialchars($dados_processo['valor_causa'] ?? '') ?>"
                                            placeholder="Ex: 150.000,00">
                                    </div>

                                    <div class="container_input">
                                        <label for="valor_honorarios">Valor dos Honorários</label>
                                        <input
                                            type="text"
                                            name="valor_honorarios"
                                            id="valor_honorarios"
                                            value="<?php echo htmlspecialchars($dados_processo['valor_honorarios'] ?? '') ?>"
                                            placeholder="Ex: 30.000,00">
                                    </div>


                                </div>

                                <div class="container_inputs">

                                    <div class="container_input">
                                        <label for="etapa_kanban">Etapa Kanban <span style="color: red;">*</span></label>
                                        <select name="etapa_kanban" id="etapa_kanban" required>
                                            <option value="">Selecione...</option>
                                            <option value="Análise do Caso" <?php echo ($dados_processo['etapa_kanban'] ?? '') === 'Análise do Caso' ? 'selected' : '' ?>>Análise do Caso</option>
                                            <option value="Negociação" <?php echo ($dados_processo['etapa_kanban'] ?? '') === 'Negociação' ? 'selected' : '' ?>>Negociação</option>
                                            <option value="Aguardando Documentos" <?php echo ($dados_processo['etapa_kanban'] ?? '') === 'Aguardando Documentos' ? 'selected' : '' ?>>Aguardando Documentos</option>
                                            <option value="Proposta" <?php echo ($dados_processo['etapa_kanban'] ?? '') === 'Proposta' ? 'selected' : '' ?>>Proposta</option>
                                            <option value="Ação Protocolada" <?php echo ($dados_processo['etapa_kanban'] ?? '') === 'Ação Protocolada' ? 'selected' : '' ?>>Ação Protocolada</option>
                                            <option value="Aguardando Audiência" <?php echo ($dados_processo['etapa_kanban'] ?? '') === 'Aguardando Audiência' ? 'selected' : '' ?>>Aguardando Audiência</option>
                                            <option value="Aguardando Julgamento" <?php echo ($dados_processo['etapa_kanban'] ?? '') === 'Aguardando Julgamento' ? 'selected' : '' ?>>Aguardando Julgamento</option>
                                            <option value="Desenvolvendo Recurso" <?php echo ($dados_processo['etapa_kanban'] ?? '') === 'Desenvolvendo Recurso' ? 'selected' : '' ?>>Desenvolvendo Recurso</option>
                                            <option value="fechamento" <?php echo ($dados_processo['etapa_kanban'] ?? '') === 'fechamento' ? 'selected' : '' ?>>Fechamento</option>
                                        </select>
                                    </div>

                                    <div class="container_input">
                                        <label for="contingenciamento">Contingenciamento <span style="color: red;">*</span></label>
                                        <select name="contingenciamento" id="contingenciamento" required>
                                            <option value="">Selecione...</option>
                                            <option value="provável/chance alta" <?php echo ($dados_processo['contingenciamento'] ?? '') === 'provável/chance alta' ? 'selected' : '' ?>>Provável/Chance Alta</option>
                                            <option value="possível/talvez" <?php echo ($dados_processo['contingenciamento'] ?? '') === 'possível/talvez' ? 'selected' : '' ?>>Possível/Talvez</option>
                                            <option value="remota/difícil" <?php echo ($dados_processo['contingenciamento'] ?? '') === 'remota/difícil' ? 'selected' : '' ?>>Remota/Difícil</option>
                                        </select>
                                    </div>

                                    <div class="container_input">
                                        <label for="data_requerimento">Data Requerimento</label>
                                        <input
                                            type="date"
                                            name="data_requerimento"
                                            id="data_requerimento"
                                            value="<?php echo htmlspecialchars($dados_processo['data_requerimento'] ?? '') ?>">
                                    </div>

                                    <div class="container_input">
                                        <label for="resultado_processo">Resultado Processo</label>
                                        <input
                                            type="text"
                                            name="resultado_processo"
                                            id="resultado_processo"
                                            value="<?php echo htmlspecialchars($dados_processo['resultado_processo'] ?? '') ?>"
                                            minlength="4"
                                            maxlength="100"
                                            placeholder="Ex: Sentença favorável, acordo homologado, improcedente...">
                                    </div>

                                    <div class="container_input">
                                        <label for="observacao">Observação</label>
                                        <input
                                            type="text"
                                            name="observacao"
                                            id="observacao"
                                            value="<?php echo htmlspecialchars($dados_processo['observacao'] ?? '') ?>"
                                            maxlength="255"
                                            placeholder="Digite aqui alguma observação relevante">
                                    </div>

                                </div>


                                <input type="hidden" name="acao" value="<?php echo ($_GET['acao'] ?? '') ? 'editar' : 'cadastrar' ?>">

                                <input type="hidden" name="pro_id" value="<?php echo htmlspecialchars($dados_processo['id_processo'] ?? '') ?>">

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


    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script>
        const tiposPorGrupo = {
            administrativo: [{
                    text: "Nulidade de Licitação"
                },
                {

                    text: "Improbidade Administrativa"
                },
                {

                    text: "Mandado de Segurança Administrativo"
                }
            ],
            trabalhista: [{

                    text: "Reclamação Trabalhista"
                },
                {

                    text: "Verbas Rescisórias"
                },
                {

                    text: "Adicional de Insalubridade/Periculosidade"
                }
            ],
            civil: [{

                    text: "Ação de Cobrança"
                },
                {

                    text: "Indenização por Danos"
                },
                {

                    text: "Execução de Título Extrajudicial"
                }
            ],
            familia: [{

                    text: "Divórcio"
                },
                {

                    text: "Guarda e Pensão Alimentícia"
                },
                {

                    text: "Inventário e Partilha"
                }
            ],
            previdenciario: [{

                    text: "Aposentadoria por Invalidez"
                },
                {

                    text: "Auxílio-Doença"
                },
                {

                    text: "Pensão por Morte"
                }
            ],
            tributario: [{

                    text: "Execução Fiscal"
                },
                {

                    text: "Mandado de Segurança Tributário"
                },
                {

                    text: "Ação Anulatória de Débito Fiscal"
                }
            ],
            consumidor: [{

                    text: "Ação Revisional de Contrato"
                },
                {

                    text: "Ação contra Planos de Saúde"
                },
                {

                    text: "Indenização por Produto/Serviço Defeituoso"
                }
            ],
            empresarial: [{

                    text: "Recuperação Judicial"
                },
                {

                    text: "Falência"
                },
                {

                    text: "Dissolução de Sociedade"
                }
            ],
            penal: [{

                    text: "Defesa Criminal"
                },
                {

                    text: "Habeas Corpus"
                },
                {

                    text: "Revisão Criminal"
                }
            ],
            imobiliario: [{

                    text: "Despejo"
                },
                {

                    text: "Usucapião"
                },
                {

                    text: "Ação Renovatória de Aluguel"
                }
            ],
            eleitoral: [{

                    text: "Prestação de Contas Eleitorais"
                },
                {

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
                    option.value = tipo.text;
                    option.textContent = tipo.text;
                    tipoSelect.appendChild(option);
                });
            }
        });
    </script>


    <script src="https://cdn.jsdelivr.net/npm/jquery-mask-plugin@1.14.16/dist/jquery.mask.min.js"></script>
    <script>
        $(document).ready(function() {

            // mascaras campos
            const hoje = new Date(); // Data atual
            const ano = hoje.getFullYear();
            const mes = String(hoje.getMonth() + 1).padStart(2, '0'); // Mês atual (ajustado para 0-11)
            const dia = String(hoje.getDate()).padStart(2, '0'); // Dia atual
            const dataMaxima = `${ano}-${mes}-${dia}`;
            $('#data_requerimento').attr('max', dataMaxima);

            $('#numero_processo').mask('0000000-00.0000.0.00.0000');
            $('#processo_originario').mask('0000000-00.0000.0.00.0000');
            $('#valor_causa').mask('000.000.000,00', {
                reverse: true
            });
            $('#valor_honorarios').mask('000.000.000,00', {
                reverse: true
            });

        });
    </script>







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



    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        $(document).ready(function() {
            // Inicializa CLIENTE
            $('#cliente').select2({
                placeholder: "Selecione o Cliente",
                minimumInputLength: 0,
                language: {
                    inputTooShort: function(args) {
                        return "Digite " + (args.minimum - args.input.length) + " ou mais caracteres";
                    },
                    noResults: function() {
                        return "Nenhum resultado encontrado";
                    },
                    searching: function() {
                        return "Buscando...";
                    }
                },
                ajax: {
                    url: 'cadastro_processo.php',
                    type: 'POST',
                    dataType: 'json',
                    delay: 250,
                    data: function(params) {
                        return {
                            busca_cliente: params.term || 'padrao'
                        };
                    },
                    processResults: function(data) {
                        return {
                            results: $.map(data, function(item) {
                                return {
                                    id: item.id_pessoa,
                                    text: item.nome
                                };
                            })
                        };
                    }
                }
            });

            // Inicializa CONTRÁRIO
            $('#contrario').select2({
                placeholder: "Selecione o Contrário",
                minimumInputLength: 0,
                language: {
                    inputTooShort: function(args) {
                        return "Digite " + (args.minimum - args.input.length) + " ou mais caracteres";
                    },
                    noResults: function() {
                        return "Nenhum resultado encontrado";
                    },
                    searching: function() {
                        return "Buscando...";
                    }
                },
                ajax: {
                    url: 'cadastro_processo.php',
                    type: 'POST',
                    dataType: 'json',
                    delay: 250,
                    data: function(params) {
                        return {
                            busca_contrario: params.term || 'padrao'
                        };
                    },
                    processResults: function(data) {
                        return {
                            results: $.map(data, function(item) {
                                return {
                                    id: item.id_pessoa,
                                    text: item.nome
                                };
                            })
                        };
                    }
                }
            });

            // ===============================
            // INJETA VALORES PRÉ-SELECIONADOS
            // ===============================
            var cliente_id = "<?php echo $dados_processo['cliente_id'] ?? '' ?>";
            var cliente_nome = "<?php echo $dados_processo['cliente_nome'] ?? '' ?>";
            var optionCliente = new Option(cliente_nome, cliente_id, true, true);
            $('#cliente').append(optionCliente).trigger('change');


            var contrario_id = "<?php echo $dados_processo['contrario_id'] ?? '' ?>";
            var contrario_nome = "<?php echo $dados_processo['contrario_nome'] ?? '' ?>";
            var optionContrario = new Option(contrario_nome, contrario_id, true, true);
            $('#contrario').append(optionContrario).trigger('change');

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