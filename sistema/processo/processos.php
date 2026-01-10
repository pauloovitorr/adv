<?php
include_once('../../scripts.php');
$id_user = $_SESSION['cod'];

if ($_SERVER['REQUEST_METHOD'] == 'GET') {

    // Páginação
    $limite = 20;
    $pagina = isset($_GET['pagina']) ? (int) $_GET['pagina'] : 1;
    if ($pagina < 1)
        $pagina = 1;
    $offset = ($pagina - 1) * $limite;

    $sql_quantidade_processos = "SELECT 
    COUNT(*) AS total_processo,
    COUNT( CASE WHEN contingenciamento = 'provável/chance alta' THEN 1 END ) AS chance_alta,
    COUNT( CASE WHEN contingenciamento = 'possível/talvez' THEN 1 END ) AS chance_media,
    COUNT( CASE WHEN contingenciamento = 'remota/difícil' THEN 1 END ) AS chance_baixa
FROM processo WHERE usuario_config_id_usuario_config = {$_SESSION['cod']}";
    $res_qtd = $conexao->query($sql_quantidade_processos);


    if ($res_qtd->num_rows == 1) {
        $res_qtd = mysqli_fetch_assoc($res_qtd);
        $total = $res_qtd["total_processo"];
        $chance_alta = $res_qtd["chance_alta"];
        $chance_media = $res_qtd["chance_media"];
        $chance_baixa = $res_qtd["chance_baixa"];

        // var_dump($res_qtd);
    }

    if (count($_GET) > 0) {
        $tipo_acao = isset($_GET['buscar_tipo_acao']) ? htmlspecialchars($conexao->real_escape_string($_GET['buscar_tipo_acao'])) : null;
        $filtrar = isset($_GET['filtrar']) ? htmlspecialchars($conexao->real_escape_string($_GET['filtrar'])) : null;
        $ordenar = isset($_GET['ordenar']) ? htmlspecialchars($conexao->real_escape_string($_GET['ordenar'])) : null;

        $sql_filtros = "SELECT p.tipo_acao, p.status, p.grupo_acao, p.referencia, p.tk , p.contingenciamento, p.cliente_id , pes.nome, pes.id_pessoa
        FROM processo as p 
        INNER JOIN pessoas as pes ON p.cliente_id = pes.id_pessoa
        WHERE p.usuario_config_id_usuario_config = $id_user";

        $params = [];
        $types = "";

        if (!empty($tipo_acao)) {
            $sql_filtros .= " AND tipo_acao LIKE ?";
            $params[] = "%$tipo_acao%";
            $types .= "s";
        }

        if (!empty($filtrar)) {
            $sql_filtros .= " AND grupo_acao = ?";
            $params[] = $filtrar;
            $types .= "s";
        }

        switch ($ordenar) {
            case "tipo_acao":
                $sql_filtros .= " ORDER BY tipo_acao ASC";
                break;
            case "recentes":
                $sql_filtros .= " ORDER BY dt_cadastro_processo DESC";
                break;
            case "antigos":
                $sql_filtros .= " ORDER BY dt_cadastro_processo ASC";
                break;
            default:
                $sql_filtros .= " ORDER BY dt_cadastro_processo DESC";
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

        $sql_busca_pessoas = "SELECT p.tipo_acao, p.grupo_acao, p.referencia, p.tk , p.status, p.contingenciamento, p.cliente_id , pes.nome, pes.id_pessoa
FROM processo as p 
INNER JOIN pessoas as pes ON p.cliente_id = pes.id_pessoa
WHERE p.usuario_config_id_usuario_config = $id_user  
AND p.status = 'ativo'
ORDER BY p.dt_cadastro_processo DESC LIMIT $limite OFFSET $offset";
        $res = $conexao->query($sql_busca_pessoas);
    }

    // Total de páginas
    $total_paginas = ceil($total / $limite);

}



if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_GET) && $_GET['acao'] == 'deletar') {

    $token = htmlspecialchars($conexao->real_escape_string($_POST['token']));

    if ($token) {

        try {
            $conexao->begin_transaction();

            $sql_busca_processo = "SELECT referencia, id_processo
                       FROM processo 
                       WHERE usuario_config_id_usuario_config = $id_user 
                         AND tk = '$token'";
            $res = $conexao->query($sql_busca_processo);

            // Encontra dados do processo necessário para exclusão
            $processo_encontrado = $res->fetch_assoc();
            $referencia_processo = $processo_encontrado['referencia'] ?? null;
            $id_processo = $processo_encontrado['id_processo'] ?? null;

            // Pega os caminhos dos arquivos vinculados ao processo
            $sql_busca_caminho_arquivo_processo = "SELECT id_documento, caminho_arquivo FROM documento_processo WHERE id_processo = $id_processo AND usuario_config_id_usuario_config = $id_user";

            $arquivos = $conexao->query($sql_busca_caminho_arquivo_processo);

            if ($arquivos->num_rows > 0) {
                while ($arquivo = $arquivos->fetch_assoc()) {
                    if (file_exists('..' . $arquivo['caminho_arquivo']))
                        if (unlink('..' . $arquivo['caminho_arquivo'])) {
                            $sql_delete_docs_processo = "DELETE FROM documento_processo WHERE id_documento = {$arquivo['id_documento']} AND usuario_config_id_usuario_config = $id_user";
                            $conexao->query($sql_delete_docs_processo);
                        }
                }
            }

            // Deleta todas as anotações vinculadas ao processo
            $sql_deleta_anotacoes = "DELETE FROM anotacoes_crm WHERE processo_id_processo = $id_processo";
            $res_dell_anotacoes = $conexao->query($sql_deleta_anotacoes);



            $sql_delete_processo = 'DELETE FROM processo WHERE tk = ? AND usuario_config_id_usuario_config = ?';
            $stmt = $conexao->prepare($sql_delete_processo);
            $stmt->bind_param('si', $token, $id_user);


            if ($stmt->execute()) {

                $ip = $_SERVER['REMOTE_ADDR'];

                if (cadastro_log('Excluiu Processo', $referencia_processo, $ip, $id_user)) {
                    $res = [
                        'status' => 'success',
                        'message' => 'Processo excluída com sucesso!',
                    ];
                    echo json_encode($res, JSON_UNESCAPED_UNICODE);
                    $conexao->commit();
                    $conexao->close();
                    exit;
                }
            }
        } catch (Exception $err) {
            $res = [
                'status' => 'erro',
                'message' => 'Erro:' . $err->getMessage()
            ];
            echo json_encode($res, JSON_UNESCAPED_UNICODE);
            $conexao->rollback();
            $conexao->close();
            exit;
        }
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
        .inativo {
            background-color: #d5d5d53b;
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
            <span class="breadcrumb-current">Processos</span>
            <span class="breadcrumb-separator">/</span>
        </div>
    </div>
</div>

<body>
    <main class="container_principal">
        <div class="pai_conteudo">

            <div class="infos_pagina">
                <button> <i class="fa-regular fa-folder"></i>
                    <?php echo $total <= 1 ? "$total Processo Cadastrado" : "$total Processos Cadastrados" ?> </button>
                <button> <i class="fa-regular fa-folder"></i> <?php echo "$chance_alta chance alta" ?> </button>
                <button> <i class="fa-regular fa-folder"></i> <?php echo "$chance_media chance média" ?> </button>
                <button> <i class="fa-regular fa-folder"></i> <?php echo "$chance_baixa chance baixa" ?> </button>
            </div>

            <div class="opcoes_funcoes">
                <button class="btn_adicionar" id="add_processo"> <i class="fa-solid fa-plus"></i> Novo Processo
                </button>

                <form action="" method="get">

                    <div class="div_pai_funcoes">
                        <input type="text" id="buscar_tipo_acao" name="buscar_tipo_acao"
                            value="<?= isset($_GET['buscar_tipo_acao']) ? htmlspecialchars($_GET['buscar_tipo_acao']) : '' ?>"
                            placeholder="Buscar Por Tipo de Ação">
                        <i class="fa-regular fa-pen-to-square"></i>
                    </div>

                    <div class="div_pai_funcoes">

                        <select name="filtrar" id="filtrar" style="width: 200px;">
                            <option value="">Filtrar</option>
                            <option value="todos" <?= (isset($_GET['filtrar']) && $_GET['filtrar'] === 'todos') ? 'selected' : '' ?>>Todos os processos</option>
                            <option value="administrativo" <?= (isset($_GET['filtrar']) && $_GET['filtrar'] === 'administrativo') ? 'selected' : '' ?>>Administrativo</option>
                            <option value="trabalhista" <?= (isset($_GET['filtrar']) && $_GET['filtrar'] === 'trabalhista') ? 'selected' : '' ?>>Trabalhista</option>
                            <option value="civil" <?= (isset($_GET['filtrar']) && $_GET['filtrar'] === 'civil') ? 'selected' : '' ?>>Civil</option>
                            <option value="familia" <?= (isset($_GET['filtrar']) && $_GET['filtrar'] === 'familia') ? 'selected' : '' ?>>Família</option>
                            <option value="previdenciario" <?= (isset($_GET['filtrar']) && $_GET['filtrar'] === 'previdenciario') ? 'selected' : '' ?>>Previdenciário</option>
                            <option value="tributario" <?= (isset($_GET['filtrar']) && $_GET['filtrar'] === 'tributario') ? 'selected' : '' ?>>Tributário</option>
                            <option value="consumidor" <?= (isset($_GET['filtrar']) && $_GET['filtrar'] === 'consumidor') ? 'selected' : '' ?>>Consumidor</option>
                            <option value="empresarial" <?= (isset($_GET['filtrar']) && $_GET['filtrar'] === 'empresarial') ? 'selected' : '' ?>>Empresarial</option>
                            <option value="penal" <?= (isset($_GET['filtrar']) && $_GET['filtrar'] === 'penal') ? 'selected' : '' ?>>Penal</option>
                            <option value="imobiliario" <?= (isset($_GET['filtrar']) && $_GET['filtrar'] === 'imobiliario') ? 'selected' : '' ?>>Imobiliário</option>
                            <option value="eleitoral" <?= (isset($_GET['filtrar']) && $_GET['filtrar'] === 'eleitoral') ? 'selected' : '' ?>>Eleitoral</option>
                        </select>
                    </div>


                    <div class="div_pai_funcoes">
                        <i class="fa-solid fa-arrow-up-wide-short"></i>
                        <select name="ordenar" id="ordenar">
                            <option value="">ordenar</option>
                            <option value="tipo_acao" <?= (isset($_GET['ordenar']) && $_GET['ordenar'] === 'tipo_acao') ? 'selected' : '' ?>>Tipo Ação</option>
                            <option value="recentes" <?= (isset($_GET['ordenar']) && $_GET['ordenar'] === 'recentes') ? 'selected' : '' ?>>Mais Recentes</option>
                            <option value="antigos" <?= (isset($_GET['ordenar']) && $_GET['ordenar'] === 'antigos') ? 'selected' : '' ?>>Mais Antigos</option>
                        </select>
                    </div>

                    <button type="submit" class="btn_pesquisar">Pesquisar <label style="cursor: pointer;"
                            for="buscar_tipo_acao"><i class="fa-solid fa-magnifying-glass"></i></label> </button>

                    <button type="submit" class="btn_pesquisar"><a href="./processos.php"
                            style="text-decoration: none;color: white;">Limpar <label style="cursor: pointer;"
                                for="buscar_tipo_acao"><i class="fa-solid fa-broom"></i></label> </a></button>
                </form>

                <!-- <div class="div_pai_funcoes">
                    <i class="fa-solid fa-file-arrow-down"></i>
                    <select name="" id="a">
                        <option value="">Exportar</option>
                        <option value="aaa">Excel (.xlsx)</option>
                        <option value="aa">Imprimir (.pdf)</option>
                    </select>
                </div> -->


            </div>


            <section class="container_lista_pessoas">

                <table>

                    <thead>
                        <tr>
                            <td>Tipo de Ação</td>
                            <td>Grupo de ação</td>
                            <td>Referência</td>
                            <td>Contingenciamento</td>
                            <td>Ações</td>
                        </tr>
                    </thead>

                    <tbody>

                        <?php

                        // var_dump($res);
                        
                        if ($res->num_rows):

                            while ($proceso = mysqli_fetch_assoc($res)):

                                ?>

                                <tr>

                                    <td colspan="5">

                                        <?php
                                        switch ($proceso["contingenciamento"]) {
                                            case 'provável/chance alta':
                                                $classeChance = 'processo_chance_alta';
                                                break;
                                            case 'possível/talvez':
                                                $classeChance = 'processo_chance_media';
                                                break;
                                            default:
                                                $classeChance = 'processo_chance_baixa';
                                        }
                                        ?>


                                        <div class="dados_processo <?php echo $classeChance ?> <?php echo $proceso['status'] == 'inativo' ? 'inativo' : 'ativo' ?> "
                                            onclick="window.location.href='./ficha_processo.php?tkn=<?php echo $proceso['tk']; ?>'  ">
                                            <div class="conteudo_pessoa container_tipo_acao">
                                                <div class="icone"><?php echo strtoupper(substr($proceso['nome'], 0, 2)); ?>
                                                </div>
                                                <div class="nome_pessoa">
                                                    <p> <?php echo $proceso['tipo_acao']; ?> </p>
                                                    <span> <?php echo $proceso['nome']; ?> </span>
                                                </div>
                                            </div>

                                            <div class="conteudo_pessoa container_grupo">
                                                <p><?php echo ucfirst($proceso['grupo_acao']); ?></p>

                                            </div>

                                            <div class="conteudo_pessoa container_ref">
                                                <p><?php echo $proceso['referencia']; ?></p>
                                            </div>

                                            <div class="conteudo_pessoa container_chance">
                                                <p> <?php echo ucfirst($proceso['contingenciamento']); ?> </p>
                                            </div>


                                            <div class="conteudo_pessoa container_acao">
                                                <div class="opcoes_acao">
                                                    <i class="fa fa-ellipsis-h"></i>

                                                    <div class="opcoes_pessoa">
                                                        <ul>
                                                            <a href="./ficha_processo.php?tkn=<?php echo $proceso['tk'] ?>  "
                                                                target="_blank">
                                                                <li><i class="fa-regular fa-file-lines"></i> Ficha</li>
                                                            </a>

                                                            <a href="./docs_processo.php?tkn=<?php echo $proceso['tk'] ?>"
                                                                target="_blank">
                                                                <li><i class="fa-regular fa-id-card"></i> Documentos</li>
                                                            </a>


                                                            <a href="./cadastro_processo.php?acao=editar&amp;tkn=<?php echo $proceso['tk'] ?>"
                                                                target="_blank">
                                                                <li><i class="fa-regular fa-pen-to-square"></i> Editar</li>
                                                            </a>

                                                            <a href="javascript:void(0)" class="excluir_processo">
                                                                <input type="hidden" class="token"
                                                                    value="<?php echo $proceso['tk'] ?>">
                                                                <li><i class="fa-regular fa-trash-can"></i> Excluir</li>
                                                            </a>
                                                        </ul>
                                                    </div>
                                                </div>

                                            </div>

                                        </div>
                                    </td>
                                </tr>

                                <?php
                            endwhile;
                        else:
                            ?>

                            <tr>
                                <td colspan="5">
                                    <div class="sem_pessoas">
                                        <p>Nenhuma Processo Cadastrado ou Ativo</p>
                                        <img src="../../img/listagem_processo.png" alt="" style="max-width: 200px;">
                                    </div>
                                </td>

                            </tr>

                            <?php
                        endif;
                        ?>

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
        $(document).ready(function () {
            $('#add_processo').click(function () {
                window.open('./cadastro_processo.php', '_self');
            })
        })
    </script>

    <script>
        $(function () {
            $('.excluir_processo').on('click', function () {
                let tk = $(this).find('.token').val()


                Swal.fire({
                    title: "Deseja realmente excluir o processo?",
                    text: "Todos os dados serão excluídos e a ação é irreversível!",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: " #d33",
                    cancelButtonColor: "#3085d6",
                    confirmButtonText: "Sim, excluir!",
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {

                        $.ajax({
                            url: './processos.php?acao=deletar',
                            type: 'POST',
                            data: {
                                token: tk
                            },
                            dataType: 'json',
                            success: function (res) {

                                if (res.status == "success") {
                                    Swal.fire({
                                        title: "Exclusão",
                                        text: "Proceso excluído com sucesso!",
                                        icon: "success"
                                    });
                                } else {
                                    Swal.fire({
                                        icon: "error",
                                        title: "Oops...",
                                        text: res.message
                                    });
                                }



                                setTimeout(() => {
                                    Swal.close()
                                    window.location.reload()
                                }, 1000)

                            }
                        })
                    }
                });
            })
        })
    </script>

</body>

</html>