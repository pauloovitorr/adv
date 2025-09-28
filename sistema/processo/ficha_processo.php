<?php
include_once('../../scripts.php');
$id_user = $_SESSION['cod'];


if ($_SERVER['REQUEST_METHOD'] === 'GET' && !empty($_GET['tkn'])) {

    $token_processo  = $conexao->escape_string(htmlspecialchars($_GET['tkn']));

    $sql_busca_processo_tkn = 'SELECT 
    p.id_processo AS id_processo,
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
    c.tk          AS cliente_tk,
    c.nome        AS cliente_nome,
    c.tipo_parte  AS cliente_tipo_parte,

    -- Dados do contrário
    ct.id_pessoa  AS contrario_id,
    ct.tk         AS contrario_tk,
    ct.nome       AS contrario_nome,
    ct.tipo_parte AS contrario_tipo_parte,

    -- Dados documentos
    doc.id_documento,
    doc.nome_original,
    doc.caminho_arquivo,
    doc.dt_criacao,
    doc.id_documento,
    doc.id_processo AS doc_id_processo


FROM processo p
LEFT JOIN pessoas c  ON p.cliente_id   = c.id_pessoa
LEFT JOIN pessoas ct ON p.contrario_id = ct.id_pessoa
LEFT JOIN documento_processo doc ON p.id_processo = doc.id_processo
where p.tk = ? and p.usuario_config_id_usuario_config = ?;';

    $stmt = $conexao->prepare($sql_busca_processo_tkn);
    $stmt->bind_param('si', $token_processo, $id_user);
    $stmt->execute();
    $result = $stmt->get_result();


    if ($result->num_rows >= 1) {

        $dados_processo = [];
        $documentos = [];

        while ($row = $result->fetch_assoc()) {

            if (empty($dados_processo)) {

                $dados_processo = [
                    'id_processo'                  => $row['id_processo'],
                    'tk'                           => $row['tk'],
                    'grupo_acao'                   => $row['grupo_acao'],
                    'tipo_acao'                    => $row['tipo_acao'],
                    'referencia'                   => $row['referencia'],
                    'num_processo'                 => $row['num_processo'],
                    'num_protocolo'                => $row['num_protocolo'],
                    'processo_originario'          => $row['processo_originario'],
                    'valor_causa'                  => $row['valor_causa'],
                    'valor_honorarios'             => $row['valor_honorarios'],
                    'etapa_kanban'                 => $row['etapa_kanban'],
                    'contingenciamento'            => $row['contingenciamento'],
                    'data_requerimento'            => $row['data_requerimento'],
                    'resultado_processo'           => $row['resultado_processo'],
                    'observacao'                   => $row['observacao'],
                    'dt_cadastro_processo'         => $row['dt_cadastro_processo'],
                    'dt_atualizacao_processo'      => $row['dt_atualizacao_processo'],
                    'cliente_id'                   => $row['cliente_id'],
                    'cliente_nome'                 => $row['cliente_nome'],
                    'cliente_tk'                   => $row['cliente_tk'],
                    'cliente_tipo_parte'           => $row['cliente_tipo_parte'],
                    'contrario_id'                 => $row['contrario_id'],
                    'contrario_nome'               => $row['contrario_nome'],
                    'contrario_tk'                 => $row['contrario_tk'],
                    'contrario_tipo_parte'         => $row['contrario_tipo_parte'],
                ];
            }


            if (!empty($row['id_documento'])) {
                $documentos[] = [
                    'id_documento'   => $row['id_documento'],
                    'nome_original'  => $row['nome_original'],
                    'caminho_arquivo' => $row['caminho_arquivo'],
                    'dt_criacao'     => $row['dt_criacao']
                ];
            }
        }

        $conexao->close();
    } else {
        header('location: ./processos.php');
        exit;
    }
}


?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/pessoas/ficha_pessoa.css">
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
            <span class="breadcrumb-current">Ficha Processo</span>
        </div>
    </div>
</div>


<body>
    <main class="container_principal">
        <div class="pai_conteudo">

            <!-- Conteúdo da página -->
            <div class="page-content">
                <!-- Header do perfil -->
                <div class="profile-header">


                    <div class="profile-title-section">
                        <div class="profile-photo-container" style="width: 100px; height: 100px; background-color:#959595; border-radius: 12px; display:flex; justify-content:center; align-items: center ">
                            <i class="fa-solid fa-folder"  style="color: #ffffffff; font-size:60px"></i>

                        </div>

                        <div class="profile-info">
                            <h1 class="profile-name"><?php echo htmlspecialchars($dados_processo['tipo_acao'] ?? '') ?></h1>

                            <div class="profile-meta">
                                <span class="profile-type">
                                    <i class="fa-solid fa-folder"></i> Tipo de ação: <?php echo ucfirst(htmlspecialchars($dados_processo['grupo_acao'] ?? '')) ?>
                                </span>

                                <span class="profile-origin">
                                    <i class="fas fa-tag"></i> Referência: <?= htmlspecialchars($dados_processo['referencia'] ?? '') ?>
                                </span>


                                <span class="profile-status active">
                                    <i class="fas fa-circle"></i> Ativo
                                </span>
                            </div>


                            <div class="profile-meta">

                                <?php
                                // Formata a data de cadastro
                                $dtCadastro = !empty($dados_processo['dt_cadastro_processo'])
                                    ? (new DateTime($dados_processo['dt_cadastro_processo']))->format('d/m/Y \à\s H:i')
                                    : '';

                                // Formata a data de atualização
                                $dtAtualizacao = !empty($dados_processo['dt_atualizacao_processo'])
                                    ? (new DateTime($dados_processo['dt_atualizacao_processo']))->format('d/m/Y \à\s H:i')
                                    : '';
                                ?>

                                <span class="profile-origin">
                                    <i class="fas fa-file-alt"></i>
                                    Cadastro: <?= htmlspecialchars($dtCadastro) ?>
                                </span>

                                <span class="profile-origin">
                                    <i class="fas fa-file-alt"></i>
                                    Última Atualização: <?= htmlspecialchars($dtAtualizacao) ?>
                                </span>

                            </div>
                        </div>
                    </div>

                    <div class="profile-actions">
                        <a href="./cadastro_processo.php?acao=editar&tkn=<?= urlencode($dados_processo['tk'] ?? '') ?>" style="text-decoration: none;">
                            <button class="btn-secondary">
                                <i class="fas fa-edit"></i> Editar
                            </button>
                        </a>


                    </div>

                </div>

                <!-- Conteúdo principal em tabs -->
                <div class="profile-content">
                    <!-- Tab Navigation -->
                    <div class="tab-navigation">
                        <a href="#personal">
                            <button class="tab-item active" data-tab="personal">
                                <i class="fas fa-user"></i> Dados Processo
                            </button>
                        </a>

                        <a href="#docs">
                            <button class="tab-item" data-tab="docs">
                                <i class="fas fa-file-alt"></i> Documentos
                            </button>
                        </a>

                    </div>


                    <!-- Tab Content -->
                    <div class="tab-content">
                        <!-- Dados Pessoais -->
                        <div class="tab-pane" id="personal">
                            <div class="info-grid">



                                <div class="info-card">
                                    <div class="info-item">
                                        <label class="info-label">Cliente</label>
                                        <a class="pessoas_processo" href="../pessoa/ficha_pessoa.php?tkn=<?php echo htmlspecialchars($dados_processo['cliente_tk'] ?? '') ?>" target="__blank">
                                            <div class="info-value"><?= htmlspecialchars($dados_processo['cliente_nome'] ?? '') ?></div>
                                        </a>
                                    </div>

                                    <div class="info-item">
                                        <label class="info-label">Contrário</label>
                                        <a class="pessoas_processo" href="../pessoa/ficha_pessoa.php?tkn=<?php echo htmlspecialchars($dados_processo['contrario_tk'] ?? '') ?>" target="__blank">
                                            <div class="info-value"><?= htmlspecialchars($dados_processo['contrario_nome'] ?? '') ?></div>
                                        </a>
                                    </div>


                                    <div class="info-item">
                                        <label class="info-label">Contingenciamento</label>
                                        <div class="info-value"><?php echo ucfirst(htmlspecialchars($dados_processo['contingenciamento'] ?? '')) ?></div>
                                    </div>

                                    <div class="info-item">
                                        <label class="info-label">Número do protocolo</label>
                                        <div class="info-value"><?= htmlspecialchars($dados_processo['num_protocolo'] ?? '') ?></div>
                                    </div>


                                    <div class="info-item">
                                        <label class="info-label">Número do processo</label>
                                        <div class="info-value"><?= htmlspecialchars($dados_processo['num_processo'] ?? '') ?></div>
                                    </div>



                                    <div class="info-item">
                                        <label class="info-label">Processo originário</label>
                                        <div class="info-value"><?= htmlspecialchars($dados_processo['processo_originario'] ?? '') ?></div>
                                    </div>

                                    <div class="info-item">
                                        <label class="info-label">Valor da causa</label>
                                        <div class="info-value"><?= htmlspecialchars($dados_processo['valor_causa'] ?? '') ?></div>
                                    </div>

                                    <div class="info-item">
                                        <label class="info-label">Valor dos honorários</label>
                                        <div class="info-value"><?= htmlspecialchars($dados_processo['valor_honorarios'] ?? '') ?></div>
                                    </div>

                                    <div class="info-item">
                                        <label class="info-label">Etapa Kanban</label>
                                        <div class="info-value"><?= htmlspecialchars($dados_processo['etapa_kanban'] ?? '') ?></div>
                                    </div>

                                    <div class="info-item">
                                        <label class="info-label">Data de requerimento</label>
                                        <div class="info-value"><?= date('d/m/Y', strtotime($dados_processo['data_requerimento'] ?? '')) ?></div>
                                    </div>

                                    <div class="info-item">
                                        <label class="info-label">Resultado do processo</label>
                                        <div class="info-value"><?= htmlspecialchars($dados_processo['resultado_processo'] ?? '') ?></div>
                                    </div>

                                    <div class="info-item">
                                        <label class="info-label">Observação</label>
                                        <div class="info-value"><?= htmlspecialchars($dados_processo['observacao'] ?? '') ?></div>
                                    </div>



                                </div>

                            </div>
                        </div>


                        <!-- Documentos -->
                        <div class="tab-pane" id="docs">
                            <div class="processes-section">
                                <div class="section-header">
                                    <h3>Documentos do Cliente</h3>
                                    <a href="./docs_processo.php?tkn=<?= urlencode($_GET['tkn']) ?>" style="text-decoration: none;">
                                        <button class="btn-secondary">
                                            <i class="fas fa-plus"></i> Novo Documento
                                        </button>
                                    </a>
                                </div>

                                <?php if (!empty($documentos)): ?>
                                    <div class="lista_arquivos">
                                        <?php foreach ($documentos as $doc): ?>
                                            <?php $ext = strtolower(pathinfo($doc["caminho_arquivo"], PATHINFO_EXTENSION)); ?>
                                            <a href="<?= htmlspecialchars($doc["caminho_arquivo"]) ?>" target="__blank">
                                                <div class="doc">

                                                    <?php if (in_array($ext, ['png', 'jpg', 'jpeg'])): ?>
                                                        <img class="img_bg_doc" src="<?= htmlspecialchars($doc["caminho_arquivo"]) ?>" alt="">
                                                        <div class="nome_arquivo"><span><?= htmlspecialchars($doc["nome_original"]) ?></span></div>
                                                    <?php else: ?>
                                                        <i class="fa-regular fa-folder" style="font-size: 30px;"></i>
                                                        <div class="nome_arquivo"><span><?= htmlspecialchars($doc["nome_original"]) ?></span></div>
                                                    <?php endif; ?>
                                                </div>
                                            </a>
                                        <?php endforeach; ?>
                                    </div>
                                <?php else: ?>
                                    <div class="empty-state">
                                        <i class="fa-solid fa-folder empty-icon"></i>
                                        <h4>Nenhum documento cadastrado</h4>
                                        <p>Este cliente ainda não possui documentos associados.</p>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>



                    </div>
                </div>

            </div>


        </div>
    </main>




    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Tab functionality
            const tabItems = document.querySelectorAll('.tab-item');
            const tabPanes = document.querySelectorAll('.tab-pane');

            tabItems.forEach(tab => {
                tab.addEventListener('click', function() {
                    const targetTab = this.getAttribute('data-tab');

                    // Remove active class from all tabs and panes
                    tabItems.forEach(t => t.classList.remove('active'));
                    tabPanes.forEach(p => p.classList.remove('active'));

                    // Add active class to clicked tab and corresponding pane
                    this.classList.add('active');
                    document.getElementById(targetTab).classList.add('active');
                });
            });
        })
    </script>


    <style>
        .pessoas_processo {
            text-decoration: none;
        }

        .pessoas_processo:hover,
        .pessoas_processo .info-value:hover {
            color: #4299e1;
            text-decoration: underline;
        }
    </style>

</body>

</html>