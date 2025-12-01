<?php
// pessoas/clientes/editar.php
require_once '../../config/conexao.php';
include '../../includes/header.php';

// 1. Segurança de Permissão
if (!isset($_SESSION['usuario_nivel']) || $_SESSION['usuario_nivel'] !== 'Gerente') {
    header("Location: cliente.php?erro_perm=1");
    exit;
}

// 2. Verifica ID
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id) { header("Location: cliente.php"); exit; }

// 3. Busca Dados Atuais
$stmt = $pdo->prepare("SELECT * FROM CLIENTE WHERE CD_CLIENTE = :id");
$stmt->execute([':id' => $id]);
$cli = $stmt->fetch();

if (!$cli) { header("Location: cliente.php"); exit; }

// 4. Lógica de Atualização
if (isset($_POST['btn_salvar'])) {
    $nome  = filter_input(INPUT_POST, 'nome', FILTER_SANITIZE_SPECIAL_CHARS);
    $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
    
    // Limpa e valida telefone
    $telefone = preg_replace('/[^0-9]/', '', $_POST['telefone']);

    if (strlen($telefone) !== 11) {
        echo "<div class='alert alert-warning fixed-top m-3 shadow' style='z-index:2000;'>
                <i class='bi bi-phone'></i> Erro: O Telefone deve conter 11 números.
                <button type='button' class='btn-close' data-bs-dismiss='alert'></button>
              </div>";
    } else {
        try {
            $sql = "UPDATE CLIENTE SET NOME=:n, EMAIL=:e, TELEFONE=:t WHERE CD_CLIENTE=:id";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([':n'=>$nome, ':e'=>$email, ':t'=>$telefone, ':id'=>$id]);
            
            // Redireciona com sucesso (você pode adicionar msg=atualizado no cliente.php igual fizemos em livros)
            header("Location: cliente.php");
            exit;
        } catch (PDOException $e) {
            echo "<div class='alert alert-danger'>Erro ao atualizar: " . $e->getMessage() . "</div>";
        }
    }
}
?>

<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card bg-dark border-secondary shadow">
            
            <div class="card-header border-secondary d-flex justify-content-between align-items-center">
                <h4 class="text-white mb-0"><i class="bi bi-pencil-square text-orange"></i> Editar Cliente</h4>
                <a href="cliente.php" class="btn btn-sm btn-outline-light">Voltar</a>
            </div>
            
            <div class="card-body">
                <form method="POST">
                    <div class="mb-3">
                        <label class="text-white-50 small">Nome Completo</label>
                        <input type="text" name="nome" class="form-control" value="<?php echo $cli['NOME']; ?>" required>
                    </div>
                    
                    <div class="mb-3">
                        <label class="text-white-50 small">CPF (Somente Leitura)</label>
                        <input type="text" class="form-control bg-secondary text-dark border-0" value="<?php echo $cli['CPF']; ?>" disabled>
                    </div>
                    
                    <div class="mb-3">
                        <label class="text-white-50 small">E-mail</label>
                        <input type="email" name="email" class="form-control" value="<?php echo $cli['EMAIL']; ?>" required>
                    </div>
                    
                    <div class="mb-3">
                        <label class="text-white-50 small">Celular (11 dígitos)</label>
                        <input type="text" name="telefone" class="form-control" 
                               value="<?php echo $cli['TELEFONE']; ?>"
                               required minlength="11" maxlength="11" pattern="\d{11}">
                    </div>
                    
                    <div class="d-grid gap-2 mt-4">
                        <button type="submit" name="btn_salvar" class="btn bg-orange fw-bold">Salvar Alterações</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include '../../includes/footer.php'; ?>