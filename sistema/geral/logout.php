<?php 

include_once('../scripts.php');

// Limpa as variáveis da sessão
session_unset();

// Destroy a sessão
session_destroy();

// Remove o cookie de sessão, se existir
if(ini_get('session.use_cookies')){
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

header('Location: ../login.php');

exit;

?>