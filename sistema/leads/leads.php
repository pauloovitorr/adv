<?php
include_once('../../scripts.php');
$id_user = $_SESSION['cod'];

if ($_SERVER['REQUEST_METHOD'] == 'GET') {

    // Paginação
    $limite = 20;
    $pagina = isset($_GET['pagina']) ? (int) $_GET['pagina'] : 1;
    if ($pagina < 1)
        $pagina = 1;
    $offset = ($pagina - 1) * $limite;

    // Quantidade de leads
    $sql_quantidade_leads = " SELECT COUNT(*) AS total_leads FROM leads  WHERE usuario_config_id_usuario_config = $id_user";

    $res_qtd = $conexao->query($sql_quantidade_leads);
    if ($res_qtd && $res_qtd->num_rows == 1) {
        $res_qtd = mysqli_fetch_assoc($res_qtd);
        $total = $res_qtd["total_leads"];
    }

    // Filtros
    if (count($_GET) > 0) {

        $nome = isset($_GET['buscar_leads']) ? trim($_GET['buscar_leads']) : null;
        $ordenar = isset($_GET['ordenar']) ? trim($_GET['ordenar']) : null;


        $sql_filtros = "
            SELECT 
                id_lead,
                nome,
                email,
                telefone,
                mensagem,
                dt_cadastro
            FROM leads 
            WHERE usuario_config_id_usuario_config = $id_user
        ";

        $params = [];
        $types = "";

        if (!empty($nome)) {
            $sql_filtros .= " AND nome LIKE ?";
            $params[] = "%$nome%";
            $types .= "s";
        }

        switch ($ordenar) {
            case "nome":
                $sql_filtros .= " ORDER BY nome ASC";
                break;
            case "recentes":
                $sql_filtros .= " ORDER BY dt_cadastro DESC";
                break;
            case "antigos":
                $sql_filtros .= " ORDER BY dt_cadastro ASC";
                break;
            default:
                $sql_filtros .= " ORDER BY dt_cadastro DESC";
                break;
        }

        $sql_filtros .= " LIMIT $limite OFFSET $offset";

        $stmt = $conexao->prepare($sql_filtros);

        if (!empty($params)) {
            $stmt->bind_param($types, ...$params);
        }

        $stmt->execute();
        $res = $stmt->get_result();

    } else {

        // Busca padrão
        $sql_busca_leads = "
            SELECT 
                id_lead,
                nome,
                email,
                telefone,
                mensagem,
                dt_cadastro
            FROM leads
            WHERE usuario_config_id_usuario_config = $id_user
            ORDER BY dt_cadastro DESC
            LIMIT $limite OFFSET $offset
        ";

        $res = $conexao->query($sql_busca_leads);
    }

    // Total de páginas
    $total_paginas = ceil($total / $limite);
}


if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST) && $_POST['acao'] == 'excluir_lead') {

    $id_lead = $conexao->real_escape_string($_POST['id_lead']);

    if ($id_lead) {

        try {
            $conexao->begin_transaction();

            // Busca o lead para verificar se existe e pertence ao usuário
            $sql_busca_lead = "SELECT id_lead, nome, email FROM leads WHERE id_lead = '$id_lead' AND usuario_config_id_usuario_config = $id_user";
            $res = $conexao->query($sql_busca_lead);

            if ($res->num_rows == 0) {
                $res = [
                    'status' => 'error',
                    'message' => 'Lead não encontrado ou você não tem permissão para excluí-lo!'
                ];
                echo json_encode($res, JSON_UNESCAPED_UNICODE);
                $conexao->rollback();
                $conexao->close();
                exit;
            }

            $lead_exclusao = $res->fetch_assoc();
            $lead_nome_exclusao = $lead_exclusao['nome'];
            $lead_id_exclusao = $lead_exclusao['id_lead'];

            // Prepara e executa a exclusão do lead
            $sql_delete_lead = 'DELETE FROM leads WHERE id_lead = ? AND usuario_config_id_usuario_config = ?';
            $stmt = $conexao->prepare($sql_delete_lead);
            $stmt->bind_param('ii', $lead_id_exclusao, $id_user);

            if ($stmt->execute()) {

                $ip = $_SERVER['REMOTE_ADDR'];

                if (cadastro_log('Excluiu Lead', $lead_nome_exclusao, $ip, $id_user)) {
                    $res = [
                        'status' => 'success',
                        'message' => 'Lead excluído com sucesso!'
                    ];
                    echo json_encode($res, JSON_UNESCAPED_UNICODE);
                    $conexao->commit();
                    $conexao->close();
                    exit;
                }
            }

        } catch (Exception $err) {
            $res = [
                'status' => 'error',
                'message' => 'Erro: ' . $err->getMessage()
            ];
            echo json_encode($res, JSON_UNESCAPED_UNICODE);
            $conexao->rollback();
            $conexao->close();
            exit;
        }
    } else {
        $res = [
            'status' => 'error',
            'message' => 'ID do lead não informado!'
        ];
        echo json_encode($res, JSON_UNESCAPED_UNICODE);
        $conexao->close();
        exit;
    }
}


?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/pessoas/pessoas.css">
    <title>ADV Conectado</title>

    <style>
        .mensagem_leads {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            text-overflow: ellipsis;
            line-height: 1.4em;
            max-height: 2.8em;
        }
    </style>

</head>

<?php
include_once('../geral/menu_lat.php');
include_once('../geral/topo.php');
?>
<div class="container_breadcrumb">
    <div class="pai_topo">
        <div class="breadcrumb">
            <span class="breadcrumb-current">Leads</span>
            <span class="breadcrumb-separator">/</span>
        </div>
    </div>
</div>

<body>
    <main class="container_principal">
        <div class="pai_conteudo">

            <div class="infos_pagina">
                <button> <i class="fa-regular fa-user"></i>
                    <?php echo $total <= 1 ? "$total Lead recebido" : "$total Leads recebidos" ?> </button>
            </div>

            <div class="opcoes_funcoes">
                <!-- <button class="btn_adicionar" id="add_pessoa"> <i class="fa-solid fa-plus"></i> Nova Pessoa </button> -->

                <form action="" method="get">

                    <div class="div_pai_funcoes">
                        <input type="text" id="buscar_leads" name="buscar_leads"
                            value="<?= isset($_GET['buscar_leads']) ? htmlspecialchars($_GET['buscar_leads']) : '' ?>"
                            placeholder="Buscar Por Nome">
                        <i class="fa-regular fa-pen-to-square"></i>
                    </div>

                    <div class="div_pai_funcoes">
                        <i class="fa-solid fa-arrow-up-wide-short"></i>
                        <select name="ordenar" id="ordenar">
                            <option value="">ordenar</option>
                            <option value="nome" <?= (isset($_GET['ordenar']) && $_GET['ordenar'] === 'nome') ? 'selected' : '' ?>>Nome do Leads</option>
                            <option value="recentes" <?= (isset($_GET['ordenar']) && $_GET['ordenar'] === 'recentes') ? 'selected' : '' ?>>Mais Recentes</option>
                            <option value="antigos" <?= (isset($_GET['ordenar']) && $_GET['ordenar'] === 'antigos') ? 'selected' : '' ?>>Mais Antigos</option>
                        </select>
                    </div>

                    <button type="submit" class="btn_pesquisar">Pesquisar <label style="cursor: pointer;"
                            for="buscar_pessoas"><i class="fa-solid fa-magnifying-glass"></i></label> </button>

                    <button type="submit" class="btn_pesquisar"><a href="./leads.php"
                            style="text-decoration: none;color: white;">Limpar <label style="cursor: pointer;"
                                for="buscar_pessoas"><i class="fa-solid fa-broom"></i></label> </a></button>
                </form>

            </div>


            <section class="container_lista_pessoas">

                <table>

                    <thead>
                        <tr>
                            <td>Nome</td>
                            <td>Contato</td>
                            <td>E-mail</td>
                            <td>Mensagem</td>
                            <td>Ações</td>
                        </tr>
                    </thead>

                    <tbody>

                        <?php if ($res->num_rows > 0): ?>

                            <?php while ($lead = mysqli_fetch_assoc($res)): ?>
                                <tr>
                                    <td colspan="5">

                                        <div class="dados_pessoa lead">

                                            <!-- Nome -->
                                            <div class="conteudo_pessoa container_nome">
                                                <div class="icone">
                                                    <?= htmlspecialchars(strtoupper(substr($lead['nome'], 0, 2)), ENT_QUOTES, 'UTF-8'); ?>
                                                </div>
                                                <div class="nome_pessoa">
                                                    <p><?= htmlspecialchars($lead['nome'], ENT_QUOTES, 'UTF-8'); ?></p>
                                                    <span>Lead</span>
                                                </div>
                                            </div>

                                            <!-- Contato -->
                                            <div class="conteudo_pessoa container_contato">
                                                <?php if (!empty($lead['telefone'])):

                                                    $telefoneLimpo = preg_replace('/\D/', '', $lead['telefone']);
                                                    if (strpos($telefoneLimpo, '55') !== 0) {
                                                        $telefoneLimpo = '55' . $telefoneLimpo;
                                                    }
                                                    ?>
                                                    <a href="https://wa.me/send?phone=<?= htmlspecialchars($telefoneLimpo, ENT_QUOTES, 'UTF-8'); ?>"
                                                        target="_blank" class="whatsapp">
                                                        <img src="../../img/whatsapp.png" alt="whatsapp">
                                                        <?= htmlspecialchars($lead['telefone'], ENT_QUOTES, 'UTF-8'); ?>
                                                    </a>
                                                <?php else: ?>
                                                    <p style="font-size: 14px; color:rgb(94, 94, 94);">Não foi cadastrado</p>
                                                <?php endif; ?>
                                            </div>

                                            <!-- Email -->
                                            <div class="conteudo_pessoa container_cidade">
                                                <?php if (!empty($lead['email'])): ?>
                                                    <p><?= htmlspecialchars($lead['email'], ENT_QUOTES, 'UTF-8'); ?></p>
                                                <?php else: ?>
                                                    <p style="font-size: 14px; color:rgb(94, 94, 94);">Não foi cadastrado</p>
                                                <?php endif; ?>
                                            </div>

                                            <!-- Mensagem -->
                                            <div class="conteudo_pessoa container_dt">
                                                <p class="mensagem_leads">
                                                    <?= nl2br(htmlspecialchars($lead['mensagem'], ENT_QUOTES, 'UTF-8')); ?>
                                                </p>
                                            </div>

                                            <!-- Ações -->
                                            <div class="conteudo_pessoa container_acao">
                                                <div class="opcoes_acao">
                                                    <i class="fa fa-ellipsis-h"></i>

                                                    <div class="opcoes_pessoa">
                                                        <ul>

                                                            <a href="./converter_lead.php?id=<?= (int) $lead['id_lead']; ?>">
                                                                <li><i class="fa-regular fa-user"></i> Criar Pessoa</li>
                                                            </a>

                                                            <a href="javascript:void(0)" class="excluir_lead">
                                                                <input type="hidden" class="token"
                                                                    value="<?= (int) $lead['id_lead']; ?>">
                                                                <li><i class="fa-regular fa-trash-can"></i> Excluir</li>
                                                            </a>

                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                    </td>
                                </tr>
                            <?php endwhile; ?>

                        <?php else: ?>
                            <tr>
                                <td colspan="5">
                                    <div class="sem_pessoas">
                                        <p>Nenhum Lead Cadastrado</p>
                                        <img src="../../img/listagem_pessoas.png" alt="" style="max-width: 200px;">
                                    </div>
                                </td>
                            </tr>
                        <?php endif; ?>

                    </tbody>




                </table>

            </section>







            <div class="pagination-container"
                style="display: flex; justify-content: center; align-items: center; margin-top: 20px; gap: 6px;">
                <?php if ($pagina > 1): ?>
                    <a href="?pagina=<?php echo $pagina - 1; ?>" class="pagination-btn">← Anterior</a>
                <?php endif; ?>

                <?php for ($i = 1; $i <= $total_paginas; $i++): ?>
                    <?php if ($i == $pagina): ?>
                        <span class="pagination-btn active"><?php echo $i; ?></span>
                    <?php else: ?>
                        <a href="?pagina=<?php echo $i; ?>" class="pagination-btn"><?php echo $i; ?></a>
                    <?php endif; ?>
                <?php endfor; ?>

                <?php if ($pagina < $total_paginas): ?>
                    <a href="?pagina=<?php echo $pagina + 1; ?>" class="pagination-btn">Próxima →</a>
                <?php endif; ?>
            </div>



        </div>
    </main>



    <!-- Script para exibir as opções quando os 3 prontinhos da ação são clicados -->
    <script>
        $(document).ready(function () {
            $('.opcoes_acao').on('click', function (e) {
                e.stopPropagation(); // Impede o clique no elemento de propagar para o documento

                var opcoesPessoa = $(this).find('.opcoes_pessoa');

                // Verifica se o menu está visível e alterna
                if (opcoesPessoa.is(':visible')) {
                    opcoesPessoa.hide(); // Esconde o menu
                } else {
                    opcoesPessoa.show(); // Exibe o menu
                }
            });

            $(document).on('click', function () {
                // Esconde qualquer menu aberto ao clicar fora
                $('.opcoes_pessoa').hide();
            });
        });
    </script>



    <script>
        $(function () {
            $('.excluir_lead').on('click', function () {
                let id_lead = $(this).find('.token').val()

                Swal.fire({
                    title: "Deseja realmente excluir o lead?",
                    text: "A ação é irreversível!",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: " #d33",
                    cancelButtonColor: "#3085d6",
                    confirmButtonText: "Sim, excluir!",
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {

                        $.ajax({
                            url: './leads.php',
                            type: 'POST',
                            data: {
                                id_lead: id_lead,
                                acao: 'excluir_lead'
                            },
                            dataType: 'json',
                            success: function (res) {

                                if (res.status == "success") {
                                    Swal.fire({
                                        title: "Exclusão",
                                        text: "Lead excluído com sucesso!",
                                        icon: "success"
                                    });

                                    setTimeout(() => {
                                        Swal.close()
                                        window.location.reload()
                                    }, 1000)

                                } else {
                                    Swal.fire({
                                        icon: "error",
                                        title: "Oops...",
                                        text: res.message
                                    });


                                }





                            }
                        })
                    }
                });
            })
        })
    </script>

</body>

</html>