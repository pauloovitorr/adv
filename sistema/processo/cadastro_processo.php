<?php

include_once('../../scripts.php');

$id_user = $_SESSION['cod'];
$ip = $_SERVER['REMOTE_ADDR'];

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

    $cliente             = (int)($conexao->escape_string(htmlspecialchars($_POST['cliente'] ?? 0)));
    $contrario           = (int)($conexao->escape_string(htmlspecialchars($_POST['contrario'] ?? 0)));
    $token               = bin2hex(random_bytes(64 / 2));
    $grupo_acao          = $conexao->escape_string(htmlspecialchars($_POST['grupo_acao'] ?? ''));
    $tipo_acao           = $conexao->escape_string(htmlspecialchars($_POST['tipo_acao'] ?? ''));
    $referencia          = $conexao->escape_string(htmlspecialchars($_POST['referencia'] ?? ''));
    $numero_processo     = $conexao->escape_string(htmlspecialchars($_POST['num_processo'] ?? ''));
    $numero_protocolo    = $conexao->escape_string(htmlspecialchars($_POST['num_protocolo'] ?? ''));
    $processo_originario = $conexao->escape_string(htmlspecialchars($_POST['processo_originario'] ?? ''));
    $valor_causa         = $conexao->escape_string(htmlspecialchars($_POST['valor_causa'] ?? ''));
    $etapa_kanban        = (int)($conexao->escape_string(htmlspecialchars($_POST['etapa_kanban'] ?? 0)));
    $contingenciamento   = $conexao->escape_string(htmlspecialchars($_POST['contingenciamento'] ?? ''));

    $data_requerimento = $_POST['data_requerimento'] ?? null;

    $observacao          = $conexao->escape_string(htmlspecialchars($_POST['observacao'] ?? ''));
    $valor_honorarios = isset($_POST['valor_honorarios']) && $_POST['valor_honorarios'] !== ''
    ? $_POST['valor_honorarios'] : NULL;
     $resultado_processo = isset($_POST['resultado_processo']) && $_POST['resultado_processo'] !== ''
    ? $_POST['resultado_processo'] : NULL;

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
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conexao->prepare($sql);

    if (!$stmt) {
        die("Erro na preparação da query: " . $conexao->error);
    }

    $stmt->bind_param(
        "iissssssssssssssi",  
        $cliente,             // 1  - i
        $contrario,           // 2  - i
        $token,               // 3  - s
        $grupo_acao,          // 4  - s
        $tipo_acao,           // 5  - s
        $referencia,          // 6  - s
        $numero_processo,     // 7  - s
        $numero_protocolo,    // 8  - s
        $processo_originario, // 9  - s
        $valor_causa,         // 10 - s
        $valor_honorarios,    // 11 - s 
        $etapa_kanban,        // 12 - i
        $contingenciamento,   // 13 - s
        $data_requerimento,   // 14 - s
        $resultado_processo,  // 15 - s
        $observacao,          // 16 - s
        $id_user              // 17 - i
    );

    if ($stmt->execute()) {
        if (cadastro_log('Cadastrou Processo', $referencia, $ip, $id_user)) {
            header('Location:' . "./docs_processo.php?tkn=$token");
            $stmt->close();
        }
    }
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
    $etapa_kanban         = $conexao->escape_string(htmlspecialchars($_POST['etapa_kanban'] ?? ''));
    $contingenciamento    = $conexao->escape_string(htmlspecialchars($_POST['contingenciamento'] ?? ''));
    $data_requerimento    = $conexao->escape_string(htmlspecialchars($_POST['data_requerimento'] ?? ''));
    $observacao           = $conexao->escape_string(htmlspecialchars($_POST['observacao'] ?? ''));
    $acao                 = $conexao->escape_string(htmlspecialchars($_POST['acao'] ?? ''));
    $pro_id               = $conexao->escape_string(htmlspecialchars($_POST['pro_id'] ?? ''));

    $valor_honorarios = isset($_POST['valor_honorarios']) && $_POST['valor_honorarios'] !== ''
    ? $_POST['valor_honorarios'] : NULL;
     $resultado_processo = isset($_POST['resultado_processo']) && $_POST['resultado_processo'] !== ''
    ? $_POST['resultado_processo'] : NULL;

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
        "iissssssssissssii",
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

        header("Location: ./cadastro_processo.php?acao=editar&tkn=" . $_GET['tkn'] . "&msg=excluido");


        exit;
    } else {
        header("Location: ./cadastro_processo.php?acao=editar&tkn=" . $_GET['tkn'] . "&msg=erro");
        exit;
    }

    // $stmt->close();
}



if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['busca_etapa'])) {

    $lista_etapas = [];

    if ($_POST['busca_etapa'] == 'padrao') {
        $sql_busca_etapas_crm = "SELECT id_etapas_crm, nome FROM etapas_crm WHERE usuario_config_id_usuario_config = $id_user ORDER BY ordem ASC";
        $etapas_padrao = $conexao->query($sql_busca_etapas_crm);


        while ($etapa = $etapas_padrao->fetch_assoc()) {
            array_push($lista_etapas, $etapa);
        }
    } else {

        $termo = $_POST['busca_etapa'];

        $sql_busca_etapas_crm = "SELECT id_etapas_crm, nome FROM etapas_crm WHERE nome LIKE '%$termo%' AND usuario_config_id_usuario_config = $id_user 
        ORDER BY ordem ASC";
        $etapas_padrao = $conexao->query($sql_busca_etapas_crm);

        while ($etapa = $etapas_padrao->fetch_assoc()) {
            array_push($lista_etapas, $etapa);
        }
    }

    echo json_encode($lista_etapas);
    $conexao->close();
    exit;
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
    ct.tipo_parte AS contrario_tipo_parte,

    -- Etapas
    e.id_etapas_crm,
    e.nome

FROM processo p
LEFT JOIN pessoas c     ON p.cliente_id         = c.id_pessoa
LEFT JOIN pessoas ct    ON p.contrario_id       = ct.id_pessoa
LEFT JOIN etapas_crm e  ON e.id_etapas_crm      = p.etapa_kanban
where p.tk = ? and p.usuario_config_id_usuario_config = ?;
';

    $stmt = $conexao->prepare($sql_busca_processo_tkn);
    $stmt->bind_param('si', $token_processo, $id_user);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $dados_processo = $result->fetch_assoc();
        // print_r($dados_processo);
        // $conexao->close();
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
                                        <select name="resultado_processo" id="resultado_processo" >
                                            <option></option>
                                            <option value="Sentença favorável" <?php echo ($dados_processo['resultado_processo'] ?? '') == 'Sentença favorável' ? 'selected' : ''; ?>>
                                                Sentença favorável
                                            </option>
                                            <option value="Acordo homologado" <?php echo ($dados_processo['resultado_processo'] ?? '') == 'Acordo homologado' ? 'selected' : ''; ?>>
                                                Acordo homologado
                                            </option>
                                            <option value="Improcedente" <?php echo ($dados_processo['resultado_processo'] ?? '') == 'Improcedente' ? 'selected' : ''; ?>>
                                                Improcedente
                                            </option>
                                            
                                        </select>
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


    <?php if (isset($_GET['msg'])): ?>
        <script>
            <?php if ($_GET['msg'] === 'excluido'): ?>
                Swal.fire({
                    title: "Atualização",
                    text: "Processo atualizado com sucesso!",
                    icon: "success",
                    draggable: true
                });
            <?php elseif ($_GET['msg'] === 'erro'): ?>
                Swal.fire({
                    title: "Erro",
                    text: "Ocorreu um problema ao atualizar o processo.",
                    icon: "error",
                    draggable: true
                });
            <?php endif; ?>
        </script>
    <?php endif; ?>




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



            // Etapas CRM
            $('#etapa_kanban').select2({
                placeholder: "Selecione a etapa",
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
                            busca_etapa: params.term || 'padrao'
                        };
                    },
                    processResults: function(data) {
                        return {
                            results: $.map(data, function(item) {
                                return {
                                    id: item.id_etapas_crm,
                                    text: item.nome
                                };
                            })
                        };
                    }
                }
            });





            // INJETA VALORES PRÉ-SELECIONADOS caso a ação seja de update

            var cliente_id = "<?php echo $dados_processo['cliente_id'] ?? '' ?>";
            var cliente_nome = "<?php echo $dados_processo['cliente_nome'] ?? '' ?>";
            var optionCliente = new Option(cliente_nome, cliente_id, true, true);
            $('#cliente').append(optionCliente).trigger('change');


            var contrario_id = "<?php echo $dados_processo['contrario_id'] ?? '' ?>";
            var contrario_nome = "<?php echo $dados_processo['contrario_nome'] ?? '' ?>";
            var optionContrario = new Option(contrario_nome, contrario_id, true, true);
            $('#contrario').append(optionContrario).trigger('change');


            var etapa_id = "<?php echo $dados_processo['id_etapas_crm'] ?? '' ?>";
            var etapa_nome = "<?php echo $dados_processo['nome'] ?? '' ?>";
            var optionEtapa = new Option(etapa_nome, etapa_id, true, true);
            $('#etapa_kanban').append(optionEtapa).trigger('change');


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