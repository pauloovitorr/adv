<?php


include_once('./scripts.php');

include_once('./enviar_email.php');


if ($_SERVER['REQUEST_METHOD'] === 'POST'  && !empty($_POST['email'])) {


    $email  = $conexao->escape_string(htmlspecialchars($_POST['email']));
    $ip = $_SERVER['REMOTE_ADDR'];

    try{

        $conexao->begin_transaction();

        $sql = "SELECT * from usuario_config where email = ?";
        $verifica_dados = $conexao->prepare($sql);
        $verifica_dados->bind_param('s', $email);
        $verifica_dados->execute();
        $verificacao = $verifica_dados->get_result();

        if ($verificacao->num_rows === 1) {

            $sql_verifica_ip = "SELECT COUNT('ip_log') AS qtd_cadastro_recente from log where ip_log = '$ip' AND acao_log = 'Dados de acesso' AND dt_acao_log >= NOW() - INTERVAL 10 MINUTE";
    
            $result_verificacao = $conexao->query($sql_verifica_ip);
            $result_ip = $result_verificacao->fetch_assoc();   
            
            $qtd_ip = $result_ip['qtd_cadastro_recente'];
    
            if($qtd_ip >= 3){
                
                $res = [
                    'status' => 'erro',
                    'message' => 'Aguarde! Você enviou os dados de acesso muitas vezes'
                ];

                $conexao->rollback();
                $conexao->close();
                echo json_encode($res, JSON_UNESCAPED_UNICODE);
                exit;
            }
    
    
            $registro = $verificacao->fetch_assoc();
            
            $id             = $registro['id_usuario_config'];
            $nome_cliente   = $registro['nome'];
            $remetente      = $registro['email'];
            $usuario        = $registro['email'];
            $senha          = $registro['senha'];
    
            // Função veio do arquivo enviar_email.php
            $result = dados_acesso($nome_cliente, $remetente, $usuario, $senha);
    
            if ($result == 'E-mail enviado com sucesso!') {

                $sql_insert_log = "INSERT INTO log (acao_log, ip_log, dt_acao_log, usuario_config_id_usuario_config) VALUES ('Dados de acesso', '$ip', NOW(), $id ) ";
                
                if($conexao->query($sql_insert_log)){
                    $res = [
                        'status' => 'success',
                        'message' => 'Dados de acesso encaminhado!'
                    ];
                    
                    $conexao->commit();
                    $conexao->close();
                    echo json_encode($res, JSON_UNESCAPED_UNICODE);
                    exit;
                }

               
            } elseif ($result == 'Erro') {
                $res = [
                    'status' => 'erro',
                    'message' => 'Erro ao enviar e-mail. Tente novamente mais tarde!'
                ];

                $conexao->rollback();
                $conexao->close();
                echo json_encode($res, JSON_UNESCAPED_UNICODE);
                exit;
            } else {
                $res = [
                    'status' => 'erro',
                    'message' => 'Erro ao enviar e-mail. Tente novamente!'
                ];
    
                $conexao->rollback();
                $conexao->close();
                echo json_encode($res, JSON_UNESCAPED_UNICODE);
                exit;
            }
        } else {
            $res = [
                'status' => 'erro',
                'message' => 'E-mail inexistente'
            ];
            $conexao->rollback();
            $conexao->close();
            echo json_encode($res, JSON_UNESCAPED_UNICODE);
            exit;
        }
    }
    catch (Exception $err){
        
        $conexao->close();
        $res = [
            'status' => 'erro',
            'message' => 'Tente novamente em alguns minutos!'
        ];
        $conexao->rollback();
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
    <title>Login</title>
</head>

<body>


    <main>

        <section class="container">

            <section class="arte_img">
                <div class="container_texto_chamada">
                    <div class="texto_chamada">
                        <h1>ESCRITÓRIO DIGITAL</h1>
                        <p>Software de alta performace!</p>
                    </div>

                </div>
            </section>

            <section class="form">
                <div class="conteudo_form">
                    <h2>Recuperar Senha</h2>

                    <form action="./recupera_senha.php" method="post" id="form_login">


                        <div class="container_conteudo_form">
                            <span>Digite o e-mail de cadastro:</span>
                            <input type="email" name="email" placeholder="Ex: jose@gmail.com" minlength="4" maxlength="100" required>
                            <p></p>
                        </div>



                        <div class="container_btn">
                            <button type="submit" id="btn_recupera_senha">Entrar</button>
                        </div>

                        <div class="container_link_login">
                            <a href="./index.php">Lembrou a senha? Faça o login</a>
                        </div>


                    </form>


                </div>




            </section>

        </section>

    </main>




    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>

    <script>
        $(document).ready(function() {

            $('input').on('blur', function() {
                validateField($(this));
            });


            function validateField($field) {
                let isValid = true;
                const value = $field.val().trim();
                const $errorMessage = $field.siblings('p');

                // Remove classe de erro por padrão
                $field.removeClass('input-error');
                $errorMessage.hide();


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


                // Atualiza a interface com base no erro
                if (isValid) {
                    $field.removeClass('input-error');
                    $errorMessage.hide();
                }

                return isValid;

            }



            $('#form_login').on('submit', function(event) {

                $('#btn_recupera_senha').attr('disabled', true)

                Swal.fire({
                    title: "Carregando...",
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                event.preventDefault()
                let isValido = true;

                $('input').each(function() {
                    if (!validateField($(this))) {
                        isValido = false;
                    }
                });



                if (isValido) {
                    let dados_form = $(this).serialize()
                    $.ajax({
                        url: $(this).attr('action'),
                        method: $(this).attr('method'),
                        data: dados_form,
                        dataType: 'json',
                        success: function(res) {

                            if (res.status == 'erro') {
                                Swal.fire({
                                    icon: "error",
                                    title: "Erro",
                                    text: res.message
                                });
                            } else if (res.status == 'success' && res.message == 'Dados de acesso encaminhado!') {
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
                            $('#btn_recupera_senha').attr('disabled', false)
                        }

                    })
                } else {

                    $('#btn_recupera_senha').attr('disabled', false)
                    Swal.close();

                    setTimeout(() => {
                        Swal.fire({
                            icon: "error",
                            title: "Erro",
                            text: 'Preencha os dados da forma correta!',
                        });
                    }, 150)

                }

            })
        })
    </script>

</body>

</html>