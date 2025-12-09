<?php
session_start();
require_once '../../../config/conexao.php';

$niveis_permitidos = ['Gerente', 'Admin', 'Administrador'];

if (!isset($_SESSION['usuario_nivel']) || !in_array($_SESSION['usuario_nivel'], $niveis_permitidos)) {
    header("Location: funcionarios.php?erro_perm=1");
    exit;
}

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id) {
    header("Location: funcionarios.php");
    exit;
}

$erro_msg = '';

if (isset($_POST['btn_atualizar'])) {
    $nome   = filter_input(INPUT_POST, 'nome', FILTER_SANITIZE_SPECIAL_CHARS);
    $cpf    = preg_replace('/[^0-9]/', '', $_POST['cpf']); 
    $funcao = filter_input(INPUT_POST, 'funcao', FILTER_SANITIZE_SPECIAL_CHARS);
    $resetar_senha = isset($_POST['reset_senha']); 

    if (strlen($cpf) !== 11) {
        $erro_msg = "Erro: O CPF deve conter exatamente 11 números.";
    } elseif (empty($funcao)) {
        $erro_msg = "Erro: Selecione uma função.";
    } else {
        $stmt_check = $pdo->prepare("SELECT CD_FUNCIONARIO FROM FUNCIONARIO WHERE CPF = :cpf AND CD_FUNCIONARIO != :id");
        $stmt_check->execute([':cpf' => $cpf, ':id' => $id]);

        if ($stmt_check->rowCount() > 0) {
            $erro_msg = "Erro: Este CPF já pertence a outro funcionário.";
        } else {
            try {
                $sql = "UPDATE FUNCIONARIO SET NOME = :nome, CPF = :cpf, FUNCAO = :funcao";
                if ($resetar_senha) {
                    $sql .= ", SENHA = '1234'"; 
                }
                
                $sql .= " WHERE CD_FUNCIONARIO = :id";

                $stmt = $pdo->prepare($sql);
                $stmt->execute([
                    ':nome'   => $nome,
                    ':cpf'    => $cpf,
                    ':funcao' => $funcao,
                    ':id'     => $id
                ]);

                header("Location: funcionarios.php?msg=atualizado");
                exit;
            } catch (PDOException $e) {
                $erro_msg = "Erro ao atualizar: " . $e->getMessage();
            }
        }
    }
}

try {
    $sql = "SELECT * FROM FUNCIONARIO WHERE CD_FUNCIONARIO = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':id' => $id]);
    $dados = $stmt->fetch();

    if (!$dados) {
        header("Location: funcionarios.php");
        exit;
    }
} catch (PDOException $e) {
    header("Location: funcionarios.php?erro_db=1");
    exit;
}

include '../../../includes/header.php';
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
                <i class="bi bi-pencil-square text-orange"></i> Editar Funcionário
            </h2>
            <a href="funcionarios.php" class="btn btn-outline-light">
                <i class="bi bi-arrow-left"></i> Voltar
            </a>
        </div>

        <div class="card bg-dark border-secondary shadow-lg">
            <div class="card-body p-4">
                <form method="POST" action="">
                    
                    <div class="mb-3">
                        <label class="form-label text-white-50 small">Código</label>
                        <input type="text" class="form-control bg-secondary text-dark border-0" value="#<?= $dados['CD_FUNCIONARIO']; ?>" disabled>
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-white-50 small">Nome Completo</label>
                        <input type="text" class="form-control" name="nome" required value="<?= $dados['NOME']; ?>">
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-white-50 small">CPF</label>
                        <input type="text" class="form-control" name="cpf" required minlength="11" maxlength="11" pattern="\d{11}" value="<?= $dados['CPF']; ?>">
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-white-50 small">Função / Cargo</label>
                        <select class="form-select bg-dark text-white border-secondary" name="funcao" required>
                            <option value="Gerente" <?= ($dados['FUNCAO'] == 'Gerente') ? 'selected' : ''; ?>>Gerente</option>
                            <option value="Vendedora" <?= ($dados['FUNCAO'] == 'Vendedora') ? 'selected' : ''; ?>>Vendedora</option>
                            <option value="Caixa" <?= ($dados['FUNCAO'] == 'Caixa') ? 'selected' : ''; ?>>Caixa</option>
                            <option value="Estoquista" <?= ($dados['FUNCAO'] == 'Estoquista') ? 'selected' : ''; ?>>Estoquista</option>
                            <option value="Admin" <?= ($dados['FUNCAO'] == 'Admin') ? 'selected' : ''; ?>>Administrador</option>
                        </select>
                    </div>

                    <hr class="border-secondary my-4">

                    <div class="form-check form-switch mb-3">
                        <input class="form-check-input" type="checkbox" name="reset_senha" id="resetSenha">
                        <label class="form-check-label text-white" for="resetSenha">
                            Redefinir senha para padrão (<strong>1234</strong>)
                        </label>
                        <div class="form-text text-white-50 small">Marque esta opção apenas se o funcionário esqueceu a senha.</div>
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

<?php include '../../../includes/footer.php'; ?>