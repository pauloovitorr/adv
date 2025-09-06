<?php
include_once('../../scripts.php');


if ($_SERVER['REQUEST_METHOD'] == 'GET' && count($_GET) === 0) {

    $id_user = $_SESSION['cod'];
    $sql_busca_pessoas = "SELECT id_pessoa,tk,nome, tipo_parte,dt_cadastro_pessoa, telefone_principal,logradouro, bairro FROM pessoas where usuario_config_id_usuario_config = $id_user";
    $res = $conexao->query($sql_busca_pessoas);


    $sql_quantidade_pessoas = "SELECT 
    COUNT(*) AS total_pessoas,
    COUNT(CASE WHEN tipo_parte = 'cliente' THEN 1 END) AS total_clientes,
    COUNT(CASE WHEN tipo_parte = 'contrário' THEN 1 END) AS total_contrarias
FROM pessoas WHERE usuario_config_id_usuario_config = {$_SESSION['cod']} ;
";
    $res_qtd = $conexao->query($sql_quantidade_pessoas);
    if ($res_qtd->num_rows == 1) {
        $res_qtd = mysqli_fetch_assoc($res_qtd);
        $total = $res_qtd["total_pessoas"];
        $cliente = $res_qtd["total_clientes"];
        $contrario = $res_qtd["total_contrarias"];
    }
}



if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    $input = file_get_contents("php://input");
    $data = json_decode($input, true);
    $token = $data['token'] ?? null;

    if ($token) {
        $sql_delete_pessoa = 'DELETE from pessoas where tk = ?';
        $stmt = $conexao->prepare($sql_delete_pessoa);
        $stmt->bind_param('s', $token);

        if ($stmt->execute()) {
            $res = [
                'status' => 'success',
                'message' => 'Pessoa excluída com sucesso!',
            ];
            echo json_encode($res, JSON_UNESCAPED_UNICODE);
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
    <title>Pessoas</title>
</head>

<?php
include_once('../geral/menu_lat.php');
include_once('../geral/topo.php');
?>

<body>
    <main class="container_principal">
        <div class="pai_conteudo">

            <div class="infos_pagina">
                <button> <i class="fa-regular fa-user"></i> <?php echo $total <= 1 ?  "$total Pessoa Cadastrada" : "$total Pessoas Cadastradas" ?> </button>
                <button> <i class="fa-regular fa-user"></i> <?php echo $cliente <= 1 ?  "$cliente Cliente Cadastrado" : "$cliente Clientes Cadastrados" ?> </button>
                <button> <i class="fa-regular fa-user"></i> <?php echo $contrario <= 1 ?  "$contrario Contrário Cadastrado" : "$contrario Contrários Cadastrados" ?> </button>
            </div>

            <div class="opcoes_funcoes">
                <button class="btn_adicionar" id="add_pessoa"> <i class="fa-solid fa-plus"></i> Nova Pessoa </button>

                <div class="div_pai_funcoes">
                    <label for="buscar_pessoas"><i class="fa-solid fa-magnifying-glass"></i></label>
                    <input type="text" id="buscar_pessoas" name="buscar_pessoas" placeholder="Buscar">
                </div>

                <div class="div_pai_funcoes">
                    <img src="../img/funil.png" alt="">
                    <select name="" id="a">
                        <option value="">Filtrar</option>
                        <option value="aaa">Clientes</option>
                        <option value="aaa">Partes Contrárias</option>
                        <option value="aa">Com Processso em Andamento</option>
                        <option value="aaaa"> Sem Processso em Andamento </option>
                    </select>
                </div>


                <div class="div_pai_funcoes">
                    <i class="fa-solid fa-arrow-up-wide-short"></i>
                    <select name="" id="a">
                        <option value="">ordenar</option>
                        <option value="aaa">Nome da Pessoa</option>
                        <option value="aa">Mais Recentes</option>
                        <option value="aaaa">Mais Antigos</option>
                    </select>
                </div>

                <div class="div_pai_funcoes">
                    <i class="fa-solid fa-file-arrow-down"></i>
                    <select name="" id="a">
                        <option value="">Exportar</option>
                        <option value="aaa">Excel (.xlsx)</option>
                        <option value="aa">Imprimir (.pdf)</option>
                    </select>
                </div>


            </div>


            <section class="container_lista_pessoas">

                <table>

                    <thead>
                        <tr>
                            <td>Nome</td>
                            <td>Contato</td>
                            <td>Logradouro/Bairro</td>
                            <td>Data de Cadastro</td>
                            <td>Ações</td>
                        </tr>
                    </thead>

                    <tbody>

                        <?php

                        if ($res->num_rows > 0):

                            while ($pessoa = mysqli_fetch_assoc($res)):

                        ?>
                                <tr>

                                    <td colspan="5">

                                        <div class="dados_pessoa <?php echo $pessoa['tipo_parte'] == 'cliente' ? 'cliente' : 'contrario'  ?>">
                                            <div class="conteudo_pessoa container_nome">
                                                <div class="icone"><?php echo strtoupper(substr($pessoa['nome'], 0, 2)); ?></div>
                                                <div class="nome_pessoa">
                                                    <p> <?php echo $pessoa['nome'] ?> </p>
                                                    <span> <?php echo $pessoa['tipo_parte'] ?> </span>
                                                </div>
                                            </div>

                                            <div class="conteudo_pessoa container_contato">
                                                <?php
                                                if ($pessoa['telefone_principal']):

                                                    $telefone = $pessoa['telefone_principal'];

                                                    // Remove tudo que não for número
                                                    $telefoneLimpo = preg_replace('/\D/', '', $telefone);

                                                    // Adiciona o DDI do Brasil (55) se ainda não tiver
                                                    if (strpos($telefoneLimpo, '55') !== 0) {
                                                        $telefoneLimpo = '55' . $telefoneLimpo;
                                                    }
                                                ?>
                                                    <a href="https://wa.me/send?phone=<?= $telefoneLimpo ?>" target="__blak" class="whatsapp">
                                                        <img src="../../img/whatsapp.png" alt="whatsapp"> <?= $pessoa['telefone_principal'] ?>
                                                    </a>

                                                <?php
                                                else:
                                                ?>
                                                    <p style="font-size: 14px; color:rgb(94, 94, 94);">Não foi cadastrado</p>

                                                <?php
                                                endif;
                                                ?>
                                            </div>

                                            <div class="conteudo_pessoa container_cidade">

                                                <?php
                                                if ($pessoa['logradouro'] || $pessoa['bairro']):
                                                ?>
                                                    <p><?php echo $pessoa['logradouro'] . '/' . $pessoa['bairro'] ?></p>

                                                <?php
                                                else:
                                                ?>
                                                    <p style="font-size: 14px; color:rgb(94, 94, 94);">Não foi cadastrado</p>

                                                <?php
                                                endif;
                                                ?>
                                            </div>

                                            <div class="conteudo_pessoa container_dt">
                                                <p><?php echo date('d-m-Y', strtotime($pessoa['dt_cadastro_pessoa'])); ?></p>
                                            </div>


                                            <div class="conteudo_pessoa container_acao">
                                                <div class="opcoes_acao">
                                                    <i class="fa fa-ellipsis-h"></i>

                                                    <div class="opcoes_pessoa">
                                                        <ul>
                                                            <a href="">
                                                                <li><i class="fa-regular fa-file-lines"></i> Ficha</li>
                                                            </a>

                                                            <a href="javascript:void(0)">
                                                                <li><i class="fa-regular fa-id-card"></i> Documentos</li>
                                                            </a>

                                                            <a href="">
                                                                <li><i class="fa-regular fa-folder"></i> Criar Processo</li>
                                                            </a>

                                                            <a href="">
                                                                <li><i class="fa-regular fa-pen-to-square"></i> Editar</li>
                                                            </a>

                                                            <a href="javascript:void(0)" class="excluir_pessoa">
                                                                <input type="hidden" class="token" value="<?php echo $pessoa['tk'] ?>">
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
                                        <p>Nenhuma Pessoa Cadastrada</p>
                                        <img src="../../img/listagem_pessoas.png" alt="" style="max-width: 200px;">
                                    </div>
                                </td>

                            </tr>

                        <?php
                        endif;
                        ?>




                    </tbody>

                </table>

            </section>


        </div>
    </main>



    <!-- Script para exibir as opções quando os 3 prontinhos da ação são clicados -->
    <script>
        $(document).ready(function() {
            $('.opcoes_acao').on('click', function(e) {
                e.stopPropagation(); // Impede o clique no elemento de propagar para o documento

                var opcoesPessoa = $(this).find('.opcoes_pessoa');

                // Verifica se o menu está visível e alterna
                if (opcoesPessoa.is(':visible')) {
                    opcoesPessoa.hide(); // Esconde o menu
                } else {
                    opcoesPessoa.show(); // Exibe o menu
                }
            });

            $(document).on('click', function() {
                // Esconde qualquer menu aberto ao clicar fora
                $('.opcoes_pessoa').hide();
            });
        });
    </script>

    <script>
        $(document).ready(function() {
            $('#add_pessoa').click(function() {
                window.open('./cadastro_pessoa.php', '_self');
            })
        })
    </script>

    <script>
        $(function() {
            $('.excluir_pessoa').on('click', function() {
                let tk = $(this).find('.token').val()

                Swal.fire({
                    title: "Deseja realmente excluir a pessoa?",
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
                            url: './pessoas.php',
                            type: 'DELETE',
                            contentType: 'application/json',
                            data: JSON.stringify({
                                token: tk
                            }),
                            dataType: 'json',
                            success: function(res) {
                                Swal.fire({
                                    title: "Exclusão",
                                    text: "Pessoa excluída com sucesso!",
                                    icon: "success"
                                });

                                setTimeout(() => {
                                    Swal.close()
                                    window.location.reload()
                                }, 800)

                            }
                        })
                    }
                });
            })
        })
    </script>

</body>

</html>