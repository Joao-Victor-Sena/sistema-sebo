<?php
session_start();
require_once '../../config/conexao.php';

$niveis_permitidos = ['Gerente', 'Admin', 'Administrador'];
if (!isset($_SESSION['usuario_nivel']) || !in_array($_SESSION['usuario_nivel'], $niveis_permitidos)) {
    header("Location: vendas.php");
    exit;
}

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if ($id) {
    $pdo->prepare("DELETE FROM COMPRA WHERE CD_COMPRA = :id")->execute([':id' => $id]);
}


header("Location: vendas.php");