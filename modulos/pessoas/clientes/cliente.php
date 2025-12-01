<?php
// pessoas/clientes/cliente.php
require_once '../../../config/conexao.php';
include '../../../includes/header.php';

// --- ALERTAS DE RETORNO ---
if (isset($_GET['msg']) && $_GET['msg'] == 'excluido') {
    echo "<div class='alert alert-success fixed-top m-3 shadow' style='z-index:2000;'>
            <i class='bi bi-check-circle-fill'></i> Cliente excluído com sucesso!
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
    // Tratamento para mensagem amigável se tiver FK presa
    echo "<div class='alert alert-warning fixed-top m-3 shadow' style='z-index:2000;'>
            <i class='bi bi-exclamation-triangle'></i> Não foi possível excluir: Cliente possui histórico de compras.
            <button type='button' class='btn-close' data-bs-dismiss='alert'></button>
          </div>";
} // <--- CORREÇÃO: ADICIONADA A CHAVE QUE FALTAVA

// --- LÓGICA DE CADASTRO (Novo Cliente) ---
if (isset($_POST['btn_cadastrar'])) {
    $nome  = filter_input(INPUT_POST, 'nome', FILTER_SANITIZE_SPECIAL_CHARS);
    $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
    
    // 1. LIMPEZA: Remove tudo que não for número
    $cpf      = preg_replace('/[^0-9]/', '', $_POST['cpf']);
    $telefone = preg_replace('/[^0-9]/', '', $_POST['telefone']);

    // 2. VALIDAÇÃO RIGOROSA DE TAMANHO
    if (strlen($cpf) !== 11) {
        echo "<div class='alert alert-warning fixed-top m-3 shadow' style='z-index:2000;'>
                <i class='bi bi-exclamation-triangle'></i> Erro: O CPF deve conter exatamente 11 números.
                <button type='button' class='btn-close' data-bs-dismiss='alert'></button>
              </div>";
    } elseif (strlen($telefone) !== 11) {
        echo "<div class='alert alert-warning fixed-top m-3 shadow' style='z-index:2000;'>
                <i class='bi bi-phone'></i> Erro: O Telefone deve conter 11 números (DDD + 9 dígitos).
                <button type='button' class='btn-close' data-bs-dismiss='alert'></button>
              </div>";
    } else {
        // Validação de duplicidade
        $stmt_check = $pdo->prepare("SELECT CD_CLIENTE FROM CLIENTE WHERE CPF = :cpf");
        $stmt_check->execute([':cpf' => $cpf]);

        if ($stmt_check->rowCount() > 0) {
            echo "<div class='alert alert-warning fixed-top m-3' style='z-index: 2000;'>
                    <i class='bi bi-person-x'></i> Erro: CPF já cadastrado.
                    <button type='button' class='btn-close' data-bs-dismiss='alert'></button>
                  </div>";
        } else {
            try {
                $sql_insert = "INSERT INTO CLIENTE (NOME, CPF, EMAIL, TELEFONE) VALUES (:nome, :cpf, :email, :tel)";
                $stmt = $pdo->prepare($sql_insert);
                $stmt->execute([':nome' => $nome, ':cpf' => $cpf, ':email' => $email, ':tel' => $telefone]);
                
                echo "<script>window.location='cliente.php';</script>";
                exit;
            } catch (PDOException $e) {
                echo "<div class='alert alert-danger'>Erro: " . $e->getMessage() . "</div>";
            }
        }
    }
}

// --- CONFIGURAÇÃO DA PAGINAÇÃO ---
$itens_por_pagina = 10;
$pagina_atual = filter_input(INPUT_GET, 'pagina', FILTER_VALIDATE_INT) ?? 1;
if (!$pagina_atual || $pagina_atual < 1) { $pagina_atual = 1; }

// --- LÓGICA DE BUSCA ---
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
        $sql = "SELECT * FROM CLIENTE 
                WHERE NOME LIKE :b OR CPF LIKE :b OR EMAIL LIKE :b
                ORDER BY NOME ASC LIMIT :limite OFFSET :offset";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':b', $termo);
    } else {
        $sql = "SELECT * FROM CLIENTE ORDER BY NOME ASC LIMIT :limite OFFSET :offset";
        $stmt = $pdo->prepare($sql);
    }

    $stmt->bindValue(':limite', $itens_por_pagina, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();
    $clientes = $stmt->fetchAll();

} catch (PDOException $e) {
    $clientes = [];
    echo "<div class='alert alert-danger'>Erro no sistema: " . $e->getMessage() . "</div>";
}
?>

<div class="row mb-4 align-items-center">
    <div class="col-md-4">
        <h2 class="text-white display-6">
            <i class="bi bi-people-fill text-orange"></i> Clientes
        </h2>
    </div>
    <div class="col-md-4">
        <form method="GET" action="" class="d-flex">
            <div class="input-group">
                <input type="text" name="busca" class="form-control bg-dark text-white border-secondary" 
                       placeholder="Buscar Nome ou CPF..." value="<?php echo $busca; ?>">
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
                                <td><small class="text-white small">#<?php echo $cli['CD_CLIENTE']; ?></small></td>
                                <td class="fw-bold text-white"><?php echo $cli['NOME']; ?></td>
                                <td class="text-light"><?php echo $cli['CPF']; ?></td>
                                <td>
                                    <div class="text-white"><?php echo $cli['EMAIL']; ?></div>
                                    <div class="small text-white-50"><?php echo $cli['TELEFONE']; ?></div>
                                </td>
                                
                                <td class="text-end">
                                    <?php if (isset($_SESSION['usuario_nivel']) && $_SESSION['usuario_nivel'] === 'Gerente'): ?>
                                        
                                        <a href="editar.php?id=<?php echo $cli['CD_CLIENTE']; ?>" class="btn btn-sm btn-outline-info me-1">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        
                                        <button type="button" 
                                                class="btn btn-sm btn-outline-danger" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#modalExcluir"
                                                data-id="<?php echo $cli['CD_CLIENTE']; ?>"
                                                data-nome="<?php echo $cli['NOME']; ?>">
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
        <small class="text-muted">
            Total: <strong><?php echo $total_registros; ?></strong> (Pág. <?php echo $pagina_atual; ?> de <?php echo $total_paginas; ?>)
        </small>
        
        <?php if ($total_paginas > 1): ?>
        <nav>
            <ul class="pagination pagination-sm mb-0">
                <li class="page-item <?php echo ($pagina_atual <= 1) ? 'disabled' : ''; ?>">
                    <a class="page-link" href="?pagina=<?php echo $pagina_atual - 1; ?>&busca=<?php echo $busca; ?>"><i class="bi bi-chevron-left"></i></a>
                </li>
                <?php 
                $inicio = max(1, $pagina_atual - 2);
                $fim = min($total_paginas, $pagina_atual + 2);
                for ($i = $inicio; $i <= $fim; $i++): 
                ?>
                    <li class="page-item <?php echo ($pagina_atual == $i) ? 'active' : ''; ?>">
                        <a class="page-link" href="?pagina=<?php echo $i; ?>&busca=<?php echo $busca; ?>"><?php echo $i; ?></a>
                    </li>
                <?php endfor; ?>
                <li class="page-item <?php echo ($pagina_atual >= $total_paginas) ? 'disabled' : ''; ?>">
                    <a class="page-link" href="?pagina=<?php echo $pagina_atual + 1; ?>&busca=<?php echo $busca; ?>"><i class="bi bi-chevron-right"></i></a>
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
                        <label class="form-label text-muted small">Nome Completo</label>
                        <input type="text" class="form-control" name="nome" required placeholder="Ex: Maria Silva">
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-muted small">CPF (Somente Números)</label>
                        <input type="text" class="form-control" name="cpf" 
                               required minlength="11" maxlength="11" pattern="\d{11}"
                               placeholder="00011122233" title="Digite exatamente 11 números">
                    </div>
                    <div class="row">
                        <div class="col-md-7 mb-3">
                            <label class="form-label text-muted small">E-mail</label>
                            <input type="email" class="form-control" name="email" required placeholder="exemplo@email.com">
                        </div>
                        <div class="col-md-5 mb-3">
                            <label class="form-label text-muted small">Celular (DDD+9)</label>
                            <input type="text" class="form-control" name="telefone" 
                                   required minlength="11" maxlength="11" pattern="\d{11}"
                                   placeholder="11999998888" title="Digite exatamente 11 números">
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
                <p class="text-muted small mt-2">Esta ação é irreversível.</p>
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
            // CAMINHO ABSOLUTO PARA EXCLUIR.PHP DENTRO DE CLIENTES
            modalExcluir.querySelector('#btnConfirmarExclusao').href = '/sistema-sebo/pessoas/clientes/excluir.php?id=' + id
        })
    }
</script>

<?php include '../../../includes/footer.php'; ?>