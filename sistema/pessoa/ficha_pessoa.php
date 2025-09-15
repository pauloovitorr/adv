<?php
include_once('../../scripts.php');
$id_user = $_SESSION['cod'];


if ($_SERVER['REQUEST_METHOD'] === 'GET' && !empty($_GET['tkn'])) {

    $token_pessoa  = $conexao->escape_string(htmlspecialchars($_GET['tkn']));

    $sql_busca_pessoa_tkn = '
    SELECT pessoas.*, documento.* 
    FROM pessoas
    LEFT JOIN documento ON documento.id_pessoa = pessoas.id_pessoa
    WHERE pessoas.tk = ? AND pessoas.usuario_config_id_usuario_config = ?
';

    $stmt = $conexao->prepare($sql_busca_pessoa_tkn);
    $stmt->bind_param('si', $token_pessoa, $id_user);
    $stmt->execute();
    $result = $stmt->get_result();


    if ($result->num_rows >= 1) {
       
        $dados_pessoa = [];
        $documentos = [];

        while ($row = $result->fetch_assoc()) {
            if (empty($dados_pessoa)) {
                // Pega os dados da pessoa apenas na primeira iteração
                $dados_pessoa = [
                    'id_pessoa' => $row['id_pessoa'],
                    'tk' => $row['tk'],
                    'nome' => $row['nome'],
                    'origem' => $row['origem'],
                    'dt_cadastro_pessoa' => $row['dt_cadastro_pessoa'],
                    'dt_atualizacao_pessoa' => $row['dt_atualizacao_pessoa'],
                    'foto_pessoa' => $row['foto_pessoa'],
                    'num_documento' => $row['num_documento'],
                    'rg' => $row['rg'],
                    'dt_nascimento' => $row['dt_nascimento'],
                    'estado_civil' => $row['estado_civil'],
                    'profissao' => $row['profissao'],
                    'pis' => $row['pis'],
                    'ctps' => $row['ctps'],
                    'sexo' => $row['sexo'],
                    'telefone_principal' => $row['telefone_principal'],
                    'telefone_secundario' => $row['telefone_secundario'],
                    'celular' => $row['celular'],
                    'email' => $row['email'],
                    'email_secundario' => $row['email_secundario'],
                    'cep' => $row['cep'],
                    'estado' => $row['estado'],
                    'cidade' => $row['cidade'],
                    'bairro' => $row['bairro'],
                    'logradouro' => $row['logradouro'],
                    'numero_casa' => $row['numero_casa'],
                    'complemento' => $row['complemento'],
                    'observacao' => $row['observacao'],
                    'nome_mae' => $row['nome_mae'],
                    'tipo_pessoa' => $row['tipo_pessoa'],
                    'tipo_parte' => $row['tipo_parte'],
                    'usuario_config_id_usuario_config' => $row['usuario_config_id_usuario_config'],
                ];
            }

            // Adiciona cada documento
            if (!empty($row['id_documento'])) {
                $documentos[] = [
                    'id_documento' => $row['id_documento'],
                    'nome_original' => $row['nome_original'],
                    'caminho_arquivo' => $row['caminho_arquivo'],
                    'dt_criacao' => $row['dt_criacao'],
                ];
            }
        }
        


        $conexao->close();
    } else {
        header('location: ./pessoas.php');
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
    <title>Perfil Pessoa</title>
</head>

<?php
include_once('../geral/menu_lat.php');
include_once('../geral/topo.php');
?>

<body>
    <main class="container_principal">
        <div class="pai_conteudo">

            <!-- Conteúdo da página -->
            <div class="page-content">
                <!-- Header do perfil -->
                <div class="profile-header">


                    <div class="profile-title-section">
                        <div class="profile-photo-container">
                            <?php if (!empty($dados_pessoa["foto_pessoa"])): ?>
                                <img src="../..<?php echo $dados_pessoa['foto_pessoa'] ?>" alt="Foto do cliente" class="profile-photo" id="clientPhoto">
                            <?php else:  ?>
                                <img src="../../img/user.png" alt="Foto do cliente" class="profile-photo" id="clientPhoto">
                            <?php endif  ?>
                            <div class="profile-photo-placeholder" id="photoPlaceholder" style="display: none;">
                                <i class="fas fa-user"></i>
                            </div>

                        </div>

                        <div class="profile-info">
                            <h1 class="profile-name"><?php echo htmlspecialchars($dados_pessoa['nome'] ?? '') ?></h1>

                            <div class="profile-meta">
                                <span class="profile-type">
                                    <i class="fas fa-user-tie"></i><?php echo htmlspecialchars($dados_pessoa['tipo_parte'] ?? '') ?>
                                </span>

                                <span class="profile-origin">
                                    <i class="fas fa-tag"></i> <?= htmlspecialchars($dados_pessoa['origem'] ?? '') ?>
                                </span>


                                <span class="profile-status active">
                                    <i class="fas fa-circle"></i> Ativo
                                </span>
                            </div>


                            <div class="profile-meta">

                                <?php
                                // Formata a data de cadastro
                                $dtCadastro = !empty($dados_pessoa['dt_cadastro_pessoa'])
                                    ? (new DateTime($dados_pessoa['dt_cadastro_pessoa']))->format('d/m/Y \à\s H:i')
                                    : '';

                                // Formata a data de atualização
                                $dtAtualizacao = !empty($dados_pessoa['dt_atualizacao_pessoa'])
                                    ? (new DateTime($dados_pessoa['dt_atualizacao_pessoa']))->format('d/m/Y \à\s H:i')
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
                        <a href="./cadastro_pessoa.php?acao=editar&tkn=<?= urlencode($dados_pessoa['tk'] ?? '') ?>" style="text-decoration: none;">
                            <button class="btn-secondary">
                                <i class="fas fa-edit"></i> Editar
                            </button>
                        </a>

                        <a href="./docs_pessoa.php?tkn=<?= urlencode($dados_pessoa['tk'] ?? '') ?>" style="text-decoration: none;">
                            <button class="btn-secondary">
                                <i class="fas fa-plus"></i> Novo Processo
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
                                <i class="fas fa-user"></i> Dados Pessoais
                            </button>
                        </a>
                        <a href="#contact">
                            <button class="tab-item" data-tab="contact">
                                <i class="fas fa-phone"></i> Contato
                            </button>
                        </a>
                        <a href="#address">
                            <button class="tab-item" data-tab="address">
                                <i class="fas fa-map-marker-alt"></i> Endereço
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

                                <?php
                                // Formata a data de nascimento
                                $dtNascimento = !empty($dados_pessoa['dt_nascimento'])
                                    ? (new DateTime($dados_pessoa['dt_nascimento']))->format('d/m/Y')
                                    : '';
                                ?>

                                <div class="info-card">
                                    <div class="info-item">
                                        <label class="info-label">Nome Completo</label>
                                        <div class="info-value"><?= htmlspecialchars($dados_pessoa['nome'] ?? '') ?></div>
                                    </div>
                                    <div class="info-item">
                                        <label class="info-label">Data de Nascimento</label>
                                        <div class="info-value"><?= htmlspecialchars($dtNascimento) ?></div>
                                    </div>
                                    <div class="info-item">
                                        <label class="info-label">Sexo</label>
                                        <div class="info-value"><?= htmlspecialchars($dados_pessoa['sexo'] ?? '') ?></div>
                                    </div>
                                    <div class="info-item">
                                        <label class="info-label">CPF/CNPJ</label>
                                        <div class="info-value"><?= htmlspecialchars($dados_pessoa['num_documento'] ?? '') ?></div>
                                    </div>
                                    <div class="info-item">
                                        <label class="info-label">RG</label>
                                        <div class="info-value"><?= htmlspecialchars($dados_pessoa['rg'] ?? '') ?></div>
                                    </div>
                                    <div class="info-item">
                                        <label class="info-label">PIS/PASEP</label>
                                        <div class="info-value"><?= htmlspecialchars($dados_pessoa['pis'] ?? '') ?></div>
                                    </div>
                                    <div class="info-item">
                                        <label class="info-label">CTPS</label>
                                        <div class="info-value"><?= htmlspecialchars($dados_pessoa['ctps'] ?? '') ?></div>
                                    </div>
                                    <div class="info-item">
                                        <label class="info-label">Estado Civil</label>
                                        <div class="info-value"><?= htmlspecialchars($dados_pessoa['estado_civil'] ?? '') ?></div>
                                    </div>
                                    <div class="info-item">
                                        <label class="info-label">Profissão</label>
                                        <div class="info-value"><?= htmlspecialchars($dados_pessoa['profissao'] ?? '') ?></div>
                                    </div>
                                    <div class="info-item">
                                        <label class="info-label">Nome da Mãe</label>
                                        <div class="info-value"><?= htmlspecialchars($dados_pessoa['nome_mae'] ?? '') ?></div>
                                    </div>
                                </div>

                            </div>
                        </div>

                        <!-- Contato -->
                        <div class="tab-pane" id="contact">
                            <div class="info-grid">

                                <div class="info-card">
                                    <div class="info-item">
                                        <label class="info-label">Telefone Principal</label>
                                        <div class="info-value">
                                            <i class="fas fa-phone contact-icon"></i>
                                            <?= htmlspecialchars($dados_pessoa['telefone_principal'] ?? '') ?>
                                        </div>
                                    </div>
                                    <div class="info-item">
                                        <label class="info-label">Telefone Secundário</label>
                                        <div class="info-value">
                                            <i class="fas fa-phone contact-icon"></i>
                                            <?= htmlspecialchars($dados_pessoa['telefone_secundario'] ?? '') ?>
                                        </div>
                                    </div>
                                    <div class="info-item">
                                        <label class="info-label">Celular</label>
                                        <div class="info-value">
                                            <i class="fas fa-mobile-alt contact-icon"></i>
                                            <?= htmlspecialchars($dados_pessoa['celular'] ?? '') ?>
                                        </div>
                                    </div>
                                    <div class="info-item">
                                        <label class="info-label">E-mail Principal</label>
                                        <div class="info-value">
                                            <i class="fas fa-envelope contact-icon"></i>
                                            <?= htmlspecialchars($dados_pessoa['email'] ?? '') ?>
                                        </div>
                                    </div>
                                    <div class="info-item">
                                        <label class="info-label">E-mail Secundário</label>
                                        <div class="info-value">
                                            <i class="fas fa-envelope contact-icon"></i>
                                            <?= htmlspecialchars($dados_pessoa['email_secundario'] ?? '') ?>
                                        </div>
                                    </div>
                                </div>


                            </div>
                        </div>

                        <!-- Endereço -->
                        <div class="tab-pane" id="address">
                            <div class="info-grid">
                                <div class="info-card">
                                    <div class="info-item">
                                        <label class="info-label">CEP</label>
                                        <div class="info-value"><?= !empty($dados_pessoa['cep']) ? htmlspecialchars($dados_pessoa['cep']) : '' ?></div>
                                    </div>
                                    <div class="info-item">
                                        <label class="info-label">Estado</label>
                                        <div class="info-value"><?= !empty($dados_pessoa['estado']) ? htmlspecialchars($dados_pessoa['estado']) : '' ?></div>
                                    </div>
                                    <div class="info-item">
                                        <label class="info-label">Cidade</label>
                                        <div class="info-value"><?= !empty($dados_pessoa['cidade']) ? htmlspecialchars($dados_pessoa['cidade']) : '' ?></div>
                                    </div>
                                    <div class="info-item">
                                        <label class="info-label">Bairro</label>
                                        <div class="info-value"><?= !empty($dados_pessoa['bairro']) ? htmlspecialchars($dados_pessoa['bairro']) : '' ?></div>
                                    </div>
                                    <div class="info-item">
                                        <label class="info-label">Logradouro</label>
                                        <div class="info-value"><?= !empty($dados_pessoa['logradouro']) ? htmlspecialchars($dados_pessoa['logradouro']) : '' ?></div>
                                    </div>
                                    <div class="info-item">
                                        <label class="info-label">Número</label>
                                        <div class="info-value"><?= !empty($dados_pessoa['numero_casa']) ? htmlspecialchars($dados_pessoa['numero_casa']) : '' ?></div>
                                    </div>
                                    <div class="info-item full-width">
                                        <label class="info-label">Complemento</label>
                                        <div class="info-value"><?= !empty($dados_pessoa['complemento']) ? htmlspecialchars($dados_pessoa['complemento']) : '' ?></div>
                                    </div>
                                    <div class="info-item full-width">
                                        <label class="info-label">Observações</label>
                                        <div class="info-value"><?= !empty($dados_pessoa['observacao']) ? htmlspecialchars($dados_pessoa['observacao']) : '' ?></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Documentos -->
                        <div class="tab-pane" id="docs">
                            <div class="processes-section">
                                <div class="section-header">
                                    <h3>Documentos do Cliente</h3>
                                    <a href="./docs_pessoa.php?tkn=<?= urlencode($_GET['tkn']) ?>" style="text-decoration: none;">
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

</body>

</html>