<?php
// pessoas/clientes/excluir.php
require_once '../../config/conexao.php';
session_start();

// 1. Verifica Login
if (!isset($_SESSION['usuario_id'])) {
    header("Location: ../../login.php");
    exit;
}

// 2. Verifica Permissão (Gerente)
if ($_SESSION['usuario_nivel'] !== 'Gerente') {
    header("Location: cliente.php?erro_perm=1");
    exit;
}

// 3. Processa Exclusão
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if ($id) {
    try {
        $sql = "DELETE FROM CLIENTE WHERE CD_CLIENTE = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':id', $id);
        $stmt->execute();
        
        header("Location: cliente.php?msg=excluido");
        exit;

    } catch (PDOException $e) {
        // Erro de FK (Cliente tem compras)
        header("Location: cliente.php?erro_db=" . urlencode("Cliente possui compras e não pode ser removido."));
        exit;
    }
} else {
    header("Location: cliente.php");
    exit;
}
?>