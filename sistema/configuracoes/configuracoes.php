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
}


// var_dump($dados_modelo);    
// Retorno do var_dump desse usuário:
// array(8) { ["nome"]=> string(12) "Andre Carlos" ["email"]=> string(21) "paulov.pv51@gmail.com" ["tell"]=> string(14) "(18) 99760-791" ["senha"]=> string(9) "@Paulo123" ["cpf"]=> NULL ["rg"]=> NULL ["oab_uf"]=> NULL ["dt_nascimento"]=> NULL }
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Utilizei a base do html e css do cadastro de modelos para criar a página de configuração. -->
    <link rel="stylesheet" href="../css/modelos/config_modelos.css">
    <title>ADV Conectado</title>
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

                    <form action="" method="POST" enctype="multipart/form-data"
                        id="<?php echo !empty($dados_modelo) ? 'form-atualizacao-modelo' : 'form-configuracao-modelo'; ?>">
                        <fieldset>
                            <legend>Dados da Conta</legend>

                            <div class="form-grid">
                                <div class="form-row">

                                    <div class="form-field">
                                        <label for="nome_user">Nome <span style="color: red;">*</span></label>
                                        <input type="text" name="nome_user" id="nome_user"
                                            placeholder="EX: Andre Carlos" maxlength="50" required
                                            value="<?php echo htmlspecialchars($dados_modelo['nome'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                                    </div>

                                    <div class="form-field">
                                        <label for="email">E-mail <span style="color: red;">*</span></label>
                                        <input type="email" name="email" id="email" placeholder="exemplo@dominio.com"
                                            maxlength="80" required
                                            value="<?php echo htmlspecialchars($dados_modelo['email'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                                    </div>

                                    <div class="form-field">
                                        <label for="telefone">Telefone <span style="color: red;">*</span></label>
                                        <input type="text" name="telefone" id="telefone" placeholder="(99) 99999-9999"
                                            minlength="10" maxlength="16" required
                                            value="<?php echo htmlspecialchars($dados_modelo['tell'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                                    </div>

                                    <div class="form-field">
                                        <label for="senha">Senha <span style="color: red;">*</span></label>
                                        <input type="password" name="senha" id="senha" placeholder="********"
                                            maxlength="150" required
                                            value="<?php echo htmlspecialchars($dados_modelo['senha'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
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


    <script src="https://cdn.jsdelivr.net/npm/jquery-mask-plugin@1.14.16/dist/jquery.mask.min.js"></script>
    <script>
        $(function() {
            $('#telefone').mask('(00) 00000-0000');
            $('#cpf').mask('000.000.000-00');
        });
    </script>


</body>

</html>