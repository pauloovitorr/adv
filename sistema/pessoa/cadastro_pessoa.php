<?php

include_once('../../scripts.php');

$id_user = $_SESSION['cod'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['tipo_pessoa']) && !empty($_POST['nome']) && !empty($_POST['origem']) && !empty($_POST['tipo_pessoa']) && $_POST['acao'] == 'cadastrar') {

    $token             = bin2hex(random_bytes(64 / 2));
    $usuario           = $_SESSION['cod'];
    $nome              = $conexao->escape_string(htmlspecialchars($_POST['nome'] ?? ''));
    $origem            = $conexao->escape_string(htmlspecialchars($_POST['origem'] ?? ''));
    $foto_pessoa       = '';
    $num_doc           = $conexao->escape_string(htmlspecialchars($_POST['num_documento'] ?? ''));
    $rg                = $conexao->escape_string(htmlspecialchars($_POST['rg'] ?? ''));
    $dt_nascimento     = !empty($_POST['dt_nascimento']) ? $conexao->escape_string(htmlspecialchars($_POST['dt_nascimento'])) : null;
    $estado_civil      = $conexao->escape_string(htmlspecialchars($_POST['estado_civil'] ?? ''));
    $profissao         = $conexao->escape_string(htmlspecialchars($_POST['profissao'] ?? ''));
    $pis               = $conexao->escape_string(htmlspecialchars($_POST['pis'] ?? ''));
    $ctps              = $conexao->escape_string(htmlspecialchars($_POST['ctps'] ?? ''));
    $sexo              = $conexao->escape_string(htmlspecialchars($_POST['sexo'] ?? ''));
    $tell_principal    = $conexao->escape_string(htmlspecialchars($_POST['telefone_principal'] ?? ''));
    $tell_secundario   = $conexao->escape_string(htmlspecialchars($_POST['telefone_secundario'] ?? ''));
    $celular           = $conexao->escape_string(htmlspecialchars($_POST['celular'] ?? ''));
    $email             = $conexao->escape_string(htmlspecialchars($_POST['email'] ?? ''));
    $email_secundario  = $conexao->escape_string(htmlspecialchars($_POST['email_secundario'] ?? ''));
    $cep               = $conexao->escape_string(htmlspecialchars($_POST['cep'] ?? ''));
    $estado            = $conexao->escape_string(htmlspecialchars($_POST['estado'] ?? ''));
    $cidade            = $conexao->escape_string(htmlspecialchars($_POST['cidade'] ?? ''));
    $bairro            = $conexao->escape_string(htmlspecialchars($_POST['bairro'] ?? ''));
    $logradouro        = $conexao->escape_string(htmlspecialchars($_POST['logradouro'] ?? ''));
    $num               = !empty($_POST['numero_casa']) ? $conexao->escape_string(htmlspecialchars($_POST['numero_casa'])) : null;
    $complemento       = $conexao->escape_string(htmlspecialchars($_POST['complemento'] ?? ''));
    $observacao        = $conexao->escape_string(htmlspecialchars($_POST['observacao'] ?? ''));
    $nome_mae          = $conexao->escape_string(htmlspecialchars($_POST['nome_mae'] ?? ''));
    $tipo_pessoa       = $conexao->escape_string(htmlspecialchars($_POST['tipo_pessoa'] ?? ''));
    $tipo_parte        = $conexao->escape_string(htmlspecialchars($_POST['tipo_parte'] ?? ''));


    try {

        $conexao->begin_transaction();

        $foto = isset($_FILES['foto']) ? $_FILES['foto'] : '';

        if ($foto['name']) {
            $nomeArquivo = $foto['name'];
            $tmpArquivo = $foto['tmp_name'];
            $tamanhoArquivo = $foto['size'];

            $extensao_arquivo = strtolower(pathinfo($nomeArquivo, PATHINFO_EXTENSION));

            $novo_nome_arquivo = uniqid() . uniqid() . '.' . $extensao_arquivo;

            if ($tamanhoArquivo > 3 * 1024 * 1024) {

                $res = [
                    'status' => 'erro',
                    'message' => 'Arquivo muito grande! Tamanho máximo permitido de 2MB'
                ];

                echo json_encode($res, JSON_UNESCAPED_UNICODE);
                $conexao->rollback();
                $conexao->close();

                exit;
            } elseif ($foto['error'] !== 0) {
                $res = [
                    'status' => 'erro',
                    'message' => 'Imagem com erro'
                ];

                echo json_encode($res, JSON_UNESCAPED_UNICODE);
                $conexao->rollback();
                $conexao->close();

                exit;
            } else {
                $caminho = '../../img/img_clientes';

                $novo_caminho = $caminho . '/' . $novo_nome_arquivo;

                $retorno_img_movida =   move_uploaded_file($tmpArquivo, $novo_caminho);

                if ($retorno_img_movida) {
                    $foto_pessoa = '/img/img_clientes/' . $novo_nome_arquivo;
                }
            }
        }


        $sql = 'INSERT INTO pessoas (
        tk, nome, origem, dt_cadastro_pessoa, dt_atualizacao_pessoa, foto_pessoa, num_documento, rg, dt_nascimento, 
        estado_civil, profissao, pis, ctps, sexo, telefone_principal, telefone_secundario, celular, email, 
        email_secundario, cep, estado, cidade, bairro, logradouro, numero_casa, complemento, observacao, 
        nome_mae, tipo_pessoa, tipo_parte ,usuario_config_id_usuario_config
    ) VALUES (
        ?, ?, ?, NOW(), NOW(), ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?
    )';

        $stmt = $conexao->prepare($sql);
        $stmt->bind_param('ssssssssssssssssssssssisssssi', $token, $nome, $origem, $foto_pessoa, $num_doc, $rg, $dt_nascimento, $estado_civil, $profissao, $pis, $ctps, $sexo, $tell_principal, $tell_secundario, $celular, $email, $email_secundario, $cep, $estado, $cidade, $bairro, $logradouro, $num, $complemento, $observacao, $nome_mae, $tipo_pessoa, $tipo_parte, $usuario);

        if ($stmt->execute()) {

            $ip = $_SERVER['REMOTE_ADDR'];
            $id_user = $_SESSION['cod'];

            if (cadastro_log('Cadastrou Pessoa', $nome, $ip, $id_user)) {

                $conexao->commit();
                $conexao->close();

                $res = [
                    'status' => 'success',
                    'message' => 'Pessoa cadastrada com sucesso!',
                    'token' => $token
                ];
                echo json_encode($res, JSON_UNESCAPED_UNICODE);
                exit;
            } else {
                $conexao->rollback();
                $conexao->close();

                $res = [
                    'status' => 'erro',
                    'message' => 'Erro ao cadastrar pessoa!'
                ];
                echo json_encode($res, JSON_UNESCAPED_UNICODE);
            }
        }
    } catch (Exception $err) {
        echo "Erro: " . $err->getMessage();
        $conexao->rollback();
        $conexao->close();
    }
}


if ($_SERVER['REQUEST_METHOD'] === 'GET' && !empty($_GET['acao']) && !empty($_GET['tkn'])) {

    $token_pessoa  = $conexao->escape_string(htmlspecialchars($_GET['tkn']));

    $sql_busca_pessoa_tkn = 'SELECT * FROM pessoas where tk = ? and usuario_config_id_usuario_config = ?';
    $stmt = $conexao->prepare($sql_busca_pessoa_tkn);
    $stmt->bind_param('si', $token_pessoa, $id_user);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $dados_pessoa = $result->fetch_assoc();
        // var_dump($dados_pessoa);
        $conexao->close();
    } else {
        header('location: ./pessoas.php');
        exit;
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $dados_pessoa = [];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['tipo_pessoa']) && !empty($_POST['nome']) && !empty($_POST['origem']) && !empty($_POST['tipo_parte']) && !empty($_POST['tkn']) && $_POST['acao'] == 'editar') {

    // var_dump($_POST['numero_casa']);
    // die();

    $token             = $conexao->escape_string(htmlspecialchars($_POST['tkn'] ?? ''));
    $nome              = $conexao->escape_string(htmlspecialchars($_POST['nome'] ?? ''));
    $origem            = $conexao->escape_string(htmlspecialchars($_POST['origem'] ?? ''));
    $foto_pessoa       = '';
    $num_doc           = $conexao->escape_string(htmlspecialchars($_POST['num_documento'] ?? ''));
    $rg                = $conexao->escape_string(htmlspecialchars($_POST['rg'] ?? ''));
    $dt_nascimento     = !empty($_POST['dt_nascimento']) ? $conexao->escape_string(htmlspecialchars($_POST['dt_nascimento'])) : null;
    $estado_civil      = $conexao->escape_string(htmlspecialchars($_POST['estado_civil'] ?? ''));
    $profissao         = $conexao->escape_string(htmlspecialchars($_POST['profissao'] ?? ''));
    $pis               = $conexao->escape_string(htmlspecialchars($_POST['pis'] ?? ''));
    $ctps              = $conexao->escape_string(htmlspecialchars($_POST['ctps'] ?? ''));
    $sexo              = $conexao->escape_string(htmlspecialchars($_POST['sexo'] ?? ''));
    $tell_principal    = $conexao->escape_string(htmlspecialchars($_POST['telefone_principal'] ?? ''));
    $tell_secundario   = $conexao->escape_string(htmlspecialchars($_POST['telefone_secundario'] ?? ''));
    $celular           = $conexao->escape_string(htmlspecialchars($_POST['celular'] ?? ''));
    $email             = $conexao->escape_string(htmlspecialchars($_POST['email'] ?? ''));
    $email_secundario  = $conexao->escape_string(htmlspecialchars($_POST['email_secundario'] ?? ''));
    $cep               = $conexao->escape_string(htmlspecialchars($_POST['cep'] ?? ''));
    $estado            = $conexao->escape_string(htmlspecialchars($_POST['estado'] ?? ''));
    $cidade            = $conexao->escape_string(htmlspecialchars($_POST['cidade'] ?? ''));
    $bairro            = $conexao->escape_string(htmlspecialchars($_POST['bairro'] ?? ''));
    $logradouro        = $conexao->escape_string(htmlspecialchars($_POST['logradouro'] ?? ''));
    $num               = !empty($_POST['numero_casa']) ? $conexao->escape_string(htmlspecialchars($_POST['numero_casa'])) : null;
    $complemento       = $conexao->escape_string(htmlspecialchars($_POST['complemento'] ?? ''));
    $observacao        = $conexao->escape_string(htmlspecialchars($_POST['observacao'] ?? ''));
    $nome_mae          = $conexao->escape_string(htmlspecialchars($_POST['nome_mae'] ?? ''));
    $tipo_pessoa       = $conexao->escape_string(htmlspecialchars($_POST['tipo_pessoa'] ?? ''));
    $tipo_parte        = $conexao->escape_string(htmlspecialchars($_POST['tipo_parte'] ?? ''));
    $excluir_foto      = $conexao->escape_string(htmlspecialchars($_POST['excluir_foto'] ?? ''));

    $excluir_foto      = $excluir_foto != '' ? '../..' . $excluir_foto : '';

    try {

        $conexao->begin_transaction();

        $verifica_img = 'SELECT foto_pessoa FROM pessoas WHERE tk = ? AND usuario_config_id_usuario_config = ?';
        $stmt_img     = $conexao->prepare($verifica_img);
        $stmt_img->bind_param('si', $token, $id_user);

        $stmt_img->execute();
        $caminho_img = $stmt_img->get_result();
        $caminho_img = $caminho_img->fetch_assoc();

        // var_dump($caminho_img);
        // exit;


        $foto = $_FILES['foto'] ?? null;
        $caminho_base = '../../img/img_clientes';
        $tamanho_maximo = 3 * 1024 * 1024; // 3MB

        function validarFoto($foto, $tamanho_maximo)
        {
            if ($foto['error'] !== 0) {
                return ['status' => false, 'mensagem' => 'Imagem com erro'];
            }

            if ($foto['size'] > $tamanho_maximo) {
                return ['status' => false, 'mensagem' => 'Arquivo muito grande! Tamanho máximo permitido de 3MB'];
            }

            return ['status' => true];
        }

        function moverFoto($foto, $caminho_base)
        {
            $extensao = strtolower(pathinfo($foto['name'], PATHINFO_EXTENSION));
            $novo_nome = uniqid() . uniqid() . '.' . $extensao;
            $novo_caminho = $caminho_base . '/' . $novo_nome;

            if (move_uploaded_file($foto['tmp_name'], $novo_caminho)) {
                return '/img/img_clientes/' . $novo_nome;
            }
            return false;
        }

        // Lógica principal
        if ($foto && $foto['name']) {

            // Caso haja foto antiga
            if (!empty($caminho_img['foto_pessoa']) && $excluir_foto) {
                if (file_exists($excluir_foto)) {
                    unlink($excluir_foto);
                }
            } elseif (!empty($caminho_img['foto_pessoa']) && !$excluir_foto) {
                $conexao->rollback();
                $conexao->close();
                echo json_encode([
                    'status' => 'erro',
                    'message' => 'Marque a opção de excluir para cadastrar uma nova foto!'
                ], JSON_UNESCAPED_UNICODE);
                exit;
            }

            // Validação
            $validacao = validarFoto($foto, $tamanho_maximo);
            if (!$validacao['status']) {
                $conexao->rollback();
                $conexao->close();
                echo json_encode([
                    'status' => 'erro',
                    'message' => $validacao['mensagem']
                ], JSON_UNESCAPED_UNICODE);
                exit;
            }

            // Movendo a foto
            $nova_foto = moverFoto($foto, $caminho_base);
            if ($nova_foto) {
                $foto_pessoa = $nova_foto;
            }
        }

        // Caso apenas queira excluir a foto atual sem enviar nova
        if (!empty($caminho_img['foto_pessoa']) && $excluir_foto) {
            if (file_exists($excluir_foto)) {
                unlink($excluir_foto);
                $foto_pessoa = '';
            }
        }



        $numero_casa_sql = !empty($num) ? intval($num) : 'NULL';


        $sql = "UPDATE pessoas SET
                nome = ?,
                origem = ?,
                dt_atualizacao_pessoa = NOW(),
                foto_pessoa = ?,
                num_documento = ?,
                rg = ?,
                dt_nascimento = ?,
                estado_civil = ?,
                profissao = ?,
                pis = ?,
                ctps = ?,
                sexo = ?,
                telefone_principal = ?,
                telefone_secundario = ?,
                celular = ?,
                email = ?,
                email_secundario = ?,
                cep = ?,
                estado = ?,
                cidade = ?,
                bairro = ?,
                logradouro = ?,
                numero_casa = $numero_casa_sql,
                complemento = ?,
                observacao = ?,
                nome_mae = ?,
                tipo_pessoa = ?,
                tipo_parte = ?
            WHERE tk = ? AND usuario_config_id_usuario_config = ?";

        $stmt = $conexao->prepare($sql);
        $stmt->bind_param(
            "sssssssssssssssssssssssssssi",
            $nome,
            $origem,
            $foto_pessoa,
            $num_doc,
            $rg,
            $dt_nascimento,
            $estado_civil,
            $profissao,
            $pis,
            $ctps,
            $sexo,
            $tell_principal,
            $tell_secundario,
            $celular,
            $email,
            $email_secundario,
            $cep,
            $estado,
            $cidade,
            $bairro,
            $logradouro,
            $complemento,
            $observacao,
            $nome_mae,
            $tipo_pessoa,
            $tipo_parte,
            $token,
            $id_user
        );

        if ($stmt->execute()) {

            $ip = $_SERVER['REMOTE_ADDR'];
            $id_user = $_SESSION['cod'];

            if (cadastro_log('Editou Pessoa', $nome, $ip, $id_user)) {

                $conexao->commit();
                $conexao->close();

                $res = [
                    'status' => 'success',
                    'message' => 'Pessoa editada com sucesso!'
                ];
                echo json_encode($res, JSON_UNESCAPED_UNICODE);
                exit;
            } else {
                $conexao->rollback();
                $conexao->close();

                $res = [
                    'status' => 'erro',
                    'message' => 'Erro ao editar pessoa!'
                ];
                echo json_encode($res, JSON_UNESCAPED_UNICODE);
                exit;
            }
        }
    } catch (Exception $err) {
        echo "Erro: " . $err->getMessage();
        $conexao->rollback();
        $conexao->close();
    }
}

?>


<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/pessoas/cadastro_pessoa.css">
    <title>ADV Conectado</title>
</head>

<?php
include_once('../geral/menu_lat.php');
include_once('../geral/topo.php');
?>
<div class="container_breadcrumb">
    <div class="pai_topo">
        <div class="breadcrumb">
            <a href="./pessoas.php" class="breadcrumb-link">Pessoas</a>
            <span class="breadcrumb-separator">/</span>
            <span class="breadcrumb-current"><?php echo ($_GET['acao'] ?? '') ? 'Edição Cadastro' : 'Cadastro' ?></span>
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
                    <div class="descricao color_selecionado"><?php echo count($dados_pessoa) > 0 ? 'Edição' : 'Cadastro'  ?></div>
                </div>



                <?php if (count($dados_pessoa) > 0): ?>
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
                    <div class="num"><?php echo count($dados_pessoa) > 0 ? '2°' : '3°' ?></div>
                    <div class="descricao">Finalização</div>
                </div>

            </section>

            <section class="container_cadastro">
                <div class="topo_sessao">
                    <i class="fa-solid fa-user-plus"></i>
                    <p><?php echo ($_GET['acao'] ?? '') ? 'Edição Pessoa' : 'Nova Pessoa' ?></p>
                </div>

                <hr>

                <div class="container_field_form">
                    <form action="" method="POST" enctype="multipart/form-data" id="<?php echo ($_GET['acao'] ?? '') ? 'editar' : 'cadastrar' ?>">
                        <fieldset>
                            <legend>Dados Pessoais</legend>

                            <div class="bloco-formulario">

                                <div class="container_inputs">
                                    <div class="container_input">
                                        <label for="pessoa">Pessoa <span style="color: red;">*</span></label>
                                        <select name="tipo_pessoa" id="pessoa" required>
                                            <option value="PF" <?php echo ($dados_pessoa["tipo_pessoa"] ?? '') == 'PF' ? 'selected' : ''  ?>>Pessoa Física</option>
                                            <option value="PJ" <?php echo ($dados_pessoa["tipo_pessoa"] ?? '') == 'PJ' ? 'selected' : ''  ?>>Pessoa Jurídica</option>
                                        </select>
                                    </div>

                                    <div class="container_input" id="nome">
                                        <label for="nome_pessoa">Nome <span style="color: red;">*</span></label>
                                        <input
                                            type="text"
                                            name="nome"
                                            id="nome_pessoa"
                                            value="<?php echo htmlspecialchars($dados_pessoa['nome'] ?? '') ?>"
                                            placeholder="EX: Paulo Vitor"
                                            minlength="4"
                                            maxlength="150"
                                            required>
                                    </div>

                                    <div class="container_input">
                                        <label for="num_doc">CPF/CNPJ</label>
                                        <input
                                            type="text"
                                            name="num_documento"
                                            id="num_doc"
                                            value="<?php echo htmlspecialchars($dados_pessoa['num_documento'] ?? '') ?>"
                                            minlength="11"
                                            maxlength="20"
                                            placeholder="999.999.999-99">
                                    </div>

                                    <div class="container_input" id="container_rg">
                                        <label for="rg">RG</label>
                                        <input
                                            type="text"
                                            name="rg"
                                            id="rg"
                                            value="<?php echo htmlspecialchars($dados_pessoa['rg'] ?? '') ?>"
                                            placeholder="Número do RG"
                                            minlength="5"
                                            maxlength="25">
                                    </div>
                                </div>

                                <div class="container_inputs">
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
                                </div>

                                <div class="container_inputs">
                                    <div class="container_input" id="container_sexo">
                                        <label for="sexo">Sexo</label>
                                        <select name="sexo" id="sexo">
                                            <option value="">Selecione o sexo</option>
                                            <option value="Masculino" <?php echo ($dados_pessoa['sexo'] ?? '') == 'Masculino' ? 'selected' : '' ?>>Masculino</option>
                                            <option value="Feminino" <?php echo ($dados_pessoa['sexo'] ?? '') == 'Feminino' ? 'selected' : '' ?>>Feminino</option>
                                        </select>
                                    </div>

                                    <div class="container_input" id="container_estado_civil">
                                        <label for="estado_civil">Estado civil</label>
                                        <select name="estado_civil" id="estado_civil">
                                            <option value="">Selecione o estado civil</option>
                                            <option value="Casado(a)" <?php echo ($dados_pessoa['estado_civil'] ?? '') == 'Casado(a)' ? 'selected' : '' ?>>Casado(a)</option>
                                            <option value="Divorciado(a)" <?php echo ($dados_pessoa['estado_civil'] ?? '') == 'Divorciado(a)' ? 'selected' : '' ?>>Divorciado(a)</option>
                                            <option value="Separado(a)" <?php echo ($dados_pessoa['estado_civil'] ?? '') == 'Separado(a)' ? 'selected' : '' ?>>Separado(a)</option>
                                            <option value="Solteiro(a)" <?php echo ($dados_pessoa['estado_civil'] ?? '') == 'Solteiro(a)' ? 'selected' : '' ?>>Solteiro(a)</option>
                                            <option value="União Estável" <?php echo ($dados_pessoa['estado_civil'] ?? '') == 'União Estável' ? 'selected' : '' ?>>União Estável</option>
                                        </select>
                                    </div>

                                    <div class="container_input" id="container_nome_mae">
                                        <label for="nome_mae">Nome da mãe</label>
                                        <input
                                            type="text"
                                            name="nome_mae"
                                            id="nome_mae"
                                            value="<?php echo htmlspecialchars($dados_pessoa['nome_mae'] ?? '') ?>"
                                            minlength="4"
                                            maxlength="150"
                                            placeholder="EX: Eliete de Sousa">
                                    </div>

                                    <div class="container_input">
                                        <label for="foto">Foto</label>
                                        <input
                                            type="file"
                                            name="foto"
                                            id="foto"
                                            accept=".jpg,.jpeg,.png"
                                            class="custom-file-input">
                                        <div class="custo_add_arquivo" onclick="document.getElementById('foto').click()">
                                            <p id="nome-arquivo"> Selecione o arquivo </p>
                                            <i class="fa-solid fa-arrow-up-from-bracket"></i>
                                        </div>
                                    </div>
                                </div>

                                <div class="container_tipo_parte">
                                    <div class="container_tipo_parte_inputs">
                                        <div>
                                            <label for="tipo_parte_cliente">Cliente</label>
                                            <input
                                                type="radio"
                                                id="tipo_parte_cliente"
                                                name="tipo_parte"
                                                value="cliente"
                                                <?php echo (!isset($dados_pessoa['tipo_parte']) || $dados_pessoa['tipo_parte'] === 'cliente') ? 'checked' : '' ?>>
                                        </div>


                                        <div>
                                            <label for="tipo_parte_contrario">Contrário</label>
                                            <input
                                                type="radio"
                                                id="tipo_parte_contrario"
                                                name="tipo_parte"
                                                value="contrário"
                                                <?php echo ($dados_pessoa['tipo_parte'] ?? '') == 'contrário' ? 'checked' : '' ?>>
                                        </div>
                                    </div>

                                    <?php if (!empty($dados_pessoa["foto_pessoa"])): ?>
                                        <div class="exclusao_foto">
                                            <label for="excluir_foto">Marque para excluir a foto</label>
                                            <input type="checkbox"
                                                name="excluir_foto" id="excluir_foto"
                                                value="<?php echo $dados_pessoa['foto_pessoa'] ?>">
                                            <img src="../..<?php echo $dados_pessoa['foto_pessoa'] ?>" alt="" srcset="">
                                        </div>
                                    <?php endif; ?>


                                </div>

                            </div>

                        </fieldset>

                        <fieldset>
                            <legend>Contato</legend>

                            <div class="bloco-formulario">

                                <div class="container_inputs">
                                    <div class="container_input">
                                        <label for="telefone_principal">Telefone principal</label>
                                        <input
                                            type="tel"
                                            name="telefone_principal"
                                            id="telefone_principal"
                                            value="<?php echo htmlspecialchars($dados_pessoa['telefone_principal'] ?? '') ?>"
                                            minlength="13"
                                            maxlength="14"
                                            placeholder="(99) 99999-9999">
                                    </div>

                                    <div class="container_input">
                                        <label for="telefone_secundario">Telefone secundário</label>
                                        <input
                                            type="tel"
                                            name="telefone_secundario"
                                            id="telefone_secundario"
                                            value="<?php echo htmlspecialchars($dados_pessoa['telefone_secundario'] ?? '') ?>"
                                            minlength="13"
                                            maxlength="14"
                                            placeholder="(99) 99999-9999">
                                    </div>

                                    <div class="container_input">
                                        <label for="celular">Telefone Fixo</label>
                                        <input
                                            type="tel"
                                            name="celular"
                                            id="celular"
                                            value="<?php echo htmlspecialchars($dados_pessoa['celular'] ?? '') ?>"
                                            minlength="13"
                                            maxlength="14"
                                            placeholder="(99) 9999-9999">
                                    </div>

                                    <div class="container_input">
                                        <label for="email">E-mail principal</label>
                                        <input
                                            type="email"
                                            name="email"
                                            id="email"
                                            value="<?php echo htmlspecialchars($dados_pessoa['email'] ?? '') ?>"
                                            minlength="7"
                                            maxlength="100"
                                            placeholder="Ex: paulo@gmail.com">
                                    </div>

                                    <div class="container_input">
                                        <label for="email_secundario">E-mail secundário</label>
                                        <input
                                            type="email"
                                            name="email_secundario"
                                            id="email_secundario"
                                            value="<?php echo htmlspecialchars($dados_pessoa['email_secundario'] ?? '') ?>"
                                            minlength="7"
                                            maxlength="100"
                                            placeholder="Ex: paulo@gmail.com">
                                    </div>
                                </div>

                            </div>

                        </fieldset>

                        <fieldset>
                            <legend>Endereço</legend>

                            <div class="bloco-formulario">

                                <div class="container_inputs">
                                    <div class="container_input">
                                        <label for="cep">CEP</label>
                                        <input
                                            type="text"
                                            name="cep"
                                            id="cep"
                                            value="<?php echo htmlspecialchars($dados_pessoa['cep'] ?? '') ?>"
                                            minlength="8"
                                            maxlength="9"
                                            placeholder="99999-999">
                                    </div>

                                    <div class="container_input" id="logradouro_container">
                                        <label for="logradouro">Logradouro</label>
                                        <input
                                            type="text"
                                            name="logradouro"
                                            id="logradouro"
                                            value="<?php echo htmlspecialchars($dados_pessoa['logradouro'] ?? '') ?>"
                                            minlength="4"
                                            maxlength="150"
                                            placeholder="EX: Rua João Goulart">
                                    </div>

                                    <div class="container_input">
                                        <label for="numero_casa">Número</label>
                                        <input
                                            type="text"
                                            name="numero_casa"
                                            id="numero_casa"
                                            value="<?php echo htmlspecialchars($dados_pessoa['numero_casa'] ?? '') ?>"
                                            minlength="1"
                                            maxlength="6"
                                            placeholder="99">
                                    </div>

                                    <div class="container_input">
                                        <label for="bairro">Bairro</label>
                                        <input
                                            type="text"
                                            name="bairro"
                                            id="bairro"
                                            value="<?php echo htmlspecialchars($dados_pessoa['bairro'] ?? '') ?>"
                                            minlength="3"
                                            maxlength="100"
                                            placeholder="Ex: Centro">
                                    </div>
                                </div>

                            </div>

                            <div class="bloco-formulario">

                                <div class="container_inputs">
                                    <div class="container_input">
                                        <label for="cidade">Cidade</label>
                                        <input
                                            type="text"
                                            name="cidade"
                                            id="cidade"
                                            value="<?php echo htmlspecialchars($dados_pessoa['cidade'] ?? '') ?>"
                                            minlength="3"
                                            maxlength="150"
                                            placeholder="Ex: São Paulo">
                                    </div>

                                    <div class="container_input">
                                        <label for="estado">Estado</label>
                                        <select id="estado" name="estado">
                                            <option value="">Selecione um estado</option>
                                            <option value="AC" <?php echo ($dados_pessoa['estado'] ?? '') == 'AC' ? 'selected' : '' ?>>Acre</option>
                                            <option value="AL" <?php echo ($dados_pessoa['estado'] ?? '') == 'AL' ? 'selected' : '' ?>>Alagoas</option>
                                            <option value="AM" <?php echo ($dados_pessoa['estado'] ?? '') == 'AM' ? 'selected' : '' ?>>Amazonas</option>
                                            <option value="AP" <?php echo ($dados_pessoa['estado'] ?? '') == 'AP' ? 'selected' : '' ?>>Amapá</option>
                                            <option value="BA" <?php echo ($dados_pessoa['estado'] ?? '') == 'BA' ? 'selected' : '' ?>>Bahia</option>
                                            <option value="CE" <?php echo ($dados_pessoa['estado'] ?? '') == 'CE' ? 'selected' : '' ?>>Ceará</option>
                                            <option value="DF" <?php echo ($dados_pessoa['estado'] ?? '') == 'DF' ? 'selected' : '' ?>>Distrito Federal</option>
                                            <option value="ES" <?php echo ($dados_pessoa['estado'] ?? '') == 'ES' ? 'selected' : '' ?>>Espírito Santo</option>
                                            <option value="GO" <?php echo ($dados_pessoa['estado'] ?? '') == 'GO' ? 'selected' : '' ?>>Goiás</option>
                                            <option value="MA" <?php echo ($dados_pessoa['estado'] ?? '') == 'MA' ? 'selected' : '' ?>>Maranhão</option>
                                            <option value="MG" <?php echo ($dados_pessoa['estado'] ?? '') == 'MG' ? 'selected' : '' ?>>Minas Gerais</option>
                                            <option value="MS" <?php echo ($dados_pessoa['estado'] ?? '') == 'MS' ? 'selected' : '' ?>>Mato Grosso do Sul</option>
                                            <option value="MT" <?php echo ($dados_pessoa['estado'] ?? '') == 'MT' ? 'selected' : '' ?>>Mato Grosso</option>
                                            <option value="PA" <?php echo ($dados_pessoa['estado'] ?? '') == 'PA' ? 'selected' : '' ?>>Pará</option>
                                            <option value="PB" <?php echo ($dados_pessoa['estado'] ?? '') == 'PB' ? 'selected' : '' ?>>Paraíba</option>
                                            <option value="PE" <?php echo ($dados_pessoa['estado'] ?? '') == 'PE' ? 'selected' : '' ?>>Pernambuco</option>
                                            <option value="PI" <?php echo ($dados_pessoa['estado'] ?? '') == 'PI' ? 'selected' : '' ?>>Piauí</option>
                                            <option value="PR" <?php echo ($dados_pessoa['estado'] ?? '') == 'PR' ? 'selected' : '' ?>>Paraná</option>
                                            <option value="RJ" <?php echo ($dados_pessoa['estado'] ?? '') == 'RJ' ? 'selected' : '' ?>>Rio de Janeiro</option>
                                            <option value="RN" <?php echo ($dados_pessoa['estado'] ?? '') == 'RN' ? 'selected' : '' ?>>Rio Grande do Norte</option>
                                            <option value="RO" <?php echo ($dados_pessoa['estado'] ?? '') == 'RO' ? 'selected' : '' ?>>Rondônia</option>
                                            <option value="RR" <?php echo ($dados_pessoa['estado'] ?? '') == 'RR' ? 'selected' : '' ?>>Roraima</option>
                                            <option value="RS" <?php echo ($dados_pessoa['estado'] ?? '') == 'RS' ? 'selected' : '' ?>>Rio Grande do Sul</option>
                                            <option value="SC" <?php echo ($dados_pessoa['estado'] ?? '') == 'SC' ? 'selected' : '' ?>>Santa Catarina</option>
                                            <option value="SE" <?php echo ($dados_pessoa['estado'] ?? '') == 'SE' ? 'selected' : '' ?>>Sergipe</option>
                                            <option value="SP" <?php echo ($dados_pessoa['estado'] ?? '') == 'SP' ? 'selected' : '' ?>>São Paulo</option>
                                            <option value="TO" <?php echo ($dados_pessoa['estado'] ?? '') == 'TO' ? 'selected' : '' ?>>Tocantins</option>
                                        </select>
                                    </div>

                                    <div class="container_input">
                                        <label for="complemento">Complemento</label>
                                        <input
                                            type="text"
                                            name="complemento"
                                            id="complemento"
                                            value="<?php echo htmlspecialchars($dados_pessoa['complemento'] ?? '') ?>"
                                            minlength="3"
                                            maxlength="150"
                                            placeholder="Ex: Próximo ao mercado">
                                    </div>

                                    <div class="container_input" id="observacao_container">
                                        <label for="observacao">Observação</label>
                                        <input
                                            type="text"
                                            name="observacao"
                                            id="observacao"
                                            value="<?php echo htmlspecialchars($dados_pessoa['observacao'] ?? '') ?>"
                                            minlength="3"
                                            maxlength="150"
                                            placeholder="EX: Visitas apenas pela manhã">
                                    </div>

                                    <input type="hidden" name="acao" value="<?php echo ($_GET['acao'] ?? '') ? 'editar' : 'cadastrar' ?>">

                                    <input type="hidden" name="tkn" value="<?php echo ($_GET['acao'] ?? '') ? $_GET['tkn'] : '' ?>">

                                </div>

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
    <script>
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

            var CpfCnpjMaskBehavior = function(val) {
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
    </script>


    <!-- Js para quando for PJ -->
    <script>
        $(document).ready(function() {

            $('#pessoa').on('change', function() {
                // PJ
                if (this.value === 'PJ') {
                    $('#container_rg').hide()
                    $('#container_dt_nascimento').hide()
                    $('#container_ctps').hide()
                    $('#container_pis').hide()
                    $('#container_sexo').hide()
                    $('#container_estado_civil').hide()
                    $('#container_nome_mae').hide()
                } else {
                    $('#container_rg').show()
                    $('#container_dt_nascimento').show()
                    $('#container_ctps').show()
                    $('#container_pis').show()
                    $('#container_sexo').show()
                    $('#container_estado_civil').show()
                    $('#container_nome_mae').show()
                }


            })

        })
    </script>



    <!-- Ajax para cadastro de pessoa -->
    <script>
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
    </script>

    <!-- Ajax para atualização de pessoa -->
    <script>
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
    </script>

    <!-- Busca do cep -->
    <script>
        $(document).ready(function() {
            let cep = document.querySelector('#cep')
            let pai = document.querySelector('.pai')

            cep.addEventListener('blur', function() {
                let pesquisar = cep.value

                if (pesquisar !== '') {

                    if (pesquisar.length === 9) {
                        fetch(`https://viacep.com.br/ws/${pesquisar}/json/`)

                            .then((res) => {
                                return res.json()
                            })

                            .then((dados) => {


                                if (dados.hasOwnProperty('erro')) {
                                    Swal.fire({
                                        icon: "error",
                                        title: "CEP Não Encontrado",
                                        text: "Adicione o CEP novamente!",

                                    });
                                } else {

                                    $('#logradouro').val(dados.logradouro)
                                    $('#bairro').val(dados.bairro)
                                    $('#cidade').val(dados.localidade)
                                    $('#estado').val(dados.uf)
                                    $('#complemento').val(dados.complemento)
                                }


                            })

                            .catch((e) => {
                                console.log('Deu erro: ' + e, message)
                            })
                    } else {
                        Swal.fire({
                            icon: "error",
                            title: "CEP Inválido",
                            text: "Digite o CEP Completo",

                        });
                    }
                }

            })
        })
    </script>



</body>

</html>