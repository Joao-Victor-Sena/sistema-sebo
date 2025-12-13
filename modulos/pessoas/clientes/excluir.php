<?php
session_start();
require_once '../../../config/conexao.php';

$niveis_permitidos = ['Gerente', 'Admin', 'Administrador'];

if (!isset($_SESSION['usuario_nivel']) || !in_array($_SESSION['usuario_nivel'], $niveis_permitidos)) {
    header("Location: cliente.php?erro_perm=1");
    exit;
}

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
        header("Location: cliente.php?erro_db=1");
        exit;
    }
} else {
    header("Location: cliente.php");
    exit;
}
