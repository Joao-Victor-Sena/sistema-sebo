<?php
// index.php
require_once 'config/conexao.php';
include 'includes/header.php';
?>

<a href="login.php" class="btn btn-outline-danger btn-sm btn-logout-floating">
    <i class="bi bi-box-arrow-right"></i> Sair
</a>

<div class="d-flex flex-column justify-content-center" style="min-height: 80vh;">

    <div class="row mb-5">
        <div class="col-12 text-center">
            <h1 class="display-1 fw-bold text-white">
                Sebo<span class="text-orange">Linhas</span>
            </h1>
        </div>
    </div>

    <div class="row justify-content-center g-4">
        
        <div class="col-md-4 col-lg-3">
            <a href="modulos/livros/acervo.php" class="card-menu">
                <i class="bi bi-book-half"></i>
                <h3 class="text-white">Acervo</h3>
                <small class="text-white-50">Gerenciar Livros</small>
            </a>
        </div>

        <div class="col-md-4 col-lg-3">
            <!-- Link apontando para breve.php -->
            <a href="breve.php" class="card-menu">
                <i class="bi bi-cart-check-fill"></i>
                <h3 class="text-white">Vendas</h3>
                <small class="text-white-50">Caixa & Pedidos</small>
            </a>
        </div>

        <div class="col-md-4 col-lg-3">
            <div class="card-menu" role="button" data-bs-toggle="modal" data-bs-target="#modalEscolhaPessoas">
                <i class="bi bi-people-fill"></i>
                <h3 class="text-white">Pessoas</h3>
                <small class="text-white-50">Clientes e Staff</small>
            </div>
        </div>
    </div>

</div>

<div class="modal fade" id="modalEscolhaPessoas" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content bg-dark text-white border-secondary shadow-lg">
            
            <div class="modal-header border-secondary">
                <h5 class="modal-title">
                    <i class="bi bi-people-fill text-orange"></i> Gerenciar Pessoas
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Fechar"></button>
            </div>
            
            <div class="modal-body p-4">
                <p class="text-center text-white-50 mb-4">Selecione qual grupo deseja gerenciar:</p>
                
                <div class="d-flex gap-3">
                    
                    <a href="pessoas/clientes/cliente.php" class="btn btn-outline-orange w-50 p-4 d-flex flex-column align-items-center justify-content-center">
                        <i class="bi bi-person-heart fs-1 mb-2"></i>
                        <span class="fw-bold fs-5 text-white">Clientes</span>
                        <small class="text-white-50 mt-1" style="font-size: 0.7rem;">Consumidores</small>
                    </a>

                    <a href="breve.php" class="btn btn-outline-light w-50 p-4 d-flex flex-column align-items-center justify-content-center">
                        <i class="bi bi-person-badge fs-1 mb-2"></i>
                        <span class="fw-bold fs-5 text-white">Staff</span>
                        <small class="text-white-50 mt-1" style="font-size: 0.7rem;">Equipe Interna</small>
                    </a>

                </div>
            </div>

        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>