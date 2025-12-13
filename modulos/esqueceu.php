<?php
require_once '../config/conexao.php';
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperar Senha - SeboLinhas</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../css/custom.css">

    <style>
        body {
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
        }
    </style>
</head>
<body>

    <div class="container d-flex flex-column justify-content-center align-items-center text-center">
        <div class="mb-4">
            <i class="bi bi-shield-lock-fill text-orange" style="font-size: 6rem;"></i>
        </div>

        <h1 class="display-3 fw-bold text-white mb-3">
            Esqueceu a <span class="text-orange">Senha?</span>
        </h1>

        <p class="lead text-white-50 mb-4 fs-4">
            Por motivos de segurança, a redefinição de senha<br>
            deve ser solicitada diretamente ao nosso suporte técnico.
        </p>

        <div class="card bg-transparent border-secondary mb-5 p-4" style="min-width: 320px;">
            <ul class="list-unstyled mb-0 text-white">
                <li class="mb-3">
                    <i class="bi bi-envelope-fill text-orange me-2"></i>
                    <a href="mailto:suporte@sebolinhas.com" class="text-white text-decoration-none">suporte@sebolinhas.com</a>
                </li>
                <li>
                    <i class="bi bi-whatsapp text-orange me-2"></i>
                    <a href="https://wa.me/5581997038325" target="_blank" class="text-white text-decoration-none">(81) 99999-9999</a>
                </li>
            </ul>
        </div>

        <a href="login.php" class="btn btn-outline-light btn-lg px-5">
            <i class="bi bi-arrow-left"></i> Voltar ao Login
        </a>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    
</body>
</html>