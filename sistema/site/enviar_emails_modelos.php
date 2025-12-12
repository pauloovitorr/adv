<?php

// Trocar domínio e ativar envio de e-mail com SSL

require_once __DIR__ . '/../../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

function envia_email($nome_cliente, $email_lead, $telefone, $msg, $email_usuario)
{
    $mail = new PHPMailer(true);

    try {

        // --- CONFIGURAÇÃO SMTP ---
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'paulov.pv50@gmail.com'; // Email que RECEBE o lead
        $mail->Password = 'okjw dhmk bbjv sjst';   // Senha de app
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        // --- REMETENTE ---
        // Quem ENVIA é sempre você (o e-mail configurado)
        $mail->setFrom('paulov.pv50@gmail.com', 'Formulário do Site');

        // --- DESTINATÁRIO ---
        // O lead NÃO recebe. Email que vai receber o alerta de lead
        $mail->addAddress($email_usuario, 'Novo Lead');

        // --- OPCIONAL: Responder ao lead ---
        $mail->addReplyTo($email_lead, $nome_cliente);

        // --- CONFIGURAÇÃO DO E-MAIL ---
        $mail->CharSet = 'UTF-8';
        $mail->isHTML(true);

        //  TÍTULO 
        $mail->Subject = 'Novo Lead no Seu Site';

        //  CORPO HTML
        $mail->Body = "
            <h2>📩 Novo Lead Recebido</h2>

            <p><strong>Nome:</strong> {$nome_cliente}</p>
            <p><strong>E-mail:</strong> {$email_lead}</p>
            <p><strong>Telefone/WhatsApp:</strong> {$telefone}</p>

            <p><strong>Mensagem:</strong><br>
            " . nl2br($msg) . "</p>

            <hr>
            <p>Este lead veio do formulário do site.</p>
        ";

        // Texto puro (fallback)
        $mail->AltBody = "
Novo Lead no Seu Site

Nome: $nome_cliente
E-mail: $email_lead
Telefone: $telefone
Mensagem:
$msg
        ";

        // --- ENVIO ---
        if ($mail->send()) {
            return 'Lead enviado com sucesso!';
        }

        return 'Erro ao enviar.';

    } catch (Exception $e) {
        return "Erro: {$mail->ErrorInfo}";
    }
}




?>
