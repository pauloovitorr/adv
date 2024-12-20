<?php 

date_default_timezone_set('America/Sao_Paulo');

$host = '149.56.31.239';
$user = 'comtest_projetoadv';
$password = 'pn=v.#qnMoy_';
$data_base = 'comtest_adv';

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

if($pag !== 'cadastro.php' && $pag !== 'login.php' && $pag !== 'recupera_senha.php' ){

   if( !isset($_SESSION['nome']) ||  !isset($_SESSION['email']) ||  !isset($_SESSION['cod'] ) ){
        header('Location: /adv/login.php');
        exit;
   }

} 


// // Verifica se a sessão expirou
if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > 2 * 60 * 60)) {
    // Se passou 2 horas sem atividade, encerra a sessão
    session_unset();
    session_destroy();
    header('Location: /advogado/login.php'); // Redireciona para o login
    exit();
} else {
    // Renova a última atividade
    $_SESSION['LAST_ACTIVITY'] = time();
}


?>