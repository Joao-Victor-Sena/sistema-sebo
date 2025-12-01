<?php
session_start();
require_once 'config/conexao.php';

// Lógica de Login (Back-end)
$erro = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $cpf = $_POST['cpf'];
    $senha = $_POST['senha'];

    // Limpa o CPF para garantir que só tem números (caso o usuário digite pontos)
    $cpf = preg_replace('/[^0-9]/', '', $cpf);

    try {
        $sql = "SELECT * FROM FUNCIONARIO WHERE CPF = :cpf AND SENHA = :senha";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':cpf', $cpf);
        $stmt->bindValue(':senha', $senha);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $usuario = $stmt->fetch();
            
            // Cria a sessão
            $_SESSION['usuario_id'] = $usuario['CD_FUNCIONARIO'];
            $_SESSION['usuario_nome'] = $usuario['NOME'];
            $_SESSION['usuario_nivel'] = $usuario['FUNCAO'];

            // Redireciona para o Dashboard
            header("Location: index.php");
            exit;
        } else {
            $erro = "CPF ou Senha incorretos!";
        }
    } catch (PDOException $e) {
        $erro = "Erro no banco: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sebo Integrador</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/custom.css">
</head>
<body class="login-page">

    <div class="card-login">
        <div class="brand-login text-center mb-4">
            <h1 class="display-5 fw-bold text-white">
                <i class="bi bi-book-half text-orange"></i> Sebo<span class="text-orange">Linhas</span>
            </h1>
            <p class="text-muted small">Acesso Administrativo</p>
        </div>

        <?php if($erro): ?>
            <div class="alert alert-danger text-center p-2">
                <?= $erro ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="mb-3">
                <label for="cpf" class="form-label">CPF</label>
                <input type="text" class="form-control" id="cpf" name="cpf" placeholder="XXX.XXX.XXX-XX" required>
            </div>
            
            <div class="mb-3">
                <label for="senha" class="form-label">Senha</label>
                <input type="password" class="form-control" id="senha" name="senha" placeholder="********" required>
            </div>

            <div class="d-grid gap-2">
                <button type="submit" class="btn login-btn btn-lg">Entrar</button>
            </div>
            
            <div class="text-center mt-3">
                <a href="#" class="text-muted small">Esqueceu a senha?</a>
            </div>
        </form>
    </div>

</body>
</html>