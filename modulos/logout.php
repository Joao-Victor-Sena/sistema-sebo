<?php
// modulos/logout.php

// 1. Inicia a sessão para ter acesso às variáveis atuais
session_start();

// 2. Remove todas as variáveis de sessão ($_SESSION['usuario_id'], etc)
$_SESSION = array();

// 3. Se desejar destruir o cookie da sessão completamente (opcional, mas recomendado)
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// 4. Destrói a sessão no servidor
session_destroy();

// 5. Redireciona o usuário para a tela de login (mesma pasta)
header("Location: login.php");
exit;
?>