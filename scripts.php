<?php

date_default_timezone_set('America/Sao_Paulo');

require 'vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();



$host =  $_ENV['DB_HOST']; 
$user = $_ENV['DB_USER']; 
$password = $_ENV['DB_PASS']; 
$data_base = $_ENV['DB_BASE']; 

$conexao = new mysqli($host, $user, $password, $data_base);
$conexao->set_charset("utf8");

session_set_cookie_params([
    'lifetime' => 2 * 60 * 60, // 2 horas
    'path' => '/',
    'secure' => false, // Alterar para true em produção com HTTPS
    'httponly' => true,
    'samesite' => 'Lax'
]);

session_start();

// Verifica se usuário tá logado
$pag = basename($_SERVER['REQUEST_URI']);

if ($pag !== 'cadastro.php' && $pag !== 'login.php' && $pag !== 'recupera_senha.php') {

    if (!isset($_SESSION['nome']) ||  !isset($_SESSION['email']) ||  !isset($_SESSION['cod'])) {
        session_unset();
        session_destroy();
        header('Location: /adv/login.php');
        exit;
    }
}


$timeout = 2 * 60 * 60; // 2 horas

// Verifica se LAST_ACTIVITY está configurado
if (isset($_SESSION['LAST_ACTIVITY'])) {
    $inactive = time() - $_SESSION['LAST_ACTIVITY'];

    // Se ultrapassou o tempo limite, encerra a sessão
    if ($inactive > $timeout) {
        session_unset();
        session_destroy();
        header('Location: /advogado/login.php'); // Redireciona para o login
        exit();
    }
}

// Renova a última atividade apenas se houver interação
if ($_SERVER['REQUEST_METHOD'] !== 'OPTIONS') { // Ignorar requisições automáticas como preflight
    $_SESSION['LAST_ACTIVITY'] = time();
}
