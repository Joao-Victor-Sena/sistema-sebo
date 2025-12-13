<?php
session_start();
require_once '../../../config/conexao.php';

$niveis_permitidos = ['Gerente', 'Admin', 'Administrador'];

if (isset($_POST['btn_cadastrar'])) {
    $nome  = filter_input(INPUT_POST, 'nome', FILTER_SANITIZE_SPECIAL_CHARS);
    $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
    $cpf   = preg_replace('/[^0-9]/', '', $_POST['cpf']);
    $tel   = preg_replace('/[^0-9]/', '', $_POST['telefone']);

    if (strlen($cpf) !== 11) {
        $erro_msg = "Erro: O CPF deve conter exatamente 11 números.";
    } elseif (strlen($tel) !== 11) {
        $erro_msg = "Erro: O Telefone deve conter 11 números (DDD + 9 dígitos).";
    } else {
        $stmt_check = $pdo->prepare("SELECT CD_CLIENTE FROM CLIENTE WHERE CPF = :cpf");
        $stmt_check->execute([':cpf' => $cpf]);

        if ($stmt_check->rowCount() > 0) {
            $erro_msg = "Erro: CPF já cadastrado.";
        } else {
            try {
                $sql = "INSERT INTO CLIENTE (NOME, CPF, EMAIL, TELEFONE) VALUES (:nome, :cpf, :email, :tel)";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([':nome' => $nome, ':cpf' => $cpf, ':email' => $email, ':tel' => $tel]);
                header("Location: cliente.php?msg=cadastrado");
                exit;
            } catch (PDOException $e) {
                $erro_msg = "Erro: " . $e->getMessage();
            }
        }
    }
}

$itens_por_pagina = 10;
$pagina_atual = filter_input(INPUT_GET, 'pagina', FILTER_VALIDATE_INT) ?? 1;
if ($pagina_atual < 1) $pagina_atual = 1;

$busca = filter_input(INPUT_GET, 'busca', FILTER_SANITIZE_SPECIAL_CHARS);
$termo = "%" . $busca . "%";

try {
    if (!empty($busca)) {
        $sql_count = "SELECT COUNT(*) as total FROM CLIENTE WHERE NOME LIKE :b OR CPF LIKE :b OR EMAIL LIKE :b";
        $stmt_count = $pdo->prepare($sql_count);
        $stmt_count->bindValue(':b', $termo);
    } else {
        $sql_count = "SELECT COUNT(*) as total FROM CLIENTE";
        $stmt_count = $pdo->prepare($sql_count);
    }
    $stmt_count->execute();
    $total_registros = $stmt_count->fetch()['total'];
    $total_paginas = ceil($total_registros / $itens_por_pagina);
    $offset = ($pagina_atual - 1) * $itens_por_pagina;

    if (!empty($busca)) {
        $sql = "SELECT * FROM CLIENTE WHERE NOME LIKE :b OR CPF LIKE :b OR EMAIL LIKE :b ORDER BY NOME ASC LIMIT :lim OFFSET :off";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':b', $termo);
    } else {
        $sql = "SELECT * FROM CLIENTE ORDER BY NOME ASC LIMIT :lim OFFSET :off";
        $stmt = $pdo->prepare($sql);
    }
    $stmt->bindValue(':lim', $itens_por_pagina, PDO::PARAM_INT);
    $stmt->bindValue(':off', $offset, PDO::PARAM_INT);
    $stmt->execute();
    $clientes = $stmt->fetchAll();
} catch (PDOException $e) {
    $clientes = [];
    $erro_msg = "Erro no sistema: " . $e->getMessage();
}

include '../../../includes/header.php';
?>

<?php if (isset($_GET['msg'])): ?>
    <div class='alert alert-success fixed-top m-3 shadow' style='z-index:2000;'>
        <?php if($_GET['msg'] == 'excluido'): ?>
            <i class='bi bi-check-circle-fill'></i> Cliente excluído com sucesso!
        <?php elseif($_GET['msg'] == 'atualizado'): ?>
            <i class='bi bi-pencil-fill'></i> Cliente atualizado com sucesso!
        <?php elseif($_GET['msg'] == 'cadastrado'): ?>
            <i class='bi bi-check-lg'></i> Cliente cadastrado com sucesso!
        <?php endif; ?>
        <button type='button' class='btn-close' data-bs-dismiss='alert'></button>
    </div>
<?php endif; ?>

<?php if (isset($_GET['erro_perm'])): ?>
    <div class='alert alert-danger fixed-top m-3 shadow' style='z-index:2000;'>
        <i class='bi bi-shield-lock-fill'></i> <strong>Acesso Negado:</strong> Somente Gerentes ou Administradores podem realizar esta ação.
        <button type='button' class='btn-close' data-bs-dismiss='alert'></button>
    </div>
<?php endif; ?>

<?php if (isset($_GET['erro_db'])): ?>
    <div class='alert alert-warning fixed-top m-3 shadow' style='z-index:2000;'>
        <i class='bi bi-exclamation-triangle'></i> Não foi possível excluir: Cliente possui histórico de compras.
        <button type='button' class='btn-close' data-bs-dismiss='alert'></button>
    </div>
<?php endif; ?>

<?php if (isset($erro_msg)): ?>
    <div class='alert alert-warning fixed-top m-3 shadow' style='z-index:2000;'>
        <i class='bi bi-exclamation-triangle'></i> <?= $erro_msg ?>
        <button type='button' class='btn-close' data-bs-dismiss='alert'></button>
    </div>
<?php endif; ?>

<div class="row mb-4 align-items-center">
    <div class="col-md-4">
        <h2 class="text-white display-6">
            <i class="bi bi-people-fill text-orange"></i> Clientes
        </h2>
    </div>
    <div class="col-md-4">
        <form method="GET" action="" class="d-flex">
            <div class="input-group">
                <input type="text" name="busca" class="form-control bg-dark text-white border-secondary" placeholder="Buscar Nome ou CPF..." value="<?= $busca; ?>">
                <button class="btn btn-outline-orange" type="submit">
                    <i class="bi bi-search"></i>
                </button>
                <?php if(!empty($busca)): ?>
                    <a href="cliente.php" class="btn btn-outline-secondary"><i class="bi bi-x-lg"></i></a>
                <?php endif; ?>
            </div>
        </form>
    </div>
    <div class="col-md-4 text-end">
        <a href="../../../index.php" class="btn btn-outline-light me-2"><i class="bi bi-arrow-left"></i> Voltar</a>
        <button type="button" class="btn bg-orange fw-bold" data-bs-toggle="modal" data-bs-target="#modalNovoCliente">
            <i class="bi bi-person-plus-fill"></i> Novo
        </button>
    </div>
</div>

<div class="card bg-dark border-secondary shadow">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-dark table-hover mb-0 align-middle">
                <thead>
                    <tr class="text-orange">
                        <th>Cód.</th>
                        <th>Nome</th>
                        <th>CPF</th>
                        <th>Contato (Email / Tel)</th>
                        <th class="text-end">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($clientes) > 0): ?>
                        <?php foreach ($clientes as $cli): ?>
                            <tr>
                                <td><small class="text-white small">#<?= $cli['CD_CLIENTE']; ?></small></td>
                                <td class="fw-bold text-white"><?= $cli['NOME']; ?></td>
                                <td class="text-light"><?= $cli['CPF']; ?></td>
                                <td>
                                    <div class="text-white"><?= $cli['EMAIL']; ?></div>
                                    <div class="small text-white-50"><?= $cli['TELEFONE']; ?></div>
                                </td>
                                <td class="text-end">
                                    <?php if (isset($_SESSION['usuario_nivel']) && in_array($_SESSION['usuario_nivel'], $niveis_permitidos)): ?>
                                        <a href="editar.php?id=<?= $cli['CD_CLIENTE']; ?>" class="btn btn-sm btn-outline-info me-1">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <button type="button" class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#modalExcluir" data-id="<?= $cli['CD_CLIENTE']; ?>" data-nome="<?= $cli['NOME']; ?>">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    <?php else: ?>
                                        <span class="text-muted small"><i class="bi bi-lock-fill"></i></span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" class="text-center py-5 text-white">
                                <h4 class="fw-light">Nenhum cliente encontrado.</h4>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
    
    <div class="card-footer bg-dark border-secondary d-flex justify-content-between align-items-center">
        <small class="text-white-50">
            Total: <strong><?= $total_registros; ?></strong> (Pág. <?= $pagina_atual; ?> de <?= $total_paginas > 0 ? $total_paginas : 1; ?>)
        </small>
        
        <?php if ($total_paginas > 1): ?>
        <nav>
            <ul class="pagination pagination-sm mb-0">
                <li class="page-item <?= ($pagina_atual <= 1) ? 'disabled' : ''; ?>">
                    <a class="page-link" href="?pagina=<?= $pagina_atual - 1; ?>&busca=<?= $busca; ?>"><i class="bi bi-chevron-left"></i></a>
                </li>
                <?php 
                $inicio = max(1, $pagina_atual - 2);
                $fim = min($total_paginas, $pagina_atual + 2);
                if($inicio > 1) echo '<li class="page-item disabled"><span class="page-link">...</span></li>';
                for ($i = $inicio; $i <= $fim; $i++): 
                ?>
                    <li class="page-item <?= ($pagina_atual == $i) ? 'active' : ''; ?>">
                        <a class="page-link" href="?pagina=<?= $i; ?>&busca=<?= $busca; ?>"><?= $i; ?></a>
                    </li>
                <?php endfor; ?>
                <?php if($fim < $total_paginas) echo '<li class="page-item disabled"><span class="page-link">...</span></li>'; ?>
                <li class="page-item <?= ($pagina_atual >= $total_paginas) ? 'disabled' : ''; ?>">
                    <a class="page-link" href="?pagina=<?= $pagina_atual + 1; ?>&busca=<?= $busca; ?>"><i class="bi bi-chevron-right"></i></a>
                </li>
            </ul>
        </nav>
        <?php endif; ?>
    </div>
</div>

<div class="modal fade" id="modalNovoCliente" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content bg-dark text-white border-secondary shadow-lg">
            <div class="modal-header border-secondary">
                <h5 class="modal-title"><i class="bi bi-person-plus text-orange"></i> Novo Cliente</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label text-white-50 small">Nome Completo</label>
                        <input type="text" class="form-control" name="nome" required placeholder="Ex: Maria Silva">
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-white-50 small">CPF (Somente Números)</label>
                        <input type="text" class="form-control" name="cpf" required minlength="11" maxlength="11" pattern="\d{11}" placeholder="00011122233" title="Digite exatamente 11 números">
                    </div>
                    <div class="row">
                        <div class="col-md-7 mb-3">
                            <label class="form-label text-white-50 small">E-mail</label>
                            <input type="email" class="form-control" name="email" required placeholder="exemplo@email.com">
                        </div>
                        <div class="col-md-5 mb-3">
                            <label class="form-label text-white-50 small">Celular (DDD+9)</label>
                            <input type="text" class="form-control" name="telefone" required minlength="11" maxlength="11" pattern="\d{11}" placeholder="11999998888" title="Digite exatamente 11 números">
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-secondary">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" name="btn_cadastrar" class="btn bg-orange fw-bold">Cadastrar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modalExcluir" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content bg-dark text-white border-danger shadow-lg">
            <div class="modal-header border-secondary">
                <h5 class="modal-title text-danger"><i class="bi bi-exclamation-triangle-fill"></i> Confirmar Exclusão</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center py-4">
                <p class="mb-1">Tem certeza que deseja remover este cliente?</p>
                <h4 id="nomeClienteExcluir" class="text-white fw-bold mt-3">...</h4>
                <p class="text-white-50 small mt-2">Esta ação é irreversível.</p>
            </div>
            <div class="modal-footer border-secondary justify-content-center">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                <a href="#" id="btnConfirmarExclusao" class="btn btn-danger fw-bold">Sim, Excluir</a>
            </div>
        </div>
    </div>
</div>

<script>
    const modalExcluir = document.getElementById('modalExcluir')
    if (modalExcluir) {
        modalExcluir.addEventListener('show.bs.modal', event => {
            const button = event.relatedTarget
            const id = button.getAttribute('data-id')
            const nome = button.getAttribute('data-nome')
            modalExcluir.querySelector('#nomeClienteExcluir').textContent = nome
            modalExcluir.querySelector('#btnConfirmarExclusao').href = 'excluir.php?id=' + id
        })
    }
</script>


<?php include '../../../includes/footer.php'; ?>