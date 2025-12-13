<?php
session_start();
require_once '../../config/conexao.php';

$niveis_permitidos = ['Gerente', 'Admin', 'Administrador'];

if (!isset($_SESSION['usuario_nivel']) || !in_array($_SESSION['usuario_nivel'], $niveis_permitidos)) {
    header("Location: acervo.php?erro_perm=1");
    exit;
}

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if ($id) {
    try {
        $sql = "DELETE FROM LIVRO WHERE CD_LIVRO = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':id', $id);
        $stmt->execute();

        header("Location: acervo.php?msg=excluido");
        exit;
    } catch (PDOException $e) {
        $erro = urlencode("Não é possível excluir este livro pois ele já possui registros de vendas.");
        header("Location: acervo.php?erro_msg=$erro");
        exit;
    }
} else {
    header("Location: acervo.php");
    exit;
}
