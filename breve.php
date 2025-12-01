<?php
// breve.php
require_once 'config/conexao.php';
include 'includes/header.php';
?>

<div class="d-flex flex-column justify-content-center align-items-center text-center" style="min-height: 70vh;">
    
    <!-- Ícone Grande Animado -->
    <div class="mb-4">
        <i class="bi bi-cone-striped text-orange" style="font-size: 6rem;"></i>
    </div>

    <!-- Título Impactante -->
    <h1 class="display-1 fw-bold text-white mb-3">
        Em <span class="text-orange">breve...</span>
    </h1>

    <!-- Mensagem Explicativa -->
    <p class="lead text-white-50 mb-5 fs-4">
        Esta área do sistema ainda está em desenvolvimento.<br>
        <small class="fs-6">Estamos trabalhando para trazer novidades.</small>
    </p>

    <!-- Botão de Voltar Inteligente -->
    <a href="javascript:history.back()" class="btn btn-outline-light btn-lg px-5">
        <i class="bi bi-arrow-left"></i> Voltar
    </a>

</div>

<?php include 'includes/footer.php'; ?>