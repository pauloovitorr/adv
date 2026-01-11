<?php

date_default_timezone_set('America/Sao_Paulo');

require __DIR__ . '/../../vendor/autoload.php';

require_once 'enviar_emails_modelos.php';


$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../../');
$dotenv->load();

$host = $_ENV['DB_HOST'];
$user = $_ENV['DB_USER'];
$password = $_ENV['DB_PASS'];
$data_base = $_ENV['DB_BASE'];

$conexao = new mysqli($host, $user, $password, $data_base);
$conexao->set_charset("utf8");

?>