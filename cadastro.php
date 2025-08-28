<?php


include_once('./scripts.php');


if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['nome'])  && !empty($_POST['email']) && !empty($_POST['tell']) && !empty($_POST['senha']) && !empty($_POST['confirmasenha'])) {

    $nome   = $conexao->escape_string(htmlspecialchars($_POST['nome']));
    $email  = $conexao->escape_string(htmlspecialchars($_POST['email']));
    $tell   = $conexao->escape_string(htmlspecialchars($_POST['tell']));
    $senha  = $conexao->escape_string(htmlspecialchars($_POST['senha']));
    $confirma_senha  = $conexao->escape_string(htmlspecialchars($_POST['confirmasenha']));
    $ip = $_SERVER['REMOTE_ADDR'];
    $token = bin2hex(random_bytes(64 / 2));



    if ($senha !== $confirma_senha) {
        $res = [
            'status' => 'erro',
            'message' => 'As senhas não coincidem!'
        ];

        $conexao->close();
        echo json_encode($res, JSON_UNESCAPED_UNICODE);
        exit;
    }

    try{
        $conexao->begin_transaction();

        $sql_verifica_email = "SELECT email FROM usuario_config WHERE email = ?";
    
        $sql_verificacao = $conexao->prepare($sql_verifica_email);
        $sql_verificacao->bind_param('s', $email);
        $sql_verificacao->execute();
        $verificacao = $sql_verificacao->get_result();
    
        
    
        if ($verificacao->num_rows < 1) {

            $sql_verifica_ip = "SELECT COUNT('ip_log') AS qtd_cadastro_recente from log where ip_log = '$ip' AND acao_log = 'Criar Conta' AND dt_acao_log >= NOW() - INTERVAL 10 MINUTE";

            $result_verificacao = $conexao->query($sql_verifica_ip);
            $result_ip = $result_verificacao->fetch_assoc();   
            
            $qtd_ip = $result_ip['qtd_cadastro_recente'];

            if($qtd_ip >= 3){
                $conexao->rollback();
    
                $res = [
                    'status' => 'erro',
                    'message' => 'Aguarde! Você tentou criar muitas contas'
                ];
    
                echo json_encode($res, JSON_UNESCAPED_UNICODE);
                exit;
            }
    
            $sql_insert_usuario = 'INSERT INTO usuario_config (tk,nome, email,tell, senha, dt_cadastro_usuario, dt_atualizacao_usuario ) VALUES (?,?,?,?,?, NOW(), NOW()) ';
    
            $stmt = $conexao->prepare($sql_insert_usuario);
            $stmt->bind_param('sssss', $token, $nome, $email, $tell, $senha,);
    
            if ($stmt->execute()) {

                $id_ultimo_cadastro = $conexao->insert_id;

                $sql_insert_log = "INSERT INTO log (acao_log, ip_log, dt_acao_log, usuario_config_id_usuario_config) values ('Criar Conta', '$ip', NOW(), $id_ultimo_cadastro ) ";

                if($conexao->query($sql_insert_log)){
                    $conexao->commit();
                    $conexao->close();
        
                    $res = [
                        'status' => 'success',
                        'message' => 'Conta cadastrada com sucesso!'
                    ];
                    echo json_encode($res, JSON_UNESCAPED_UNICODE);
                    exit;
                } else{
                    $conexao->rollback();
                    $conexao->close();
                    $res = [
                        'status' => 'erro',
                        'message' => 'Erro ao abrir conta, aguarde alguns minutos!'
                    ];
        
                    echo json_encode($res, JSON_UNESCAPED_UNICODE);
                    exit;
                }
    

            } else {
                $conexao->rollback();
                $conexao->close();
                $res = [
                    'status' => 'erro',
                    'message' => 'Erro ao abrir conta, tente novamente!'
                ];
    
    
                echo json_encode($res, JSON_UNESCAPED_UNICODE);
                exit;
            }
        } else {
            $conexao->rollback();
            $conexao->close();
            $res = [
                'status' => 'erro',
                'message' => 'E-mail já cadastrado no sistema!'
            ];
    
    
            echo json_encode($res, JSON_UNESCAPED_UNICODE);
            exit;
        }
    }
    catch (Exception $err){

        $conexao->rollback();
        $conexao->close();
        $res = [
            'status' => 'erro',
            'message' => 'Tente novamente em alguns minutos!'
        ];

        echo json_encode($res, JSON_UNESCAPED_UNICODE);
        exit;
    }

}

?>


<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/cad_login.css">
    <title>home</title>
</head>

<body>

    <main>

        <section class="container">

            <section class="form">
                <div class="conteudo_form">
                    <h2>Abra sua conta agora!</h2>

                    <form action="" method="post">

                        <div class="container_conteudo_form">
                            <span>Nome Completo:</span>
                            <input type="text" name="nome" placeholder="Ex: José Carlos Henrique" minlength="4" maxlength="150" required>
                            <p></p>
                        </div>

                        <div class="container_conteudo_form">
                            <span>E-mail:</span>
                            <input type="email" name="email" placeholder="Ex: jose@gmail.com" minlength="4" maxlength="100" required>
                            <p></p>
                        </div>

                        <div class="container_conteudo_form">
                            <span>Telefone:</span>
                            <input type="tell" name="tell" placeholder="Ex: (99)99999-9999" minlength="13" maxlength="14" required>
                            <p></p>
                        </div>

                        <div class="container_senhas">
                            <div class="container_conteudo_form">
                                <span>Senha:</span>
                                <input type="password" name="senha" placeholder="**********" minlength="8" maxlength="16" required>
                                <p></p>
                            </div>

                            <div class="container_conteudo_form">
                                <span>Confirmar Senha: </span>
                                <input type="password" name="confirmasenha" placeholder="**********" minlength="8" maxlength="16" required>
                                <p></p>
                            </div>
                        </div>

                        <div class="container_btn">
                            <button type="submit" id="abrir_conta">Abrir Conta</button>
                        </div>

                        <div class="container_link_login">
                            <a href="./login.php">Já possui um conta? faça o login</a>
                        </div>


                    </form>


                </div>
            </section>

            <section class="arte_img">
                <div class="container_texto_chamada">
                    <div class="texto_chamada">
                        <h1>ESCRITÓRIO DIGITAL</h1>
                        <p>Software de alta performace!</p>
                    </div>

                </div>
            </section>

        </section>

    </main>



    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>

    <script>
        $(document).ready(function() {
            // Máscara para o telefone
            $('input[name="tell"]').mask('(00) 00000-0000');

            // Ao perder o foco (blur), valida individualmente cada campo
            $('input').on('blur', function() {
                validateField($(this));
            });

            // Validação ao submeter o formulário
            $('form').on('submit', function(e) {

                $('#abrir_conta').attr('disabled', true)

                Swal.fire({
                    title: "Carregando...",
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });


                e.preventDefault();
                let isValid = true;

                // Valida todos os campos
                $('input').each(function() {
                    if (!validateField($(this))) {
                        isValid = false;
                    }
                });


                if (isValid) {
                    // Ajax para realizar o cadastro
                    let dados_form = $(this).serialize()
                    $.ajax({
                        url: $(this).attr('action'),
                        method: $(this).attr('method'),
                        data: dados_form,
                        dataType: 'json',
                        success: function(res) {
                            if (res.status === 'erro') {

                                if (res.message == 'E-mail já cadastrado no sistema!') {
                                    Swal.fire({
                                        icon: "error",
                                        title: "Erro",
                                        text: res.message,
                                        footer: '<a href="./login.php">Realizar Login!</a>'
                                    });
                                } else {
                                    Swal.fire({
                                        icon: "error",
                                        title: "Erro",
                                        text: res.message
                                    });
                                }

                            } else if (res.status === 'success') {
                                Swal.close();
                                
                                setTimeout(() => {
                                    Swal.fire({
                                        title: "Sucesso!",
                                        text: res.message,
                                        icon: "success"
                                    }).then((result) => {
                                            window.location.href = "./login.php";
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
                        },
                        complete: function() {
                            $('#abrir_conta').attr('disabled', false)
                            
                        }
                    })

                } else {
                    $('#abrir_conta').attr('disabled', false)
                    Swal.close();

                    setTimeout(()=>{
                        Swal.fire({
                        icon: "error",
                        title: "Erro",
                        text: 'Preencha os dados da forma correta!',
                    });
                    }, 150)

                }

            });

            // Função para validar um campo específico
            function validateField($field) {
                let isValid = true;
                const value = $field.val().trim();
                const $errorMessage = $field.siblings('p');

                // Remove classe de erro por padrão
                $field.removeClass('input-error');
                $errorMessage.hide();

                // Valida Nome Completo (apenas letras e espaços)
                if ($field.attr('name') === 'nome') {
                    const nameRegex = /^[a-zA-Zà-úÀ-Ú\s]+$/; // Aceita letras, acentos e espaços
                    if (!nameRegex.test(value) || value.length < 4 || value.length > 150) {
                        isValid = false;
                        $field.addClass('input-error');
                        $errorMessage.text('Digite um nome válido');
                        $errorMessage.show();
                    }
                }

                // Valida E-mail
                if ($field.attr('name') === 'email') {
                    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                    if (!emailRegex.test(value)) {
                        isValid = false;
                        $field.addClass('input-error');
                        $errorMessage.text('Digite um e-mail válido');
                        $errorMessage.show();
                    }
                }

                // Valida Telefone
                if ($field.attr('name') === 'tell') {
                    if (value.length < 14) {
                        isValid = false;
                        $field.addClass('input-error');
                        $errorMessage.text('Digite um número de telefone válido no formato (99) 99999-9999');
                        $errorMessage.show();
                    }
                }

                // Valida Senhas
                if ($field.attr('name') === 'senha' || $field.attr('name') === 'confirmasenha') {
                    const senha = $('input[name="senha"]').val();
                    const confirmarSenha = $('input[name="confirmasenha"]').val();

                    if ($field.attr('name') === 'senha') {
                        // Regex para validar letra maiúscula, caractere especial e número
                        const regexMaiuscula = /[A-Z]/; // Verifica ao menos uma letra maiúscula
                        const regexCaractereEspecial = /[!@#$%^&*(),.?":{}|<>]/; // Verifica ao menos um caractere especial
                        const regexNumero = /[0-9]/; // Verifica ao menos um número

                        if (senha.length < 8 || senha.length > 16) {
                            isValid = false;
                            $field.addClass('input-error');
                            $errorMessage.text('A senha deve ter entre 8 e 16 caracteres');
                            $errorMessage.show();
                        } else if (!regexMaiuscula.test(senha)) {
                            isValid = false;
                            $field.addClass('input-error');
                            $errorMessage.text('A senha deve conter pelo menos uma letra maiúscula');
                            $errorMessage.show();
                        } else if (!regexCaractereEspecial.test(senha)) {
                            isValid = false;
                            $field.addClass('input-error');
                            $errorMessage.text('A senha deve conter pelo menos um caractere especial');
                            $errorMessage.show();
                        } else if (!regexNumero.test(senha)) {
                            isValid = false;
                            $field.addClass('input-error');
                            $errorMessage.text('A senha deve conter pelo menos um número');
                            $errorMessage.show();
                        } else {
                            $field.removeClass('input-error');
                            $errorMessage.hide();
                        }
                    }

                    if ($field.attr('name') === 'confirmasenha') {
                        if (senha !== confirmarSenha) {
                            isValid = false;
                            $field.addClass('input-error');
                            $errorMessage.text('As senhas não coincidem');
                            $errorMessage.show();
                        } else {
                            $field.removeClass('input-error');
                            $errorMessage.hide();
                        }
                    }
                }




                // Atualiza a interface com base no erro
                if (isValid) {
                    $field.removeClass('input-error');
                    $errorMessage.hide();
                }

                return isValid;
            }
        });
    </script>

    <!-- Alertas -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


</body>

</html>