<?php
// modulos/livros/excluir.php
require_once '../../config/conexao.php';

// Inicia sessão para ver quem é o usuário
session_start();

// 1. SEGURANÇA DE LOGIN
if (!isset($_SESSION['usuario_id'])) {
    // CORREÇÃO: Aponta para a nova localização do login (dentro de modulos)
    header("Location: /sistema-sebo/modulos/login.php");
    exit;
}

// 2. SEGURANÇA DE NÍVEL (Só 'Gerente' pode excluir)
// Se a função NÃO for Gerente, chuta de volta com erro
if (!isset($_SESSION['usuario_nivel']) || $_SESSION['usuario_nivel'] !== 'Gerente') {
    // Redireciona com um parâmetro de erro na URL para mostrarmos um alerta
    header("Location: acervo.php?erro_perm=1");
    exit;
}

// 3. VALIDAR O ID
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if ($id) {
    try {
        // Prepara a exclusão
        $sql = "DELETE FROM LIVRO WHERE CD_LIVRO = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':id', $id);
        $stmt->execute();
        
        // Sucesso
        header("Location: acervo.php?msg=excluido");
        exit;

    } catch (PDOException $e) {
        // Se der erro (ex: livro já vendido e preso na tabela compras), avisa
        header("Location: acervo.php?erro_db=" . urlencode($e->getMessage()));
        exit;
    }
} else {
    // Se não mandou ID
    header("Location: acervo.php");
    exit;
}
?>