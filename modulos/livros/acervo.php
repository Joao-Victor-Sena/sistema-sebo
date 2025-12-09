<?php
session_start();
require_once '../../config/conexao.php';

$niveis_admin = ['Gerente', 'Admin', 'Administrador'];

if (isset($_POST['btn_cadastrar'])) {

    if (!isset($_SESSION['usuario_id'])) {
        header("Location: ../../index.php?erro=login");
        exit;
    }

    $titulo = filter_input(INPUT_POST, 'titulo', FILTER_SANITIZE_SPECIAL_CHARS);
    $autor  = filter_input(INPUT_POST, 'autor', FILTER_SANITIZE_SPECIAL_CHARS);
    $ano    = filter_input(INPUT_POST, 'ano', FILTER_VALIDATE_INT);
    $preco  = str_replace(',', '.', $_POST['preco']); 
    $estado = filter_input(INPUT_POST, 'estado', FILTER_SANITIZE_SPECIAL_CHARS);
    $func_id = $_SESSION['usuario_id'];
    $ano_atual = date('Y');
    
    if ($ano > $ano_atual) {
        $erro_msg = "Erro: Ano futuro não permitido.";
    } elseif ($preco < 0) {
        $erro_msg = "Erro: Preço negativo.";
    } else {
        try {
            $sql = "INSERT INTO LIVRO (TITULO, AUTOR, ANO, PRECO, ESTADO, CD_FUNCIONARIO) VALUES (:titulo, :autor, :ano, :preco, :estado, :func_id)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':titulo' => $titulo,
                ':autor' => $autor,
                ':ano' => $ano,
                ':preco' => $preco,
                ':estado' => $estado,
                ':func_id' => $func_id
            ]);
            echo "<script>window.location='acervo.php?msg=cadastrado';</script>";
            exit;
        } catch (PDOException $e) {
            $erro_msg = "Erro ao cadastrar: " . $e->getMessage();
        }
    }
}

$itens_por_pagina = 15;
$pagina_atual = filter_input(INPUT_GET, 'pagina', FILTER_VALIDATE_INT) ?? 1;
if ($pagina_atual < 1) $pagina_atual = 1;

$busca = filter_input(INPUT_GET, 'busca', FILTER_SANITIZE_SPECIAL_CHARS);
$termo = "%" . $busca . "%";

try {
    if (!empty($busca)) {
        $sql_count = "SELECT COUNT(*) as total FROM LIVRO WHERE CD_LIVRO LIKE :t OR TITULO LIKE :t OR AUTOR LIKE :t OR ESTADO LIKE :t OR ANO LIKE :t";
        $stmt_count = $pdo->prepare($sql_count);
        $stmt_count->bindValue(':t', $termo);
    } else {
        $sql_count = "SELECT COUNT(*) as total FROM LIVRO";
        $stmt_count = $pdo->prepare($sql_count);
    }
    $stmt_count->execute();
    $total_registros = $stmt_count->fetch()['total'];
    $total_paginas = ceil($total_registros / $itens_por_pagina);
    $offset = ($pagina_atual - 1) * $itens_por_pagina;

    if (!empty($busca)) {
        $sql = "SELECT * FROM LIVRO WHERE CD_LIVRO LIKE :t OR TITULO LIKE :t OR AUTOR LIKE :t OR ESTADO LIKE :t OR ANO LIKE :t ORDER BY TITULO ASC LIMIT :lim OFFSET :off";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':t', $termo);
    } else {
        $sql = "SELECT * FROM LIVRO ORDER BY CD_LIVRO DESC LIMIT :lim OFFSET :off";
        $stmt = $pdo->prepare($sql);
    }
    $stmt->bindValue(':lim', $itens_por_pagina, PDO::PARAM_INT);
    $stmt->bindValue(':off', $offset, PDO::PARAM_INT);
    $stmt->execute();
    $livros = $stmt->fetchAll();
} catch (PDOException $e) {
    $livros = [];
    $erro_msg = "Erro no sistema: " . $e->getMessage();
}

include '../../includes/header.php';
?>

<?php if (isset($_GET['msg'])): ?>
    <div class='alert alert-success fixed-top m-3 shadow' style='z-index:2000;'>
        <?php if($_GET['msg'] == 'excluido'): ?>
            <i class='bi bi-check-circle-fill'></i> Livro excluído com sucesso!
        <?php elseif($_GET['msg'] == 'atualizado'): ?>
            <i class='bi bi-pencil-fill'></i> Livro atualizado com sucesso!
        <?php elseif($_GET['msg'] == 'cadastrado'): ?>
            <i class='bi bi-check-lg'></i> Livro cadastrado com sucesso!
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

<?php if (isset($erro_msg)): ?>
    <div class='alert alert-warning fixed-top m-3 shadow' style='z-index:2000;'>
        <i class='bi bi-exclamation-triangle'></i> <?= $erro_msg ?>
        <button type='button' class='btn-close' data-bs-dismiss='alert'></button>
    </div>
<?php endif; ?>

<div class="row mb-4 align-items-center">
    <div class="col-md-4">
        <h2 class="text-white display-6">
            <i class="bi bi-book-half text-orange"></i> Acervo
        </h2>
    </div>
    <div class="col-md-4">
        <form method="GET" action="" class="d-flex">
            <div class="input-group">
                <input type="text" name="busca" class="form-control bg-dark text-white border-secondary" placeholder="Pesquisar..." value="<?= $busca; ?>">
                <button class="btn btn-outline-orange" type="submit">
                    <i class="bi bi-search"></i>
                </button>
                <?php if(!empty($busca)): ?>
                    <a href="acervo.php" class="btn btn-outline-secondary"><i class="bi bi-x-lg"></i></a>
                <?php endif; ?>
            </div>
        </form>
    </div>
    <div class="col-md-4 text-end">
        <a href="../../index.php" class="btn btn-outline-light me-2"><i class="bi bi-arrow-left"></i> Voltar</a>
        
        <?php if (isset($_SESSION['usuario_id'])): ?>
            <button type="button" class="btn bg-orange fw-bold" data-bs-toggle="modal" data-bs-target="#modalNovoLivro">
                <i class="bi bi-plus-lg"></i> Novo
            </button>
        <?php endif; ?>
    </div>
</div>

<div class="card bg-dark border-secondary shadow">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-dark table-hover mb-0 align-middle">
                <thead>
                    <tr class="text-orange">
                        <th>Cód.</th>
                        <th>Título</th>
                        <th>Autor</th>
                        <th>Ano</th>
                        <th>Preço</th>
                        <th>Estado</th>
                        <th class="text-end">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($livros) > 0): ?>
                        <?php 
                        $cores_estado = [
                            'Novo' => 'bg-success',
                            'Seminovo' => 'bg-primary',
                            'Usado' => 'bg-warning text-dark',
                            'Velho' => 'bg-danger'
                        ];

                        foreach ($livros as $livro): 
                            $badge_cor = $cores_estado[$livro['ESTADO']] ?? 'bg-secondary';
                        ?>
                            <tr>
                                <td><small class="text-white small">#<?= $livro['CD_LIVRO']; ?></small></td>
                                <td class="fw-bold text-white"><?= $livro['TITULO']; ?></td>
                                <td class="text-light"><?= $livro['AUTOR']; ?></td>
                                <td class="text-light"><?= $livro['ANO']; ?></td>
                                <td class="text-success fw-bold">R$ <?= number_format($livro['PRECO'], 2, ',', '.'); ?></td>
                                <td><span class="badge <?= $badge_cor; ?>"><?= $livro['ESTADO']; ?></span></td>
                                <td class="text-end">
                                    
                                    <?php 
                                    if (isset($_SESSION['usuario_nivel']) && in_array($_SESSION['usuario_nivel'], $niveis_admin)): 
                                    ?>
                                        <a href="editar.php?id=<?= $livro['CD_LIVRO']; ?>" class="btn btn-sm btn-outline-info me-1">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <button type="button" class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#modalExcluir" data-id="<?= $livro['CD_LIVRO']; ?>" data-titulo="<?= $livro['TITULO']; ?>">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    <?php else: ?>
                                        <span class="text-muted small" title="Apenas Admin pode alterar"><i class="bi bi-lock-fill"></i></span>
                                    <?php endif; ?>

                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" class="text-center py-5 text-white">
                                <h4 class="fw-light">Nenhum livro encontrado.</h4>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
    
    <div class="card-footer bg-dark border-secondary d-flex justify-content-between align-items-center">
        <small class="text-white-50">
            Total: <strong><?= $total_registros; ?></strong> livros (Pág. <?= $pagina_atual; ?> de <?= $total_paginas > 0 ? $total_paginas : 1; ?>)
        </small>
        
        <?php if ($total_paginas > 1): ?>
        <nav aria-label="Navegação">
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

<div class="modal fade" id="modalNovoLivro" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content bg-dark text-white border-secondary shadow-lg">
            <div class="modal-header border-secondary">
                <h5 class="modal-title"><i class="bi bi-book-half text-orange"></i> Novo Livro</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label text-white-50 small">Título da Obra</label>
                        <input type="text" class="form-control" name="titulo" required placeholder="Ex: Dom Casmurro">
                    </div>
                    <div class="row">
                        <div class="col-md-7 mb-3">
                            <label class="form-label text-white-50 small">Autor</label>
                            <input type="text" class="form-control" name="autor" required placeholder="Ex: Machado de Assis">
                        </div>
                        <div class="col-md-5 mb-3">
                            <label class="form-label text-white-50 small">Ano Edição</label>
                            <input type="number" class="form-control" name="ano" required max="<?= date('Y'); ?>" placeholder="Máx: <?= date('Y'); ?>">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-white-50 small">Preço (R$)</label>
                            <input type="number" class="form-control" name="preco" min="0" step="0.01" required placeholder="0.00">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-white-50 small">Condição</label>
                            <select class="form-control form-select bg-dark text-white border-secondary" name="estado">
                                <option value="Novo">Novo</option>
                                <option value="Seminovo" selected>Seminovo</option>
                                <option value="Usado">Usado</option>
                                <option value="Velho">Velho (Raridade)</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-secondary">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" name="btn_cadastrar" class="btn bg-orange fw-bold">Salvar Livro</button>
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
                <p class="mb-1">Você tem certeza que deseja remover este livro?</p>
                <h4 id="nomeLivroExcluir" class="text-white fw-bold mt-3">...</h4>
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
            const titulo = button.getAttribute('data-titulo')
            
            modalExcluir.querySelector('#nomeLivroExcluir').textContent = titulo
            modalExcluir.querySelector('#btnConfirmarExclusao').href = 'excluir.php?id=' + id
        })
    }
</script>

<?php include '../../includes/footer.php'; ?>