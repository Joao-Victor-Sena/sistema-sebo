<?php
session_start();
require_once '../../config/conexao.php';

$niveis_permitidos = ['Gerente', 'Admin', 'Administrador'];

if (!isset($_SESSION['usuario_nivel']) || !in_array($_SESSION['usuario_nivel'], $niveis_permitidos)) {
    header("Location: acervo.php?erro_perm=1");
    exit;
}

$id_livro = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id_livro) {
    header("Location: acervo.php");
    exit;
}

$erro_msg = '';

if (isset($_POST['btn_atualizar'])) {
    $titulo = filter_input(INPUT_POST, 'titulo', FILTER_SANITIZE_SPECIAL_CHARS);
    $autor  = filter_input(INPUT_POST, 'autor', FILTER_SANITIZE_SPECIAL_CHARS);
    $ano    = filter_input(INPUT_POST, 'ano', FILTER_VALIDATE_INT);
    $preco  = str_replace(',', '.', $_POST['preco']); 
    $estado = filter_input(INPUT_POST, 'estado', FILTER_SANITIZE_SPECIAL_CHARS);
    $ano_atual = date('Y');

    if ($ano > $ano_atual) {
        $erro_msg = "Erro: Ano futuro não permitido.";
    } elseif ($preco < 0) {
        $erro_msg = "Erro: Preço negativo.";
    } else {
        try {
            $sql_update = "UPDATE LIVRO SET TITULO = :titulo, AUTOR = :autor, ANO = :ano, PRECO = :preco, ESTADO = :estado WHERE CD_LIVRO = :id";
            $stmt = $pdo->prepare($sql_update);
            $stmt->execute([
                ':titulo' => $titulo,
                ':autor' => $autor,
                ':ano' => $ano,
                ':preco' => $preco,
                ':estado' => $estado,
                ':id' => $id_livro
            ]);
            header("Location: acervo.php?msg=atualizado");
            exit;
        } catch (PDOException $e) {
            $erro_msg = "Erro ao atualizar: " . $e->getMessage();
        }
    }
}

$dados_livro = [];
try {
    $sql = "SELECT * FROM LIVRO WHERE CD_LIVRO = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':id', $id_livro);
    $stmt->execute();
    $dados_livro = $stmt->fetch();

    if (!$dados_livro) {
        header("Location: acervo.php");
        exit;
    }
} catch (PDOException $e) {
    header("Location: acervo.php?erro_db=1");
    exit;
}

include '../../includes/header.php';
?>

<?php if ($erro_msg): ?>
    <div class='alert alert-warning fixed-top m-3 shadow' style='z-index:2000;'>
        <i class='bi bi-exclamation-triangle'></i> <?= $erro_msg ?>
        <button type='button' class='btn-close' data-bs-dismiss='alert'></button>
    </div>
<?php endif; ?>

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
                        <label class="form-label text-white-50 small">Código do Sistema</label>
                        <input type="text" class="form-control bg-secondary text-dark border-0" value="#<?= $dados_livro['CD_LIVRO']; ?>" disabled>
                    </div>

                    <div class="mb-3">
                        <label for="titulo" class="form-label text-white-50 small">Título da Obra</label>
                        <input type="text" class="form-control" name="titulo" required value="<?= $dados_livro['TITULO']; ?>">
                    </div>

                    <div class="row">
                        <div class="col-md-7 mb-3">
                            <label for="autor" class="form-label text-white-50 small">Autor</label>
                            <input type="text" class="form-control" name="autor" required value="<?= $dados_livro['AUTOR']; ?>">
                        </div>
                        <div class="col-md-5 mb-3">
                            <label for="ano" class="form-label text-white-50 small">Ano Edição</label>
                            <input type="number" class="form-control" name="ano" required max="<?= date('Y'); ?>" value="<?= $dados_livro['ANO']; ?>">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="preco" class="form-label text-white-50 small">Preço (R$)</label>
                            <input type="number" class="form-control" name="preco" min="0" step="0.01" required value="<?= $dados_livro['PRECO']; ?>">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="estado" class="form-label text-white-50 small">Condição</label>
                            <select class="form-control form-select bg-dark text-white border-secondary" name="estado">
                                <option value="Novo" <?= ($dados_livro['ESTADO'] == 'Novo') ? 'selected' : ''; ?>>Novo</option>
                                <option value="Seminovo" <?= ($dados_livro['ESTADO'] == 'Seminovo') ? 'selected' : ''; ?>>Seminovo</option>
                                <option value="Usado" <?= ($dados_livro['ESTADO'] == 'Usado') ? 'selected' : ''; ?>>Usado</option>
                                <option value="Velho" <?= ($dados_livro['ESTADO'] == 'Velho') ? 'selected' : ''; ?>>Velho (Raridade)</option>
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