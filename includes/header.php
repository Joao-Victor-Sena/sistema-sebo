<?php
// includes/header.php

// 1. Segurança de Sessão (Obrigatório em todas as páginas)
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// No topo do includes/header.php
if (!isset($_SESSION['usuario_id']) && basename($_SERVER['PHP_SELF']) != 'login.php') {
    // Caminho atualizado para a nova pasta
    header("Location: /sistema-sebo/modulos/login.php"); 
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SeboLinhas - Gestão</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Ícones -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    
    <!-- CSS Personalizado (CAMINHO ABSOLUTO PARA FUNCIONAR EM QUALQUER PASTA) -->
    <link rel="stylesheet" href="/sistema-sebo/css/custom.css">
</head>
<body>

<!-- Container Principal -->
<div class="container mt-4">