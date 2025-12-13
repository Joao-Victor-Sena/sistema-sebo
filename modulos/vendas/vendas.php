<?php
session_start();
require_once '../../config/conexao.php';

$niveis_exclusao = ['Gerente', 'Admin', 'Administrador'];

$itens_por_pagina = 10;
$pagina_atual = filter_input(INPUT_GET, 'pagina', FILTER_VALIDATE_INT) ?? 1;
$busca = filter_input(INPUT_GET, 'busca', FILTER_SANITIZE_SPECIAL_CHARS);
$termo = "%" . $busca . "%";
$offset = ($pagina_atual - 1) * $itens_por_pagina;

try {
    if (!empty($busca)) {
        $sql_count = "SELECT COUNT(*) as total FROM COMPRA c 
                      JOIN CLIENTE cl ON c.CD_CLIENTE = cl.CD_CLIENTE 
                      WHERE cl.NOME LIKE :b OR c.CD_COMPRA LIKE :b";
        $sql = "SELECT c.*, cl.NOME as NOME_CLIENTE FROM COMPRA c 
                JOIN CLIENTE cl ON c.CD_CLIENTE = cl.CD_CLIENTE 
                WHERE cl.NOME LIKE :b OR c.CD_COMPRA LIKE :b 
                ORDER BY c.DATA DESC, c.HORA DESC LIMIT :lim OFFSET :off";
        $params = [':b' => $termo];
    } else {
        $sql_count = "SELECT COUNT(*) as total FROM COMPRA";
        $sql = "SELECT c.*, cl.NOME as NOME_CLIENTE FROM COMPRA c 
                JOIN CLIENTE cl ON c.CD_CLIENTE = cl.CD_CLIENTE 
                ORDER BY c.DATA DESC, c.HORA DESC LIMIT :lim OFFSET :off";
        $params = [];
    }

    $stmt_count = $pdo->prepare($sql_count);
    if(!empty($busca)) $stmt_count->bindValue(':b', $termo);
    $stmt_count->execute();
    $total_registros = $stmt_count->fetch()['total'];
    $total_paginas = ceil($total_registros / $itens_por_pagina);

    $stmt = $pdo->prepare($sql);
    if(!empty($busca)) $stmt->bindValue(':b', $termo);
    $stmt->bindValue(':lim', $itens_por_pagina, PDO::PARAM_INT);
    $stmt->bindValue(':off', $offset, PDO::PARAM_INT);
    $stmt->execute();
    $vendas = $stmt->fetchAll();

} catch (PDOException $e) {
    $erro_msg = "Erro no banco: " . $e->getMessage();
    $vendas = [];
}

include '../../includes/header.php';
?>

<?php if (isset($_GET['msg']) && $_GET['msg'] == 'cadastrado'): ?>
    <div class='alert alert-success fixed-top m-3 shadow'>
        <i class='bi bi-check-lg'></i> Venda registrada com sucesso!
        <button type='button' class='btn-close' data-bs-dismiss='alert'></button>
    </div>
<?php endif; ?>

<div class="row mb-4 align-items-center">
    <div class="col-md-4">
        <h2 class="text-white display-6">
            <i class="bi bi-cart-check-fill text-orange"></i> Vendas
        </h2>
    </div>
    <div class="col-md-4">
        <form method="GET" action="" class="d-flex">
            <div class="input-group">
                <input type="text" name="busca" class="form-control bg-dark text-white border-secondary" placeholder="ID da Venda ou Cliente..." value="<?= $busca; ?>">
                <button class="btn btn-outline-orange" type="submit"><i class="bi bi-search"></i></button>
            </div>
        </form>
    </div>
    <div class="col-md-4 text-end">
        <a href="../../index.php" class="btn btn-outline-light me-2"><i class="bi bi-arrow-left"></i> Voltar</a>
        <a href="nova.php" class="btn bg-orange fw-bold">
            <i class="bi bi-plus-lg"></i> Nova Venda
        </a>
    </div>
</div>

<div class="card bg-dark border-secondary shadow">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-dark table-hover mb-0 align-middle">
                <thead>
                    <tr class="text-orange">
                        <th>#ID</th>
                        <th>Data / Hora</th>
                        <th>Cliente</th>
                        <th>Total</th>
                        <th class="text-end">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($vendas) > 0): ?>
                        <?php foreach ($vendas as $v): ?>
                            <tr>
                                <td><small class="text-white-50">#<?= $v['CD_COMPRA']; ?></small></td>
                                <td>
                                    <div><?= date('d/m/Y', strtotime($v['DATA'])); ?></div>
                                    <small class="text-white-50"><?= substr($v['HORA'], 0, 5); ?></small>
                                </td>
                                <td class="fw-bold"><?= $v['NOME_CLIENTE']; ?></td>
                                <td class="text-success fw-bold">R$ <?= number_format($v['TOTAL'], 2, ',', '.'); ?></td>
                                <td class="text-end">
                                    <a href="detalhes.php?id=<?= $v['CD_COMPRA']; ?>" class="btn btn-sm btn-outline-info me-1" title="Ver Detalhes">
                                        <i class="bi bi-eye"></i>
                                    </a>

                                    <?php if (isset($_SESSION['usuario_nivel']) && in_array($_SESSION['usuario_nivel'], $niveis_exclusao)): ?>
                                        <button type="button" class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#modalExcluir" data-id="<?= $v['CD_COMPRA']; ?>">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="5" class="text-center py-5">Nenhuma venda encontrada.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
    
    <div class="card-footer bg-dark border-secondary d-flex justify-content-between align-items-center">
        <small class="text-white-50">
            Total: <strong><?= $total_registros; ?></strong> vendas (Pág. <?= $pagina_atual; ?> de <?= $total_paginas > 0 ? $total_paginas : 1; ?>)
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
                for ($i = $inicio; $i <= $fim; $i++): 
                ?>
                    <li class="page-item <?= ($pagina_atual == $i) ? 'active' : ''; ?>">
                        <a class="page-link" href="?pagina=<?= $i; ?>&busca=<?= $busca; ?>"><?= $i; ?></a>
                    </li>
                <?php endfor; ?>
                <li class="page-item <?= ($pagina_atual >= $total_paginas) ? 'disabled' : ''; ?>">
                    <a class="page-link" href="?pagina=<?= $pagina_atual + 1; ?>&busca=<?= $busca; ?>"><i class="bi bi-chevron-right"></i></a>
                </li>
            </ul>
        </nav>
        <?php endif; ?>
    </div>
</div>

<div class="modal fade" id="modalExcluir" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content bg-dark text-white border-danger">
            <div class="modal-header border-secondary">
                <h5 class="modal-title text-danger">Excluir Venda</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center">
                <p>Deseja excluir a Venda <strong id="idVendaExcluir"></strong>?</p>
                <small class="text-white-50">Os itens da venda também serão removidos.</small>
            </div>
            <div class="modal-footer border-secondary justify-content-center">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                <a href="#" id="btnConfirmarExclusao" class="btn btn-danger">Excluir</a>
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
            modalExcluir.querySelector('#idVendaExcluir').textContent = '#' + id
            modalExcluir.querySelector('#btnConfirmarExclusao').href = 'excluir.php?id=' + id
        })
    }
</script>


<?php include '../../includes/footer.php'; ?>