<?php
// modulos/livros/acervo.php
// Sobe 2 níveis para achar o config
require_once '../../config/conexao.php';
include '../../includes/header.php';

// --- ALERTAS DE RETORNO ---
if (isset($_GET['msg']) && $_GET['msg'] == 'excluido') {
    echo "<div class='alert alert-success fixed-top m-3 shadow' style='z-index:2000;'>
            <i class='bi bi-check-circle-fill'></i> Livro excluído com sucesso!
            <button type='button' class='btn-close' data-bs-dismiss='alert'></button>
          </div>";
}
if (isset($_GET['msg']) && $_GET['msg'] == 'atualizado') {
    echo "<div class='alert alert-success fixed-top m-3 shadow' style='z-index:2000;'>
            <i class='bi bi-pencil-fill'></i> Livro atualizado com sucesso!
            <button type='button' class='btn-close' data-bs-dismiss='alert'></button>
          </div>";
}
if (isset($_GET['erro_perm'])) {
    echo "<div class='alert alert-danger fixed-top m-3 shadow' style='z-index:2000;'>
            <i class='bi bi-shield-lock-fill'></i> <strong>Acesso Negado:</strong> Somente Gerentes podem editar ou excluir.
            <button type='button' class='btn-close' data-bs-dismiss='alert'></button>
          </div>";
}
if (isset($_GET['erro_db'])) {
    echo "<div class='alert alert-warning fixed-top m-3 shadow' style='z-index:2000;'>
            <i class='bi bi-exclamation-triangle'></i> Não foi possível excluir: Este livro pode estar vinculado a uma venda.
            <button type='button' class='btn-close' data-bs-dismiss='alert'></button>
          </div>";
}

// --- LÓGICA DE CADASTRO (Novo Livro) ---
if (isset($_POST['btn_cadastrar'])) {
    $titulo = filter_input(INPUT_POST, 'titulo', FILTER_SANITIZE_SPECIAL_CHARS);
    $autor  = filter_input(INPUT_POST, 'autor', FILTER_SANITIZE_SPECIAL_CHARS);
    $ano    = filter_input(INPUT_POST, 'ano', FILTER_VALIDATE_INT);
    $preco  = str_replace(',', '.', $_POST['preco']); 
    $estado = filter_input(INPUT_POST, 'estado', FILTER_SANITIZE_SPECIAL_CHARS);
    $func_id = $_SESSION['usuario_id'];

    // --- VALIDAÇÃO DE DADOS ---
    $ano_atual = date('Y');
    
    if ($ano > $ano_atual) {
        echo "<div class='alert alert-warning fixed-top m-3' style='z-index: 2000;'>
                <i class='bi bi-exclamation-triangle'></i> Erro: Ano futuro não permitido.
                <button type='button' class='btn-close' data-bs-dismiss='alert'></button>
              </div>";
    } elseif ($preco < 0) {
        echo "<div class='alert alert-warning fixed-top m-3' style='z-index: 2000;'>
                <i class='bi bi-cash-coin'></i> Erro: Preço negativo.
                <button type='button' class='btn-close' data-bs-dismiss='alert'></button>
              </div>";
    } else {
        try {
            $sql_insert = "INSERT INTO LIVRO (TITULO, AUTOR, ANO, PRECO, ESTADO, CD_FUNCIONARIO) 
                           VALUES (:titulo, :autor, :ano, :preco, :estado, :func_id)";
            $stmt_insert = $pdo->prepare($sql_insert);
            $stmt_insert->bindValue(':titulo', $titulo);
            $stmt_insert->bindValue(':autor', $autor);
            $stmt_insert->bindValue(':ano', $ano);
            $stmt_insert->bindValue(':preco', $preco);
            $stmt_insert->bindValue(':estado', $estado);
            $stmt_insert->bindValue(':func_id', $func_id);
            
            $stmt_insert->execute();
            echo "<script>window.location='acervo.php';</script>";
            exit;

        } catch (PDOException $e) {
            echo "<div class='alert alert-danger'>Erro ao cadastrar: " . $e->getMessage() . "</div>";
        }
    }
}

// --- CONFIGURAÇÃO DA PAGINAÇÃO ---
$itens_por_pagina = 15;
$pagina_atual = filter_input(INPUT_GET, 'pagina', FILTER_VALIDATE_INT) ?? 1;
if (!$pagina_atual || $pagina_atual < 1) { $pagina_atual = 1; }

// --- LÓGICA DE BUSCA ---
$busca = filter_input(INPUT_GET, 'busca', FILTER_SANITIZE_SPECIAL_CHARS);
$termo = "%" . $busca . "%";

try {
    if (!empty($busca)) {
        $sql_count = "SELECT COUNT(*) as total FROM LIVRO 
                      WHERE CD_LIVRO LIKE :t1 OR TITULO LIKE :t2 OR AUTOR LIKE :t3 OR ESTADO LIKE :t4 OR ANO LIKE :t5";
        $stmt_count = $pdo->prepare($sql_count);
        $stmt_count->bindValue(':t1', $termo); $stmt_count->bindValue(':t2', $termo);
        $stmt_count->bindValue(':t3', $termo); $stmt_count->bindValue(':t4', $termo);
        $stmt_count->bindValue(':t5', $termo);
    } else {
        $sql_count = "SELECT COUNT(*) as total FROM LIVRO";
        $stmt_count = $pdo->prepare($sql_count);
    }
    $stmt_count->execute();
    $total_registros = $stmt_count->fetch()['total'];
    $total_paginas = ceil($total_registros / $itens_por_pagina);

    $offset = ($pagina_atual - 1) * $itens_por_pagina;

    if (!empty($busca)) {
        $sql = "SELECT * FROM LIVRO 
                WHERE CD_LIVRO LIKE :t1 OR TITULO LIKE :t2 OR AUTOR LIKE :t3 OR ESTADO LIKE :t4 OR ANO LIKE :t5
                ORDER BY TITULO ASC LIMIT :limite OFFSET :offset";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':t1', $termo); $stmt->bindValue(':t2', $termo);
        $stmt->bindValue(':t3', $termo); $stmt->bindValue(':t4', $termo);
        $stmt->bindValue(':t5', $termo);
    } else {
        $sql = "SELECT * FROM LIVRO ORDER BY CD_LIVRO DESC LIMIT :limite OFFSET :offset";
        $stmt = $pdo->prepare($sql);
    }

    $stmt->bindValue(':limite', $itens_por_pagina, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();
    $livros = $stmt->fetchAll();

} catch (PDOException $e) {
    $livros = [];
    echo "<div class='alert alert-danger'>Erro no sistema: " . $e->getMessage() . "</div>";
}
?>

<!-- HEADER DA PÁGINA -->
<div class="row mb-4 align-items-center">
    <div class="col-md-4">
        <h2 class="text-white display-6">
            <i class="bi bi-book-half text-orange"></i> Acervo
        </h2>
    </div>
    <div class="col-md-4">
        <form method="GET" action="" class="d-flex">
            <div class="input-group">
                <input type="text" name="busca" class="form-control bg-dark text-white border-secondary" 
                       placeholder="Pesquisar..." value="<?php echo $busca; ?>">
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
        <button type="button" class="btn bg-orange fw-bold" data-bs-toggle="modal" data-bs-target="#modalNovoLivro">
            <i class="bi bi-plus-lg"></i> Novo
        </button>
    </div>
</div>

<!-- TABELA DE LIVROS -->
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
                        <?php foreach ($livros as $livro): ?>
                            <tr>
                                <td><small class="text-white small">#<?php echo $livro['CD_LIVRO']; ?></small></td>
                                <td class="fw-bold text-white"><?php echo $livro['TITULO']; ?></td>
                                <td class="text-light"><?php echo $livro['AUTOR']; ?></td>
                                <td class="text-light"><?php echo $livro['ANO']; ?></td>
                                <td class="text-success fw-bold">R$ <?php echo number_format($livro['PRECO'], 2, ',', '.'); ?></td>
                                <td>
                                    <?php 
                                        $cor = 'bg-secondary';
                                        if($livro['ESTADO'] == 'Novo') $cor = 'bg-success';
                                        if($livro['ESTADO'] == 'Usado') $cor = 'bg-warning text-dark';
                                        if($livro['ESTADO'] == 'Velho') $cor = 'bg-danger';
                                    ?>
                                    <span class="badge <?php echo $cor; ?>"><?php echo $livro['ESTADO']; ?></span>
                                </td>
                                
                                <td class="text-end">
                                    <?php if (isset($_SESSION['usuario_nivel']) && $_SESSION['usuario_nivel'] === 'Gerente'): ?>
                                        
                                        <!-- Botão Editar -->
                                        <a href="editar.php?id=<?php echo $livro['CD_LIVRO']; ?>" class="btn btn-sm btn-outline-info me-1">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        
                                        <!-- Botão Excluir -->
                                        <button type="button" 
                                                class="btn btn-sm btn-outline-danger" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#modalExcluir"
                                                data-id="<?php echo $livro['CD_LIVRO']; ?>"
                                                data-titulo="<?php echo $livro['TITULO']; ?>">
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
        <!-- CORREÇÃO AQUI: Trocado text-muted por text-white-50 -->
        <small class="text-white-50">
            Total: <strong><?php echo $total_registros; ?></strong> livros (Pág. <?php echo $pagina_atual; ?> de <?php echo $total_paginas; ?>)
        </small>
        
        <?php if ($total_paginas > 1): ?>
        <nav aria-label="Navegação">
            <ul class="pagination pagination-sm mb-0">
                <li class="page-item <?php echo ($pagina_atual <= 1) ? 'disabled' : ''; ?>">
                    <a class="page-link" href="?pagina=<?php echo $pagina_atual - 1; ?>&busca=<?php echo $busca; ?>"><i class="bi bi-chevron-left"></i></a>
                </li>
                <?php 
                $inicio = max(1, $pagina_atual - 2);
                $fim = min($total_paginas, $pagina_atual + 2);
                if($inicio > 1) { echo '<li class="page-item disabled"><span class="page-link">...</span></li>'; }
                for ($i = $inicio; $i <= $fim; $i++): 
                ?>
                    <li class="page-item <?php echo ($pagina_atual == $i) ? 'active' : ''; ?>">
                        <a class="page-link" href="?pagina=<?php echo $i; ?>&busca=<?php echo $busca; ?>"><?php echo $i; ?></a>
                    </li>
                <?php endfor; ?>
                <?php if($fim < $total_paginas) { echo '<li class="page-item disabled"><span class="page-link">...</span></li>'; } ?>
                <li class="page-item <?php echo ($pagina_atual >= $total_paginas) ? 'disabled' : ''; ?>">
                    <a class="page-link" href="?pagina=<?php echo $pagina_atual + 1; ?>&busca=<?php echo $busca; ?>"><i class="bi bi-chevron-right"></i></a>
                </li>
            </ul>
        </nav>
        <?php endif; ?>
    </div>
</div>

<!-- MODAL NOVO LIVRO -->
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
                            <input type="number" class="form-control" name="ano" required max="<?php echo date('Y'); ?>" placeholder="Máx: <?php echo date('Y'); ?>">
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

<!-- MODAL EXCLUIR -->
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

<!-- SCRIPT JS PARA MODAL EXCLUIR -->
<script>
    const modalExcluir = document.getElementById('modalExcluir')
    if (modalExcluir) {
        modalExcluir.addEventListener('show.bs.modal', event => {
            const button = event.relatedTarget
            const id = button.getAttribute('data-id')
            const titulo = button.getAttribute('data-titulo')
            
            modalExcluir.querySelector('#nomeLivroExcluir').textContent = titulo
            // CAMINHO ABSOLUTO CORRETO PARA A NOVA PASTA
            modalExcluir.querySelector('#btnConfirmarExclusao').href = '/sistema-sebo/modulos/livros/excluir.php?id=' + id
        })
    }
</script>

<?php include '../../includes/footer.php'; ?>