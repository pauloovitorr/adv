<?php 

// Trocar domínio e ativar envio de e-mail com SSL

require_once('./vendor/phpmailer/phpmailer/src/PHPMailer.php');
require_once('./vendor/phpmailer/phpmailer/src/SMTP.php');
require_once('./vendor/phpmailer/phpmailer/src/Exception.php');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

function dados_acesso($nome_cliente,$remetente, $usuario, $senha){

    $mail = new PHPMailer(true);

    try {
        // Configuração do servidor SMTP
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com'; // Servidor SMTP do Gmail
        $mail->SMTPAuth = true; // Ativar autenticação SMTP
        $mail->Username = 'paulov.pv50@gmail.com'; // Seu e-mail do Gmail
        $mail->Password = 'okjw dhmk bbjv sjst'; // Sua senha ou senha de aplicativo
        // $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS; // Usar SSL
        $mail->Port = 587; // Porta SMTP para SSL 
    
        // Configuração do remetente e destinatário
        $mail->setFrom('paulov.pv50@gmail.com'); // Remetente
        $mail->addAddress("$remetente"); // Destinatário principal
        $mail->addReplyTo('paulov.pv50@gmail.com'); // E-mail de resposta
    
        // Conteúdo do e-mail
        $mail->CharSet = 'UTF-8';
        $mail->isHTML(true); // Permitir HTML no e-mail
        $mail->Subject = 'Dados de acesso';
        $mail->Body = "<h1>Olá, $nome_cliente!</h1><p>Segue os dados de acesso ao sistema: </p><br><p>Tela de login: <a href='http://localhost/advogado/login.php'>Clique aqui!</a></p> <p>Usuário: <strong>$usuario</strong> </p><p>Senha: <strong>$senha</strong> </p>";
        $mail->AltBody = "Olá, $nome_cliente! 
        Segue os dados de acesso ao sistema: Tela de login: http://localhost/adv/login.php 
        Usuário: $usuario 
        Senha: $senha";
    
        // Enviar o e-mail
        if($mail->send()){
           return 'E-mail enviado com sucesso!';
        }else{
            return 'Erro';
        }
        
    } catch (Exception $e) {
        return "Erro ao enviar o e-mail: {$mail->ErrorInfo}";
    }

}


  





?>
