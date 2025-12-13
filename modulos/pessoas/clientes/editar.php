<?php
session_start();
require_once '../../../config/conexao.php';

$niveis_permitidos = ['Gerente', 'Admin', 'Administrador'];

if (!isset($_SESSION['usuario_nivel']) || !in_array($_SESSION['usuario_nivel'], $niveis_permitidos)) {
    header("Location: cliente.php?erro_perm=1");
    exit;
}

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id) {
    header("Location: cliente.php");
    exit;
}

$erro_msg = '';

if (isset($_POST['btn_atualizar'])) {
    $nome  = filter_input(INPUT_POST, 'nome', FILTER_SANITIZE_SPECIAL_CHARS);
    $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
    $cpf   = preg_replace('/[^0-9]/', '', $_POST['cpf']);
    $tel   = preg_replace('/[^0-9]/', '', $_POST['telefone']);

    if (strlen($cpf) !== 11) {
        $erro_msg = "Erro: O CPF deve conter exatamente 11 números.";
    } elseif (strlen($tel) !== 11) {
        $erro_msg = "Erro: O Telefone deve conter 11 números.";
    } else {
        try {
            $sql = "UPDATE CLIENTE SET NOME = :nome, CPF = :cpf, EMAIL = :email, TELEFONE = :tel WHERE CD_CLIENTE = :id";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':nome'  => $nome,
                ':cpf'   => $cpf,
                ':email' => $email,
                ':tel'   => $tel,
                ':id'    => $id
            ]);
            header("Location: cliente.php?msg=atualizado");
            exit;
        } catch (PDOException $e) {
            $erro_msg = "Erro ao atualizar (Verifique se o CPF já existe em outro cadastro).";
        }
    }
}

try {
    $sql = "SELECT * FROM CLIENTE WHERE CD_CLIENTE = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':id' => $id]);
    $dados = $stmt->fetch();

    if (!$dados) {
        header("Location: cliente.php");
        exit;
    }
} catch (PDOException $e) {
    header("Location: cliente.php?erro_db=1");
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
                <i class="bi bi-pencil-square text-orange"></i> Editar Cliente
            </h2>
            <a href="cliente.php" class="btn btn-outline-light">
                <i class="bi bi-arrow-left"></i> Voltar
            </a>
        </div>

        <div class="card bg-dark border-secondary shadow-lg">
            <div class="card-body p-4">
                <form method="POST" action="">
                    <div class="mb-3">
                        <label class="form-label text-white-50 small">Código</label>
                        <input type="text" class="form-control bg-secondary text-dark border-0" value="#<?= $dados['CD_CLIENTE']; ?>" disabled>
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-white-50 small">Nome Completo</label>
                        <input type="text" class="form-control" name="nome" required value="<?= $dados['NOME']; ?>">
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-white-50 small">CPF</label>
                        <input type="text" class="form-control" name="cpf" required minlength="11" maxlength="11" pattern="\d{11}" value="<?= $dados['CPF']; ?>">
                    </div>

                    <div class="row">
                        <div class="col-md-7 mb-3">
                            <label class="form-label text-white-50 small">E-mail</label>
                            <input type="email" class="form-control" name="email" required value="<?= $dados['EMAIL']; ?>">
                        </div>
                        <div class="col-md-5 mb-3">
                            <label class="form-label text-white-50 small">Telefone</label>
                            <input type="text" class="form-control" name="telefone" required minlength="11" maxlength="11" pattern="\d{11}" value="<?= $dados['TELEFONE']; ?>">
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


<?php include '../../../includes/footer.php'; ?>