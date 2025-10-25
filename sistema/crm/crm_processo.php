<?php
include_once('../../scripts.php');
$id_user = $_SESSION['cod'];

// Listagem de anotações
if ($_SERVER['REQUEST_METHOD'] === 'GET' && !empty($_GET['acao']) && $_GET['acao'] == 'puxar_anotacoes_card' && !empty($_GET['id_processo'])) {

    $id_processo = $conexao->escape_string(htmlspecialchars($_GET['id_processo']));

    $sql_busca_anotacoes = "SELECT anotacoes_crm.*, p.id_processo FROM anotacoes_crm
    INNER JOIN processo p ON anotacoes_crm.processo_id_processo = p.id_processo
    WHERE usuario_config_id_usuario_config = $id_user AND p.id_processo = $id_processo ORDER BY dt_cadastro_anotacoes DESC";
    $anotacoes = $conexao->query($sql_busca_anotacoes);

    if ($anotacoes->num_rows > 0) {
        $lista_anotacoes = [];

        while ($anotacao = $anotacoes->fetch_assoc()) {
            array_push($lista_anotacoes, $anotacao);
        }

        $res = [
            'status' => 'success',
            'anotacoes' => $lista_anotacoes
        ];
    } else {
        $res = [
            'status' => 'success',
            'anotacoes' => ''
        ];
    }

    echo json_encode($res, JSON_UNESCAPED_UNICODE);
    $conexao->close();
    exit;
}


if ($_SERVER['REQUEST_METHOD'] === 'GET') {

    $sql_busca_etapas_crm = "SELECT * FROM etapas_crm WHERE usuario_config_id_usuario_config = $id_user ORDER BY ordem ASC";
    $etapas = $conexao->query($sql_busca_etapas_crm);


    $lista_etapas_kanban = [];
    while ($row = $etapas->fetch_assoc()) {
        $lista_etapas_kanban[] = $row;
    }

    // $conexao->close();
}


if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['nova_etapa'])) {
    $noma_etapa = $conexao->escape_string(htmlspecialchars($_POST['nova_etapa']));

    $ultima_etapa = "SELECT ordem FROM etapas_crm WHERE usuario_config_id_usuario_config = $id_user ORDER BY 'ordem' DESC LIMIT 1";
    $ultimo = $conexao->query($ultima_etapa);
    $ultimo = $ultimo->fetch_assoc();

    $ordem_nova_etapa = intval($ultimo['ordem']) + 1;
    $sql_cadastra_etapa = "INSERT INTO etapas_crm (ordem,nome, usuario_config_id_usuario_config) 
    VALUES ($ordem_nova_etapa, '$noma_etapa', $id_user)";

    if ($conexao->query($sql_cadastra_etapa)) {
        $res = [
            'status' => 'success',
            'message' => 'Etapa cadastrada com sucesso!',
            'data' => [
                'id' => $conexao->insert_id,
                'nome' => $noma_etapa
            ]
        ];
    } else {
        $res = [
            'status' => 'erro',
            'message' => 'Etapa cadastrada com sucesso!',
        ];
    }


    echo json_encode($res, JSON_UNESCAPED_UNICODE);
    $conexao->close();
    exit;
}


if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['id_etapa_exclusao'])) {

    $id_etapa_exclusao = $conexao->real_escape_string(htmlspecialchars($_POST['id_etapa_exclusao']));
    // Primeiro, verificar se existem processos nessa etapa
    $sql_verifica_processo_etapa = "
        SELECT id_processo 
        FROM processo 
        WHERE etapa_kanban = ? 
        AND usuario_config_id_usuario_config = ?";

    $stmt = $conexao->prepare($sql_verifica_processo_etapa);
    $stmt->bind_param('ii', $id_etapa_exclusao, $id_user);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows > 0) {
        $res = [
            'status' => 'erro',
            'message' => 'Não é possível excluir esta etapa. Existem processos vinculados a ela.'
        ];
    } else {
        $sql_excluir_etapa = "
            DELETE FROM etapas_crm 
            WHERE id_etapas_crm = ? 
            AND usuario_config_id_usuario_config = ?";

        $stmt_delete = $conexao->prepare($sql_excluir_etapa);
        $stmt_delete->bind_param('ii', $id_etapa_exclusao, $id_user);

        if ($stmt_delete->execute()) {
            $res = [
                'status' => 'success',
                'message' => 'Etapa excluída com sucesso!'
            ];
        } else {
            $res = [
                'status' => 'erro',
                'message' => 'Erro ao excluir a etapa.'
            ];
        }
    }

    echo json_encode($res);
    $conexao->close();
    exit;
}



if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['ordem'])) {
    foreach ($_POST['ordem'] as $indice => $valor) {

        $ordem = $indice + 1;
        $sql_atualiza_ordem = "UPDATE etapas_crm SET ordem = $ordem where id_etapas_crm = $valor ";
        if ($conexao->query($sql_atualiza_ordem)) {
            echo json_encode(['status' => 'sucesso']);
        }
    }

    $conexao->close();
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['id_nova_etapa_kanban']) && !empty($_POST['id_card_movido'])) {

    $id_nova_etapa_kanban = $conexao->real_escape_string(htmlspecialchars($_POST['id_nova_etapa_kanban']));
    $id_card_movido = $conexao->real_escape_string(htmlspecialchars($_POST['id_card_movido']));

    $sql_atualiza_etapa_processo = "UPDATE processo SET etapa_kanban = ? WHERE id_processo = ? AND usuario_config_id_usuario_config = ? ";

    $stmt = $conexao->prepare($sql_atualiza_etapa_processo);
    $stmt->bind_param('iii', $id_nova_etapa_kanban, $id_card_movido, $id_user);

    if ($stmt->execute()) {
        $res = [
            'status' => 'success',
            'message' => 'Etapa atualizada com sucesso!',
        ];
    } else {
        $res = [
            'status' => 'erro',
            'message' => 'Erro ao atualizar etapa!',
        ];
    }

    echo json_encode($res);
    $conexao->close();
    exit;
}


// Cadastro de anotações
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['titulo_anotacao']) && !empty($_POST['anotacao']) && !empty($_POST['id_cadastro_anotacao'])) {

    $titulo_anotacao        = $conexao->real_escape_string(htmlspecialchars($_POST['titulo_anotacao']));
    $anotacao               = $conexao->real_escape_string(htmlspecialchars($_POST['anotacao']));
    $id_processo            = $conexao->real_escape_string(htmlspecialchars($_POST['id_cadastro_anotacao']));

    $sql_cadastra_anotacao  = "INSERT INTO anotacoes_crm (titulo, descricao,processo_id_processo, dt_cadastro_anotacoes) VALUES (?,?,?, NOW())";
    $stmt = $conexao->prepare($sql_cadastra_anotacao);
    $stmt->bind_param('ssi', $titulo_anotacao, $anotacao, $id_processo);

    if ($stmt->execute()) {
        $res = [
            'status' => 'success',
            'message' => 'Anotação cadastrada com sucesso!',
            'id_anotacao' => $conexao->insert_id
        ];
    } else {
        $res = [
            'status' => 'erro',
            'message' => 'Erro ao cadastrada anotação!',
        ];
    }

    echo json_encode($res);
    $conexao->close();
    exit;
}


// Excluir anotação
if (
    $_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['acao']) && $_POST['acao'] === 'delete_anotacao' && !empty($_POST['id_anotacao']) && !empty($_POST['id_processo'])
) {

    $id_anotacao = $conexao->real_escape_string(htmlspecialchars($_POST['id_anotacao']));
    $id_processo = $conexao->real_escape_string(htmlspecialchars($_POST['id_processo']));

    // Monta o SQL de exclusão garantindo que a anotação pertence ao processo
    $sql_delete_anotacao = "DELETE FROM anotacoes_crm WHERE id_anotacao_crm = ? AND processo_id_processo = ?";
    $stmt = $conexao->prepare($sql_delete_anotacao);
    $stmt->bind_param('ii', $id_anotacao, $id_processo);

    if ($stmt->execute()) {
        // Sucesso
        $res = [
            'status' => 'success',
            'message' => 'Anotação excluída com sucesso!',
        ];
    } else {
        // Falha ao excluir
        $res = [
            'status' => 'error',
            'message' => 'Erro ao excluir anotação. Tente novamente.'
        ];
    }

    echo json_encode($res);
    $stmt->close();
    $conexao->close();
    exit;
}




// Função para mapear contingenciamento para classe CSS
function getBadgeClass($contingenciamento)
{
    $contingenciamento = strtolower($contingenciamento);

    if (strpos($contingenciamento, 'provável') !== false) {
        return 'low';
    } elseif (strpos($contingenciamento, 'possível') !== false) {
        return 'medium';
    } elseif (strpos($contingenciamento, 'remota') !== false) {
        return 'high';
    } else {
        return 'medium'; // padrão
    }
}

?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/kanban/crm.css">
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Sortable/1.15.0/Sortable.min.js"></script>
    <script src="../js/geral.js"></script>

    <title>ADV Conectado</title>
</head>

<?php
include_once('../geral/menu_lat.php');
include_once('../geral/topo.php');
?>
<div class="container_breadcrumb">
    <div class="pai_topo">
        <div class="breadcrumb">
            <span class="breadcrumb-current">CRM</span>
            <span class="breadcrumb-separator">/</span>
        </div>
    </div>
</div>

<body>
    <main class="container_principal">
        <div class="pai_conteudo">

            <div class="topo_kanban">
                <h1>Gestão CRM</h1>

                <div style="display: flex; gap:8px">
                    <button class="btn_adicionar" id="add_processo"> <i class="fa-solid fa-plus"></i> Novo Processo </button>
                    <button id="config_crm"><i class="fa-solid fa-gear"></i> Configurar CRM</button>
                </div>

            </div>

            <div class="kanban">
                <?php foreach ($lista_etapas_kanban as $index => $etapa): ?>

                    <?php
                    $id_etapa =  $etapa['id_etapas_crm'];

                    $sql_busca_processo_etapa = "SELECT 
                                p.id_processo,
                                p.tk,
                                p.grupo_acao,
                                p.tipo_acao,
                                p.referencia,
                                p.valor_causa,
                                p.valor_honorarios,
                                p.etapa_kanban,
                                p.contingenciamento,                         

                                -- Dados do cliente
                                c.id_pessoa   AS cliente_id,
                                c.nome        AS cliente_nome,
                                c.foto_pessoa AS cliente_foto

                            FROM processo p
                            LEFT JOIN pessoas c     ON p.cliente_id         = c.id_pessoa
                            where p.etapa_kanban = $id_etapa and p.usuario_config_id_usuario_config = $id_user";

                    $cards_etapa = $conexao->query($sql_busca_processo_etapa);



                    ?>

                    <div class="kanban-column" data-id="<?php echo $index + 1; ?>" data-cod-etapa="<?php echo $etapa['id_etapas_crm']; ?>">
                        <h2><?php echo $etapa['ordem'] . ' - ' . $etapa['nome']; ?></h2>
                        <div class="kanban-cards" id="column<?php echo $index + 1; ?>">

                            <?php while ($card = $cards_etapa->fetch_assoc()):
                                $badgeClass = getBadgeClass($card['contingenciamento']);
                            ?>

                                <div class="kanban-card" data-cod-card="<?php echo $card['id_processo']; ?>">
                                    <div class="header_card">
                                        <p class="ref_card">ref: <?php echo $card['referencia']; ?></p>
                                        <div class="badge <?php echo $badgeClass; ?>">
                                            <span><?php echo ucfirst($card['contingenciamento']); ?></span>
                                        </div>
                                    </div>

                                    <div class="container_card">
                                        <div class="dados_processo">
                                            <p class="tipo_acao"><?php echo $card['tipo_acao']; ?></p>
                                            <p class="grupo_acao"><?php echo $card['grupo_acao']; ?></p>
                                        </div>

                                        <div class="dados_cliente">
                                            <p class="card-subtitle"><?php echo $card['cliente_nome']; ?></p>
                                            <?php if ($card['cliente_foto']): ?>
                                                <img src="../..<?php echo $card['cliente_foto']; ?>" alt="" srcset="">
                                            <?php endif ?>
                                        </div>

                                    </div>

                                    <div class="card-footer">
                                        <?php if (!empty($card['valor_causa'])): ?>
                                            <span>Causa: <?php echo $card['valor_causa']; ?></span>
                                        <?php endif; ?>

                                        <?php if (!empty($card['valor_honorarios'])): ?>
                                            <span>Comissão: <?php echo $card['valor_honorarios']; ?></span>
                                        <?php endif; ?>
                                    </div>

                                    <div class="opcoes_card_processo">
                                        <a href="../processo/ficha_processo.php?tkn=<?php echo $card['tk'] ?>" target="__blank"><i class="fa-solid fa-magnifying-glass"></i> Ficha</a>
                                        <a href="javascript:void(0)" class="add_anotacao"><i class="fa-solid fa-plus"></i> Anotação
                                            <input type="hidden" class="id_card" value="<?php echo $card['id_processo'] ?>">
                                        </a>
                                        <a href="javascript:void(0)" class="finalizar_processo">
                                            <i class="fa-solid fa-check"></i> Encerrar
                                            <input type="hidden" class="id_card" value="<?php echo $card['id_processo'] ?>">
                                        </a>
                                    </div>


                                </div>



                            <?php endwhile; ?>

                        </div>
                    </div>
                <?php endforeach; ?>





            </div>


        </div>
    </main>



    <script>
        $('#add_processo').click(() => {
            window.location.href = '../processo/cadastro_processo.php'
        })
    </script>


    <!-- Lógica do CRUD de anotações -->
    <script>
        $(document).ready(function() {
            $(".add_anotacao").on("click", function() {
                let id_card = $(this).find('.id_card').val()

                // Exibe que estamos carregando os cados
                Swal.fire({
                    title: "Carregando Anotações",
                    html: "Aguarde",
                    timer: 1000,
                    timerProgressBar: true,
                    willClose: () => {
                        clearInterval(timerInterval);
                    }
                })
                if (id_card) {
                    $.ajax({
                        url: './crm_processo.php',
                        method: 'GET',
                        dataType: 'JSON',
                        data: {
                            acao: 'puxar_anotacoes_card',
                            id_processo: id_card
                        },
                        success: function(res) {

                            if (res.status == 'success') {
                                Swal.fire({
                                    title: 'Gerenciamento de Anotações',
                                    html: `
                           <div class="container_anotacoes">
              
                <div class="container_form_anotacoes">
                    <form id="formAnotacao" autocomplete="off">
                        <input type="text" name="titulo_anotacao" id="titulo_anotacao" placeholder="Título" maxlength="60" required>
                        <textarea name="anotacao" id="anotacao" placeholder="Adicione sua anotação" rows="3" maxlength="200"  required></textarea>
                        <input type="hidden" name="id_cadastro_anotacao" value="${id_card}">
                        <button type="submit" class="btn_adicionar" style="display:flex; justify-content: center; max-width:250px; margin: 0 auto ">
                            <i class="fa-solid fa-plus"></i> Adicionar Anotação
                        </button>
                    </form>
                </div>
                <div class="container_listagem_anotacoes" id="listagemAnotacoes">
                                        
                </div>

            </div>
                `,

                                    confirmButtonText: 'Fechar',
                                    confirmButtonColor: " #06112483",
                                    didOpen: () => {


                                        $('#listagemAnotacoes').empty();

                                        // Verifica se existe o array de anotações e se ele contém itens
                                        if (res.anotacoes && res.anotacoes.length > 0) {

                                            res.anotacoes.forEach(function(anotacao) {
                                                // Formatar data para dd/mm/yy às hh:mm
                                                let data = new Date(anotacao.dt_cadastro_anotacoes);
                                                let dataFormatada = data.toLocaleDateString('pt-BR', {
                                                    year: '2-digit',
                                                    month: '2-digit',
                                                    day: '2-digit'
                                                });
                                                let horaFormatada = data.toLocaleTimeString('pt-BR', {
                                                    hour: '2-digit',
                                                    minute: '2-digit'
                                                });

                                                // Monta o HTML da anotação
                                                let item = `
                    <div class="anotacao_item">
                        <div class="delete_anotacao">
                            X
                            <input type="hidden" name="id_anotacao" value="${anotacao.id_anotacao_crm}">
                            <input type="hidden" name="id_processo" value="${id_card}">
                        </div>
                        <div class="titulo">
                            ${anotacao.titulo}
                            <div class="infos_anotacoes">${dataFormatada} às ${horaFormatada}</div>
                        </div>
                        <div class="descricao">${anotacao.descricao}</div>
                    </div>
                `;

                                                // Adiciona no container
                                                $('#listagemAnotacoes').append(item);
                                            });

                                        } else {
                                            // Se não houver anotações, exibe mensagem amigável
                                            $('#listagemAnotacoes').html(`
                <div class="anotacao_item" style="text-align:center; opacity:0.7;">
                    Nenhuma anotação cadastrada
                </div>
            `);
                                        }


                                        $('#formAnotacao').submit(function(e) {
                                            e.preventDefault();

                                            let form = $(this).serialize();

                                            $.ajax({
                                                url: './crm_processo.php',
                                                method: 'POST',
                                                dataType: 'json',
                                                data: form,
                                                success: function(res) {

                                                    if (res.status === 'success') {
                                                        // Captura os campos diretamente do formulário
                                                        let titulo = $('#formAnotacao [name="titulo_anotacao"]').val();
                                                        let descricao = $('#formAnotacao [name="anotacao"]').val();
                                                        let id_anotacao = res.id_anotacao; // vem do backend

                                                        // Pega a data/hora atual
                                                        let agora = new Date();
                                                        let dataFormatada = agora.toLocaleDateString('pt-BR', {
                                                            year: '2-digit',
                                                            month: '2-digit',
                                                            day: '2-digit'
                                                        });
                                                        let horaFormatada = agora.toLocaleTimeString('pt-BR', {
                                                            hour: '2-digit',
                                                            minute: '2-digit'
                                                        });

                                                        // Monta o HTML da nova anotação
                                                        let novaAnotacao = `
                    <div class="anotacao_item">
                        <div class="delete_anotacao">
                            X
                            <input type="hidden" name="id_anotacao" value="${id_anotacao}">
                            <input type="hidden" name="id_processo" value="${id_card}">
                        </div>
                        <div class="titulo">
                            ${titulo}
                            <div class="infos_anotacoes">${dataFormatada} às ${horaFormatada}</div>
                        </div>
                        <div class="descricao">${descricao}</div>
                    </div>
                `;

                                                        // Remove a mensagem "Nenhuma anotação cadastrada", se existir
                                                        $('#listagemAnotacoes .anotacao_item').first().text().trim() === 'Nenhuma anotação cadastrada' ?
                                                            $('#listagemAnotacoes').empty() :
                                                            null;

                                                        // Adiciona a nova anotação no topo da lista
                                                        $('#listagemAnotacoes').prepend(novaAnotacao);

                                                        // (Opcional) Limpa o formulário
                                                        $('#formAnotacao')[0].reset();
                                                    }
                                                },
                                                error: function(err) {
                                                    console.error('Erro no AJAX:', err);
                                                }
                                            });
                                        });


                                        $(document).off('click', '.delete_anotacao').on('click', '.delete_anotacao', function() {
                                            let idAnotacao = $(this).find('input[name="id_anotacao"]').val();
                                            let idProcesso = $(this).find('input[name="id_processo"]').val();
                                            let card_anotacao = $(this).closest('.anotacao_item');

                                            $.ajax({
                                                url: './crm_processo.php',
                                                method: 'POST',
                                                dataType: 'json',
                                                data: {
                                                    acao: 'delete_anotacao',
                                                    id_anotacao: idAnotacao,
                                                    id_processo: idProcesso
                                                },
                                                success: function(res) {
                                                    if (res.status === 'success') {
                                                        card_anotacao.fadeOut(300, function() {
                                                            $(this).remove();

                                                            if ($('#listagemAnotacoes .anotacao_item').length === 0) {
                                                                $('#listagemAnotacoes').html(`
                            <div class="anotacao_item" style="text-align:center; opacity:0.7;">
                                Nenhuma anotação cadastrada
                            </div>
                        `);
                                                            }
                                                        });
                                                    }
                                                }
                                            });
                                        });






                                    }

                                })
                            }
                        }
                    })
                }

            })
        })
    </script>


    <!-- Formulário para finalizar processo -->
    <script>
        $(document).ready(function() {
            $('.finalizar_processo').on('click', function() {
                let id_finalizar = $(this).closest('.kanban-card').attr('data-cod-card')

                Swal.fire({
                    title: 'Finalizar Processo',
                    html: `
              <div class="container_encerra">
              
                <div class="container_form_encerra">
                    <form id="formAnotacao" autocomplete="off">

                        <label for="resultado">Causa Foi Ganha?</label>
                        <select id="resultado" name="resultado" required>
                            <option value="">Selecione</option>
                            <option value="sim">Sim</option>
                            <option value="nao">Não</option>
                        </select>

                        <label for="resultado">Observações:</label>
                        <textarea name="anotacao" id="anotacao" placeholder="Anotações sobre o processo" rows="3" maxlength="200"  required></textarea>
                        <input type="hidden" name="id_cadastro_anotacao" value="${id_finalizar}">
                        <button type="submit" class="btn_adicionar" style="display:flex; justify-content: center; max-width:250px; margin: 0 auto ">
                            <i class="fa-solid fa-check"></i> Encerrar Processo
                        </button>
                    </form>
                </div>
                

            </div>
            `,
                    showCancelButton: true,
                    cancelButtonText: 'Cancelar',
                    confirmButtonColor: " #3085d6",
                    cancelButtonColor: "#d33",
                    didOpen: () => {

                    }


                })
            })
        })
    </script>






    <script>
        $(document).ready(function() {
            $("#config_crm").on("click", function() {
                Swal.fire({
                    title: 'Configuração do CRM',
                    html: `
              <div class="crm-config">
                <!-- Lista de etapas -->
                <div class="crm-steps">
                  <h3>Etapas CRM</h3>
                  <span>Arraste para ordenar as etapas. A lista está na ordem atual.</span>
                  <table>
                    <thead>
                      <tr>
                        <th>Nome</th>
                        <th>Ações</th>
                      </tr>
                    </thead>

                    <tbody id="sortable-steps">
                        <?php foreach ($lista_etapas_kanban as $etapa): ?>
                            <tr class="linha_etapa" data-id="<?php echo $etapa['id_etapas_crm'] ?>">
                                <td><?php echo $etapa['nome'] ?></td>
                                <td>
                                    <button class="icon-btn delete"><i class="fa-solid fa-trash"></i></button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>

                  </table>
                </div>

                <!-- Adicionar nova etapa -->
                <div class="crm-add-step">
                  <h3>Adicionar Etapa</h3>
                  <form id="form-add-etapa">
                    <label for="etapa-nome">Nome</label>
                    <input type="text" id="etapa-nome" placeholder="Digite o nome da etapa">
                    <button type="submit" class="add-btn">
                      <i class="fa-solid fa-plus"></i> Adicionar
                    </button>
                  </form>
                </div>
              </div>
            `,
                    showCancelButton: true,
                    confirmButtonText: 'Salvar',
                    cancelButtonText: 'Cancelar',
                    confirmButtonColor: " #3085d6",
                    cancelButtonColor: "#d33",
                    didOpen: () => {
                        // Inicializa sortable apenas depois do SweetAlert abrir
                        new Sortable(document.getElementById('sortable-steps'), {
                            animation: 150,
                            ghostClass: 'drag-highlight'
                        });

                        $('.delete').on('click', function() {
                            let remover_linha = $(this).closest('.linha_etapa')
                            let id_etapa_exclusao = $(this).closest('.linha_etapa').attr('data-id')

                            $.ajax({
                                url: './crm_processo.php',
                                method: 'POST',
                                dataType: 'json',
                                data: {
                                    id_etapa_exclusao: id_etapa_exclusao
                                },
                                success: function(res) {
                                    if (res.status !== 'success') {

                                        Swal.fire({
                                            icon: "error",
                                            title: "Oops...",
                                            text: res.message,
                                        });

                                        setTimeout(() => {
                                            window.location.reload()
                                        }, 1500)


                                    } else {
                                        remover_linha.fadeOut(300, function() {
                                            $(this).remove();
                                        });
                                    }

                                }

                            })

                        })

                        tippy('.delete', {
                            content: "Ao clicar em excluir, a ação será realizada de forma instantânea.",
                            placement: "top",
                        });

                        // Captura envio do formulário dentro do Swal
                        $("#form-add-etapa").on("submit", function(e) {
                            e.preventDefault();
                            const nome = $("#etapa-nome").val();

                            if (nome.trim() !== "") {
                                $("#etapa-nome").val("");
                            }

                            $.ajax({
                                url: './crm_processo.php',
                                method: 'POST',
                                dataType: 'json',
                                data: {
                                    nova_etapa: nome
                                },
                                success: function(resposta) {

                                    if (resposta.status == 'success') {
                                        $("#sortable-steps").append(`
                                    <tr class="linha_etapa" data-id="${resposta.data.id}">
                                        <td>${nome}</td>
                                        <td>
                                            <button class="icon-btn delete"><i class="fa-solid fa-trash"></i></button>
                                        </td>
                                    </tr>
                                `);
                                    }


                                }
                            })

                        });

                    },
                    preConfirm: () => {

                        const ordemAtual = [...document.querySelectorAll("#sortable-steps tr")]
                            .map(el => el.dataset.id);

                        $.ajax({
                            url: './crm_processo.php',
                            method: 'POST',
                            dataType: 'json',
                            data: {
                                ordem: ordemAtual
                            }
                        })
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        Swal.fire('Configuração salva!', '', 'success');
                        setTimeout(() => {
                            window.location.reload()
                        }, 1000)
                    }
                });
            });
        });
    </script>



    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Inicializar Sortable para cada coluna
            const columns = document.querySelectorAll('.kanban-cards');

            columns.forEach(column => {
                new Sortable(column, {
                    group: 'kanban', // Define um grupo para permitir arrastar entre colunas
                    animation: 150, // Duração da animação em ms
                    ghostClass: 'sortable-ghost', // Classe para o elemento fantasma
                    chosenClass: 'sortable-chosen', // Classe para o elemento escolhido
                    dragClass: 'sortable-drag', // Classe para o elemento sendo arrastado

                    // Evento chamado quando um elemento é movido
                    onEnd: function(evt) {

                        let id_nova_etapa_kanban = evt.to.closest('.kanban-column').dataset.codEtapa
                        let id_card_movido = evt.item.dataset.codCard

                        $.ajax({
                            url: './crm_processo.php',
                            method: 'POST',
                            dataType: 'json',
                            data: {
                                id_nova_etapa_kanban: id_nova_etapa_kanban,
                                id_card_movido: id_card_movido

                            },
                            success: function(resposta) {
                                if (resposta.status !== 'success') {
                                    Swal.fire({
                                        icon: "error",
                                        title: "Erro ao atualizar etapa!",
                                        text: "Tente novamente em alguns minutos",

                                    });

                                    setTimeout(() => {
                                        window.location.reload()
                                    }, 1200)

                                }
                            }
                        })

                    }
                });
            });

        });
    </script>


    <script>
        const kanban = document.querySelector(".kanban");

        let isDown = false;
        let startX;
        let scrollLeft;

        kanban.addEventListener("mousedown", (e) => {
            // 🔹 só ativa o drag-to-scroll se não clicou em um card
            if (e.target.closest(".kanban-card")) return;

            isDown = true;
            // kanban.classList.add("dragging");
            startX = e.pageX;
            scrollLeft = kanban.scrollLeft;
        });

        kanban.addEventListener("mouseup", () => {
            isDown = false;
            // kanban.classList.remove("dragging");
        });

        kanban.addEventListener("mouseleave", () => {
            isDown = false;
            // kanban.classList.remove("dragging");
        });

        kanban.addEventListener("mousemove", (e) => {
            if (!isDown) return;
            e.preventDefault();
            const x = e.pageX;
            const walk = (x - startX);
            kanban.scrollLeft = scrollLeft - walk;
        });
    </script>

    <script src="../js/geral.js"></script>
</body>

</html>