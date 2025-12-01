<?php
// modulos/livros/editar.php
require_once '../../config/conexao.php';
include '../../includes/header.php';

// --- SEGURANÇA: NÍVEL DE ACESSO ---
// Se não for Gerente, manda de volta para o acervo com erro
if (!isset($_SESSION['usuario_nivel']) || $_SESSION['usuario_nivel'] !== 'Gerente') {
    header("Location: acervo.php?erro_perm=1");
    exit;
}

// 1. VERIFICA SE PASSOU UM ID NA URL
$id_livro = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$id_livro) {
    header("Location: acervo.php"); // Se não tem ID, volta pro acervo
    exit;
}

// 2. BUSCA OS DADOS ATUAIS DO LIVRO (Para preencher o formulário)
$dados_livro = [];
try {
    $sql = "SELECT * FROM LIVRO WHERE CD_LIVRO = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':id', $id_livro);
    $stmt->execute();
    $dados_livro = $stmt->fetch();

    if (!$dados_livro) {
        header("Location: acervo.php"); // ID não existe no banco
        exit;
    }
} catch (PDOException $e) {
    die("Erro ao buscar livro: " . $e->getMessage());
}

// 3. LÓGICA DE ATUALIZAÇÃO (QUANDO CLICA EM SALVAR)
if (isset($_POST['btn_atualizar'])) {
    $titulo = filter_input(INPUT_POST, 'titulo', FILTER_SANITIZE_SPECIAL_CHARS);
    $autor  = filter_input(INPUT_POST, 'autor', FILTER_SANITIZE_SPECIAL_CHARS);
    $ano    = filter_input(INPUT_POST, 'ano', FILTER_VALIDATE_INT);
    $preco  = str_replace(',', '.', $_POST['preco']); 
    $estado = filter_input(INPUT_POST, 'estado', FILTER_SANITIZE_SPECIAL_CHARS);
    
    // Validações (Mesmas do Cadastro)
    $ano_atual = date('Y');

    if ($ano > $ano_atual) {
        echo "<div class='alert alert-warning fixed-top m-3 shadow' style='z-index:2000;'>
                <i class='bi bi-exclamation-triangle'></i> Erro: Ano futuro não permitido.
                <button type='button' class='btn-close' data-bs-dismiss='alert'></button>
              </div>";
    } elseif ($preco < 0) {
        echo "<div class='alert alert-warning fixed-top m-3 shadow' style='z-index:2000;'>
                <i class='bi bi-cash-coin'></i> Erro: Preço negativo.
                <button type='button' class='btn-close' data-bs-dismiss='alert'></button>
              </div>";
    } else {
        try {
            // QUERY DE ATUALIZAÇÃO (UPDATE)
            $sql_update = "UPDATE LIVRO SET 
                           TITULO = :titulo, 
                           AUTOR = :autor, 
                           ANO = :ano, 
                           PRECO = :preco, 
                           ESTADO = :estado 
                           WHERE CD_LIVRO = :id";
            
            $stmt = $pdo->prepare($sql_update);
            $stmt->bindValue(':titulo', $titulo);
            $stmt->bindValue(':autor', $autor);
            $stmt->bindValue(':ano', $ano);
            $stmt->bindValue(':preco', $preco);
            $stmt->bindValue(':estado', $estado);
            $stmt->bindValue(':id', $id_livro);
            $stmt->execute();

            // Redireciona com mensagem de sucesso (podemos criar essa msg no acervo depois)
            header("Location: acervo.php?msg=atualizado");
            exit;

        } catch (PDOException $e) {
            echo "<div class='alert alert-danger fixed-top m-3'>Erro ao atualizar: " . $e->getMessage() . "</div>";
        }
    }
}
?>

<div class="row justify-content-center">
    <div class="col-md-8 col-lg-6">
        
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="text-white">
                <i class="bi bi-pencil-square text-orange"></i> Editar Livro
            </h2>
            <a href="acervo.php" class="btn btn-outline-light">
                <i class="bi bi-arrow-left"></i> Voltar
            </a>
        </div>

        <div class="card bg-dark border-secondary shadow-lg">
            <div class="card-body p-4">
                
                <form method="POST" action="">
                    <div class="mb-3">
                        <label class="form-label text-muted small">Código do Sistema</label>
                        <input type="text" class="form-control bg-secondary text-dark border-0" 
                               value="#<?php echo $dados_livro['CD_LIVRO']; ?>" disabled>
                    </div>

                    <div class="mb-3">
                        <label for="titulo" class="form-label text-muted small">Título da Obra</label>
                        <input type="text" class="form-control" name="titulo" required 
                               value="<?php echo $dados_livro['TITULO']; ?>">
                    </div>

                    <div class="row">
                        <div class="col-md-7 mb-3">
                            <label for="autor" class="form-label text-muted small">Autor</label>
                            <input type="text" class="form-control" name="autor" required 
                                   value="<?php echo $dados_livro['AUTOR']; ?>">
                        </div>
                        <div class="col-md-5 mb-3">
                            <label for="ano" class="form-label text-muted small">Ano Edição</label>
                            <input type="number" class="form-control" name="ano" required 
                                   max="<?php echo date('Y'); ?>" 
                                   value="<?php echo $dados_livro['ANO']; ?>">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="preco" class="form-label text-muted small">Preço (R$)</label>
                            <input type="number" class="form-control" name="preco" min="0" step="0.01" required 
                                   value="<?php echo $dados_livro['PRECO']; ?>">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="estado" class="form-label text-muted small">Condição</label>
                            <select class="form-control form-select bg-dark text-white border-secondary" name="estado">
                                <option value="Novo" <?php echo ($dados_livro['ESTADO'] == 'Novo') ? 'selected' : ''; ?>>Novo</option>
                                <option value="Seminovo" <?php echo ($dados_livro['ESTADO'] == 'Seminovo') ? 'selected' : ''; ?>>Seminovo</option>
                                <option value="Usado" <?php echo ($dados_livro['ESTADO'] == 'Usado') ? 'selected' : ''; ?>>Usado</option>
                                <option value="Velho" <?php echo ($dados_livro['ESTADO'] == 'Velho') ? 'selected' : ''; ?>>Velho (Raridade)</option>
                            </select>
                        </div>
                    </div>

                    <div class="d-grid gap-2 mt-4">
                        <button type="submit" name="btn_atualizar" class="btn bg-orange fw-bold btn-lg">
                            <i class="bi bi-check-lg"></i> Salvar Alterações
                        </button>
                    </div>
                </form>

            </div>
        </div>

    </div>
</div>

<?php include '../../includes/footer.php'; ?>