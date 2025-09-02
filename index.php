<?php


include_once('./scripts.php');


if ($_SERVER['REQUEST_METHOD'] === 'POST'  && !empty($_POST['email']) &&  !empty($_POST['senha'])) {


    $email  = $conexao->escape_string(htmlspecialchars($_POST['email']));
    $senha  = $conexao->escape_string(htmlspecialchars($_POST['senha']));

    $sql = "SELECT * from usuario_config where email = ? and senha = ? ";
    $verifica_dados = $conexao->prepare($sql);
    $verifica_dados->bind_param('ss', $email, $senha);
    $verifica_dados->execute();
    $verificacao = $verifica_dados->get_result();


    if ($verificacao->num_rows === 1) {

        $registro = $verificacao->fetch_assoc();

        $_SESSION['nome'] = $registro['nome'];
        $_SESSION['email'] = $registro['email'];
        $_SESSION['cod'] = $registro['id_usuario_config'];

        $res = [
            'status' => 'success',
            'message' => 'login realizado'
        ];

        echo json_encode($res, JSON_UNESCAPED_UNICODE);
         $conexao->close();
        exit;
    } else {
        $res = [
            'status' => 'erro',
            'message' => 'Erro ao acessar. Credenciais inválidas.'
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
                    <h2>Bem-vindo de volta!</h2>

                    <form action="#" method="post" id="form_login">


                        <div class="container_conteudo_form">
                            <span>E-mail:</span>
                            <input type="email" name="email" placeholder="Ex: jose@gmail.com" minlength="4" maxlength="100" required>
                            <p></p>
                        </div>

                        <div class="container_conteudo_form">
                            <span>Senha:</span>
                            <input type="password" name="senha" placeholder="**********" minlength="8" maxlength="16" required>
                            <p></p>
                        </div>

                        <div class="container_btn">
                            <button type="submit" id="btn_login">Entrar</button>
                        </div>

                        <div class="container_link_login">
                            <a href="./recupera_senha.php">Esqueceu a senha? Clique aqui </a>
                        </div>

                        <div class="container_link_login">
                            <a href="./cadastro.php">Não tem conta? Faça o seu cadastro </a>
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


                if ($field.attr('name') === 'senha') {
                    // Regex para validar letra maiúscula, caractere especial e número
                    const regexMaiuscula = /[A-Z]/; // Verifica ao menos uma letra maiúscula
                    const regexCaractereEspecial = /[!@#$%^&*(),.?":{}|<>]/; // Verifica ao menos um caractere especial
                    const regexNumero = /[0-9]/; // Verifica ao menos um número



                    if (value.length < 8 || value.length > 16) {
                        isValid = false;
                        $field.addClass('input-error');
                        $errorMessage.text('A senha deve ter entre 8 e 16 caracteres');
                        $errorMessage.show();
                    } else if (!regexMaiuscula.test(value)) {
                        isValid = false;
                        $field.addClass('input-error');
                        $errorMessage.text('A senha deve conter pelo menos uma letra maiúscula');
                        $errorMessage.show();
                    } else if (!regexCaractereEspecial.test(value)) {
                        isValid = false;
                        $field.addClass('input-error');
                        $errorMessage.text('A senha deve conter pelo menos um caractere especial');
                        $errorMessage.show();
                    } else if (!regexNumero.test(value)) {
                        isValid = false;
                        $field.addClass('input-error');
                        $errorMessage.text('A senha deve conter pelo menos um número');
                        $errorMessage.show();
                    } else {
                        $field.removeClass('input-error');
                        $errorMessage.hide();
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

                $('#btn_login').attr('disabled', true)

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
                            } else if (res.status == 'success' && res.message == 'login realizado') {
                                Swal.close();
                                window.location.href = "./sistema/geral/home.php";
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
                            $('#btn_login').attr('disabled', false)
                          
                        }


                    })
                } else {

                    $('#btn_login').attr('disabled', false)
                    Swal.close();

                    setTimeout(()=>{
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