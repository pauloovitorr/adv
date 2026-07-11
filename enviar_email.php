<?php 

// Trocar domínio e ativar envio de e-mail com SSL

require_once('./vendor/phpmailer/phpmailer/src/PHPMailer.php');
require_once('./vendor/phpmailer/phpmailer/src/SMTP.php');
require_once('./vendor/phpmailer/phpmailer/src/Exception.php');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

function dados_acesso($nome_cliente, $remetente, $usuario, $senha) {

    $mail = new PHPMailer(true);

    try {
        // Configuração do servidor SMTP da Umbler vindo do .env
        $mail->isSMTP();
        $mail->Host       = $_ENV['MAIL_HOST'];
        $mail->SMTPAuth   = true;
        $mail->Username   = $_ENV['MAIL_USER'];
        $mail->Password   = $_ENV['MAIL_PASS'];
        $mail->Port       = $_ENV['MAIL_PORT'];
        $mail->SMTPSecure = 'tls'; // Padrão recomendado para a porta 587

        // Configuração do remetente e destinatário
        $mail->setFrom($_ENV['MAIL_USER'], 'ADV'); // Remetente profissional
        $mail->addAddress($remetente); // Destinatário principal
        $mail->addReplyTo($_ENV['MAIL_USER']);
    
        // Conteúdo do e-mail
        $mail->CharSet = 'UTF-8';
        $mail->isHTML(true);
        $mail->Subject = 'Dados de acesso';
        
        // Links atualizados para o seu domínio real na VPS em vez de localhost
        $mail->Body = "<h1>Olá, $nome_cliente!</h1>
                       <p>Segue os dados de acesso ao sistema:</p><br>
                       <p>Tela de login: <a href='https://adv.paulovitordev.com.br/login.php'>Clique aqui!</a></p> 
                       <p>Usuário: <strong>$usuario</strong></p>
                       <p>Senha: <strong>$senha</strong></p>";
                       
        $mail->AltBody = "Olá, $nome_cliente!\n
                          Segue os dados de acesso ao sistema:\n
                          Tela de login: https://adv.paulovitordev.com.br/login.php\n
                          Usuário: $usuario\n
                          Senha: $senha";
    
        // Enviar o e-mail
        if ($mail->send()) {
           return 'E-mail enviado com sucesso!';
        } else {
            return 'Erro';
        }
        
    } catch (Exception $e) {
        return "Erro ao enviar o e-mail: {$mail->ErrorInfo}";
    }
}


  





?>
