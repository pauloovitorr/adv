<?php


include_once('../../scripts.php');


// Buscando os dados da conta do usuário logado
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $sql_dados_conta = "SELECT nome, email, tell, senha, cpf, rg, oab_uf, dt_nascimento FROM usuario_config WHERE id_usuario_config = $id_user";
    $res = $conexao->query($sql_dados_conta);

    if ($res && $res->num_rows > 0) {
        $dados_modelo = $res->fetch_assoc();
    } else {
        $dados_modelo = [];
    }
};


// Atualizando os dados da conta do usuário logado
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['nome_user']) && !empty($_POST['email']) && !empty($_POST['telefone']) && !empty($_POST['senha'])) {

    $nome = $conexao->escape_string(htmlspecialchars($_POST['nome_user']));
    $email = $conexao->escape_string(htmlspecialchars($_POST['email']));
    $telefone = $conexao->escape_string(htmlspecialchars($_POST['telefone']));
    $senha = $conexao->escape_string(htmlspecialchars($_POST['senha']));
    $cpf = $conexao->escape_string(htmlspecialchars($_POST['cpf'] ?? ''));
    $rg = $conexao->escape_string(htmlspecialchars($_POST['rg'] ?? ''));
    $oab = $conexao->escape_string(htmlspecialchars($_POST['oab'] ?? ''));
    $dt_nas = $conexao->escape_string(htmlspecialchars($_POST['dt_nascimento'] ?? ''));


    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $res = [
            'status' => 'erro',
            'message' => 'Não é um e-mail válido!'
        ];

        $conexao->close();
        echo json_encode($res, JSON_UNESCAPED_UNICODE);
        exit;
    }


    // Verifico se existe data de nascimento e se é menor que a data atual
    if (!empty($dt_nas)) {
        $data_atual = date('Y-m-d');
        if ($dt_nas >= $data_atual) {
            $res = [
                'status' => 'erro',
                'mensagem' => 'Data de nascimento inválida.'
            ];
            echo json_encode($res, JSON_UNESCAPED_UNICODE);
            $conexao->close();
            exit;

        }
    }

    // Bindo os dados atualizados na query
    $sql_atualiza_dados = "UPDATE usuario_config SET nome = ?, email = ?, tell = ?, senha = ?, cpf = ?, rg = ?, oab_uf = ?, dt_nascimento = ? WHERE id_usuario_config = ?";
    $stmt = $conexao->prepare($sql_atualiza_dados);
    $stmt->bind_param('ssssssssi', $nome, $email, $telefone, $senha, $cpf, $rg, $oab, $dt_nas, $id_user);
    if ($stmt->execute()) {
        $res = [
            'status' => 'success',
            'mensagem' => 'Configurações atualizadas com sucesso.'
        ];
    } else {
        $res = [
            'status' => 'erro',
            'mensagem' => 'Erro ao atualizar as configurações.'
        ];
    }


    echo json_encode($res, JSON_UNESCAPED_UNICODE);
    $conexao->close();
    exit;


}

?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Utilizei a base do html e css do cadastro de modelos para criar a página de configuração. -->
    <link rel="stylesheet" href="../css/modelos/config_modelos.css">
    <title>ADV Conectado</title>

    <style>
        .input-error {
            border: 1px solid red !important;
        }

        .msg_validacao {
            font-size: 9px;
            color: red;
            display: none;
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
            <span class="breadcrumb-current">Configuração</span>
            <span class="breadcrumb-separator">/</span>
        </div>
    </div>
</div>




<body>
    <main class="container_principal">
        <div class="pai_conteudo">

            <section class="cadastro-modelo">

                <div class="cadastro-modelo__header">
                    <div class="infos_pagina">
                        <i class="fa-solid fa-display"></i>
                        <p>Configuração do Cadastro</p>
                    </div>

                </div>

                <hr>

                <div class="cadastro-modelo__form-wrapper">

                    <form id="atualizar_configuracoes_form">
                        <fieldset>
                            <legend>Dados da Conta</legend>

                            <div class="form-grid">
                                <div class="form-row">

                                    <div class="form-field">
                                        <label for="nome_user">Nome <span style="color: red;">*</span> </label>
                                        <input type="text" name="nome_user" id="nome_user"
                                            placeholder="EX: Andre Carlos" maxlength="50" required
                                            value="<?php echo htmlspecialchars($dados_modelo['nome'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                                        <span class="msg_validacao"></span>
                                    </div>

                                    <div class="form-field">
                                        <label for="email">E-mail <span style="color: red;">*</span></label>
                                        <input type="email" name="email" id="email" placeholder="exemplo@dominio.com"
                                            maxlength="80" required
                                            value="<?php echo htmlspecialchars($dados_modelo['email'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                                        <span class="msg_validacao"></span>
                                    </div>

                                    <div class="form-field">
                                        <label for="telefone">Telefone <span style="color: red;">*</span></label>
                                        <input type="text" name="telefone" id="telefone" placeholder="(99) 99999-9999"
                                            minlength="10" maxlength="16" required
                                            value="<?php echo htmlspecialchars($dados_modelo['tell'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                                        <span class="msg_validacao"></span>
                                    </div>

                                    <div class="form-field">
                                        <label for="senha">Senha <span style="color: red;">*</span></label>
                                        <input type="text" name="senha" id="senha" placeholder="********"
                                            maxlength="150" required
                                            value="<?php echo htmlspecialchars($dados_modelo['senha'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                                        <span class="msg_validacao"></span>
                                    </div>

                                </div>

                                <div class="form-row">

                                    <div class="form-field">
                                        <label for="cpf">CPF</label>
                                        <input type="text" name="cpf" id="cpf" placeholder="000.000.000-00"
                                            maxlength="14"
                                            value="<?php echo htmlspecialchars($dados_modelo['cpf'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                                    </div>

                                    <div class="form-field">
                                        <label for="rg">RG</label>
                                        <input type="text" name="rg" id="rg" placeholder="00.000.000-0" maxlength="20"
                                            value="<?php echo htmlspecialchars($dados_modelo['rg'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                                    </div>

                                    <div class="form-field">
                                        <label for="oab">OAB</label>
                                        <input type="text" name="oab" id="oab" placeholder="OAB/UF 00000" maxlength="20"
                                            value="<?php echo htmlspecialchars($dados_modelo['oab_uf'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                                    </div>

                                    <div class="form-field">
                                        <label for="dt_nascimento">Data de Nascimento</label>

                                        <input type="date" name="dt_nascimento" id="dt_nascimento"
                                            max="<?php echo date('Y-m-d'); ?>"
                                            value="<?php echo htmlspecialchars($dados_modelo['dt_nascimento'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                                    </div>

                                </div>



                            </div>
                        </fieldset>

                        <div class="cadastro-modelo__submit">
                            <button type="submit" class="btn_cadastrar">Salvar Configuração</button>
                        </div>

                    </form>

                </div>

            </section>


        </div>
    </main>


    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-mask-plugin@1.14.16/dist/jquery.mask.min.js"></script>
    <!-- Mascaras para os campos de telefone, cpf e data de nascimento -->
    <script>
        $(function () {
            $('#telefone').mask('(00) 00000-0000');
            $('#cpf').mask('000.000.000-00');

        });
    </script>

    <script>
        $(function () {

            $('#atualizar_configuracoes_form').on('submit', function (e) {
                e.preventDefault();

                if (!validarFormulario()) {
                    // Alerta de erro de validação
                    Swal.fire({
                        icon: 'error',
                        title: 'Erro de Validação',
                        text: 'Por favor, corrija os campos destacados em vermelho.'
                    });
                    return;
                }

                // swal de carregango
                 Swal.showLoading();

                $.ajax({
                    type: 'POST',
                    url: './configuracoes.php',
                    dataType: 'json',
                    data: $(this).serialize(),
                    success: function (res) {
                        
                        if (res.status === 'success') {
                            Swal.fire({
                                icon: 'success',
                                title: 'Sucesso',
                                text: res.mensagem
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Erro',
                                text: res.mensagem
                            });
                        }
                    },
                    error: function () {
                        alert('Erro ao enviar formulário');
                    }
                });
            });

            function validarFormulario() {
                let isValid = true;

                $('.input-error').removeClass('input-error');

                // Nome
                const nome = $('#nome_user');
                const nomeVal = nome.val().trim();
                const nameRegex = /^[a-zA-Zà-úÀ-Ú\s]+$/;

                if (!nameRegex.test(nomeVal) || nomeVal.length < 4 || nomeVal.length > 150) {
                    nome.addClass('input-error');
                    nome.closest('div').find('.msg_validacao').text('Digite um nome válido').show();
                    isValid = false;
                }

                // Email
                const email = $('#email');
                const emailVal = email.val().trim();
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

                if (!emailRegex.test(emailVal)) {
                    email.addClass('input-error');
                    email.closest('div').find('.msg_validacao').text('Digite um email válido').show();
                    isValid = false;
                }

                // Telefone
                const telefone = $('#telefone');
                const telefoneVal = telefone.val().trim();

                if (telefoneVal.length < 14) {
                    telefone.addClass('input-error');
                    telefone.closest('div').find('.msg_validacao').text('Digite um telefone válido').show();
                    isValid = false;
                }

                // Senha
                const senha = $('#senha');
                const senhaVal = senha.val();

                const regexMaiuscula = /[A-Z]/;
                const regexEspecial = /[!@#$%^&*(),.?":{}|<>]/;
                const regexNumero = /[0-9]/;

                if (
                    senhaVal.length < 8 ||
                    senhaVal.length > 16 ||
                    !regexMaiuscula.test(senhaVal) ||
                    !regexEspecial.test(senhaVal) ||
                    !regexNumero.test(senhaVal)
                ) {
                    senha.addClass('input-error');
                    senha
                        .closest('div')
                        .find('.msg_validacao')
                        .text('A senha deve conter letras maiúsculas, números e caracteres especiais.')
                        .show();

                    isValid = false;
                }

                return isValid;
            }

        });
    </script>



</body>

</html>