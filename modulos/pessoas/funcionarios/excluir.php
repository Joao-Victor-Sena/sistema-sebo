<?php
session_start();
require_once '../../../config/conexao.php';

$niveis_permitidos = ['Gerente', 'Admin', 'Administrador'];

if (!isset($_SESSION['usuario_nivel']) || !in_array($_SESSION['usuario_nivel'], $niveis_permitidos)) {
    header("Location: funcionarios.php?erro_perm=1");
    exit;
}

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if ($id) {

    if (isset($_SESSION['usuario_id']) && $_SESSION['usuario_id'] == $id) {
        header("Location: funcionarios.php?erro_self=1"); 
        exit;
    }

    try {
        $sql = "DELETE FROM FUNCIONARIO WHERE CD_FUNCIONARIO = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':id', $id);
        $stmt->execute();

        header("Location: funcionarios.php?msg=excluido");
        exit;

    } catch (PDOException $e) {
        header("Location: funcionarios.php?erro_db=1");
        exit;
    }

} else {
    header("Location: funcionarios.php");
    exit;
}